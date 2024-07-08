<?php
// Include CORS headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

// Get the JSON POST body
$data = json_decode(file_get_contents("php://input"), true);

$firstName = $data['firstName'] ?? '';
$lastName = $data['lastName'] ?? '';
$country = $data['country'] ?? '';
$dob = $data['dob'] ?? '';
$fatherName = $data['fatherName'] ?? '';
$class = $data['class'] ?? '';

// Validate required inputs
if (empty($firstName) || empty($lastName) || empty($country) || empty($dob) || empty($fatherName) || empty($class)) {
    http_response_code(400);
    echo json_encode(['message' => 'All fields are required. Please fill out all required fields.']);
    exit();
}

// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=website_db', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO students (firstName, lastName, country, dob, fatherName, class) VALUES (:firstName, :lastName, :country, :dob, :fatherName, :class)");

    // Bind parameters
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':dob', $dob);
    $stmt->bindParam(':fatherName', $fatherName);
    $stmt->bindParam(':class', $class);

    // Execute the statement
    $stmt->execute();

    // Check if insert was successful
    if ($stmt->rowCount()) {
        http_response_code(200); // Explicitly setting success status
        echo json_encode(['message' => 'Thank you for registering. Your information has been successfully submitted.']);
    } else {
        // If no rows were inserted
        http_response_code(500); // Internal Server Error
        echo json_encode(['message' => 'Registration failed, please try again.']);
    }
} catch (PDOException $e) {
    // Return error message if connection fails
    http_response_code(500); // Internal Server Error
    echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
