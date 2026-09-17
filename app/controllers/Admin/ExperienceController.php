<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

final class ExperienceController extends CrudResourceController
{
    protected function resource(): array
    {
        return [
            'model'       => \Portfolio\Models\Experience::class,
            'label'       => 'Experience',
            'route'       => 'experience',
            'view_folder' => 'admin/resources',
            'sidebar'     => 'experience',
            'has_slug'    => false,
            'fields'      => [
                'position'        => ['label' => 'Position', 'type' => 'text', 'rules' => ['required', 'max:255']],
                'organization'    => ['label' => 'Organization', 'type' => 'text', 'rules' => ['max:255']],
                'employment_type' => ['label' => 'Employment type', 'type' => 'text'],
                'location'        => ['label' => 'Location', 'type' => 'text'],
                'start_date'      => ['label' => 'Start date', 'type' => 'date', 'rules' => ['date']],
                'end_date'        => ['label' => 'End date', 'type' => 'date', 'rules' => ['date']],
                'description'     => ['label' => 'Description', 'type' => 'textarea'],
                'sort_order'      => ['label' => 'Sort order', 'type' => 'number', 'rules' => ['numeric'], 'default' => 0],
                'published'       => ['label' => 'Published', 'type' => 'checkbox'],
            ],
        ];
    }
}