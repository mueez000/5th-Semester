<?php
// Database Configuration
$host = "localhost";
$username = "root"; // Change as needed
$password = ""; // Change as needed
$database = "learnercanvas4";

// Create Connection
$conn = new mysqli($host, $username, $password);

// Check Connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Create Database if not exists
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql_create_db) === FALSE) {
    die("Error creating database: " . $conn->error);
}

// Select Database
$conn->select_db($database);

// Create Tables
$tables = [
    "CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        degree VARCHAR(255),
        graduation_year VARCHAR(4),
        profile_completed BOOLEAN DEFAULT 0,
        photo VARCHAR(255),
        resume VARCHAR(255)
    )",

    "CREATE TABLE IF NOT EXISTS alumni (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        job_title VARCHAR(255),
        company VARCHAR(255),
        linkedin VARCHAR(255),
        profile_completed BOOLEAN DEFAULT 0,
        photo VARCHAR(255),
        resume VARCHAR(255)
    )",

    "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )",

    "CREATE TABLE IF NOT EXISTS mentors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        job_title VARCHAR(255),
        company VARCHAR(255),
        photo VARCHAR(255)
    )",

    "CREATE TABLE IF NOT EXISTS mentorship_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        mentor_id INT NOT NULL,
        FOREIGN KEY (student_id) REFERENCES students(id),
        FOREIGN KEY (mentor_id) REFERENCES mentors(id)
    )",

    "CREATE TABLE IF NOT EXISTS accepted_mentorships (
        id INT AUTO_INCREMENT PRIMARY KEY,
        mentor_id INT NOT NULL,
        student_id INT NOT NULL,
        FOREIGN KEY (mentor_id) REFERENCES mentors(id),
        FOREIGN KEY (student_id) REFERENCES students(id)
    )",

    "CREATE TABLE IF NOT EXISTS jobs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        company VARCHAR(255) NOT NULL,
        location VARCHAR(255),
        employment_type VARCHAR(50),
        deadline DATE,
        website VARCHAR(255),
        experience TEXT,
        skills TEXT,
        description TEXT,
        documents TEXT,
        alumni_id INT NOT NULL,
        FOREIGN KEY (alumni_id) REFERENCES alumni(id)
    )",

    "CREATE TABLE IF NOT EXISTS freelance_projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        company VARCHAR(255),
        location VARCHAR(255),
        employment_type VARCHAR(50),
        deadline VARCHAR(20),
        contact_email VARCHAR(255),
        experience TEXT,
        skills TEXT,
        description TEXT,
        documents TEXT,
        alumni_id INT NOT NULL,
        FOREIGN KEY (alumni_id) REFERENCES alumni(id)
    )",

    "CREATE TABLE IF NOT EXISTS feedback (
        id INT AUTO_INCREMENT PRIMARY KEY,
        alumni_id INT NOT NULL,
        satisfaction VARCHAR(255),
        education TEXT,
        faculty TEXT,
        facilities VARCHAR(255),
        networking TEXT,
        career TEXT,
        alumni TEXT,
        improvements TEXT,
        industry_support TEXT,
        comments TEXT,
        FOREIGN KEY (alumni_id) REFERENCES alumni(id)
    )",

    "CREATE TABLE IF NOT EXISTS success_stories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        role VARCHAR(255),
        story TEXT,
        image VARCHAR(255)
    )",

 "CREATE TABLE IF NOT EXISTS profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        role VARCHAR(50),
        education VARCHAR(255),
        job_title VARCHAR(255),
        company VARCHAR(255),
        linkedin VARCHAR(255),
        experience TEXT,
        skills TEXT,
        description TEXT,
        photo VARCHAR(255),
        resume VARCHAR(255),
        profile_completed BOOLEAN DEFAULT 0
    )"
];

// Execute Table Creation Quer

// Execute Table Creation Queries
foreach ($tables as $sql) {
    if ($conn->query($sql) === FALSE) {
        die("Error creating table: " . $conn->error);
    }
}


?>