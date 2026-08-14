<?php
// Harus di paling atas sebelum output apapun
session_start();
include "inc/koneksi.php";

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION["ses_username"]) && $_SESSION["ses_username"] != "") {
  header("Location: index.php");
  exit;
}

$login_status = '';

if (isset($_POST['btnLogin'])) {
  $username  = mysqli_real_escape_string($koneksi, $_POST['username']);
  $password  = mysqli_real_escape_string($koneksi, md5($_POST['password']));

  $sql_login    = "SELECT * FROM tb_pengguna WHERE BINARY username='$username' AND password='$password'";
  $query_login  = mysqli_query($koneksi, $sql_login);
  $data_login   = mysqli_fetch_array($query_login, MYSQLI_BOTH);
  $jumlah_login = mysqli_num_rows($query_login);

  if ($jumlah_login == 1) {
    $_SESSION["ses_id"]       = $data_login["id_pengguna"];
    $_SESSION["ses_nama"]     = $data_login["nama_pengguna"];
    $_SESSION["ses_username"] = $data_login["username"];
    $_SESSION["ses_password"] = $data_login["password"];
    $_SESSION["ses_level"]    = $data_login["level"];
    $login_status = 'success';
  } else {
    $anggotaUser = mysqli_real_escape_string($koneksi, trim((string) $_POST['username']));
    $anggotaPass = trim((string) $_POST['password']);
    $sql_anggota = "SELECT * FROM tb_anggota WHERE nip='$anggotaUser' AND nip <> '' LIMIT 1";
    $query_anggota = mysqli_query($koneksi, $sql_anggota);
    $data_anggota = $query_anggota ? mysqli_fetch_array($query_anggota, MYSQLI_BOTH) : null;

    if ($data_anggota && $anggotaPass === (string) $data_anggota['nip']) {
      $_SESSION["ses_id"]       = $data_anggota["id_anggota"];
      $_SESSION["ses_nama"]     = $data_anggota["nama"];
      $_SESSION["ses_username"] = $data_anggota["nip"];
      $_SESSION["ses_password"] = $data_anggota["nip"];
      $_SESSION["ses_level"]    = 'Anggota';
      $login_status = 'success';
    } else {
      $login_status = 'failed';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Login &mdash; Sistem Informasi Perpustakaan</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', 'Segoe UI', Calibri, sans-serif;
      height: 100vh;
      position: relative;
      overflow-x: hidden;
      overflow-y: hidden;
      padding: 0;
      background: #0d3f29;
    }

    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(9, 28, 21, 0.62), rgba(20, 50, 38, 0.44)),
        url('https://media.istockphoto.com/id/1509201431/id/video/latar-belakang-jaringan-4k-technologic-line.jpg?s=640x640&k=20&c=oh38vAGdd5r5V2IbA-itmpWvhoTVV7kVmskAdCHeoDI=');
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center center;
      pointer-events: none;
      opacity: 1;
    }

    body::after {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        radial-gradient(circle at 15% 20%, rgba(255, 255, 255, 0.08), transparent 22%),
        radial-gradient(circle at 85% 10%, rgba(199, 169, 91, 0.18), transparent 16%),
        linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
      background-size: auto, auto, 34px 34px, 34px 34px;
      background-position: center center, center center, center center, center center;
      pointer-events: none;
    }

    .deco-circle {
      position: absolute;
      border-radius: 50%;
      pointer-events: none;
    }

    .deco-1 {
      width: 420px;
      height: 420px;
      top: -120px;
      right: -140px;
      background: radial-gradient(circle, rgba(61, 126, 99, 0.22) 0%, transparent 72%);
    }

    .deco-2 {
      width: 320px;
      height: 320px;
      bottom: -110px;
      left: -70px;
      background: radial-gradient(circle, rgba(199, 169, 91, 0.16) 0%, transparent 72%);
    }

    .deco-3 {
      width: 180px;
      height: 180px;
      top: 14%;
      left: 10%;
      background: radial-gradient(circle, rgba(255, 255, 255, 0.12) 0%, transparent 72%);
    }

    .login-wrap {
      position: fixed;
      inset: 16px;
      z-index: 10;
      width: auto;
      max-width: 1140px;
      max-height: calc(100vh - 32px);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      margin: auto;
      pointer-events: none;
    }

    .login-card {
      position: relative;
      display: grid;
      grid-template-columns: 1fr 1.02fr;
      align-items: stretch;
      background: rgba(255, 251, 241, 0.42);
      border-radius: 28px;
      box-shadow: 0 26px 70px rgba(53, 65, 92, 0.14), 0 0 0 1px rgba(255, 255, 255, 0.28);
      overflow: hidden;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      width: 100%;
      pointer-events: auto;
    }

    .card-shell {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      background: rgba(255, 255, 255, 0.34);
      border-bottom: 1px solid rgba(255, 255, 255, 0.22);
      padding: 16px 26px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      z-index: 4;
    }

    .brand-mini {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #21362a;
      font-weight: 800;
      font-size: 22px;
      letter-spacing: -0.6px;
    }

    .brand-mini span:first-child {
      color: #ffd978;
      font-weight: 700;
      text-shadow: 0 1px 0 rgba(87, 53, 10, 0.18), 0 0 10px rgba(255, 208, 92, 0.18);
    }

    .brand-mini span:last-child {
      color: #21362a;
    }

    .brand-mini img {
      width: 34px;
      height: 34px;
      object-fit: contain;
    }

    .top-note {
      display: flex;
      align-items: center;
      gap: 12px;
      color: #6f7c72;
      font-size: 12px;
    }

    .top-note .btn-shell {
      background: linear-gradient(135deg, #1f4d3a, #2f6a51);
      color: #fff8eb;
      border: 0;
      border-radius: 8px;
      padding: 8px 18px;
      font-size: 12px;
      font-weight: 700;
    }

    .card-top {
      padding: 96px 36px 34px;
      text-align: left;
      background: linear-gradient(180deg, rgba(253, 249, 237, 0.32) 0%, rgba(251, 246, 234, 0.2) 100%);
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: center;
      grid-column: 1;
      grid-row: 1;
    }

    .brand-stack {
      position: relative;
      z-index: 1;
    }

    .visual-copy {
      max-width: 430px;
      margin: 6px auto 18px;
      text-align: center;
    }

    .visual-title {
      color: #214031;
      font-size: 23px;
      font-weight: 800;
      line-height: 1.3;
      letter-spacing: -0.4px;
      margin-bottom: 8px;
    }

    .visual-subtitle {
      color: #6d7a70;
      font-size: 13px;
      line-height: 1.7;
      max-width: 360px;
      margin: 0 auto;
    }

    .visual-points {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
      margin: 18px 0 8px;
    }

    .visual-point {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 12px;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.18);
      border: 1px solid rgba(47, 106, 81, 0.12);
      color: #315644;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.2px;
      backdrop-filter: blur(6px);
    }

    .visual-point i {
      color: #c4a55d;
      font-size: 12px;
    }

    .card-top h2 {
      color: #1d3024;
      font-size: 26px;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 20px;
      position: relative;
      z-index: 1;
      letter-spacing: -0.5px;
      text-align: center;
    }

    .book-illustration {
      position: relative;
      width: 100%;
      max-width: 390px;
      height: 320px;
      margin: 0 auto;
    }

    .spark {
      position: relative;
      display: inline-block;
      color: #90bfd2;
      font-size: 24px;
      margin: 0 10px;
    }

    .book-stack {
      position: absolute;
      left: 50%;
      top: 52%;
      transform: translate(-50%, -50%);
      width: 250px;
      height: 220px;
    }

    .book {
      position: absolute;
      left: 50%;
      transform: translateX(-50%) rotate(-2deg);
      height: 34px;
      border-radius: 7px 10px 10px 7px;
      box-shadow: 0 8px 20px rgba(39, 54, 70, 0.12);
    }

    .book::before {
      content: '';
      position: absolute;
      left: 14px;
      top: 7px;
      width: 34px;
      height: 20px;
      border-radius: 4px;
      background: rgba(255, 255, 255, 0.18);
    }

    .book::after {
      content: '';
      position: absolute;
      right: 18px;
      top: 5px;
      width: 8px;
      height: 24px;
      border-radius: 4px;
      background: rgba(255, 248, 233, 0.75);
    }

    .book.b1 {
      width: 205px;
      bottom: 8px;
      background: #f2a26f;
    }

    .book.b2 {
      width: 180px;
      bottom: 40px;
      background: #eb6f7d;
      transform: translateX(-50%) rotate(4deg);
    }

    .book.b3 {
      width: 220px;
      bottom: 72px;
      background: #4da37f;
    }

    .book.b4 {
      width: 176px;
      bottom: 106px;
      background: #2d364c;
      transform: translateX(-50%) rotate(-4deg);
    }

    .book.b5 {
      width: 198px;
      bottom: 140px;
      background: #efc256;
    }

    .book.b6 {
      width: 165px;
      bottom: 174px;
      background: #4b77b6;
      transform: translateX(-50%) rotate(5deg);
    }

    .book-base {
      position: absolute;
      left: 50%;
      bottom: -2px;
      transform: translateX(-50%);
      width: 260px;
      height: 22px;
      background: #efcfb0;
      border-radius: 50%;
      filter: blur(1px);
    }

    .plant {
      position: absolute;
      right: 20px;
      bottom: 18px;
      width: 54px;
      height: 78px;
    }

    .plant .pot {
      position: absolute;
      bottom: 0;
      left: 9px;
      width: 36px;
      height: 24px;
      background: #d88d67;
      border-radius: 0 0 12px 12px;
    }

    .plant .leaf {
      position: absolute;
      bottom: 18px;
      width: 20px;
      height: 32px;
      background: #53a971;
      border-radius: 20px 20px 0 20px;
      transform-origin: bottom center;
    }

    .plant .leaf.l1 {
      left: 6px;
      transform: rotate(-18deg);
    }

    .plant .leaf.l2 {
      left: 18px;
      height: 38px;
      transform: rotate(12deg);
    }

    .plant .leaf.l3 {
      left: 28px;
      transform: rotate(32deg);
    }

    .lib-badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: rgba(255, 255, 255, 0.24);
      border: 1px solid rgba(47, 106, 81, 0.14);
      color: #335f4a;
      font-size: 10px;
      font-weight: 800;
      padding: 6px 12px;
      border-radius: 999px;
      margin: 0 auto 0;
      text-transform: uppercase;
      letter-spacing: 1.2px;
    }

    .institution-panel {
      margin: 22px auto 0;
      max-width: 360px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 12px;
      padding: 18px 20px;
      background: rgba(255, 253, 248, 0.18);
      border: 1px solid rgba(255, 255, 255, 0.18);
      border-radius: 18px;
      backdrop-filter: blur(8px);
      box-shadow: 0 10px 24px rgba(31, 77, 58, 0.05);
    }

    .institution-panel img {
      width: 56px;
      height: 56px;
      object-fit: contain;
    }

    .institution-panel strong {
      display: block;
      color: #1f3b2d;
      font-size: 14px;
      font-weight: 800;
      letter-spacing: 0.5px;
      text-align: center;
    }

    .gold-divider {
      position: absolute;
      top: 0;
      bottom: 0;
      left: 49.8%;
      width: 1px;
      height: auto;
      background: linear-gradient(180deg, transparent, rgba(199, 169, 91, 0.56) 18%, rgba(227, 204, 141, 0.8) 50%, rgba(199, 169, 91, 0.56) 82%, transparent);
      z-index: 2;
    }

    .card-body {
      padding: 96px 36px 30px;
      background:
        radial-gradient(circle at top right, rgba(217, 191, 124, 0.08), transparent 22%),
        linear-gradient(180deg, rgba(255, 253, 248, 0.3) 0%, rgba(246, 243, 234, 0.2) 100%);
      grid-column: 2;
      grid-row: 1;
    }

    .form-kicker {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      color: #2f6a51;
      background: rgba(255, 255, 255, 0.24);
      border: 1px solid rgba(47, 106, 81, 0.1);
      padding: 6px 12px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 16px;
    }

    .form-title {
      font-size: 27px;
      line-height: 1.2;
      color: #21362a;
      font-weight: 800;
      letter-spacing: -0.5px;
      margin-bottom: 8px;
    }

    .form-subtitle {
      color: rgba(255, 255, 255, 0.88);
      font-size: 14px;
      line-height: 1.7;
      margin-bottom: 22px;
    }

    .field-label {
      display: block;
      font-size: 11px;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.92);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 7px;
    }

    .input-wrap {
      position: relative;
      margin-bottom: 18px;
    }

    .input-wrap .ico {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #9aa59d;
      font-size: 14px;
      pointer-events: none;
      transition: color 0.2s;
    }

    .input-wrap input {
      width: 100%;
      padding: 13px 14px 13px 42px;
      border: 1.5px solid #d7ddd4;
      border-radius: 14px;
      font-size: 14px;
      color: #2d3f34;
      background: rgba(255, 255, 255, 0.5);
      transition: border-color 0.22s, box-shadow 0.22s, background 0.22s;
      outline: none;
      font-family: 'Plus Jakarta Sans', 'Segoe UI', Calibri, sans-serif;
    }

    .input-wrap input:focus {
      border-color: #3d7e63;
      background: #fff;
      box-shadow: 0 0 0 4px rgba(61, 126, 99, 0.11);
    }

    .input-wrap:focus-within .ico {
      color: #3d7e63;
    }

    .input-wrap input::placeholder {
      color: #bbb;
    }

    .btn-masuk {
      display: block;
      width: 100%;
      padding: 14px;
      margin-top: 6px;
      background: linear-gradient(135deg, #1f4d3a 0%, #2d6a50 58%, #c4a55d 100%);
      border: none;
      border-radius: 10px;
      color: #fff;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      cursor: pointer;
      transition: opacity 0.2s, transform 0.18s, box-shadow 0.2s;
      box-shadow: 0 8px 24px rgba(31, 77, 58, 0.26);
      font-family: 'Plus Jakarta Sans', 'Segoe UI', Calibri, sans-serif;
      border: none;
    }

    .btn-masuk:hover {
      opacity: 0.95;
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(31, 77, 58, 0.34);
    }

    .btn-masuk:active {
      transform: translateY(0);
    }

    .btn-masuk i {
      margin-right: 8px;
    }

    .card-note {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      color: rgba(255, 255, 255, 0.82);
      font-size: 12px;
      padding: 14px 0 4px;
      border-top: 1px solid #ecefe7;
      margin-top: 18px;
    }

    .card-note i {
      color: #c4a55d;
    }

    .page-footer {
      position: fixed;
      left: 50%;
      bottom: 16px;
      transform: translateX(-50%);
      text-align: center;
      color: rgba(255, 255, 255, 0.58);
      font-size: 12px;
      z-index: 10;
      width: max-content;
    }

    @media (max-width: 860px) {
      .login-card {
        grid-template-columns: 1fr;
      }

      .gold-divider {
        left: 0;
        right: 0;
        top: auto;
        bottom: auto;
        width: auto;
        height: 3px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.26) 18%, rgba(255, 255, 255, 0.45) 50%, rgba(255, 255, 255, 0.26) 82%, transparent);
      }

      .card-top {
        padding: 88px 24px 26px;
        grid-column: 1;
        grid-row: 1;
      }

      .card-body {
        padding: 32px 24px 24px;
        grid-column: 1;
        grid-row: 2;
      }

      .card-top h2 {
        font-size: 24px;
      }

      .book-illustration {
        height: 280px;
      }

      .visual-title {
        font-size: 21px;
      }
    }

    @media (max-width: 480px) {
      body {
        padding: 0;
      }

      .login-wrap {
        inset: 12px;
        max-height: calc(100vh - 24px);
      }

      .card-top {
        padding: 84px 20px 24px;
      }

      .card-body {
        padding: 24px 20px 20px;
      }

      .form-title {
        font-size: 23px;
      }

      .top-note {
        display: none;
      }

      .brand-mini {
        font-size: 19px;
      }

      .visual-points {
        gap: 8px;
      }

      .page-footer {
        bottom: 10px;
      }
    }
  </style>
