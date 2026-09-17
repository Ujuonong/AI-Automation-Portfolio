<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

final class TestimonialController extends CrudResourceController
{
    protected function resource(): array
    {
        return [
            'model'       => \Portfolio\Models\Testimonial::class,
            'label'       => 'Testimonials',
            'route'       => 'testimonials',
            'view_folder' => 'admin/resources',
            'sidebar'     => 'testimonials',
            'has_slug'    => false,
            'fields'      => [
                'client_name'     => ['label' => 'Client name', 'type' => 'text', 'rules' => ['required', 'max:150']],
                'client_role'     => ['label' => 'Client role', 'type' => 'text'],
                'company'         => ['label' => 'Company', 'type' => 'text'],
                'testimonial'     => ['label' => 'Testimonial', 'type' => 'textarea', 'rules' => ['required']],
                'client_image'    => ['label' => 'Client image', 'type' => 'image'],
                'featured'        => ['label' => 'Featured', 'type' => 'checkbox'],
                'published'       => ['label' => 'Published', 'type' => 'checkbox'],
            ],
        ];
    }
}