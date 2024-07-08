<?php
// Retrieve form data
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$country = $_POST['country'] ?? '';
$dob = $_POST['dob'] ?? '';
$fatherName = $_POST['fatherName'] ?? '';
$class = $_POST['class'] ?? '';

// Validate required inputs
if (empty($firstName) || empty($lastName) || empty($country) || empty($dob) || empty($fatherName) || empty($class)) {
    echo '<script>
            alert("All fields are required. Please fill out all required fields.");
            window.location.href = "Home.php"; // Redirect to the registration form page
          </script>';
    exit();
}

// Here you can add your logic to store data to a database or send an email

// For demonstration, let's assume we're sending an email (to be replaced or removed depending on your needs)
$emailheader = "From: Virtual School Registration <noreply@yourdomain.com>\r\n";
$emailheader .= "MIME-Version: 1.0\r\n";
$emailheader .= "Content-Type: text/html; charset=utf-8\r\n";
$emailheader .= "X-Mailer: PHP/" . phpversion();

// Recipient email address
$recipient = "abd.alrahman.olabi@gmail.com"; // Replace with your or school's admin email

// Construct email body
$body = "
<html>
<head>
  <style>
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
      background-color: #f9f9f9;
      border-radius: 10px;
      text-align: left;
    }
    .content {
      font-family: Arial, sans-serif;
      line-height: 1.6;
    }
  </style>
</head>
<body>
  <div class='container'>
    <h2>Student Registration Details:</h2>
    <div class='content'>
      <p><strong>First Name:</strong> " . htmlspecialchars($firstName) . "</p>
      <p><strong>Last Name:</strong> " . htmlspecialchars($lastName) . "</p>
      <p><strong>Country:</strong> " . htmlspecialchars($country) . "</p>
      <p><strong>Date of Birth:</strong> " . htmlspecialchars($dob) . "</p>
      <p><strong>Father's Name:</strong> " . htmlspecialchars($fatherName) . "</p>
      <p><strong>Current Grade:</strong> " . htmlspecialchars($class) . "</p>
    </div>
  </div>
</body>
</html>
";

// Email subject
$subject = "Alm-International School - New Student Registration";

// Send email and handle success or failure
if (mail($recipient, $subject, $body, $emailheader)) {
    echo '<script>
            alert("Thank You for Registering. Your Information Has Been Successfully Submitted.");
            window.location.href = "Home.php"; // Redirect to the registration form page or to a confirmation page
          </script>';
} else {
    echo '<script>
            alert("Sorry, There Was an Error While Processing Your Registration. Please Try Again Later.");
            window.location.href = "Home.php"; // Redirect to the registration form page
          </script>';
}
?>