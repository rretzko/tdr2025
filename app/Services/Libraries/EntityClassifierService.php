<?php

namespace App\Services\Libraries;

use Aws\Comprehend\ComprehendClient;

class EntityClassifierService
{
    protected ComprehendClient $client;

    public function __construct()
    {
        $this->client = new ComprehendClient([
            'version' => 'latest',
            'region'  => config('filesystems.disks.s3.region'),
        ]);
    }

    /**
     * Determine if a string is classified as a person by AWS Comprehend
     */
    public function isPerson(string $text): bool
    {
        $result = $this->client->detectEntities([
            'LanguageCode' => 'en',
            'Text'         => $text,
        ]);

        foreach ($result['Entities'] as $entity) {
            if ($entity['Type'] === 'PERSON' && $entity['Score'] > 0.80) {
                return true;
            }
        }

        return false;
    }
}

