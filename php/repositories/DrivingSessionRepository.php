<?php

require_once __DIR__ . '/../utils/Queries.inc.php';

class DrivingSessionRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllForUser(int $userId): array {
        $stmt = $this->pdo->prepare(Queries::GET_ALL_TRIPS_DETAILS);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSessionById(int $sessionId): ?array {
        $stmt = $this->pdo->prepare(Queries::FIND_SESSION_BY_ID);
        $stmt->execute(['id' => $sessionId]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    public function createSession(int $userId, array $data): int {
        $stmt = $this->pdo->prepare(Queries::INSERT_SESSION);
        $stmt->execute([
            $userId,
            $data['start_date'],
            $data['end_date'],
            $data['distance'],
            $data['visibility'],
            $data['weather']
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function linkRoadType(int $sessionId, int $roadTypeId): void {
        $stmt = $this->pdo->prepare(Queries::INSERT_OCCURS_ON);
        $stmt->execute([$sessionId, $roadTypeId]);
    }

    public function linkTrafficCondition(int $sessionId, int $trafficId): void {
        $stmt = $this->pdo->prepare(Queries::INSERT_TAKES_PLACE);
        $stmt->execute([$sessionId, $trafficId]);
    }

    public function addRoutePoint(int $sessionId, array $point): void {
        $stmt = $this->pdo->prepare(Queries::INSERT_ROUTE_POINT);
        $stmt->execute([
            $sessionId,
            $point['lat'],
            $point['lng'],
            $point['timestamp'] ?? date('Y-m-d H:i:s')
        ]);
    }

    public function deleteDependencies(int $sessionId): void {
        $this->pdo->prepare(Queries::DELETE_OCCURS)->execute([$sessionId]);
        $this->pdo->prepare(Queries::DELETE_TAKES_PLACE)->execute([$sessionId]);
        $this->pdo->prepare(Queries::DELETE_ROUTE_POINTS)->execute([$sessionId]);
    }

    public function deleteSession(int $sessionId, int $userId): void {
        $stmt = $this->pdo->prepare(Queries::DELETE_TRIP);
        $stmt->execute([$sessionId, $userId]);
    }
}
?>
