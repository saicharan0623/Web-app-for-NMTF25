<?php
session_start();
include('db_connect.php');
$sql = "SELECT sapid, name, festacoins FROM students WHERE festacoins > 500 ORDER BY festacoins DESC LIMIT 5";
$result = $conn->query($sql);

$leaderboard = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }

} else {
    $_SESSION['error'] = "No data available!";
    header("Location: admin_dashboard.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
/* General body styling */
body {
    background-image: url("../images/bg.png");
    font-family: 'Roboto', sans-serif;
    color: #fff;
    min-height: 100vh;
    display: flex;
    background-size: cover;
    justify-content: center;
    align-items: center;
    margin: 0;
    padding: 20px;
    box-sizing: border-box;
}

/* Main container */
.leaderboard-container {
    width: 100%;
    max-width: 800px;
    background: rgba(30, 30, 30, 0.85);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    position: relative;
    box-sizing: border-box;
}

/* Navigation bar */
.nav-bar {
    position: absolute;
    top: 20px;
    left: 20px;
    right: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 10px;
    z-index: 10;
}

/* Logo styling */
.logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.logo:hover {
    transform: scale(1.1);
}

.logo img {
    width: 70px;
    height: 70px;
    object-fit: contain;
}

/* Leaderboard title */
.leaderboard-title {
    text-align: center;
    font-size: 2rem;
    margin: 70px 0 10px;
    text-shadow: 0 0 10px rgba(220, 20, 60, 0.5);
    color: #ffd700;
}

/* Slogan */
.slogan {
    text-align: center;
    font-size: 1rem;
    margin-bottom: 20px;
    color: #ccc;
    font-style: italic;
    text-shadow: 0 0 5px rgba(255, 255, 255, 0.3);
}

/* Leaderboard rows */
.leaderboard-row {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding: 10px;
    border-radius: 10px;
    background: rgba(40, 40, 40, 0.9);
    transition: transform 0.3s ease-in-out;
}

.leaderboard-row:hover {
    transform: scale(1.05);
    background: rgba(51, 51, 51, 0.9);
}

/* Rank styling */
.rank {
    font-size: 1.5rem;
    width: 50px;
    text-align: center;
    color: #ffd700;
    font-weight: bold;
}

/* Player info */
.player-info {
    flex: 1;
    margin-left: 10px;
}

.player-name {
    font-size: 1.2rem;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.festacoins {
    font-size: 1rem;
    color: #aaa;
}

/* Progress bar */
.progress-bar-container {
    height: 8px;
    background: #444;
    border-radius: 4px;
    overflow: hidden;
    margin-top: 5px;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #ffd700, #ff4500);
    transition: width 0.5s ease;
}

/* Icons */
.icon {
    font-size: 1.2rem;
}

/* Coin Animations */
@keyframes rotate3d {
    0% { transform: rotateY(0); }
    100% { transform: rotateY(360deg); }
}

@keyframes shine {
    0% { transform: translateY(0) rotate(-25deg); }
    100% { transform: translateY(400px) rotate(-25deg); }
}

/* Base coin styles */
.coin {
    position: absolute;
    width: 250px;
    height: 250px;
    transform-style: preserve-3d;
    animation: rotate3d 8s linear infinite;
    transition: all 0.3s;
}

.coin__front,
.coin__back {
    position: absolute;
    width: 100%;
    height: 100%;
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
    animation: shine linear 4s infinite;
}

/* Coin images */
.coin--left .coin__front {
    background-image: url("../images/10.png");
    background-size: cover;
    transform: translateZ(10px);
}

.coin--left .coin__back {
    background-image: url("../images/9.png");
    background-size: cover;
    transform: translateZ(-10px) rotateY(180deg);
}

.coin--right .coin__front {
    background-image: url("../images/9.png");
    background-size: cover;
    transform: translateZ(10px);
}

.coin--right .coin__back {
    background-image: url("../images/10.png");
    background-size: cover;
    transform: translateZ(-10px) rotateY(180deg);
}

/* Desktop styles (> 1024px) */
@media (min-width: 1024px) {
    .leaderboard-container {
        margin: 0 300px;
    }
    
    .coin--left {
        left: -320px;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .coin--right {
        right: -320px;
        top: 50%;
        transform: translateY(-50%);
    }

    .coin--right, .coin--left {
        display: block;
    }
}

/* Tablet styles */
@media (min-width: 768px) and (max-width: 1023px) {
    .leaderboard-container {
        margin: 0 200px;
    }
    
    .coin {
        width: 200px;
        height: 200px;
    }
    
    .coin--left {
        left: -220px;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .coin--right {
        right: -220px;
        top: 50%;
        transform: translateY(-50%);
    }

    .coin--right, .coin--left {
        display: block;
    }
}

/* Mobile styles */
@media (max-width: 767px) {
    body {
        padding: 10px;
        padding-top: 160px; /* Space for the coin */
    }

    .leaderboard-container {
        margin: 20px;
        padding-top: 60px;
    }
    
    /* Hide right coin on mobile */
    .coin--right {
        display: none;
    }
    
    /* Style left coin for mobile */
    .coin--left {
        width: 150px;
        height: 150px;
        position: fixed;
        left: 30%;
        top: 20px;
        transform: translateX(-30%);
        z-index: 100;
    }

    .logo {
        width: 60px;
        height: 60px;
    }

    .logo img {
        width: 50px;
        height: 50px;
    }

    .leaderboard-title {
        font-size: 1.5rem;
        margin-top: 20px;
    }

    .nav-bar {
        position: relative;
        top: 0;
        left: 0;
        right: 0;
        padding: 10px;
    }

    .rank {
        font-size: 1.2rem;
    }

    .player-name {
        font-size: 1rem;
    }

    .festacoins {
        font-size: 0.9rem;
    }
}

/* Small mobile styles */
@media (max-width: 375px) {
    .coin--left {
        width: 120px;
        height: 120px;
        top: 15px;
    }

    body {
        padding-top: 140px;
    }

    .leaderboard-container {
        margin: 10px;
    }

    .logo {
        width: 50px;
        height: 50px;
    }

    .logo img {
        width: 40px;
        height: 40px;
    }
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
}}
  

    </style>
</head>
<body>
    <div class="leaderboard-container">
        
    <div class="coin coin--left">
        <div class="coin__front"></div>
        <div class="coin__edge">
            <div></div><div></div><div></div><div></div><div></div>
            <div></div><div></div><div></div><div></div><div></div>
            <div></div><div></div><div></div><div></div><div></div>
            <div></div><div></div><div></div><div></div><div></div>
            <div></div><div></div><div></div><div></div><div></div>
        </div>
        <div class="coin__back"></div>
        <div class="coin__shadow"></div>
    </div>
    
    <div class="coin coin--right">
        <div class="coin__front"></div>
        <div class="coin__edge">
            <div></div><div></div><div></div><div></div><div></div>
            <div></div><div></div><div></div><div></div><div></div>
            <div></div><div></div><div></div><div></div><div></div>
            <div></div><div></div><div></div><div></div><div></div>
            <div></div><div></div><div></div><div></div><div></div>
        </div>
        <div class="coin__back"></div>
        <div class="coin__shadow"></div>
    </div>
        <div class="nav-bar">
            <a href="#" class="logo">
                <img src="../images/stmelogo.png" alt="Fest Logo">
            </a>
            <a href="#" class="logo">
                <img src="../images/NMTF.png" alt="College Logo">
            </a>
        </div>

        <h1 class="leaderboard-title">
            <i class="fas fa-trophy"></i> Fiesta Coins Leaderboard
        </h1>

        <?php
        $maxCoins = max(array_column($leaderboard, 'festacoins'));

        foreach ($leaderboard as $index => $student) {
            $percentage = ($student['festacoins'] / $maxCoins) * 100;
            $rankIcons = [
                1 => 'fa-crown',
                2 => 'fa-medal',
                3 => 'fa-award',
            ];
            $rankIcon = $rankIcons[$index + 1] ?? 'fa-star';
        ?>
            <div class="leaderboard-row">
                <div class="rank">
                    <i class="fas <?php echo $rankIcon; ?>"></i>
                    <?php echo $index + 1; ?>
                </div>
                <div class="player-info">
                    <div class="player-name">
                        <?php echo htmlspecialchars($student['name']); ?>
                    </div>
                    <div class="festacoins">
                        <i class="fas fa-coins icon"></i>
                        <?php echo number_format($student['festacoins']); ?> FC
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <h4 class="slogan">"Collect max FC's to stay ahead in the race!"</h4>
    </div>
    
</body>
</html>