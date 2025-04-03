<?php
session_start();
include('db_connect.php');

if (!isset($_GET['token'])) {
    header("Location: login.php");
    exit();
}

$token = mysqli_real_escape_string($conn, $_GET['token']);

// Verify token exists and is valid
$sql = "SELECT * FROM students WHERE reset_token = '$token'";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password and remove reset token
        $update_sql = "UPDATE students SET password = '$hashed_password', reset_token = NULL 
                      WHERE reset_token = '$token'";
        
        if ($conn->query($update_sql)) {
            $success_message = "Password has been reset successfully. You can now login with your new password.";
        } else {
            $error_message = "Error updating password. Please try again.";
        }
    } else {
        $error_message = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        .password-requirements {
            margin-top: 10px;
            font-size: 0.8rem;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Password</h2>
            <p>Enter your new password</p>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
                <div class="register-link">
                    <a href="student_login.php">Proceed to Login</a>
                </div>
            </div>
        <?php else: ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group" style="--i:1">
                <i class="fas fa-lock icon"></i>
                <input type="password" class="form-control" id="password" name="password" 
                       placeholder="New Password" required minlength="8">
            </div>

            <div class="form-group" style="--i:2">
                <i class="fas fa-lock icon"></i>
                <input type="password" class="form-control" id="confirm_password" 
                       name="confirm_password" placeholder="Confirm Password" required minlength="8">
            </div>

            <div class="password-requirements">
                Password must be at least 8 characters long
            </div>

            <button type="submit" class="btn-login">
                Reset Password
            </button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>