<?php
// Retrieve form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

  // Construct email headers
  $emailheader = "From: " . $name . " <" . $email . ">\r\n";
  $emailheader .= "Reply-To: " . $email . "\r\n";
  $emailheader .= "MIME-Version: 1.0\r\n";
  $emailheader .= "Content-Type: text/html; charset=utf-8\r\n";
  $emailheader .= "X-Mailer: PHP/" . phpversion();

// Recipient email address
$recipient = "abd.alrahman.olabi@gmail.com";

// Construct email body
$body = '
  <html>
  <head>
    <style>
      .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 10px;
        text-align: right;
      }
      .content {
        font-family: Arial, sans-serif;
        line-height: 1.6;
      }
      .message {
        padding: 10px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h2>:تفاصيل الرسالة</h2>
      <div class="content">
        <p><strong>الاسم:</strong> ' . htmlspecialchars($name) . '</p>
        <p><strong>البريد الإلكتروني:</strong> ' . htmlspecialchars($email) . '</p>
        <p><strong>الموضوع:</strong> ' . htmlspecialchars($subject) . '</p>
        <div class="message">
          <p><strong>:الرسالة</strong></p>
          <p>' . nl2br(htmlspecialchars($message)) . '</p>
        </div>
      </div>
    </div>
  </body>
  </html>
  ';

// Email subject
$subject = "مدرسة علم الدولية - " . $_POST['subject'];

// Send email and handle success or failure
if (mail($recipient, $subject, $body, $emailheader)) {
  echo '<script>
            alert("تم إرسال رسالتك بنجاح.");
            window.location.href = "Home.html"; 
          </script>';
} else {
  echo '<script>
            alert("عذراً، حدث خطأ أثناء إرسال رسالتك. يرجى المحاولة مرة أخرى لاحقاً.");
            window.location.href = "Home.html"; 
          </script>';
}
?>