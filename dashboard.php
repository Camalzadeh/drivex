<?php
require_once __DIR__ . '/php/config/bootstrap.php';
session_start();
require_once "php/config/db.inc.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$page = $_GET['page'] ?? 'dashboard';

require_once "php/repositories/UserRepository.php";
require_once "php/repositories/LookupRepository.php";
require_once "php/services/LookupService.php";

$userRepo = new UserRepository($conn);
$lookupRepo = new LookupRepository($conn);
$lookupService = new LookupService($lookupRepo);

require_once "php/repositories/DrivingSessionRepository.php";
require_once "php/services/TripService.php";

$tripRepo = new DrivingSessionRepository($conn);
$tripService = new TripService($tripRepo, $conn);

$trips = $tripService->getAllTripsForUser((int)$userId);
$userProfileObj = $userRepo->findById((int)$userId);
$userProfile = [
    'email' => $userProfileObj ? $userProfileObj->getEmail() : '', 
    'created_at' => $userProfileObj ? $userProfileObj->getCreatedAt() : ''
];

$roadTypes = $lookupService->getRoadTypes();
$visibilities = $lookupService->getVisibilities();
$weathers = $lookupService->getWeatherConditions();
$trafficConditions = $lookupService->getTrafficConditions();

$weatherStats = [];
$roadStats = [];
$dateStats = [];
$visStats = [];
$trafficStats = [];

foreach ($trips as $trip) {
    $w = $trip['weather_condition'];
    $weatherStats[$w] = ($weatherStats[$w] ?? 0) + 1;

    $rList = $trip['road_types'] ? explode(', ', $trip['road_types']) : ['Unknown'];
    foreach ($rList as $r) {
        $roadStats[$r] = ($roadStats[$r] ?? 0) + 1;
    }
    
    $date = date('Y-m-d', strtotime($trip['start_date']));
    if (!isset($dateStats[$date])) $dateStats[$date] = 0;
    $dateStats[$date] += $trip['mileage'];

    $v = $trip['visibility'];
    $visStats[$v] = ($visStats[$v] ?? 0) + 1;

    $tList = $trip['traffic_conditions'] ? explode(', ', $trip['traffic_conditions']) : ['Unknown'];
    foreach ($tList as $t) {
        $trafficStats[$t] = ($trafficStats[$t] ?? 0) + 1;
    }
}

$weatherLabels = json_encode(array_keys($weatherStats));
$weatherData = json_encode(array_values($weatherStats));
$roadLabels = json_encode(array_keys($roadStats));
$roadData = json_encode(array_values($roadStats));
$visLabels = json_encode(array_keys($visStats ?? []));
$visData = json_encode(array_values($visStats ?? []));
$trafficLabels = json_encode(array_keys($trafficStats ?? []));
$trafficData = json_encode(array_values($trafficStats ?? []));

$lineLabels = [];
$lineData = [];
$cumul = 0;
foreach ($dateStats as $date => $miles) {
    $cumul += $miles;
    $lineLabels[] = $date;
    $lineData[] = $cumul;
}
$lineLabelsJson = json_encode($lineLabels);
$lineDataJson = json_encode($lineData);

$totalDistance = array_sum(array_column($trips, 'mileage'));
$totalTrips = count($trips);

$userEmail = $userProfile['email'] ?? 'user@example.com';
$memberSince = $userProfile['created_at'] ? date('M Y', strtotime($userProfile['created_at'])) : 'Jan 2025';

$totalDurationSeconds = 0;
$i = 0;
$count = count($trips);
if ($count > 0) {
    do {
        $t = $trips[$i];
        $start = strtotime($t['start_date']);
        $end = strtotime($t['end_date']);
        $totalDurationSeconds += ($end - $start);
        $i++;
    } while ($i < $count);
}

$totalHours = $totalDurationSeconds > 0 ? $totalDurationSeconds / 3600 : 0;
$avgSpeed = $totalHours > 0 ? $totalDistance / $totalHours : 0;
$fuelSaved = $totalDistance * 0.12; 

$badges = [];
$nightDrives = array_filter($trips, fn($t) => in_array($t['visibility'], ['Low', 'Moderate'])); 
if (count($nightDrives) > 0) {
    $badges[] = ['icon' => 'moon', 'color' => 'bg-dark', 'title' => strtoupper('Night Rider'), 'desc' => 'Experienced in low visibility driving.'];
}
$rainDrives = array_filter($trips, fn($t) => $t['weather_condition'] === 'Rainy');
if (count($rainDrives) > 0) {
    $badges[] = ['icon' => 'thunderstorm', 'color' => 'bg-primary', 'title' => strtoupper('Rain Master'), 'desc' => 'Safely navigated rainy conditions.'];
}
if ($totalDistance > 50) {

    $badges[] = ['icon' => 'ribbon', 'color' => 'bg-warning', 'title' => 'Marathon Driver', 'desc' => 'Logged over 50km of total driving.'];
}
if (empty($badges)) {
    $badges[] = ['icon' => 'school', 'color' => 'bg-info', 'title' => 'Learner Driver', 'desc' => 'Keep driving to earn badges!'];
}

$pageTitle = 'Dashboard';
switch ($page) {
    case 'add_trip':
        $pageTitle = 'Add New Trip';
        break;
    case 'profile':
        $pageTitle = 'My User Profile';
        break;
    case 'dashboard':
    default:
        $pageTitle = 'Dashboard Overview';
        break;
}

require_once 'html_views/dashboard.view.php';
?>

