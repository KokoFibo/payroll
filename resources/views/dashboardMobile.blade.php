<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Dashboard</title>
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Set a background color and font styles */
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        /* Sticky header section with the logo and menu */
        header {
            background-color: #282828;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        /* Dashboard container */
        .dashboard {
            margin: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Individual dashboard items (you can add more as needed) */
        .dashboard-item {
            padding: 20px;
            border-bottom: 1px solid #e5e5e5;
            text-align: center;
        }

        /* Style for dashboard item icons */
        .dashboard-item i {
            font-size: 24px;
            color: #C62A27;
        }

        /* Style for dashboard item labels */
        .dashboard-item span {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        /* Icon menu at the bottom */
        .icon-menu {
            background-color: #C62A27;
            display: flex;
            justify-content: space-around;
            align-items: center;
            position: sticky;
            bottom: 0;
            z-index: 100;
        }

        .icon-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <header>
        <img src="{{ asset('images/Yifang-transparant-logo.png') }}" alt="Yifang Logo"
            style="opacity: .8; width:150px; height: 90px">
        {{-- <h1>Yifang Presensi</h1> --}}
    </header>

    <div class="dashboard">
        <div class="dashboard-item">
            <i class="fas fa-chart-bar"></i>
            <span>Analytics</span>
        </div>

        <div class="dashboard-item">
            <i class="fas fa-envelope"></i>
            <span>Messages</span>
        </div>

        <div class="dashboard-item">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </div>
    </div>

    <div class="icon-menu">
        <a href="#"><i class="fas fa-home"></i></a>
        <a href="#"><i class="fas fa-search"></i></a>
        <a href="#"><i class="fas fa-plus"></i></a>
        <a href="#"><i class="fas fa-bell"></i></a>
        <a href="#"><i class="fas fa-user"></i></a>
    </div>
</body>

</html>
