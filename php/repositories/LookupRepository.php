<?php

require_once __DIR__ . '/../utils/Queries.inc.php';
require_once __DIR__ . '/../models/RoadType.php';
require_once __DIR__ . '/../models/Visibility.php';
require_once __DIR__ . '/../models/WeatherCondition.php';
require_once __DIR__ . '/../models/TrafficCondition.php';

class LookupRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllRoadTypes(): array {
        return $this->pdo->query(Queries::GET_ALL_ROAD_TYPES)->fetchAll(PDO::FETCH_CLASS, 'RoadType');
    }

    public function getAllVisibilities(): array {
        return $this->pdo->query(Queries::GET_ALL_VISIBILITIES)->fetchAll(PDO::FETCH_CLASS, 'Visibility');
    }

    public function getAllWeatherConditions(): array {
        return $this->pdo->query(Queries::GET_ALL_WEATHER_CONDITIONS)->fetchAll(PDO::FETCH_CLASS, 'WeatherCondition');
    }

    public function getAllTrafficConditions(): array {
        return $this->pdo->query(Queries::GET_ALL_TRAFFIC_CONDITIONS)->fetchAll(PDO::FETCH_CLASS, 'TrafficCondition');
    }
}
?>
