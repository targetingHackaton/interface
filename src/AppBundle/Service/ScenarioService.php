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
    const API_PATH_CAMERA_SHOULD_REFRESH = 'cameraShouldRefresh';

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

    public function cameraShouldRefresh(): bool
    {
        $cameraId = $this->settingsService->getCamera();
        $response = $this->getRawResponseFromApi(self::API_PATH_CAMERA_SHOULD_REFRESH, ['cameraId' => $cameraId]);

        return $response == 'false' ? false : true;
    }

    public function getResponseFromApi(string $path, array $dataToSend = []): array
    {
        $contents = $this->getRawResponseFromApi($path, $dataToSend);

        $productIds = json_decode($contents, true)['data'] ?? [];

        return is_array($productIds) ? $productIds : [];
    }

    public function getRawResponseFromApi(string $path, array $dataToSend = []): string
    {
        $dataToSend += ['showroomId' => $this->settingsService->getShowroom()];

        try {
            $response = $this->httpClient->request('GET', $this->getUri($path), ['query' => $dataToSend]);
            $contents = $response->getBody()->getContents();
            dump($contents); // todo: we'll let this here for now because all servers run under dev in testing phase
        } catch (\Throwable $e) {
            dump($e); // todo: we'll let this here for now because all servers run under dev in testing phase
        }

        return $contents ?? '';
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
