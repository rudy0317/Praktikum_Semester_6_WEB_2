<?php
session_start();
date_default_timezone_set("Asia/Makassar");

if (isset($_SESSION["login"])) {
    header("Location: " . ($_SESSION["peran"] === "ADMIN" ? "admin/index.php" : "user/index.php"));
    exit;
}

require 'koneksi.php';

//query tampil data mahasiswa
// $query = "SELECT * FROM mahasiswa";
// $result = mysqli_query($conn, $query);

if (isset($_POST["Login"])){

    $username = $_POST["username"];
    $password = $_POST["password"];
    $login_terakhir = date("Y-m-d H:i:s");

    $result = mysqli_query($conn, "SELECT * FROM pengguna WHERE username='$username'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["peran"] = $row["peran"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["id"] = $row["id"];

            if ($row["peran"] === "ADMIN") {
                $_SESSION["nama"] = "Admin";
            } else {
                $_SESSION["nama"] = $row["nama"];
            }
            $update = mysqli_query($conn, "UPDATE pengguna SET login_terakhir
            ='$login_terakhir' WHERE username='$username'");
            header("Location: admin/index.php");
            } else if ($row["peran"] == "USER") {
            $update = mysqli_query($conn, "UPDATE pengguna SET login_terakhir
            = '$login_terakhir' WHERE username='$username'");
            header("Location: user/index.php");
            }

            exit;
        }
    } else {
        $error = true;
    }



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | PRAKTIKUM FTI UNISKA</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <h3><b>SISTEM INFORMASI</b><br>KEPEGAWAIAN FTI UNISKA</h3>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Masukkan Username dan Password anda</p>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Username / Password salah!
                    </div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type=   "submit" name="Login" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="forgot-password.php">Lupa Password?</a>
            </div>
        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
