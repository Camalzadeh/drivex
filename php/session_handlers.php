<?php
require "db.php"; // PDO $pdo burada olsun

function sess_open($savePath, $sessionName) {
    return true;
}

function sess_close() {
    return true;
}

function sess_read($id) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT data FROM sessions WHERE id = ?");
    $stmt->execute([$id]);

    $row = $stmt->fetch();
    return $row ? $row['data'] : '';
}

function sess_write($id, $data) {
    global $pdo;

    $time = time();

    $stmt = $pdo->prepare("
        REPLACE INTO sessions (id, data, timestamp)
        VALUES (?, ?, ?)
    ");

    return $stmt->execute([$id, $data, $time]);
}

function sess_destroy($id) {
    global $pdo;

    $stmt = $pdo->prepare("DELETE FROM sessions WHERE id = ?");
    return $stmt->execute([$id]);
}

function sess_gc($maxlifetime) {
    global $pdo;

    $old = time() - $maxlifetime;
    $stmt = $pdo->prepare("DELETE FROM sessions WHERE timestamp < ?");
    return $stmt->execute([$old]);
}

session_set_save_handler(
    "sess_open",
    "sess_close",
    "sess_read",
    "sess_write",
    "sess_destroy",
    "sess_gc"
);

session_start();
?>
