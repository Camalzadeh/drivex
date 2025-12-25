<?php
require_once __DIR__ . '/config/bootstrap.php';
session_start();

require_once __DIR__ . '/config/db.inc.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/TripController.php';

$action = $_REQUEST['action'] ?? '';


$authController = new AuthController($conn);
$tripController = new TripController($conn);

switch ($action) {
    case 'login':
        $authController->login($_POST);
        break;
    case 'register':
        $authController->register($_POST);
        break;
    case 'logout':
        $authController->logout();
        break;
        
    case 'save_trip':
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
            exit;
        }
        $tripController->saveTrip((int)$_SESSION['user_id'], $_POST);
        break;
        
    case 'delete_trip':
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../index.php");
            exit;
        }
        $tripController->deleteTrip((int)$_SESSION['user_id'], $_POST);
        break;
        
    default:
        if (isset($_SESSION['user_id'])) {
            header("Location: ../dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        break;
}
?>
