<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Models\BaseModel;
use Portfolio\Services\SlugService;
use Portfolio\Services\UploadService;
use Portfolio\Services\Validator;

/**
 * Reusable CRUD controller for simple single-table resources
 * (services, skills, experiences, education, testimonials).
 */
abstract class CrudResourceController extends AdminBaseController
{
    /**
     * Resource metadata — overridden by each concrete controller.
     *
     * @return array{
     *   model: string,
     *   label: string,
     *   route: string,
     *   view_folder: string,
     *   sidebar: string,
     *   has_slug: bool,
     *   fields: array<string, array{label:string, type:string, rules?:string[], options?:array}>
     * }
     */
    abstract protected function resource(): array;

    public function index(): void
    {
        $r = $this->resource();
        /** @var BaseModel $model */
        $model = new $r['model']();

        $results = $model->paginate(
            max(1, (int) $this->request->query('page', 1)),
            12,
            '',
            [],
            'created_at',
            'DESC'
        );

        $this->view($r['view_folder'] . '/index', [
            'sidebar_active' => $r['sidebar'],
            'page_title'     => $r['label'],
            'items'          => $results['items'],
            'pagination'     => $results,
            'resource'       => $r,
        ]);
    }

    public function create(): void
    {
        $r = $this->resource();
        $this->view($r['view_folder'] . '/form', [
            'sidebar_active' => $r['sidebar'],
            'page_title'     => 'New ' . rtrim($r['label'], 's'),
            'resource'       => $r,
            'item'           => null,
            'old'            => [],
            'errors'         => [],
        ]);
    }

    public function store(): void
    {
        $r = $this->resource();
        $input = $this->request->all();

        $validator = $this->validateInput($r, $input);

        $attrs = $this->buildAttributes($r, $input, $validator, null);

        if ($validator->passes() && empty($attrs['_upload_errors'])) {
            try {
                /** @var BaseModel $model */
                $model = new $r['model']();
                $id = $model->create($attrs);
                flash('success', rtrim($r['label'], 's') . ' created.');
                $this->redirect('/admin/' . $r['route']);
            } catch (\Throwable $e) {
                $this->cleanupUploads($attrs);
                error_log('[' . $r['label'] . '] Store error: ' . $e->getMessage());
                flash('error', 'Could not save.');
            }
        }

        $this->view($r['view_folder'] . '/form', [
            'sidebar_active' => $r['sidebar'],
            'page_title'     => 'New ' . rtrim($r['label'], 's'),
            'resource'       => $r,
            'item'           => null,
            'old'            => $input,
            'errors'         => $validator->errors(),
        ]);
    }

    public function edit(string $id): void
    {
        $r = $this->resource();
        /** @var BaseModel $model */
        $model = new $r['model']();
        $item = $model->find((int) $id);
        if ($item === null) {
            $this->abort(404);
        }

        $this->view($r['view_folder'] . '/form', [
            'sidebar_active' => $r['sidebar'],
            'page_title'     => 'Edit ' . rtrim($r['label'], 's'),
            'resource'       => $r,
            'item'           => $item,
            'old'            => [],
            'errors'         => [],
        ]);
    }

    public function update(string $id): void
    {
        $r = $this->resource();
        /** @var BaseModel $model */
        $model = new $r['model']();
        $itemId = (int) $id;
        $item = $model->find($itemId);
        if ($item === null) {
            $this->abort(404);
        }

        $input = $this->request->all();
        $validator = $this->validateInput($r, $input, $itemId);
        $attrs = $this->buildAttributes($r, $input, $validator, $item);

        if ($validator->passes() && empty($attrs['_upload_errors'])) {
            try {
                $model->update($itemId, $attrs);
                flash('success', rtrim($r['label'], 's') . ' updated.');
                $this->redirect('/admin/' . $r['route']);
            } catch (\Throwable $e) {
                $this->cleanupUploads($attrs);
                error_log('[' . $r['label'] . '] Update error: ' . $e->getMessage());
                flash('error', 'Could not update.');
                $this->back('/admin/' . $r['route']);
            }
        }

        $this->view($r['view_folder'] . '/form', [
            'sidebar_active' => $r['sidebar'],
            'page_title'     => 'Edit ' . rtrim($r['label'], 's'),
            'resource'       => $r,
            'item'           => $item,
            'old'            => $input,
            'errors'         => $validator->errors(),
        ]);
    }

