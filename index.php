<?php
session_start();
include 'koneksi.php';

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if ($row = mysqli_fetch_assoc($result)) {
        if ($password == $row['password']) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
<style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #ffffff;
            width: 380px;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.35);
        }

        .login-card h2 {
            text-align: center;
            margin-bottom: 28px;
            color: #2c5364;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            font-size: 14px;
            color: #555;
        }

        input[type="email"],
        input[type="password"] {
            width: 94%;
            padding: 11px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #2c5364;
        }

        .btn {
            width: 100%;
            padding: 11px;
            background: #2c5364;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 12px;
        }

        .btn:hover {
            background: #1b3a4b;
        }

        .btn-reset {
            width: 100%;
            padding: 11px;
            margin-top: 8px;
            background: #e0e0e0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
        }

        .btn-reset:hover {
            background: #cfcfcf;
        }

        .error {
            background: #ffe0e0;
            color: #a40000;
            padding: 9px;
            border-radius: 8px;
            margin-bottom: 18px;
            text-align: center;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 22px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>POLGAN MART</h2>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukkan email anda" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
        </div>

        <button type="submit" class="btn">Login</button>
        <button type="reset" class="btn-reset">Batal</button>
    </form>

    <div class="footer">
        <p>© 2026 POLGAN MART</p>
    </div>
</div>

</body>
</html>
