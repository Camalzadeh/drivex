<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth.php");
    exit;
}

require_once "db.php"; // DB connection

$user_id = $_SESSION['user_id'];

// Visibility
$visibility = $conn->query("SELECT * FROM Visibility ORDER BY visibility_id")->fetch_all(MYSQLI_ASSOC);

// Weather
$weather = $conn->query("SELECT * FROM WeatherCondition ORDER BY weather_condition_id")->fetch_all(MYSQLI_ASSOC);

// RoadType
$roadTypes = $conn->query("SELECT * FROM RoadType ORDER BY road_type_id")->fetch_all(MYSQLI_ASSOC);

// Traffic
$traffic = $conn->query("SELECT * FROM TrafficCondition ORDER BY traffic_condition_id")->fetch_all(MYSQLI_ASSOC);

// User-a aid bütün driving session-ları (JOIN-lərlə)
$sessions = $conn->query("
    SELECT d.*, 
           v.visibility, 
           w.weather_condition, 
           t.traffic_condition
    FROM DrivingSession d
    JOIN Visibility v ON d.visibility_id = v.visibility_id
    JOIN WeatherCondition w ON d.weather_condition_id = w.weather_condition_id
    LEFT JOIN TakesPlace tp ON d.session_id = tp.session_id
    LEFT JOIN TrafficCondition t ON tp.traffic_condition_id = t.traffic_condition_id
    WHERE d.user_id = $user_id
    GROUP BY d.session_id
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driving Experience Log</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<header>
    <div class="header-content">
        <img src="../assets/logo.png" alt="Logo" class="logo">
        <h1 id="title">DrivEx</h1>
        <a href="logout.php">Logout</a>
    </div>
</header>

<main>
    <form action="save_experience.php" method="POST">
        <h2>Add New Driving Experience</h2>

        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Start Time:</label>
        <input type="time" name="startTime" required>

        <label>End Time:</label>
        <input type="time" name="endTime" required>

        <label>Mileage (km):</label>
        <input type="number" name="mileage" min="0" step="0.1" required>

        <label>Weather:</label>
        <select name="weather" required>
            <?php foreach ($weather as $w): ?>
                <option value="<?= $w['weather_condition_id'] ?>">
                    <?= $w['weather_condition'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Road Type:</label>
        <select name="roadType" required>
            <?php foreach ($roadTypes as $r): ?>
                <option value="<?= $r['road_type_id'] ?>">
                    <?= $r['road_type'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Visibility:</label>
        <select name="visibility" required>
            <?php foreach ($visibility as $vis): ?>
                <option value="<?= $vis['visibility_id'] ?>">
                    <?= $vis['visibility'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Traffic Condition:</label>
        <select name="traffic" required>
            <?php foreach ($traffic as $t): ?>
                <option value="<?= $t['traffic_condition_id'] ?>">
                    <?= $t['traffic_condition'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Save Driving Experience</button>
    </form>

    <div id="secondPart">
        <h2>Your Driving Sessions</h2>

        <?php if (count($sessions) == 0): ?>
            <p>No driving sessions found.</p>
        <?php else: ?>
            <?php foreach ($sessions as $s): ?>
                <div class="session-box">
                    <p><strong>Date:</strong> <?= $s['start_date'] ?></p>
                    <p><strong>Mileage:</strong> <?= $s['mileage'] ?> km</p>
                    <p><strong>Weather:</strong> <?= $s['weather_condition'] ?></p>
                    <p><strong>Traffic:</strong> <?= $s['traffic_condition'] ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</main>

<footer><p>&copy; 2024 DrivEx</p></footer>

</body>
</html>
