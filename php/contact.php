<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Team</title>
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
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
            background-color: #000;
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
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            font-family: 'Orbitron', sans-serif;
            animation: fadeIn 0.8s ease-out;
        }

        .header h1 {
            font-size: 3rem;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
            text-shadow: 0 0 15px rgba(128, 0, 0, 0.7);
        }

        .header p {
            color: #aaa;
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            padding: 20px;
        }

        .team-card {
            background: rgba(15, 15, 15, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(128, 0, 0, 0.2);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: cardAppear 0.5s ease-out forwards;
            opacity: 0;
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(128, 0, 0, 0.3);
        }

        .team-card::before {
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
            z-index: 0;
        }

        .card-content {
            position: relative;
            z-index: 1;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 3px solid #800000;
            padding: 5px;
            background: #1a1a1a;
            transition: all 0.3s ease;
        }

        .profile-img img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .team-card:hover .profile-img {
            transform: scale(1.1);
            border-color: #ff0000;
            box-shadow: 0 0 20px rgba(128, 0, 0, 0.5);
        }

        .name {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            color: #fff;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .role {
            color: #800000;
            font-size: 1rem;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .email {
            color: #aaa;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom:6px;
        }
        .phone {
            color: #aaa;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .email i {
            color: #800000;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes cardAppear {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .team-card:nth-child(n) {
            animation-delay: calc(0.1s * var(--i));
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            
            .team-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Our Team</h1>
        <p>Meet the brilliant minds behind TechFest</p>
    </div>

    <div class="team-grid">
        <!-- Card 1 -->
        <div class="team-card" style="--i:1">
            <div class="card-content">
                <div class="profile-img">
                    <img src="../images/harsh.png" alt="Harsh Bang">
                </div>
                <h3 class="name">XYZ</h3>
                <p class="role">Operations Head</p>
                <div class="email">
                    <i class="fas fa-envelope"></i>
                    XYZ@nmims.edu.in
                </div>
                <div class="phone">
                    <i class="fas fa-phone"></i>
                    XYZ
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="team-card" style="--i:2">
            <div class="card-content">
                <div class="profile-img">
                    <img src="../images/manishankar.png" alt="Manishankar Goud">
                </div>
                <h3 class="name">XYZ</h3>
                <p class="role">Finance Head</p>
                <div class="email">
                    <i class="fas fa-envelope"></i>
                    XYZ014@nmims.edu.in
                </div>
                <div class="phone">
                    <i class="fas fa-phone"></i>
                    XYZ
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="team-card" style="--i:3">
            <div class="card-content">
                <div class="profile-img">
                    <img src="../images/ruthwik.png" alt="Ruthvik Akula">
                </div>
                <h3 class="name">XYZ</h3>
                <p class="role">Marketing & PR Head</p>
                <div class="email">
                    <i class="fas fa-envelope"></i>
                    XYZ@nmims.edu.in
                </div>
                <div class="phone">
                    <i class="fas fa-phone"></i>
                    XYZ
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="team-card" style="--i:5">
            <div class="card-content">
                <div class="profile-img">
                    <img src="../images/saicharan.png" alt="Sai Charan">
                </div>
                <h3 class="name">Sai Charan</h3>
                <p class="role">Technical Head</p>
                <div class="email">
                    <i class="fas fa-envelope"></i>
                    XYZ@nmims.in
                </div>
                <div class="phone">
                    <i class="fas fa-phone"></i>
                    XYZ
                </div>
            </div>
        </div>

        <!-- Card 5 -->
        <div class="team-card" style="--i:4">
            <div class="card-content">
                <div class="profile-img">
                    <img src="../images/khushal.png" alt="Khushal Baldava">
                </div>
                <h3 class="name">XYZ</h3>
                <p class="role">Sponsorship Head</p>
                <div class="email">
                    <i class="fas fa-envelope"></i>
                    XYZ@nmims.edu.in
                </div>
                <div class="phone">
                    <i class="fas fa-phone"></i>
                    XYZ
                </div>
            </div>
        </div>

        <!-- Card 6 -->
        <div class="team-card" style="--i:6">
            <div class="card-content">
                <div class="profile-img">
                    <img src="../images/pragnya.png" alt="Pragnya Reddy">
                </div>
                <h3 class="name">XYZ</h3>
                <p class="role">Cultural Head</p>
                <div class="email">
                    <i class="fas fa-envelope"></i>
                    vXYZ@nmims.edu.in
                </div>
                <div class="phone">
                    <i class="fas fa-phone"></i>
                    XYZ
                </div>
            </div>
        </div>

        <!-- Card 7 -->
        <div class="team-card" style="--i:7">
            <div class="card-content">
                <div class="profile-img">
                    <img src="../images/john.png" alt="John Austin">
                </div>
                <h3 class="name">XYZ</h3>
                <p class="role">Design Head</p>
                <div class="email">
                    <i class="fas fa-envelope"></i>
                    XYZ@gmail.com
                </div>
                <div class="phone">
                    <i class="fas fa-phone"></i>
                    XYZ
                </div>
            </div>
        </div>

        <!-- Card 8 -->
        <div class="team-card" style="--i:8">
            <div class="card-content">
                <div class="profile-img">
                    <img src="../images/rachit.png" alt="Rachit Jain">
                </div>
                <h3 class="name">XYZ</h3>
                <p class="role">Hospitality Head</p>
                <div class="email">
                    <i class="fas fa-envelope"></i>
                    XYZ@nmims.edu.in
                </div>
                <div class="phone">
                    <i class="fas fa-phone"></i>
                    XYZ
                </div>
            </div>
        </div>

        <!-- Card 9 -->
        <div class="team-card" style="--i:9">
            <div class="card-content">
                <div class="profile-img">
                    <img src="../images/vaishnavi.png" alt="M Vaishnavi">
                </div>
                <h3 class="name">XYZ</h3>
                <p class="role">Documentation Head</p>
                <div class="email">
                    <i class="fas fa-envelope"></i>
                    XYZ@nmims.edu.in
                </div>
                <div class="phone">
                    <i class="fas fa-phone"></i>
                    XYZ
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>