    public function destroy(string $id): void
    {
        $r = $this->resource();
        /** @var BaseModel $model */
        $model = new $r['model']();
        $item = $model->find((int) $id);
        if ($item !== null) {
            $uploader = new UploadService();
            foreach ($r['fields'] as $field => $config) {
                if ($config['type'] === 'image' && !empty($item[$field])) {
                    $uploader->delete($item[$field]);
                }
            }
            $model->delete((int) $id);
            flash('success', rtrim($r['label'], 's') . ' deleted.');
        }
        $this->redirect('/admin/' . $r['route']);
    }

    public function togglePublished(string $id): void
    {
        $r = $this->resource();
        /** @var BaseModel $model */
        $model = new $r['model']();
        $item = $model->find((int) $id);
        if ($item !== null) {
            $model->updateColumns((int) $id, ['published' => 1 - (int) $item['published']]);
        }
        flash('success', 'Publish status updated.');
        $this->back('/admin/' . $r['route']);
    }

    private function validateInput(array $r, array $input, ?int $ignoreId = null): Validator
    {
        $validator = new Validator($input);
        foreach ($r['fields'] as $field => $config) {
            $label = $config['label'];
            $rules = $config['rules'] ?? [];

            if (in_array('required', $rules, true) && $config['type'] !== 'image') {
                $validator->required($field, $label);
            }
            if (in_array('email', $rules, true)) {
                $validator->email($field, $label);
            }
            if (in_array('url', $rules, true)) {
                $validator->url($field, $label);
            }
            if (in_array('date', $rules, true)) {
                $validator->date($field, $label);
            }
            if (in_array('numeric', $rules, true)) {
                $validator->custom($field, fn ($v) => $v === '' || is_numeric($v), "The {$label} must be a number.");
            }
            $max = null;
            foreach ($rules as $rule) {
                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                }
            }
            if ($max) {
                $validator->maxLength($field, $label, $max);
            }
        }
        return $validator;
    }

    /**
     * Map raw input into DB column values, handling checkboxes and uploads.
     */
    private function buildAttributes(array $r, array $input, Validator $validator, ?array $existing): array
    {
        $attrs = [];
        $uploader = new UploadService();

        foreach ($r['fields'] as $field => $config) {
            $type = $config['type'];

            if ($type === 'checkbox') {
                $attrs[$field] = isset($input[$field]) ? 1 : 0;
                continue;
            }

            if ($type === 'image') {
                $file = $this->request->file($field);
                if ($file !== null) {
                    try {
                        $attrs[$field] = $uploader->uploadImage($file, $r['route'], $field);
                        if ($existing !== null && !empty($existing[$field])) {
                            $uploader->delete($existing[$field]);
                        }
                    } catch (\RuntimeException $e) {
                        $validator->addError($field, $e->getMessage());
                        $attrs['_upload_errors'] = true;
                    }
                } elseif ($existing !== null) {
                    $attrs[$field] = $existing[$field] ?? '';
                } else {
                    $attrs[$field] = '';
                }
                continue;
            }

            if ($type === 'select') {
                $value = $input[$field] ?? '';
                $allowed = $config['options'] ?? [];
                if ($value !== '' && !in_array($value, $allowed, true)) {
                    $validator->addError($field, 'Invalid selection.');
                }
                $attrs[$field] = $value;
                continue;
            }

            // text / textarea / number / date / email
            $value = (string) ($input[$field] ?? '');
            if ($type === 'date' && $value === '') {
                $attrs[$field] = null;
            } else {
                $attrs[$field] = $value;
            }
        }

        if ($r['has_slug'] ?? false) {
            $title = (string) ($input['title'] ?? ($input['name'] ?? ''));
            $slug = (string) ($input['slug'] ?? SlugService::make($title));
            /** @var BaseModel $slugModel */
            $slugModel = new $r['model']();
            $attrs['slug'] = SlugService::ensureUnique($slugModel->tableName(), $slug, $existing['id'] ?? null);
        }

        // Empty optional values -> default or null for optional fields
        foreach ($attrs as $field => $value) {
            if ($value !== '') {
                continue;
            }
            $config = $r['fields'][$field] ?? null;
            if ($config === null) {
                continue;
            }
            if (array_key_exists('default', $config)) {
                $attrs[$field] = $config['default'];
            } elseif (in_array($config['type'], ['date', 'number'], true) || ($config['rules'] ?? []) === []) {
                $attrs[$field] = null;
            }
        }

        return $attrs;
    }

    private function cleanupUploads(array $attrs): void
    {
        $uploader = new UploadService();
        foreach ($attrs as $field => $value) {
            if (is_string($value) && str_contains($value, '/') && !str_starts_with($value, 'http')) {
                $uploader->delete($value);
            }
        }
    }
}