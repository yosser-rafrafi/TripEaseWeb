<?php

namespace App\Service;

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CloudinaryService
{
    private $cloudinary;
    private $uploadApi;

    public function __construct(string $cloudName, string $apiKey, string $apiSecret)
    {
        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            throw new \RuntimeException('Cloudinary credentials are missing. Please check your .env file.');
        }

        dump('Cloudinary credentials:', [
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'api_secret' => substr($apiSecret, 0, 4) . '...' // Only show first 4 chars of secret
        ]);

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $cloudName,
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
            ],
            'url' => [
                'secure' => true
            ]
        ]);

        // Initialize UploadApi with the configuration
        $this->uploadApi = new UploadApi([
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
        ]);
    }

    public function upload(UploadedFile $file, string $folder = 'posts'): string
    {
        try {
            dump('Uploading file:', [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);

            $result = $this->uploadApi->upload($file->getPathname(), [
                'folder' => $folder,
                'resource_type' => 'auto',
                'use_filename' => true,
                'unique_filename' => true
            ]);

            dump('Upload result:', $result);

            if (!isset($result['secure_url'])) {
                throw new \RuntimeException('Upload failed: No secure URL returned');
            }

            return $result['secure_url'];
        } catch (\Exception $e) {
            dump('Upload error details:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \RuntimeException('Cloudinary upload failed: ' . $e->getMessage());
        }
    }
}