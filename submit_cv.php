<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['cv']['tmp_name'];
        $fileName = $_FILES['cv']['name'];
        $fileType = $_FILES['cv']['type'];
        $fileSize = $_FILES['cv']['size'];

        // Sanitize file name
        $fileName = preg_replace("/[^a-zA-Z0-9\.\-\_]/", "", $fileName);

        $allowedfileExtensions = array('doc', 'docx', 'pdf');
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $mail->isSMTP();
            // SMTP configuration
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'abd.alrahman.olabi@gmail.com';
            $mail->Password = 'hgmejbjmobfzkxba';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';
            $mail->setFrom('abd.alrahman.olabi@gmail.com', 'قسم الموارد البشرية');
            $mail->addAddress('abd.alrahman.olabi@gmail.com');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'تقديم سيرة ذاتية جديدة';
            $mail->Body = 'تم تقديم سيرة ذاتية.<br>';
            $mail->AltBody = 'تم تقديم سيرة ذاتية.';

            // Attach file
            $mail->addAttachment($fileTmpPath, $fileName);

            if ($mail->send()) {
                echo '<script>alert("تم تقديم سيرتك الذاتية بنجاح."); window.location="Home.html";</script>';
            } else {
                throw new Exception('خطأ في المرسل: ' . $mail->ErrorInfo);
            }
        } else {
            echo '<script>alert("نوع الملف غير صالح. يرجى تقديم ملفات PDF أو DOC أو DOCX فقط."); window.location="Home.html";</script>';
        }
    } else {
        throw new Exception("خطأ: لم يتم تحميل أي ملف أو الملف كبير جدًا.");
    }
} catch (Exception $e) {
    echo '<script>alert("' . $e->getMessage() . '"); window.location="Home.html";</script>';
}
?>