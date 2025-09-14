<?php
require_once 'db.php';
require_once 'auth.php';

$auth = new Auth($conn);
$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Bezbednije dohvaćanje unosa
  $u = isset($_POST['username']) ? trim($_POST['username']) : '';
  $p = $_POST['password'] ?? '';

  // Jednostavna validacija
  if ($u === '' || $p === '') {
    $err = 'Popuni korisničko ime i lozinku.';
  } elseif (mb_strlen($u) < 3) {
    $err = 'Korisničko ime mora imati bar 3 karaktera.';
  } elseif (mb_strlen($p) < 4) {
    $err = 'Lozinka mora imati bar 4 karaktera.';
  } else {
    if ($auth->register($u, $p)) {
      // Ako želiš odmah preusmerenje na login:
      // header('Location: login.php?ok=1'); exit;
      $msg = 'Uspešno! Sada se prijavi.';
    } else {
      $err = 'Korisničko ime već postoji ili je došlo do greške.';
    }
  }
}
?>
<!doctype html>
<html lang="sr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registracija</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Registracija</h4>

          <?php if ($msg): ?>
            <div class="alert alert-success"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>
          <?php if ($err): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>

          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label">Korisničko ime</label>
              <input class="form-control" name="username" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Lozinka</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            <button class="btn btn-success w-100">Registruj se</button>
          </form>

          <div class="text-center mt-3">
            Već imaš nalog? <a href="login.php">Prijava</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
