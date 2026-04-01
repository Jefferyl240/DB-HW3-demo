CREATE VIEW AirportView AS
SELECT airport_code, name, city
FROM Airport;

--1
INSERT INTO AirportView (airport_code, name, city)
VALUES ('''DXB''', '''Dubai International''', '''Dubai''');

--2
DELETE FROM AirportView
WHERE city = 'Chicago';

--3
-- Not possible using only AirportView

--4
SELECT
    city,
    COUNT(DISTINCT airport_code) AS num_airports
FROM AirportView
GROUP BY city;