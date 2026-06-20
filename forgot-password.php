<?php
session_start();
date_default_timezone_set("Asia/Makassar");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password | PRAKTIKUM FTI UNISKA</title>
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
                <p class="login-box-msg">Masukkan Username anda</p>

                <?php if (isset($error)) : ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Username tidak ditemukan!
                    </div>
                <?php endif; ?>

                <?php if (isset($success)) : ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        Password telah dikirim ke email terdaftar.
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
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="Kirim" class="btn btn-primary btn-block">Kirim Permintaan</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <a href="index.php">Kembali ke Login</a>
            </div>
        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>
