<?php
// backend/session_handlers.php

global $conn;

// Sessionu açır
function my_session_open($savePath, $sessionName) {
    global $conn;
    return $conn !== null;
}

// Sessionu bağlayır
function my_session_close() {
    return true;
}

// Session ID-yə görə məlumatı DB-dən oxuyur
function my_session_read($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT data FROM sessions WHERE id = ? AND last_activity > ?");
        $lifetime = time() - ini_get('session.gc_maxlifetime');
        $stmt->execute([$id, $lifetime]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['data'] : '';
    } catch (PDOException $e) {
        error_log("Session Read Error: " . $e->getMessage());
        return '';
    }
}

// Session ID-yə görə məlumatı DB-yə yazır/yeniləyir
function my_session_write($id, $data) {
    global $conn;
    $now = time();

    try {
        $stmt = $conn->prepare("
            INSERT INTO sessions (id, last_activity, data) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE 
                last_activity = VALUES(last_activity), 
                data = VALUES(data)
        ");
        $stmt->execute([$id, $now, $data]);
        return true;
    } catch (PDOException $e) {
        error_log("Session Write Error: " . $e->getMessage());
        return false;
    }
}

// Session ID-yə görə Sessionu DB-dən silir
function my_session_destroy($id) { // Ad dəyişdirildi!
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM sessions WHERE id = ?");
        $stmt->execute([$id]);
        return true;
    } catch (PDOException $e) {
        error_log("Session Destroy Error: " . $e->getMessage());
        return false;
    }
}

// Vaxtı bitmiş Sessionları DB-dən təmizləyir
function my_session_gc($maxlifetime) {
    global $conn;
    try {
        $old = time() - $maxlifetime;
        $stmt = $conn->prepare("DELETE FROM sessions WHERE last_activity < ?");
        $stmt->execute([$old]);
        return true;
    } catch (PDOException $e) {
        error_log("Session GC Error: " . $e->getMessage());
        return false;
    }
}
?>