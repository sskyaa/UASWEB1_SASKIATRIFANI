<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard POLGANMART</title>
    <style>
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, sans-serif;
        background: #f2f3f5;
    }

    /* Sidebar */
    .sidebar {
        width: 230px;
        height: 100vh;
        background: #1f2933;
        color: #e5e7eb;
        position: fixed;
        top: 0;
        left: 0;
        box-shadow: 2px 0 10px rgba(0,0,0,0.15);
    }

    .sidebar h2 {
        text-align: center;
        padding: 20px 0;
        margin: 0;
        font-size: 20px;
        letter-spacing: 1px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar a {
        display: block;
        color: #d1d5db;
        padding: 14px 22px;
        text-decoration: none;
        transition: 0.3s;
        font-size: 14px;
    }

    .sidebar a:hover {
        background: #374151;
        color: #ffffff;
    }

    /* Header */
    .header {
        height: 60px;
        background: #ffffff;
        padding: 0 25px;
        margin-left: 230px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
    }

    .profile-btn {
        cursor: pointer;
        padding: 8px 18px;
        border-radius: 20px;
        background: #374151;
        color: white;
        font-size: 14px;
        transition: 0.3s;
    }

    .profile-btn:hover {
        background: #697688;
    }

    /* Dropdown */
    .dropdown {
        position: relative;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        top: 45px;
        background: white;
        min-width: 160px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        border-radius: 8px;
        overflow: hidden;
        z-index: 100;
    }

    .dropdown-content a {
        display: block;
        padding: 12px 15px;
        text-decoration: none;
        color: #374151;
        font-size: 14px;
        transition: 0.3s;
    }

    .dropdown-content a:hover {
        background: #f3f4f6;
    }

    /* Content */
    .content {
        margin-left: 230px;
        padding: 25px;
    }

    .content h2 {
        color: #1f2933;
        margin-top: 0;
    }
</style>


</head>

<body>

    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="#">Home</a>
        <a href="#">List Produk</a>
        <a href="#">Customer</a>
        <a href="#">Transaksi</a>
        <a href="#">Laporan</a>
    </div>
    <div class="header">
        <div class="dropdown">
            <div class="profile-btn" onclick="toggleMenu()">Profile ▾</div>
            <div class="dropdown-content" id="profileMenu">
                <a href="dashboard.php?page=profile">My Profile</a>
                <a href="#">Logout</a>
            </div>
        </div>
    </div>
    <div class="content">
        <?php
        $page = $_GET['page'] ?? 'home';
        $file = "pages/$page.php";
        if (file_exists($file)) {
            include $file;
        } else {
            echo "<h2>Welcome Dashboard</h2>";
        }
        ?>
    </div>


    <script>
        function toggleMenu() {
            var menu = document.getElementById("profileMenu");
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
        }

        // Menutup dropdown jika klik di luar
        window.onclick = function(event) {
            if (!event.target.matches('.profile-btn')) {
                document.getElementById("profileMenu").style.display = "none";
            }
        }
    </script>

</body>

</html
