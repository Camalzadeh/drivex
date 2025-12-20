<?php
session_start();

// bütün sessiya dəyişənlərini sil
$_SESSION = [];

// sessiyanı yox et
session_destroy();

// cookie session varsa onu da silək
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// yönləndir
header("Location: ../index.php");
exit;
