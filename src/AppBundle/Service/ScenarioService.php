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
        return $this->getRecommendationsFromApi(self::API_PATH_CAMERA, ['cameraId' => $tvId]);
    }

    private function getUri(string $path): string
    {
        return "{$this->apiHostname}/{$path}";
    }

    private function getRecommendationsFromApi(string $path, array $dataToSend = []): array
    {
        // hardcode showroomId because api needs showroomId for all requests and interface supports only 1
        $dataToSend += ['showroomId' => 1];

        try {
            $response = $this->httpClient->request('GET', $this->getUri($path), ['query' => $dataToSend]);
            $contents = $response->getBody()->getContents();
            dump($contents); // todo: we'll let this here for now because all servers run under dev in testing phase
            $productIds = json_decode($contents, true)['data'] ?? [];
            if (!is_array($productIds)) {
                $productIds = [];
            }
        } catch (\Throwable $e) {
            $productIds = [];
            dump($e); // todo: we'll let this here for now because all servers run under dev in testing phase
        }

        return $productIds;
    }
}
