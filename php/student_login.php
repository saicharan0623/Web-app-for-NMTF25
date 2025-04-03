<?php
session_start();  // Start session

include('db_connect.php');

$email_error = '';
$password_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $sql = "SELECT * FROM students WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email']; 

            header("Location: student_dashboard.php");
            exit(); 
        } else {
            $password_error = "Incorrect password!";
        }
    } else {
        $email_error = "Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        
        /* Add these new styles for error messages */
        .error-message {
            color: #ff0000;
            font-size: 0.8rem;
            margin-top: 5px;
            margin-left: 45px;
            display: block;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group.error .form-control {
            border-color: #ff0000;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.1);
        }

        .form-group.error .icon {
            color: #ff0000;
        }
             .register-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
        }

        .register-text {
            color: #aaa;
            font-size: 0.9rem;
        }

        .btn-register {
            background: linear-gradient(45deg, #00a000, #006000);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-decoration:none;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 160, 0, 0.4);
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transform: rotate(45deg);
            transition: all 0.5s ease;
            opacity: 0;
        }

        .btn-register:hover::before {
            opacity: 1;
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

        /* Pulsing animation for register button */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .btn-register.pulse {
            animation: pulse 1.5s infinite;
        }
        .event-note {
            background-color: rgba(0, 128, 0, 0.1);
            border-left: 4px solid green;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 0.9rem;
            color: #fff;
        }

        /* Rest of your previous styles */
    </style>
</head>
<body>
  <div class="container">
        <div class="header">
            <h2>Login</h2>
            <p>Welcome back to techfest</p>
        </div>

        <!-- New Event Note Section -->
        <div class="event-note">
            🎉 Not registered yet? Click on register now button and create an account before login.
        </div>

        <form method="POST" action="">
            <div class="form-group <?php echo $email_error ? 'error' : ''; ?>" style="--i:1">
                <i class="fas fa-envelope icon"></i>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    name="email" 
                    placeholder="Email Address" 
                    required
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                >
                <?php if ($email_error): ?>
                    <span class="error-message"><?php echo $email_error; ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo $password_error ? 'error' : ''; ?>" style="--i:2">
                <i class="fas fa-lock icon"></i>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Password" 
                    required
                >
                <?php if ($password_error): ?>
                    <span class="error-message"><?php echo $password_error; ?></span>
                <?php endif; ?>
            </div>

            <div class="forgot-password">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-login">
                Login
            </button>

            <div class="register-section">
                <span class="register-text">Don't have an account?</span>
                <a href="register.php" class="btn-register pulse">
                    Register Now
                </a>
            </div>
        </form>
    </div>
</body>
</html>