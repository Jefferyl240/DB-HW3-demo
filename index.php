<?php
require 'config.php';

$flights = [];
$error = "";

$origin = $_GET['origin'] ?? '';
$dest = $_GET['dest'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $origin = strtoupper(trim($origin));
    $dest = strtoupper(trim($dest));

    if (strlen($origin) !== 3 || strlen($dest) !== 3) {
        $error = "Airport codes must be exactly 3 letters.";
    } elseif (!$start_date || !$end_date) {
        $error = "Please select both a start date and an end date.";
    } elseif ($start_date > $end_date) {
        $error = "Start date cannot be later than end date.";
    } else {
        $sql = "
            SELECT
                fs.flight_number,
                f.departure_date,
                fs.origin_code,
                fs.dest_code,
                fs.departure_time
            FROM Flight f
            JOIN FlightService fs
              ON f.flight_number = fs.flight_number
            WHERE fs.origin_code = :origin
              AND fs.dest_code = :dest
              AND f.departure_date BETWEEN :start_date AND :end_date
            ORDER BY f.departure_date, fs.departure_time, fs.flight_number
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':origin' => $origin,
            ':dest' => $dest,
            ':start_date' => $start_date,
            ':end_date' => $end_date
        ]);

        $flights = $stmt->fetchAll();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flight Search</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Flight Search</h1>

    <form method="GET" class="search-form">
        <div class="row">
            <div class="field">
                <label for="origin">Source Airport Code</label>
                <input type="text" id="origin" name="origin" maxlength="3"
                       value="<?= htmlspecialchars($origin) ?>" required>
            </div>

            <div class="field">
                <label for="dest">Destination Airport Code</label>
                <input type="text" id="dest" name="dest" maxlength="3"
                       value="<?= htmlspecialchars($dest) ?>" required>
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date"
                       value="<?= htmlspecialchars($start_date) ?>" required>
            </div>

            <div class="field">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date"
                       value="<?= htmlspecialchars($end_date) ?>" required>
            </div>
        </div>

        <button type="submit" name="search" value="1">Search Flights</button>
    </form>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['search']) && !$error): ?>
        <h2>Available Flights</h2>

        <?php if (count($flights) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Flight Number</th>
                        <th>Departure Date</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Departure Time</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($flights as $flight): ?>
                        <tr>
                            <td><?= htmlspecialchars($flight['flight_number']) ?></td>
                            <td><?= htmlspecialchars($flight['departure_date']) ?></td>
                            <td><?= htmlspecialchars($flight['origin_code']) ?></td>
                            <td><?= htmlspecialchars($flight['dest_code']) ?></td>
                            <td><?= htmlspecialchars($flight['departure_time']) ?></td>
                            <td>
                                <a class="details-link"
                                   href="flight_details.php?flight_number=<?= urlencode($flight['flight_number']) ?>&departure_date=<?= urlencode($flight['departure_date']) ?>">
                                   View Seats
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No matching flights found.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>