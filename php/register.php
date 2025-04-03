<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $sapid = mysqli_real_escape_string($conn, $_POST['sapid']);
    $campus = mysqli_real_escape_string($conn, $_POST['campus']);
    $stream = mysqli_real_escape_string($conn, $_POST['stream']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $mobile_number = mysqli_real_escape_string($conn, $_POST['mobile_number']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash password

    // Check if email already exists
    $email_check = "SELECT * FROM students WHERE email = '$email'";
    $result = $conn->query($email_check);
    if ($result->num_rows > 0) {
        $error = "Email already exists!";
    } else {
        // Insert new student into the database
        $sql = "INSERT INTO students (name, sapid, campus, stream, year, mobile_number, gender, email, password)
                VALUES ('$name', '$sapid', '$campus', '$stream', '$year', '$mobile_number', '$gender', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            // Get the newly inserted student's ID
            $student_id = $conn->insert_id;

            $add_coins_sql = "UPDATE students SET festacoins = festacoins + 999 WHERE sapid = '$sapid'";

            if ($conn->query($add_coins_sql) === TRUE) {
                // Redirect to login after successful registration and coin addition
                header("Location: student_login.php");
                exit();
            } else {
                $error = "Error adding Fiesta coins: " . $conn->error;
            }
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp</title>
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
            max-width: 900px;
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

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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

        select.form-control {
            appearance: none;
            cursor: pointer;
        }

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(128, 0, 0, 0.4);
        }

        .btn-register::after {
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

        .btn-register:hover::after {
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

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: #aaa;
        }

        .login-link a {
            color:red;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: #a00000;
            text-shadow: 0 0 10px rgba(128, 0, 0, 0.5);
        }

        @media (max-width: 768px) {
            .row {
                grid-template-columns: 1fr;
            }
            .container {
                padding: 20px;
            }
        }

        .form-group {
            animation-delay: calc(var(--i) * 0.1s);
        }
 .login-link {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 20px;
    gap: 10px;
    color:#fff;
}
.login-text {
    color: #aaa;
    font-size: 0.9rem;
}
.btn-login {
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
}
.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 160, 0, 0.4);
}
.btn-login::before {
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
.btn-login:hover::before {
    opacity: 1;
    animation: login-shine 1.5s ease;
}
 @keyframes shine {
            from {
                transform: translateX(-100%) rotate(45deg);
            }
            to {
                transform: translateX(100%) rotate(45deg);
            }
        }

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

.btn-login.pulse {
    animation: pulse 1.5s infinite ease-in-out;
}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="font-size:2rem;">Registration</h2>
            <p>Get ready for techfest</p>
        </div>

        <form method="POST" action="">
            <div class="row">
                <div class="form-group" style="--i:1">
                    <i class="fas fa-user icon"></i>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" required>
                </div>
                <div class="form-group" style="--i:2">
                    <i class="fas fa-id-card icon"></i>
                 <input type="text" class="form-control" id="sapid" name="sapid" placeholder="SAP ID" maxlength="11" pattern="\d{11}" required>

                </div>
            </div>

            <div class="row">
                <div class="form-group" style="--i:3">
                    <i class="fas fa-university icon"></i>
                    <select class="form-control" id="campus" name="campus" required>
                        <option value="" disabled selected>Select Campus</option>
                       <option value="NMIMS_Mumbai_Campus">NMIMS Mumbai Campus</option>
<option value="NMIMS_NaviMumbai_Campus">NMIMS Navi Mumbai Campus</option>
<option value="NMIMS_Shirpur_Campus">NMIMS Shirpur Campus</option>
<option value="NMIMS_Indore_Campus">NMIMS Indore Campus</option>
<option value="NMIMS_Chandigarh_Campus">NMIMS Chandigarh Campus</option>
<option value="NMIMS_Hyderabad_Campus">NMIMS Hyderabad Campus</option>
<option value="Others">Others</option>

                    </select>
                </div>
                <div class="form-group" style="--i:4">
                    <i class="fas fa-graduation-cap icon"></i>
                    <select class="form-control" id="stream" name="stream" required>
        <option value="" disabled selected>Select Stream</option>
        <option value="BTECH_COMPUTER">BTECH COMPUTER</option>
        <option value="BTECH_DATA_SCIENCE">BTECH DATA SCIENCE</option>
        <option value="BTECH_DATA_SCIENCE_311">BTECH DATA SCIENCE 311</option>
        <option value="BTECH_IT">BTECH IT</option>
        <option value="BTECH_AIDS">BTECH AIDS</option>
        <option value="BTECH_CE">BTECH CE</option>
        <option value="BTECH_CS">BTECH CS</option>
        <option value="BTECH_CSBS">BTECH CSBS</option>
        <option value="MBA_TECH_COMPUTER">MBA TECH COMPUTER</option>
        <option value="Other">Other</option>


                    </select>                
                </div>
            </div>

            <div class="row">
                <div class="form-group" style="--i:5">
                    <i class="fas fa-calendar-alt icon"></i>
                    <select class="form-control" id="year" name="year" required>
                        <option value="" disabled selected>Select Year</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                        <option value="5">5th Year</option>
                    </select>
                </div>
                <div class="form-group" style="--i:6">
                    <i class="fas fa-phone icon"></i>
                    <input type="tel" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile Number" required>
                </div>
            </div>

            <div class="row">
                <div class="form-group" style="--i:7">
                    <i class="fas fa-venus-mars icon"></i>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group" style="--i:8">
                    <i class="fas fa-envelope icon"></i>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                </div>
            </div>

            <div class="form-group" style="--i:9">
                <i class="fas fa-lock icon"></i>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-register">
                Register
            </button>

          <div class="login-link">
    <span class="login-text">Already have an account?</span>
    <a href="student_login.php" class="btn-login" style="color:#fff;">Login Now</a>
</div>
        </form>
    </div>
</body>
</html>