</head>

<body>

  <div class="deco-circle deco-1"></div>
  <div class="deco-circle deco-2"></div>
  <div class="deco-circle deco-3"></div>

  <div class="login-wrap">
    <div class="login-card">
      <div class="card-shell">
        <div class="brand-mini">
          <img src="dist/img/logokemhan.png" alt="Logo Kemhan">
          <div><span>Libra</span><span>Han</span></div>
        </div>
        <div class="top-note">
          <span>Akses internal perpustakaan</span>
          <button type="button" class="btn-shell">Kemhan</button>
        </div>
      </div>

      <div class="card-top">
        <div class="brand-stack">
          <!-- <div class="institution-panel">
            <div class="lib-badge">
              <i class="fa fa-bookmark"></i> Visual Perpustakaan Kemhan
            </div>
          </div> -->

          <div class="visual-copy">
            <div class="visual-title">Sistem Informasi Perpustakaan</div>
            <!-- <div class="visual-subtitle">
              Kelola koleksi, anggota, dan sirkulasi dalam tampilan yang ringan, rapi, dan nyaman digunakan setiap hari.
            </div> -->
          </div>

        

          <div class="book-illustration">
            <div style="text-align:center; padding-top:12px;">
              <span class="spark">✦</span>
              <span class="spark">✦</span>
              <span class="spark">✦</span>
            </div>
            <div class="book-stack">
              <div class="book b6"></div>
              <div class="book b5"></div>
              <div class="book b4"></div>
              <div class="book b3"></div>
              <div class="book b2"></div>
              <div class="book b1"></div>
              <div class="book-base"></div>
            </div>
            <div class="plant">
              <div class="leaf l1"></div>
              <div class="leaf l2"></div>
              <div class="leaf l3"></div>
              <div class="pot"></div>
            </div>
          </div>

            <div class="visual-points">
            <div class="visual-point"><i class="fa fa-book"></i> Koleksi Tertata</div>
            <div class="visual-point"><i class="fa fa-refresh"></i> Sirkulasi Cepat</div>
            <div class="visual-point"><i class="fa fa-file-text-o"></i> Laporan Ringkas</div>
          </div>
        </div>
      </div>

      <div class="gold-divider"></div>

      <div class="card-body">
        <div class="form-kicker">
          <i class="fa fa-shield"></i> Portal Masuk
        </div>
        <div class="form-title">Masuk ke Sistem</div>
        <div class="form-subtitle">
          Gunakan akun yang terdaftar untuk mengakses pengelolaan perpustakaan dan layanan sirkulasi.
        </div>
        <form action="" method="post" autocomplete="off">
          <label class="field-label">Username</label>
          <div class="input-wrap">
            <i class="fa fa-user ico"></i>
            <input type="text" name="username" placeholder="Masukkan username" required
              value="<?= isset($_POST['username']) && $login_status === 'failed' ? htmlspecialchars($_POST['username']) : '' ?>">
          </div>

          <label class="field-label">Password</label>
          <div class="input-wrap">
            <i class="fa fa-lock ico"></i>
            <input type="password" name="password" placeholder="Masukkan password" required>
          </div>

          <button type="submit" name="btnLogin" class="btn-masuk">
            <i class="fa fa-sign-in"></i> Masuk ke Sistem
          </button>

        </form>

        <div class="card-note">
          <i class="fa fa-shield"></i>
          Akses hanya untuk pengguna terdaftar
        </div>
      </div>
    </div>

    <div class="page-footer">
      &copy; <?php echo date('Y'); ?> Sistem Informasi Perpustakaan
    </div>
  </div>

  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>

  <?php if ($login_status === 'success'): ?>
    <script>
      Swal.fire({
        title: 'Login Berhasil',
        text: 'Selamat datang, <?= htmlspecialchars($_SESSION["ses_nama"]) ?>!',
        icon: 'success',
        confirmButtonColor: '#2d6a50',
        timer: 1500,
        timerProgressBar: true,
        showConfirmButton: false
      }).then(function() {
        window.location.href = 'index.php';
      });
    </script>
  <?php elseif ($login_status === 'failed'): ?>
    <script>
      Swal.fire({
        title: 'Login Gagal',
        text: 'Username atau password salah.',
        icon: 'error',
        confirmButtonText: 'Coba Lagi',
        confirmButtonColor: '#2d6a50'
      });
    </script>
  <?php endif; ?>

</body>

</html>
