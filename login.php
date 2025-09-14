<?php
require_once 'db.php';
require_once 'Auth.php';

$auth = new Auth($conn);

// Ako je već ulogovan → na početnu
if ($auth->isLoggedIn()) {
  header('Location: index.php');
  exit;
}

$msg = '';
$ok  = isset($_GET['ok']) ? 'Uspešna registracija! Sada se prijavi.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $u = isset($_POST['username']) ? trim($_POST['username']) : '';
  $p = $_POST['password'] ?? '';

  if ($u === '' || $p === '') {
    $msg = 'Unesi korisničko ime i lozinku.';
  } else {
    if ($auth->login($u, $p)) {
      header('Location: index.php');
      exit;
    } else {
      $msg = 'Pogrešno korisničko ime ili lozinka.';
    }
  }
}
?>
<!doctype html>
<html lang="sr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prijava</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-3">Prijava</h4>

          <?php if ($ok): ?>
            <div class="alert alert-success"><?= htmlspecialchars($ok, ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>
          <?php if ($msg): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
          <?php endif; ?>

          <form method="post" novalidate>
            <div class="mb-3">
              <label class="form-label">Korisničko ime</label>
              <input class="form-control" name="username" required autocomplete="username"
                     value="<?= isset($u) ? htmlspecialchars($u, ENT_QUOTES, 'UTF-8') : '' ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Lozinka</label>
              <input type="password" class="form-control" name="password" required autocomplete="current-password">
            </div>
            <button class="btn btn-primary w-100">Prijavi se</button>
          </form>

          <div class="text-center mt-3">
            Nemaš nalog? <a href="register.php">Registruj se</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
