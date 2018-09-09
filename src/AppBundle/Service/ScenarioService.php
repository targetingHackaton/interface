<?php

namespace AppBundle\Service;

use AppBundle\Traits\GuzzleHttpClientTrait;
use AppBundle\Traits\ParamHoldersTrait;

class ScenarioService
{
    use GuzzleHttpClientTrait, ParamHoldersTrait;

    const API_PATH_ALL = 'all';
    const API_PATH_PERSON = 'person';
    const API_PATH_CAMERA = 'camera';

    const SCENARIO_ALL = 'all';
    const SCENARIO_PERSON = 'person';
    const SCENARIO_CAMERA = 'camera';

    /** @var SettingsService */
    protected $settingsService;

    public function getRecommendationsForScenarioAll(): array
    {
        return $this->getResponseFromApi(self::API_PATH_ALL);
    }

    public function getRecommendationsForScenarioPerson(string $email): array
    {
        return $this->getResponseFromApi(self::API_PATH_PERSON, ['email' => $email]);
    }

    public function getRecommendationsForScenarioCamera(): array
    {
        $cameraId = $this->settingsService->getCamera();
        return $this->getResponseFromApi(self::API_PATH_CAMERA, ['cameraId' => $cameraId]);
    }

    public function getResponseFromApi(string $path, array $dataToSend = []): array
    {
        $dataToSend += ['showroomId' => $this->settingsService->getShowroom()];

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

    private function getUri(string $path): string
    {
        return "{$this->apiHostname}/{$path}";
    }

    /**
     * @param SettingsService $settingsService
     * @return $this
     */
    public function setSettingsService(SettingsService $settingsService): self
    {
        $this->settingsService = $settingsService;
        return $this;
    }
}
