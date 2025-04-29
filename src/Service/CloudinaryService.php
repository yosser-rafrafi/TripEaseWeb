<?php

namespace App\Service;

use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Cloudinary\Api\ApiUtils;
use GuzzleHttp\Client;

class CloudinaryService
{
    private $cloudinary;
    private $uploadApi;
    private $cloudName;
    private $apiKey;
    private $apiSecret;

    public function __construct(string $cloudName, string $apiKey, string $apiSecret)
    {
        if (empty($cloudName) || empty($apiKey) || empty($apiSecret)) {
            throw new \RuntimeException('Cloudinary credentials are missing. Please check your .env file.');
        }

        $this->cloudName = $cloudName;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

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
       
        $this->uploadApi = new UploadApi([
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
        ]);
    }

    public function upload(UploadedFile $file, string $folder = 'posts'): string
    {
        try {
            $client = new Client([
                'verify' => false // ❗ À utiliser uniquement en développement
            ]);

            $timestamp = time();
            
            // Préparer les paramètres pour la signature
            $params = [
                'timestamp' => $timestamp,
                'folder' => $folder
            ];
            
            // Trier les paramètres par ordre alphabétique
            ksort($params);
            
            // Créer la chaîne à signer
            $stringToSign = '';
            foreach ($params as $key => $value) {
                $stringToSign .= $key . '=' . $value . '&';
            }
            $stringToSign = rtrim($stringToSign, '&');
            
            // Générer la signature
            $signature = sha1($stringToSign . $this->apiSecret);

            $response = $client->request('POST', "https://api.cloudinary.com/v1_1/{$this->cloudName}/auto/upload", [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($file->getPathname(), 'r')
                    ],
                    [
                        'name'     => 'api_key',
                        'contents' => $this->apiKey
                    ],
                    [
                        'name'     => 'timestamp',
                        'contents' => $timestamp
                    ],
                    [
                        'name'     => 'signature',
                        'contents' => $signature
                    ],
                    [
                        'name'     => 'folder',
                        'contents' => $folder
                    ]
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (!isset($result['secure_url'])) {
                throw new \RuntimeException('Upload failed: No secure URL returned');
            }

            return $result['secure_url'];

        } catch (\Exception $e) {
            throw new \RuntimeException('Cloudinary upload failed: ' . $e->getMessage());
        }
    }
}