<?php

require_once __DIR__ . '/../repositories/DrivingSessionRepository.php';
require_once __DIR__ . '/../services/TripService.php';
require_once __DIR__ . '/../config/db.inc.php';

class TripController {
    private TripService $tripService;

    public function __construct(PDO $pdo) {
        $repo = new DrivingSessionRepository($pdo);
        $this->tripService = new TripService($repo, $pdo);
    }

    public function saveTrip(int $userId, array $postData): void {
        $tripType = $postData['trip_type'] ?? 'manual';
        $distance = $postData['distance'] ?? 0;

        if ($tripType === 'live' && (float)$distance < 0) {
             die("Invalid distance value.");
        }

        try {
            $this->tripService->createTrip($userId, $postData);
            header("Location: ../dashboard.php");
            exit;
        } catch (Exception $e) {
            die("Error saving trip: " . $e->getMessage());
        }
    }

    public function deleteTrip(int $userId, array $postData): void {
        $sessionId = isset($postData['session_id']) ? (int)$postData['session_id'] : 0;
        $redirectPage = $postData['redirect_page'] ?? 'dashboard';

        try {
            $this->tripService->deleteTrip($sessionId, $userId);
        } catch (Exception $e) {
        }
        
        header("Location: ../dashboard.php?page=" . $redirectPage);
        exit;
    }
}
?>
