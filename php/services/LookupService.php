<?php

require_once __DIR__ . '/../repositories/LookupRepository.php';

class LookupService {
    private LookupRepository $repo;

    public function __construct(LookupRepository $repo) {
        $this->repo = $repo;
    }

    public function getRoadTypes(): array {
        return $this->repo->getAllRoadTypes();
    }

    public function getVisibilities(): array {
        return $this->repo->getAllVisibilities();
    }

    public function getWeatherConditions(): array {
        return $this->repo->getAllWeatherConditions();
    }

    public function getTrafficConditions(): array {
        return $this->repo->getAllTrafficConditions();
    }
}
?>
