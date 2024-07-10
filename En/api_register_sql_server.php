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

// Database connection
try {
    $pdo = new PDO('sqlsrv:Server=localhost;Database=website_db', 'sa', 'olabi9591@$');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO students (firstName, lastName, country, dob, fatherName, class) VALUES (?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bindParam(1, $firstName);
    $stmt->bindParam(2, $lastName);
    $stmt->bindParam(3, $country);
    $stmt->bindParam(4, $dob);
    $stmt->bindParam(5, $fatherName);
    $stmt->bindParam(6, $class);

    // Execute the statement
    $stmt->execute();

    // Check if insert was successful
    if ($stmt->rowCount()) {
        http_response_code(200); 
        echo json_encode(['message' => 'Thank you for registering. Your information has been successfully submitted.']);
    } else {
        // If no rows were inserted
        http_response_code(500); 
        echo json_encode(['message' => 'Registration failed, please try again.']);
    }
} catch (PDOException $e) {
    // Return error message if connection fails
    http_response_code(500); 
    echo json_encode(['message' => 'Database error: ' . $e->getMessage()]);
}
?>
