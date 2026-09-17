<?php

declare(strict_types=1);

namespace Portfolio\Controllers\Admin;

use Portfolio\Models\ContactMessage;

final class MessageController extends AdminBaseController
{
    public function index(): void
    {
        $status = (string) $this->request->query('status', '');

        $where = '';
        $params = [];
        if (in_array($status, ContactMessage::STATUSES, true)) {
            $where = 'status = :status';
            $params = ['status' => $status];
        }

        $results = (new ContactMessage())->paginate(
            max(1, (int) $this->request->query('page', 1)),
            10,
            $where,
            $params,
            'created_at',
            'DESC'
        );

        $this->view('admin/messages/index', [
            'sidebar_active' => 'messages',
            'page_title'     => 'Contact messages',
            'messages'       => $results['items'],
            'pagination'     => $results,
            'current_status' => $status,
            'statuses'       => ContactMessage::STATUSES,
            'enquiry_types'  => require CONFIG_PATH . '/enquiries.php',
        ]);
    }

    public function show(string $id): void
    {
        $message = (new ContactMessage())->find((int) $id);
        if ($message === null) {
            $this->abort(404);
        }

        if ($message['status'] === 'new') {
            (new ContactMessage())->setStatus((int) $id, 'read');
        }

        $this->view('admin/messages/show', [
            'sidebar_active' => 'messages',
            'page_title'     => 'Message',
            'message'        => $message,
            'enquiry_types'  => require CONFIG_PATH . '/enquiries.php',
        ]);
    }

    public function status(string $id): void
    {
        $status = (string) $this->request->input('status', '');
        (new ContactMessage())->setStatus((int) $id, $status);
        flash('success', 'Message status updated.');
        $this->back('/admin/messages');
    }

    public function destroy(string $id): void
    {
        (new ContactMessage())->delete((int) $id);
        flash('success', 'Message deleted.');
        $this->redirect('/admin/messages');
    }
}