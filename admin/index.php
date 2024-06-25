<?php
session_start();
include '../config.php';

if (isset($_SESSION['user'])) {
    header("Location: " . $config['web']['url']);
    exit();
}

// Login functionality
if (isset($_POST['login'])) {
    $post_username = $conn->real_escape_string(trim($_POST['username']));
    $post_password = $conn->real_escape_string(trim($_POST['password']));

    if (!$post_username || !$post_password) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Lengkapi Bidang Berikut:<br/> - Username<br/> - Password');
        header("Location: login");
        exit();
    }

    $check_user = $conn->query("SELECT * FROM users WHERE username = '$post_username'");
    if ($check_user->num_rows === 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Akun Tidak Ditemukan');
        header("Location: login");
        exit();
    }

    $data_user = $check_user->fetch_assoc();
    if (!password_verify($post_password, $data_user['password'])) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Login Gagal', 'pesan' => 'Username / Password Salah');
        header("Location: login");
        exit();
    }

    $_SESSION['user'] = $data_user;
    header("Location: " . $config['web']['url']);
    exit();
}

// Registration functionality
if (isset($_POST['register'])) {
    $post_username = $conn->real_escape_string(trim($_POST['username']));
    $post_name = $conn->real_escape_string(trim($_POST['name']));
    $post_password = $conn->real_escape_string(trim($_POST['password']));
    $hashed_password = password_hash($post_password, PASSWORD_BCRYPT);

    if (!$post_username || !$post_name || !$post_password) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Registrasi Gagal', 'pesan' => 'Lengkapi Semua Bidang');
        header("Location: register");
        exit();
    }

    $check_user = $conn->query("SELECT * FROM users WHERE username = '$post_username'");
    if ($check_user->num_rows > 0) {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Registrasi Gagal', 'pesan' => 'Username Sudah Digunakan');
        header("Location: register");
        exit();
    }

    $sql = "INSERT INTO users (username, name, password) VALUES ('$post_username', '$post_name', '$hashed_password')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['hasil'] = array('alert' => 'success', 'judul' => 'Registrasi Berhasil', 'pesan' => 'Anda Telah Terdaftar');
        header("Location: login");
        exit();
    } else {
        $_SESSION['hasil'] = array('alert' => 'danger', 'judul' => 'Registrasi Gagal', 'pesan' => 'Terjadi kesalahan: ' . $conn->error);
        header("Location: register");
        exit();
    }
}
?>


<div class="container">
   <link rel="stylesheet" href="style.css">
   <div class="wrapper">
       <div class="card-switch">
           <label class="switch">
              <input type="checkbox" class="toggle">
              <span class="slider"></span>
              <span class="card-side"></span>
              <div class="flip-card__inner">
                 <div class="flip-card__front">
                    <div class="title">Log in</div>
                    <form class="flip-card__form" action="" method="POST">
                       <input class="flip-card__input" name="username" placeholder="Username" type="text" autocomplete="username" required>
                       <input class="flip-card__input" name="password" placeholder="Password" type="password" autocomplete="current-password" required>
                       <button class="flip-card__btn" name="login" type="submit">Let's go!</button>
                    </form>
                 </div>
                 <div class="flip-card__back">
                    <div class="title">Sign up</div>
                    <form class="flip-card__form" action="" method="POST">
                       <input class="flip-card__input" name="username" placeholder="Username" type="text" required>
                       <input class="flip-card__input" name="name" placeholder="Name" type="text" required>
                       <input class="flip-card__input" name="password" placeholder="Password" type="password" required>
                       <button class="flip-card__btn" name="register" type="submit">Confirm!</button>
                    </form>
                 </div>
              </div>
           </label>
       </div>   
  </div>
</div>
