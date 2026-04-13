# Flight Search Web App

## Overview
This project is a simple PHP and PostgreSQL web application for searching flights between two airports over a selected date range.

Users can:
- enter a source airport code
- enter a destination airport code
- choose a start date and end date
- view all matching flights
- click a flight to see seat availability details

## Features
- Search flights by origin, destination, and departure date range
- Display flight number, departure date, origin, destination, and departure time
- View detailed seat information for each flight
- Input validation for airport codes and date range
- PostgreSQL database connection using PDO

## Project Files
- `index.php`  
  Main search page for finding flights.

- `flight_details.php`  
  Displays detailed information for a selected flight, including:
  - plane type
  - aircraft capacity
  - booked seats
  - available seats

- `config.php`  
  Stores PostgreSQL database connection settings.

## Requirements
- PHP
- PostgreSQL
- PDO PostgreSQL driver enabled
- A local web server such as XAMPP, MAMP, WAMP, or Apache

## Database Setup
Make sure you have a PostgreSQL database created for this project.

Example connection settings are stored in `config.php`:
- host
- port
- database name
- username
- password

Update these values to match your own PostgreSQL setup before running the project.

## How to Run
1. Place the project files inside your web server directory.
2. Start Apache and PostgreSQL.
3. Make sure your database is created and contains the required tables and data.
4. Update `config.php` with your PostgreSQL credentials.
5. Open your browser and go to the project URL.

Example:
```text
http://localhost/index.php
