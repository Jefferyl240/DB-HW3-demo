<?php
require 'config.php';

$flight_number = $_GET['flight_number'] ?? '';
$departure_date = $_GET['departure_date'] ?? '';

if (!$flight_number || !$departure_date) {
    die("Missing flight number or departure date.");
}

$sql = "
    SELECT
        f.flight_number,
        f.departure_date,
        fs.origin_code,
        fs.dest_code,
        fs.departure_time,
        f.plane_type,
        a.capacity,
        COUNT(b.pid) AS booked_seats,
        (a.capacity - COUNT(b.pid)) AS available_seats
    FROM Flight f
    JOIN FlightService fs
      ON f.flight_number = fs.flight_number
    JOIN Aircraft a
      ON f.plane_type = a.plane_type
    LEFT JOIN Booking b
      ON f.flight_number = b.flight_number
     AND f.departure_date = b.departure_date
    WHERE f.flight_number = :flight_number
      AND f.departure_date = :departure_date
    GROUP BY
        f.flight_number,
        f.departure_date,
        fs.origin_code,
        fs.dest_code,
        fs.departure_time,
        f.plane_type,
        a.capacity
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':flight_number' => $flight_number,
    ':departure_date' => $departure_date
]);

$flight = $stmt->fetch();

if (!$flight) {
    die("Flight not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flight Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Flight Details</h1>

    <div class="card">
        <p><strong>Flight Number:</strong> <?= htmlspecialchars($flight['flight_number']) ?></p>
        <p><strong>Departure Date:</strong> <?= htmlspecialchars($flight['departure_date']) ?></p>
        <p><strong>Origin:</strong> <?= htmlspecialchars($flight['origin_code']) ?></p>
        <p><strong>Destination:</strong> <?= htmlspecialchars($flight['dest_code']) ?></p>
        <p><strong>Departure Time:</strong> <?= htmlspecialchars($flight['departure_time']) ?></p>
        <p><strong>Plane Type:</strong> <?= htmlspecialchars($flight['plane_type']) ?></p>
        <p><strong>Capacity:</strong> <?= htmlspecialchars($flight['capacity']) ?></p>
        <p><strong>Booked Seats:</strong> <?= htmlspecialchars($flight['booked_seats']) ?></p>
        <p><strong>Available Seats:</strong> <?= htmlspecialchars($flight['available_seats']) ?></p>
    </div>

    <p><a href="index.php">Back to Search</a></p>
</div>
</body>
</html>