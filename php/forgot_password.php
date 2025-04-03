<?php
session_start();
include('db_connect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/src/Exception.php';
require '../vendor/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists
    $sql = "SELECT * FROM students WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Update database with reset token
        $update_sql = "UPDATE students SET reset_token = '$token' WHERE email = '$email'";
        $conn->query($update_sql);
        
        // Send email
        $mail = new PHPMailer(true);
        
        try {

            $mail->isSMTP();
            $mail->Host = 'mail.nmimstechfiesta.in';
            $mail->SMTPAuth = true;
            $mail->Username = 'supportteam@nmimstechfiesta.in';
            $mail->Password = 'Nmims@1234';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('supportteam@nmimstechfiesta.in', 'Techfest');
            $mail->addAddress($email);
            
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $reset_link = "http://nmimstechfiesta.in/php/reset_password.php?token=" . $token;
          $mail->Body = "
<html>
<body style='font-family: \"Arial\", sans-serif; background-color: #000000; color: #800000; line-height: 1.6; padding: 20px;'>
    <div style='max-width: 600px; margin: 0 auto; background-color: #1a1a1a; border-radius: 10px; padding: 30px; text-align: center;'>
        
        <h1 style='color: #800000; font-size: 24px;'>Password Reset Request</h1>
        
        <p style='color: #a9a9a9;'>A password reset has been requested for your Techfest account.</p>
        
        <a href='$reset_link' style='
            display: inline-block; 
            background-color: #800000; 
            color: #ffffff; 
            padding: 12px 24px; 
            text-decoration: none; 
            border-radius: 5px; 
            margin: 20px 0;
            font-weight: bold;
        '>Reset Your Password</a>
        
        <p style='color: #a9a9a9; font-size: 12px;'>
            This link will expire in 1 hour. If you didn't request this reset, please ignore this email.
        </p>
        
        <footer style='margin-top: 20px; color: #a9a9a9; font-size: 10px;'>
            © Nmims Techfiesta | Innovate Beyond Boundaries
        </footer>
    </div>
</body>
</html>";
            $mail->send();
            $success_message = "Password reset instructions have been sent to your email.";
        } catch (Exception $e) {
            $error_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error_message = "Email address not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
              * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-image: url("../images/bg.png");
            font-family: 'Roboto', sans-serif;
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, #80000015 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, #40000015 0%, transparent 50%);
            pointer-events: none;
        }

        .container {
            background: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(128, 0, 0, 0.2);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
            animation: containerAppear 0.5s ease-out;
        }

        .container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(
                from 0deg at 50% 50%,
                transparent 0%,
                rgba(128, 0, 0, 0.1) 25%,
                transparent 25%
            );
            animation: rotate 15s linear infinite;
            z-index: -1;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes containerAppear {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            font-family: 'Orbitron', sans-serif;
        }

        .header h2 {
            font-size: 2.5rem;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            text-shadow: 0 0 10px rgba(128, 0, 0, 0.5);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
            animation: slideIn 0.5s ease-out forwards;
            opacity: 0;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .form-control {
            width: 100%;
            padding: 12px 45px;
            background: rgba(20, 20, 20, 0.9);
            border: 1px solid rgba(128, 0, 0, 0.3);
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #800000;
            box-shadow: 0 0 15px rgba(128, 0, 0, 0.3);
            background: rgba(25, 25, 25, 0.9);
        }

        .icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #800000;
            font-size: 1.2rem;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #800000, #400000);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(128, 0, 0, 0.4);
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }

        .btn-login:hover::after {
            animation: shine 1.5s ease;
        }

        @keyframes shine {
            from {
                transform: translateX(-100%) rotate(45deg);
            }
            to {
                transform: translateX(100%) rotate(45deg);
            }
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #aaa;
        }

        .register-link a {
            color:red;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: #a00000;
            text-shadow: 0 0 10px rgba(128, 0, 0, 0.5);
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .forgot-password a {
            color: red;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .forgot-password a:hover {
            color: #a00000;
            text-shadow: 0 0 10px rgba(128, 0, 0, 0.5);
        }

        .form-group {
            animation-delay: calc(var(--i) * 0.1s);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reset Password</h2>
            <p>Enter your email to receive reset instructions</p>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group" style="--i:1">
                <i class="fas fa-envelope icon"></i>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
            </div>

            <button type="submit" class="btn-login">
                Send Reset Link
            </button>

            <div class="register-link">
                Remember your password? <a href="student_login.php">Login</a>
            </div>
        </form>
    </div>
</body>
</html>