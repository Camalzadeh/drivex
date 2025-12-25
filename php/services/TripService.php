<?php

require_once __DIR__ . '/../repositories/DrivingSessionRepository.php';

class TripService {
    private DrivingSessionRepository $repo;
    private PDO $pdo;

    public function __construct(DrivingSessionRepository $repo, PDO $pdo) {
        $this->repo = $repo;
        $this->pdo = $pdo;
    }

    public function getAllTripsForUser(int $userId): array {
        return $this->repo->getAllForUser($userId);
    }

    public function createTrip(int $userId, array $postData): int {
        $tripType = $postData['trip_type'] ?? 'manual';
        $distance = $postData['distance'] ?? 0;
        
        $startTime = $postData['start_time'] ?? date('Y-m-d H:i:s');
        $endTime = $postData['end_time'] ?? date('Y-m-d H:i:s'); 
        
        $sessionData = [
            'start_date' => $startTime,
            'end_date' => $endTime,
            'distance' => $distance,
            'weather' => $postData['weather'],
            'visibility' => $postData['visibility']
        ];

        try {
            $this->pdo->beginTransaction();

            $sessionId = $this->repo->createSession($userId, $sessionData);
            
            if (!empty($postData['road_type'])) {
                foreach ($postData['road_type'] as $rtId) {
                    $this->repo->linkRoadType($sessionId, (int)$rtId);
                }
            }

            if (!empty($postData['traffic'])) {
                foreach ($postData['traffic'] as $tcId) {
                    $this->repo->linkTrafficCondition($sessionId, (int)$tcId);
                }
            }

            if ($tripType === 'live' && !empty($postData['route_points'])) {
                $points = json_decode($postData['route_points'], true);
                if (is_array($points)) {
                    foreach ($points as $p) {
                        $this->repo->addRoutePoint($sessionId, $p);
                    }
                }
            }

            $this->pdo->commit();
            return $sessionId;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function deleteTrip(int $sessionId, int $userId): void {
        try {
            $this->pdo->beginTransaction();

            $session = $this->repo->getSessionById($sessionId);
            if (!$session || (int)$session['user_id'] !== $userId) {
                throw new Exception("Session not found or access denied.");
            }

            $this->repo->deleteDependencies($sessionId);
            $this->repo->deleteSession($sessionId, $userId);

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
?>
