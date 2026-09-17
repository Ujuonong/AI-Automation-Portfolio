<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

final class ServiceController extends CrudResourceController
{
    protected function resource(): array
    {
        return [
            'model'       => \Portfolio\Models\Service::class,
            'label'       => 'Services',
            'route'       => 'services',
            'view_folder' => 'admin/resources',
            'sidebar'     => 'services',
            'has_slug'    => true,
            'fields'      => [
                'title'             => ['label' => 'Title', 'type' => 'text', 'rules' => ['required', 'max:255']],
                'slug'              => ['label' => 'Slug', 'type' => 'text', 'rules' => []],
                'short_description' => ['label' => 'Short description', 'type' => 'textarea'],
                'description'       => ['label' => 'Full description', 'type' => 'textarea'],
                'icon'              => ['label' => 'Icon', 'type' => 'text', 'default' => 'rocket'],
                'sort_order'        => ['label' => 'Sort order', 'type' => 'number', 'rules' => ['numeric'], 'default' => 0],
                'published'         => ['label' => 'Published', 'type' => 'checkbox'],
            ],
        ];
    }
}