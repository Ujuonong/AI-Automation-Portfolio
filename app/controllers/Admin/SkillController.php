<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

final class SkillController extends CrudResourceController
{
    protected function resource(): array
    {
        return [
            'model'       => \Portfolio\Models\Skill::class,
            'label'       => 'Skills',
            'route'       => 'skills',
            'view_folder' => 'admin/resources',
            'sidebar'     => 'skills',
            'has_slug'    => false,
            'fields'      => [
                'name'        => ['label' => 'Name', 'type' => 'text', 'rules' => ['required', 'max:150']],
                'category'    => ['label' => 'Category', 'type' => 'text', 'rules' => ['max:100']],
                'proficiency' => ['label' => 'Proficiency (0-100)', 'type' => 'number', 'rules' => ['numeric'], 'default' => 80],
                'icon'        => ['label' => 'Icon', 'type' => 'text'],
                'sort_order'  => ['label' => 'Sort order', 'type' => 'number', 'rules' => ['numeric'], 'default' => 0],
                'published'   => ['label' => 'Published', 'type' => 'checkbox'],
            ],
        ];
    }
}