<?php
session_start();

include('db_connect.php');

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    
    // Fetch student data
    $sql = "SELECT name, sapid, campus, stream, year, mobile_number, gender, email, festacoins FROM students WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Student not found. Please try logging in again.";
        header("Location: student_login.php");
        exit();
    }
    $position_sql = "
    SELECT position 
    FROM (
        SELECT 
            email,
            festacoins,
            DENSE_RANK() OVER (ORDER BY festacoins DESC) as position
        FROM students
        WHERE festacoins > 0
    ) ranked 
    WHERE email = ?";
$position_stmt = $conn->prepare($position_sql);
$position_stmt->bind_param("s", $email);
$position_stmt->execute();
$position_result = $position_stmt->get_result();
$position_data = $position_result->fetch_assoc();
if ($position_data) {
    $student_position = $position_data['position'];
} else {
    echo "Position data not available.";
}
} else {
    header("Location: student_login.php");
    exit();
}
function logout() {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Redirect to login page
    header("Location: ../index.html");
    exit();
}

// Check if logout parameter is set
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    logout();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/loaders/FBXLoader.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-maroon: #800020;
            --secondary-maroon: #5c0017;
            --accent-white: #ffffff;
            --bg-black: #000000;
            --card-black: #0a0a0a;
            --neon-glow: 0 0 10px rgba(128, 0, 32, 0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Orbitron', sans-serif;
        }

        body {
            background: var(--bg-black);
            color: var(--accent-white);
            min-height: 100vh;
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
                linear-gradient(45deg, transparent 48%, rgba(128, 0, 32, 0.1) 50%, transparent 52%),
                linear-gradient(-45deg, transparent 48%, rgba(128, 0, 32, 0.1) 50%, transparent 52%);
            background-size: 60px 60px;
            z-index: 0;
            pointer-events: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        .welcome-header {
            background: linear-gradient(135deg, rgba(128, 0, 32, 0.2), rgba(10, 10, 10, 0.9));
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border: 1px solid var(--primary-maroon);
            box-shadow: var(--neon-glow);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .welcome-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary-maroon), transparent);
            animation: scan-line 2s linear infinite;
        }

        @keyframes scan-line {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .welcome-text {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 3px;
            color: var(--accent-white);
            text-shadow: 0 0 10px rgba(128, 0, 32, 0.8);
        }

        .register-btn {
            background: var(--primary-maroon);
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            color: var(--accent-white);
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            overflow: hidden;
        }

        .register-btn:hover {
            background: var(--secondary-maroon);
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(128, 0, 32, 0.5);
        }

        .register-btn::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(45deg);
            animation: btn-shine 3s linear infinite;
        }

        @keyframes btn-shine {
            0% { transform: rotate(45deg) translateY(-100%); }
            100% { transform: rotate(45deg) translateY(100%); }
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .card {
            background: linear-gradient(145deg, var(--card-black), rgba(10, 10, 10, 0.9));
            border-radius: 15px;
            padding: 2rem;
            border: 1px solid var(--primary-maroon);
            box-shadow: var(--neon-glow);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 65%, rgba(128, 0, 32, 0.1) 75%, transparent 85%);
            animation: card-shine 3s linear infinite;
        }

        @keyframes card-shine {
            0% { transform: translateX(-200%); }
            100% { transform: translateX(200%); }
        }

        .card-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid var(--primary-maroon);
            color: var(--accent-white);
            display: flex;
            align-items: center;
            gap: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(128, 0, 32, 0.5);
        }

        .info-grid {
            display: grid;
            gap: 1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(128, 0, 32, 0.1);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(128, 0, 32, 0.2);
        }

        .info-item:hover {
            background: rgba(128, 0, 32, 0.2);
            transform: translateX(10px);
            box-shadow: var(--neon-glow);
        }

        .info-label {
            font-weight: 600;
            min-width: 100px;
            color: var(--accent-white);
            text-transform: uppercase;
            font-size: 0.9rem;
            opacity: 0.8;
        }
        .coin {
  position: relative;
  width: 250px;
  height: 250px;
  margin: 50px auto;
  transform-style: preserve-3d;
  -webkit-animation: rotate3d 8s linear infinite;
          animation: rotate3d 8s linear infinite;
  transition: all 0.3s;
}

