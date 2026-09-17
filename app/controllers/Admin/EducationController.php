<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

final class EducationController extends CrudResourceController
{
    protected function resource(): array
    {
        return [
            'model'       => \Portfolio\Models\Education::class,
            'label'       => 'Education',
            'route'       => 'education',
            'view_folder' => 'admin/resources',
            'sidebar'     => 'education',
            'has_slug'    => false,
            'fields'      => [
                'institution' => ['label' => 'Institution', 'type' => 'text', 'rules' => ['required', 'max:255']],
                'degree'      => ['label' => 'Degree', 'type' => 'text'],
                'field'       => ['label' => 'Field of study', 'type' => 'text'],
                'start_date'  => ['label' => 'Start date', 'type' => 'date', 'rules' => ['date']],
                'end_date'    => ['label' => 'End date', 'type' => 'date', 'rules' => ['date']],
                'description' => ['label' => 'Description', 'type' => 'textarea'],
                'sort_order'  => ['label' => 'Sort order', 'type' => 'number', 'rules' => ['numeric'], 'default' => 0],
                'published'   => ['label' => 'Published', 'type' => 'checkbox'],
            ],
        ];
    }
}