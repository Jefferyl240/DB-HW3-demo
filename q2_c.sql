CREATE VIEW FlightOccupancy AS
SELECT
    f.flight_number,
    f.departure_date,
    (
        (f.departure_date + fs.departure_time + fs.duration)::date
    ) AS arrival_date,
    fs.origin_code,
    fs.dest_code,
    a.capacity,
    COUNT(b.pid) AS total_passengers
FROM Flight f
JOIN FlightService fs
    ON f.flight_number = fs.flight_number
JOIN Aircraft a
    ON f.plane_type = a.plane_type
LEFT JOIN Booking b
    ON b.flight_number = f.flight_number
   AND b.departure_date = f.departure_date
GROUP BY
    f.flight_number,
    f.departure_date,
    fs.departure_time,
    fs.duration,
    fs.origin_code,
    fs.dest_code,
    a.capacity;

-- (1)
SELECT
    flight_number,
    departure_date,
    total_passengers
FROM FlightOccupancy
ORDER BY total_passengers DESC
LIMIT 1;

-- (2)
SELECT
    dest_code AS airport_code,
    SUM(total_passengers) AS total_arriving_passengers
FROM FlightOccupancy
WHERE arrival_date = DATE '2025-12-31'
GROUP BY dest_code;

-- (3)
SELECT
    flight_number,
    departure_date,
    arrival_date,
    origin_code,
    dest_code,
    capacity,
    total_passengers
FROM FlightOccupancy
WHERE total_passengers > 0.9 * capacity;