.coin__front,
.coin__back {
  position: absolute;
  width: 100%; /* Match coin width */
  height: 100%; /* Match coin height */
  border-radius: 50%;
  background-position: center;
  background-size: cover;
  overflow: hidden;
}
.coin__front:after,
.coin__back:after {
  content: "";
  position: absolute;
  left: -150px;
  bottom: 100%;
  display: block;
  height: 250px;
  width: 600px;
  background: #fff;
  opacity: 0.3;
  -webkit-animation: shine linear 4s infinite;
          animation: shine linear 4s infinite;
}

.coin__front {
  background-image: url("../images/10.png");
  background-size: cover;
  transform: translateZ(10px);
}

.coin__back {
    background-image: url("../images/9.png");
    background-size: cover;
  transform: translateZ(-10px) rotateY(180deg);
}

.coin__edge div:nth-child(1) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #c0c0c0;
  transform: translateY(144.1125px) translateX(140px) rotateZ(94.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(2) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #482f18;
  transform: translateY(144.1125px) translateX(140px) rotateZ(99deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(3) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #55371d;
  transform: translateY(144.1125px) translateX(140px) rotateZ(103.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(4) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #613f21;
  transform: translateY(144.1125px) translateX(140px) rotateZ(108deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(5) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #6d4625;
  transform: translateY(144.1125px) translateX(140px) rotateZ(112.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(6) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #784e29;
  transform: translateY(144.1125px) translateX(140px) rotateZ(117deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(7) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #84552c;
  transform: translateY(144.1125px) translateX(140px) rotateZ(121.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(8) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #8e5c30;
  transform: translateY(144.1125px) translateX(140px) rotateZ(126deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(9) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #996334;
  transform: translateY(144.1125px) translateX(140px) rotateZ(130.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(10) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #a36937;
  transform: translateY(144.1125px) translateX(140px) rotateZ(135deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(11) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #ad703a;
  transform: translateY(144.1125px) translateX(140px) rotateZ(139.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(12) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #b6763e;
  transform: translateY(144.1125px) translateX(140px) rotateZ(144deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(13) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #bf7c41;
  transform: translateY(144.1125px) translateX(140px) rotateZ(148.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(14) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #c2824a;
  transform: translateY(144.1125px) translateX(140px) rotateZ(153deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(15) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #c58853;
  transform: translateY(144.1125px) translateX(140px) rotateZ(157.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(16) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #c88e5b;
  transform: translateY(144.1125px) translateX(140px) rotateZ(162deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(17) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #ca9363;
  transform: translateY(144.1125px) translateX(140px) rotateZ(166.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(18) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #cd986a;
  transform: translateY(144.1125px) translateX(140px) rotateZ(171deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(19) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #cf9d71;
  transform: translateY(144.1125px) translateX(140px) rotateZ(175.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(20) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #d1a278;
  transform: translateY(144.1125px) translateX(140px) rotateZ(180deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(21) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #d4a67f;
  transform: translateY(144.1125px) translateX(140px) rotateZ(184.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(22) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #d6ab85;
  transform: translateY(144.1125px) translateX(140px) rotateZ(189deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(23) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #d8af8b;
  transform: translateY(144.1125px) translateX(140px) rotateZ(193.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(24) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #dab290;
  transform: translateY(144.1125px) translateX(140px) rotateZ(198deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(25) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #dbb695;
  transform: translateY(144.1125px) translateX(140px) rotateZ(202.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(26) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #ddb99a;
  transform: translateY(144.1125px) translateX(140px) rotateZ(207deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(27) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #debd9f;
  transform: translateY(144.1125px) translateX(140px) rotateZ(211.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(28) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e0bfa3;
  transform: translateY(144.1125px) translateX(140px) rotateZ(216deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(29) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e1c2a7;
  transform: translateY(144.1125px) translateX(140px) rotateZ(220.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(30) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e2c4aa;
  transform: translateY(144.1125px) translateX(140px) rotateZ(225deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(31) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e3c7ad;
  transform: translateY(144.1125px) translateX(140px) rotateZ(229.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(32) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e4c9b0;
  transform: translateY(144.1125px) translateX(140px) rotateZ(234deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(33) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e5cab3;
  transform: translateY(144.1125px) translateX(140px) rotateZ(238.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(34) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e6ccb5;
  transform: translateY(144.1125px) translateX(140px) rotateZ(243deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(35) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e7cdb7;
  transform: translateY(144.1125px) translateX(140px) rotateZ(247.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(36) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e7ceb8;
  transform: translateY(144.1125px) translateX(140px) rotateZ(252deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(37) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e7cfb9;
  transform: translateY(144.1125px) translateX(140px) rotateZ(256.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(38) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e8d0ba;
  transform: translateY(144.1125px) translateX(140px) rotateZ(261deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(39) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e8d0bb;
  transform: translateY(144.1125px) translateX(140px) rotateZ(265.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(40) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e8d0bb;
  transform: translateY(144.1125px) translateX(140px) rotateZ(270deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(41) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e8d0bb;
  transform: translateY(144.1125px) translateX(140px) rotateZ(274.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(42) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e8d0ba;
  transform: translateY(144.1125px) translateX(140px) rotateZ(279deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(43) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e7cfb9;
  transform: translateY(144.1125px) translateX(140px) rotateZ(283.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(44) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e7ceb8;
  transform: translateY(144.1125px) translateX(140px) rotateZ(288deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(45) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e7cdb7;
  transform: translateY(144.1125px) translateX(140px) rotateZ(292.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(46) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e6ccb5;
  transform: translateY(144.1125px) translateX(140px) rotateZ(297deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(47) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e5cab3;
  transform: translateY(144.1125px) translateX(140px) rotateZ(301.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(48) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e4c9b0;
  transform: translateY(144.1125px) translateX(140px) rotateZ(306deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(49) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e3c7ad;
  transform: translateY(144.1125px) translateX(140px) rotateZ(310.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(50) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e2c4aa;
  transform: translateY(144.1125px) translateX(140px) rotateZ(315deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(51) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e1c2a7;
  transform: translateY(144.1125px) translateX(140px) rotateZ(319.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(52) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #e0bfa3;
  transform: translateY(144.1125px) translateX(140px) rotateZ(324deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(53) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #debd9f;
  transform: translateY(144.1125px) translateX(140px) rotateZ(328.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(54) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #ddb99a;
  transform: translateY(144.1125px) translateX(140px) rotateZ(333deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(55) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #dbb695;
  transform: translateY(144.1125px) translateX(140px) rotateZ(337.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(56) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #dab290;
  transform: translateY(144.1125px) translateX(140px) rotateZ(342deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(57) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #d8af8b;
  transform: translateY(144.1125px) translateX(140px) rotateZ(346.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(58) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #d6ab85;
  transform: translateY(144.1125px) translateX(140px) rotateZ(351deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(59) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #d4a67f;
  transform: translateY(144.1125px) translateX(140px) rotateZ(355.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(60) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #d1a278;
  transform: translateY(144.1125px) translateX(140px) rotateZ(360deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(61) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #cf9d71;
  transform: translateY(144.1125px) translateX(140px) rotateZ(364.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(62) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #cd986a;
  transform: translateY(144.1125px) translateX(140px) rotateZ(369deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(63) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #ca9363;
  transform: translateY(144.1125px) translateX(140px) rotateZ(373.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(64) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #c88e5b;
  transform: translateY(144.1125px) translateX(140px) rotateZ(378deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(65) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #c58853;
  transform: translateY(144.1125px) translateX(140px) rotateZ(382.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(66) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #c2824a;
  transform: translateY(144.1125px) translateX(140px) rotateZ(387deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(67) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #bf7c41;
  transform: translateY(144.1125px) translateX(140px) rotateZ(391.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(68) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #b6763e;
  transform: translateY(144.1125px) translateX(140px) rotateZ(396deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(69) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #ad703a;
  transform: translateY(144.1125px) translateX(140px) rotateZ(400.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(70) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #a36937;
  transform: translateY(144.1125px) translateX(140px) rotateZ(405deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(71) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #996334;
  transform: translateY(144.1125px) translateX(140px) rotateZ(409.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(72) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #8e5c30;
  transform: translateY(144.1125px) translateX(140px) rotateZ(414deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(73) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #84552c;
  transform: translateY(144.1125px) translateX(140px) rotateZ(418.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(74) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #784e29;
  transform: translateY(144.1125px) translateX(140px) rotateZ(423deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(75) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #6d4625;
  transform: translateY(144.1125px) translateX(140px) rotateZ(427.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(76) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #613f21;
  transform: translateY(144.1125px) translateX(140px) rotateZ(432deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(77) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #55371d;
  transform: translateY(144.1125px) translateX(140px) rotateZ(436.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(78) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #482f18;
  transform: translateY(144.1125px) translateX(140px) rotateZ(441deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(79) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #3c2714;
  transform: translateY(144.1125px) translateX(140px) rotateZ(445.5deg) translateX(150px) rotateY(90deg);
}
.coin__edge div:nth-child(80) {
  position: absolute;
  height: 11.775px;
  width: 20px;
  background: #2e1e10;
  transform: translateY(144.1125px) translateX(140px) rotateZ(450deg) translateX(150px) rotateY(90deg);
}

.coin__shadow {
  position: absolute;
  width: 300px;
  height: 20px;
  border-radius: 50%;
  background: #000;
  box-shadow: 0 0 100px 100px #000;
  opacity: 0.125;
  transform: rotateX(90deg) translateZ(-330px) scale(0.5);
}

@-webkit-keyframes rotate3d {
  0% {
    transform: perspective(1000px) rotateY(0deg);
  }
  100% {
    transform: perspective(1000px) rotateY(360deg);
  }
}

@keyframes rotate3d {
  0% {
    transform: perspective(1000px) rotateY(0deg);
  }
  100% {
    transform: perspective(1000px) rotateY(360deg);
  }
}
@-webkit-keyframes shine {
  0%, 15% {
    transform: translateY(600px) rotate(-40deg);
  }
  50% {
    transform: translateY(-300px) rotate(-40deg);
  }
}
@keyframes shine {
  0%, 15% {
    transform: translateY(600px) rotate(-40deg);
  }
  50% {
    transform: translateY(-300px) rotate(-40deg);
  }
}
        .position-value {
            font-size: 4rem;
            font-weight: 800;
            text-align: center;
            margin: 2rem 0;
            background: linear-gradient(135deg, var(--accent-white), var(--primary-maroon));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 20px rgba(128, 0, 32, 0.5);
        }

        .reward-item {
            background: rgba(128, 0, 32, 0.1);
            padding: 1rem;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(128, 0, 32, 0.2);
        }

        .reward-item:hover {
            background: rgba(128, 0, 32, 0.2);
            transform: translateX(10px);
            box-shadow: var(--neon-glow);
        }

        .reward-name {
            color: var(--accent-white);
            font-size: 1rem;
        }

        .reward-value {
            color: var(--accent-white);
            font-weight: 700;
            text-shadow: 0 0 10px rgba(128, 0, 32, 0.5);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .welcome-header {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }

            .welcome-text {
                font-size: 1.5rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
        .welcome-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.logout-btn {
    display: flex;
    align-items: center;
    color: #800000;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.logout-btn:hover {
    background-color: rgba(128, 0, 0, 0.1);
}

.logout-btn i {
    margin-right: 5px;
}

@media (max-width: 600px) {
    .welcome-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .header-actions {
        width: 100%;
        justify-content: space-between;
        margin-top: 10px;
    }

    .logout-text {
        display: none;
    }

    .logout-btn {
        padding: 8px;
    }
}
    </style>
</head>
<body>
    <div class="container">
       <div class="welcome-header">
    <div class="welcome-text">
        Welcome, <?php echo htmlspecialchars($student['name']); ?>!
    </div>
    <div class="header-actions">
        <a href="https://docs.google.com/forms/d/e/1FAIpQLSeDV9IfmvsdDyhkKoJ_XQSpYVyE-WlB7ZaQ_zbrjCJhj14_Xw/viewform" class="register-btn">
            <i class="fas fa-calendar-plus"></i>
            Register for Events
        </a>
        <a href="?logout=true" class="logout-btn" title="Logout">
    <i class="fas fa-sign-out-alt"></i>
    <span class="logout-text">Logout</span>
</a>
    </div>
</div>

        <div class="dashboard-grid">
        <div class="card">
                <h2 class="card-title">
                    <i class="fas fa-coins"></i>
                    Fiesta Coins
                </h2>
                <div class="coin">
  <div class="coin__front"></div>
  <div class="coin__edge">
    <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
    <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
    <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
    <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
     <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
    <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
    <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
    <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>

  </div>
  <div class="coin__back"></div>
  <div class="coin__shadow"></div>
</div>
                <div class="position-value"><?php echo htmlspecialchars($student['festacoins']); ?> FC's</div>
                <div class="position-value"># <?php echo htmlspecialchars($student_position); ?></div>
            </div>

            <div class="card">
                <h2 class="card-title">
                    <i class="fas fa-user-circle"></i>
                    Profile Information
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <span class="info-label">Name:</span>
                        <span><?php echo htmlspecialchars($student['name']); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-id-card"></i>
                        <span class="info-label">SAP ID:</span>
                        <span><?php echo htmlspecialchars($student['sapid']); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-university"></i>
                        <span class="info-label">Campus:</span>
                        <span><?php echo htmlspecialchars($student['campus']); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-graduation-cap"></i>
                        <span class="info-label">Stream:</span>
                        <span><?php echo htmlspecialchars($student['stream']); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="info-label">Year:</span>
                        <span><?php echo htmlspecialchars($student['year']); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <span class="info-label">Mobile:</span>
                        <span><?php echo htmlspecialchars($student['mobile_number']); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-venus-mars"></i>
                        <span class="info-label">Gender:</span>
                        <span><?php echo htmlspecialchars($student['gender']); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" style="margin-top:40px;">
    <h2 class="card-title">
        <i class="fas fa-coins"></i>
        How to Collect Fiesta Coins
    </h2>
    <div class="coin-info">
        <h3>🏆 Event Rewards</h3>
        <p>Showcase your skills in competitions and earn big rewards:</p>
        <div class="coin-info-grid">
            <div class="reward-item">
                <span class="reward-name">🥇 1st Place</span>
                <span class="reward-value">5000 FC</span>
            </div>
            <div class="reward-item">
                <span class="reward-name">🥈 2nd Place</span>
                <span class="reward-value">3500 FC</span>
            </div>
            <div class="reward-item">
                <span class="reward-name">🥉 3rd Place</span>
                <span class="reward-value">2000 FC</span>
            </div>
        </div>
    </div>

    <div class="coin-info" style="margin-top:50px; margin-bottom:20px;">
        <h3 >🍽️ Food Purchase Rewards</h3>
        <p>Earn Fiesta Coins while enjoying your favorite snacks:</p>
        <div class="coin-info-grid">
            <div class="reward-item">
                <span class="reward-name">₹100 - ₹150</span>
                <span class="reward-value">150 FC</span>
            </div>
            <div class="reward-item">
                <span class="reward-name">₹150 - ₹300</span>
                <span class="reward-value">300 FC</span>
            </div>
            <div class="reward-item">
                <span class="reward-name">₹300 - ₹500</span>
                <span class="reward-value">500 FC</span>
            </div>
            <div class="reward-item">
                <span class="reward-name">₹500 - ₹750</span>
                <span class="reward-value">750 FC</span>
            </div>
            <div class="reward-item">
                <span class="reward-name">₹750 - ₹1000</span>
                <span class="reward-value">1000 FC</span>
            </div>
            <div class="reward-item">
                <span class="reward-name">₹1500+</span>
                <span class="reward-value">1500 FC</span>
            </div>
        </div>
    </div>

    <div class="coin-info">
        <h3>💼 Registration Bonus</h3>
        <p>Start your Fiesta journey with a bonus:</p>
        <div class="reward-item">
            <span class="reward-name">🎟️ Registration</span>
            <span class="reward-value">999 FC</span>
        </div>
    </div>
</div>

    </div>
    </div>
    <script>
        // Futuristic hover effects for cards
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const xc = rect.width / 2;
                const yc = rect.height / 2;
                
                const dx = x - xc;
                const dy = y - yc;
                
                const tiltX = dy / yc;
                const tiltY = -(dx / xc);
                
                card.style.transform = `perspective(1000px) rotateX(${tiltX * 3}deg) rotateY(${tiltY * 3}deg) translateZ(10px)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
            });
        });

        // Create cyberpunk background particles
        function createParticle() {
            const particle = document.createElement('div');
            particle.style.position = 'fixed';
            particle.style.width = '2px';
            particle.style.height = '2px';
            particle.style.background = 'rgba(128, 0, 32, 0.5)';
            particle.style.boxShadow = '0 0 10px rgba(128, 0, 32, 0.3)';
            particle.style.borderRadius = '50%';
            particle.style.pointerEvents = 'none';
            particle.style.zIndex = '0';
            
            // Random position
            particle.style.left = Math.random() * 100 + 'vw';
            particle.style.top = Math.random() * 100 + 'vh';
            
            // Random animation duration
            const duration = 3 + Math.random() * 4;
            particle.style.animation = `float ${duration}s linear infinite`;
            
            document.body.appendChild(particle);
            
            // Remove particle after animation
            setTimeout(() => {
                particle.remove();
            }, duration * 1000);
        }

        // Add floating animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0% {
                    transform: translateY(0) translateX(0);
                    opacity: 0;
                }
                50% {
                    opacity: 0.5;
                }
                100% {
                    transform: translateY(-100vh) translateX(50px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Create particles periodically
        setInterval(createParticle, 300);

        // Add smooth page load animation
        document.addEventListener('DOMContentLoaded', () => {
            document.body.style.opacity = '0';
            requestAnimationFrame(() => {
                document.body.style.transition = 'opacity 0.5s ease';
                document.body.style.opacity = '1';
            });
        });

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add hover effect to info items with glowing border
        const infoItems = document.querySelectorAll('.info-item');
        infoItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                item.style.transform = 'translateX(10px)';
                item.style.boxShadow = '0 0 15px rgba(128, 0, 32, 0.5)';
            });
            item.addEventListener('mouseleave', () => {
                item.style.transform = 'translateX(0)';
                item.style.boxShadow = 'none';
            });
        });

        // Add reactive glow effect to buttons
        const buttons = document.querySelectorAll('.register-btn');
        buttons.forEach(button => {
            button.addEventListener('mousemove', (e) => {
                const rect = button.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                button.style.setProperty('--x', `${x}px`);
                button.style.setProperty('--y', `${y}px`);
            });
        });
    </script>
<footer class="bg-black text-white py-8 w-full">
  <div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-start justify-between">
      <!-- Left Quick Links -->
      <div class="w-1/2 sm:w-1/3 mb-4 sm:mb-0">
        <h3 class="text-sm sm:text-lg font-bold mb-2">Quick Links</h3>
        <ul class="space-y-2">
          <li><a href="../index.html" class="hover:underline text-sm sm:text-base">Home</a></li>
          <li><a href="../tracks/nmsharks.html" class="hover:underline text-sm sm:text-base">NM Sharks</a></li>
          <li><a href="../tracks/projectexpo.html" class="hover:underline text-sm sm:text-base">Project Expo</a></li>
          <li><a href="../tracks/hackathon.html" class="hover:underline text-sm sm:text-base">Hackathon</a></li>
          <li><a href="../tracks/dalalden.html" class="hover:underline text-sm sm:text-base">Dalal Den</a></li>
        </ul>
      </div>
      
      <!-- Center Image and Contact -->
      <div class="w-full sm:w-1/3 text-center mb-4 order-first sm:order-none">
        <img src="../images/image.png" alt="STME Logo" class="h-16 sm:h-24 mx-auto mb-4">
        <!-- Contact Links -->
        <div class="flex flex-col items-center space-y-2">
          <a href="https://instagram.com/nmims.techfiesta25" class="flex items-center hover:text-pink-500 transition-colors">
            <i class="fab fa-instagram text-xl mr-2"></i>
            <span class="text-sm sm:text-base">@nmims.techfiesta25</span>
          </a>
          <a href="mailto:stme.hyd@nmims.edu" class="flex items-center hover:text-blue-400 transition-colors">
            <i class="far fa-envelope text-xl mr-2"></i>
            <span class="text-sm sm:text-base">stme.hyd@nmims.edu</span>
          </a>
        </div>
      </div>
      
      <!-- Right Quick Links -->
      <div class="w-1/2 sm:w-1/3 text-right">
        <h3 class="text-sm sm:text-lg font-bold mb-2">Quick Links</h3>
        <ul class="space-y-2">
          <li><a href="../tracks/roborumble.html" class="hover:underline text-sm sm:text-base">Robo Rumble</a></li>
          <li><a href="../tracks/gamiverse.html" class="hover:underline text-sm sm:text-base">Gamiverse</a></li>
          <li><a href="../tracks/filmotsav.html" class="hover:underline text-sm sm:text-base">Filmotsav</a></li>
          <li><a href="../tracks/futureminds.html" class="hover:underline text-sm sm:text-base">Future Minds</a></li>
          <li><a href="../php/contacts.php" class="hover:underline text-sm sm:text-base">Contact</a></li>
        </ul>
      </div>
    </div>
    
    <div class="text-center mt-8 border-t border-gray-700 pt-4">
      <p class="text-sm sm:text-base">All rights reserved &copy; Saicharan, STME</p>
    </div>
  </div>
</footer>
</body>
</html>