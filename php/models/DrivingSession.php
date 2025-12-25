<?php

require_once __DIR__ . '/RoadType.php';
require_once __DIR__ . '/Visibility.php';
require_once __DIR__ . '/WeatherCondition.php';
require_once __DIR__ . '/TrafficCondition.php';
require_once __DIR__ . '/RoutePoint.php';
require_once __DIR__ . '/User.php';

class DrivingSession {
    public int $session_id;
    public int $user_id;
    public string $start_date;
    public string $end_date;
    public float $mileage;
    public int $weather_condition_id;
    public int $visibility_id;
    

    public ?User $user = null;
    public ?WeatherCondition $weather = null;
    public ?Visibility $visibility = null;
    public array $roadTypes = [];
    public array $trafficConditions = [];
    public array $routePoints = [];
}
?>
