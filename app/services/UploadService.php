<?php

declare(strict_types=1);

namespace Portfolio\Services;

use RuntimeException;

/**
 * Secure file upload handling.
 *
 * Enforces extension whitelist, MIME sniffing, size limits,
 * randomized filenames and irreversible storage under public/uploads.
 */
final class UploadService
{
    private array $imageExts;
    private array $documentExts;
    private int $maxSize;

    public function __construct()
    {
        $config = config('upload');
        $this->imageExts    = $config['image_exts'];
        $this->documentExts = $config['document_exts'];
        $this->maxSize      = (int) $config['max_size'];
    }

    /**
     * Store an uploaded image.
     *
     * @param array{name:string,type:string,tmp_name:string,error:int,size:int} $file
     */
    public function uploadImage(array $file, string $category = 'misc', string $prefix = 'image'): string
    {
        return $this->store($file, $category, $prefix, $this->imageExts, 'image/');
    }

    /**
     * Store an uploaded document (PDF).
     */
    public function uploadDocument(array $file, string $category = 'documents', string $prefix = 'document'): string
    {
        return $this->store($file, $category, $prefix, $this->documentExts, 'application/pdf');
    }

    /**
     * Core upload routine. Returns the relative storage path (category/filename).
     */
    private function store(array $file, string $category, string $prefix, array $allowedExts, string $mimePrefix): string
    {
        if (!isset($file['error']) || !isset($file['name']) || !isset($file['tmp_name'])) {
            throw new RuntimeException('No file was uploaded.');
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException($this->errorMessage($file['error']));
        }

        if ($file['size'] > $this->maxSize) {
            throw new RuntimeException(sprintf('File exceeds the maximum allowed size of %d MB.', intdiv($this->maxSize, 1048576)));
        }

        if ($file['size'] <= 0) {
            throw new RuntimeException('The uploaded file is empty.');
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Upload failed: the file was not received from the client.');
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExts, true)) {
            throw new RuntimeException('File type not allowed. Allowed: ' . implode(', ', $allowedExts) . '.');
        }

        // Sniff MIME from actual content, never from the client-supplied type.
        $finfo      = new \finfo(FILEINFO_MIME_TYPE);
        $mime       = (string) $finfo->file($file['tmp_name']);
        $mimeAllowed = $this->mimeAllowed($mime, $allowedExts);
        if (!$mimeAllowed) {
            throw new RuntimeException('File content does not match an allowed type.');
        }

        // Verify images are genuine.
        if (in_array($extension, $this->imageExts, true)) {
            $info = @getimagesize($file['tmp_name']);
            if ($info === false) {
                throw new RuntimeException('Uploaded file is not a valid image.');
            }
        }

        // PDF must start with %PDF
        if ($extension === 'pdf') {
            $header = (string) file_get_contents($file['tmp_name'], false, null, 0, 5);
            if (str_starts_with($header, '%PDF-') === false) {
                throw new RuntimeException('Uploaded file is not a valid PDF.');
            }
        }

        $name  = $prefix . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $rel   = trim($category, '/') . '/' . $name;
        $dest  = PUBLIC_UPLOADS_PATH . '/' . $rel;

        $dir = dirname($dest);
        if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
            throw new RuntimeException('Unable to create upload directory.');
        }

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new RuntimeException('Unable to move the uploaded file into storage.');
        }

        // Double safety: never leave anything executable.
        @chmod($dest, 0644);

        return $rel;
    }

    private function mimeAllowed(string $mime, array $allowedExts): bool
    {
        if (in_array('pdf', $allowedExts, true) && $mime === 'application/pdf') {
            return true;
        }

        $imageMimes = [
            'jpg'  => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png'  => ['image/png'],
            'webp' => ['image/webp'],
            'gif'  => ['image/gif'],
        ];

        foreach ($imageMimes as $ext => $mimes) {
            if (in_array($ext, $allowedExts, true) && in_array($mime, $mimes, true)) {
                return true;
            }
        }

        return false;
    }

    private function errorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the server upload limit.',
            UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the form size limit.',
            UPLOAD_ERR_PARTIAL    => 'The file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server is missing a temporary upload folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write the file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the upload.',
            default               => 'Unknown upload error.',
        };
    }

    /**
     * Remove a stored upload (relative path) if present.
     */
    public function delete(string $relativePath): void
    {
        if ($relativePath === '') {
            return;
        }
        $full = PUBLIC_UPLOADS_PATH . '/' . ltrim($relativePath, '/');
        if (is_file($full) && is_writable($full)) {
            @unlink($full);
        }
    }

    public function maxSize(): int
    {
        return $this->maxSize;
    }

    public function imageExtensions(): array
    {
        return $this->imageExts;
    }

    public function documentExtensions(): array
    {
        return $this->documentExts;
    }
}