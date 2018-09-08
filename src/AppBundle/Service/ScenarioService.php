<?php

namespace AppBundle\Service;

use AppBundle\Traits\GuzzleHttpClientTrait;
use AppBundle\Traits\ParamHoldersTrait;

class ScenarioService
{
    use GuzzleHttpClientTrait, ParamHoldersTrait;

    const API_PATH_ALL = 'all';
    const API_PATH_PERSONAL = 'personal';
    const API_PATH_CAMERA = 'camera';

    public function getRecommendationsForScenarioAll(): array
    {
        return $this->getRecommendationsFromApi(self::API_PATH_ALL);
    }

    public function getRecommendationsForScenarioPersonal(string $email): array
    {
        return $this->getRecommendationsFromApi(self::API_PATH_PERSONAL, ['email' => $email]);
    }

    public function getRecommendationsForScenarioCamera(int $tvId): array
    {
        return $this->getRecommendationsFromApi(self::API_PATH_CAMERA, ['id' => $tvId]);
    }

    private function getUri(string $path): string
    {
        return "{$this->apiHostname}/{$path}";
    }

    private function getRecommendationsFromApi(string $path, array $dataToSend = []): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->getUri($path), ['query' => $dataToSend]);
            $contents = $response->getBody()->getContents();
            $productIds = json_decode($contents, true)['data'] ?? [];
            if (!is_array($productIds)) {
                $productIds = [];
            }
        } catch (\Throwable $e) {
            $productIds = [];
        }

        return $productIds;
    }
}
