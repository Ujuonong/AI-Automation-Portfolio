<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Models\Certificate;
use Portfolio\Services\UploadService;
use Portfolio\Services\Validator;

final class CertificateController extends AdminBaseController
{
    public function index(): void
    {
        $results = (new Certificate())->paginate(
            max(1, (int) $this->request->query('page', 1)),
            12,
            '',
            [],
            'issue_date',
            'DESC'
        );

        $this->view('admin/certificates/index', [
            'sidebar_active' => 'certificates',
            'page_title'     => 'Certificates',
            'certificates'   => $results['items'],
            'pagination'     => $results,
        ]);
    }

    public function create(): void
    {
        $this->view('admin/certificates/form', [
            'sidebar_active' => 'certificates',
            'page_title'     => 'New Certificate',
            'certificate'    => null,
        ]);
    }

    public function store(): void
    {
        $input = $this->request->all();

        $validator = (new Validator($input))
            ->required('title', 'title')
            ->maxLength('title', 'title', 255)
            ->required('issuer', 'issuer')
            ->maxLength('issuer', 'issuer', 255)
            ->date('issue_date', 'issue date')
            ->url('credential_url', 'credential URL');

        $uploader = new UploadService();
        $thumbnail = null;
        $file = null;

        $thumbFile = $this->request->file('thumbnail');
        if ($thumbFile !== null) {
            try {
                $thumbnail = $uploader->uploadImage($thumbFile, 'certificates', 'certificate');
            } catch (\RuntimeException $e) {
                $validator->addError('thumbnail', $e->getMessage());
            }
        }

        $certFile = $this->request->file('certificate_file');
        if ($certFile !== null) {
            try {
                $file = $uploader->uploadDocument($certFile, 'certificates', 'certificate');
            } catch (\RuntimeException $e) {
                $validator->addError('certificate_file', $e->getMessage());
            }
        }

        if ($validator->passes()) {
            try {
                $id = (new Certificate())->create([
                    'title'            => $input['title'],
                    'issuer'           => $input['issuer'],
                    'description'      => $input['description'] ?? '',
                    'issue_date'       => ($input['issue_date'] ?? '') !== '' ? $input['issue_date'] : null,
                    'credential_id'    => $input['credential_id'] ?? '',
                    'credential_url'   => $input['credential_url'] ?? '',
                    'certificate_file' => $file,
                    'thumbnail'        => $thumbnail,
                    'featured'         => isset($input['featured']) ? 1 : 0,
                    'published'        => isset($input['published']) ? 1 : 0,
                ]);
                flash('success', 'Certificate created.');
                $this->redirect('/admin/certificates/edit/' . $id);
            } catch (\Throwable $e) {
                if ($thumbnail) {
                    $uploader->delete($thumbnail);
                }
                if ($file) {
                    $uploader->delete($file);
                }
                error_log('[Certificate] Store error: ' . $e->getMessage());
                flash('error', 'Could not save the certificate.');
            }
        }

        $this->view('admin/certificates/form', [
            'sidebar_active' => 'certificates',
            'page_title'     => 'New Certificate',
            'certificate'    => null,
            'errors'         => $validator->errors(),
            'old'            => $input,
        ]);
    }

    public function edit(string $id): void
    {
        $cert = (new Certificate())->find((int) $id);
        if ($cert === null) {
            $this->abort(404);
        }

        $this->view('admin/certificates/form', [
            'sidebar_active' => 'certificates',
            'page_title'     => 'Edit Certificate',
            'certificate'    => $cert,
        ]);
    }

    public function update(string $id): void
    {
        $certId = (int) $id;
        $cert = (new Certificate())->find($certId);
        if ($cert === null) {
            $this->abort(404);
        }

        $input = $this->request->all();

        $validator = (new Validator($input))
            ->required('title', 'title')
            ->maxLength('title', 'title', 255)
            ->required('issuer', 'issuer')
            ->date('issue_date', 'issue date')
            ->url('credential_url', 'credential URL');

        $uploader = new UploadService();
        $thumbnail = $cert['thumbnail'];
        $file = $cert['certificate_file'];
        $thumbError = null;
        $fileError = null;

        $thumbFile = $this->request->file('thumbnail');
        if ($thumbFile !== null) {
            try {
                $new = $uploader->uploadImage($thumbFile, 'certificates', 'certificate');
                if ($cert['thumbnail']) {
                    $uploader->delete($cert['thumbnail']);
                }
                $thumbnail = $new;
            } catch (\RuntimeException $e) {
                $thumbError = $e->getMessage();
            }
        }

        $certFile = $this->request->file('certificate_file');
        if ($certFile !== null) {
            try {
                $new = $uploader->uploadDocument($certFile, 'certificates', 'certificate');
                if ($cert['certificate_file']) {
                    $uploader->delete($cert['certificate_file']);
                }
                $file = $new;
            } catch (\RuntimeException $e) {
                $fileError = $e->getMessage();
            }
        }

        if ($thumbError !== null) {
            $validator->addError('thumbnail', $thumbError);
        }
        if ($fileError !== null) {
            $validator->addError('certificate_file', $fileError);
        }

        if ($validator->passes()) {
            try {
                (new Certificate())->update($certId, [
                    'title'            => $input['title'],
                    'issuer'           => $input['issuer'],
                    'description'      => $input['description'] ?? '',
                    'issue_date'       => ($input['issue_date'] ?? '') !== '' ? $input['issue_date'] : null,
                    'credential_id'    => $input['credential_id'] ?? '',
                    'credential_url'   => $input['credential_url'] ?? '',
                    'certificate_file' => $file,
                    'thumbnail'        => $thumbnail,
                    'featured'         => isset($input['featured']) ? 1 : 0,
                    'published'        => isset($input['published']) ? 1 : 0,
                ]);
                flash('success', 'Certificate updated.');
                $this->redirect('/admin/certificates/edit/' . $certId);
            } catch (\Throwable $e) {
                error_log('[Certificate] Update error: ' . $e->getMessage());
                flash('error', 'Could not update the certificate.');
                $this->back('/admin/certificates');
            }
        }

        $this->view('admin/certificates/form', [
            'sidebar_active' => 'certificates',
            'page_title'     => 'Edit Certificate',
            'certificate'    => $cert,
            'errors'         => $validator->errors(),
            'old'            => $input,
        ]);
    }

    public function destroy(string $id): void
    {
        $cert = (new Certificate())->find((int) $id);
        if ($cert === null) {
            $this->abort(404);
        }

        $uploader = new UploadService();
        if ($cert['thumbnail']) {
            $uploader->delete($cert['thumbnail']);
        }
        if ($cert['certificate_file']) {
            $uploader->delete($cert['certificate_file']);
        }
        (new Certificate())->delete((int) $id);

        flash('success', 'Certificate deleted.');
        $this->redirect('/admin/certificates');
    }

    public function togglePublished(string $id): void
    {
        (new Certificate())->togglePublished((int) $id);
        flash('success', 'Certificate publish status updated.');
        $this->back('/admin/certificates');
    }

    public function toggleFeatured(string $id): void
    {
        (new Certificate())->toggleFeatured((int) $id);
        flash('success', 'Certificate featured status updated.');
        $this->back('/admin/certificates');
    }
}