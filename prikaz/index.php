<?php
require_once __DIR__ . '/../baza/db.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/umetnik.php';

$auth = new Auth($conn);
if (!$auth->isLoggedIn()) {
  header('Location: login.php');
  exit;
}
$user = $auth->getUser();

$rez = $conn->query("SELECT * FROM umetnik ORDER BY id_umetnika DESC");


function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="sr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Umetnička dela – Početna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Umetnička dela</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainnav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="mainnav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link active" href="index.php">Umetnici</a></li>
        <li class="nav-item"><a class="nav-link" href="umetnickoPrikaz.php">Dela</a></li>
        <li class="nav-item"><a class="nav-link" href="galerijaPrikaz.php">Galerije</a></li>
        <li class="nav-item"><a class="nav-link" href="prodajaPrikaz.php">Prodaje</a></li>
      </ul>
      <span class="navbar-text text-white me-3">👤 <?= e($user['username']) ?></span>
      <a class="btn btn-outline-light btn-sm" href="logout.php">Odjavi se</a>
    </div>
  </div>
</nav>

<div class="container py-4">

  <?php if(isset($_GET['ok'])): ?>
    <div class="alert alert-success">Uspešno sačuvano.</div>
  <?php elseif(isset($_GET['err'])): ?>
    <div class="alert alert-danger"><?= e($_GET['err']) ?></div>
  <?php endif; ?>

  <div class="row g-3">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span class="fw-semibold">Umetnici</span>
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalUmetnik">+ Novi</button>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Ime</th>
                  <th>Prezime</th>
                  <th>Biografija</th>
                  <th class="text-end">Akcije</th>
                </tr>
              </thead>
              <tbody>
              <?php while($r = $rez->fetch_assoc()): ?>
                <tr>
                  <td><?= (int)$r['id_umetnika'] ?></td>
                  <td><?= e($r['ime']) ?></td>
                  <td><?= e($r['prezime']) ?></td>
                  <td><?= e($r['biografija']) ?></td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-outline-secondary"
                            data-bs-toggle="modal"
                            data-bs-target="#edit<?= (int)$r['id_umetnika'] ?>">
                      Izmeni
                    </button>
                    <a class="btn btn-sm btn-outline-danger"
                       href="umetnik_delete.php?id=<?= (int)$r['id_umetnika'] ?>"
                       onclick="return confirm('Obriši umetnika?');">
                      Obriši
                    </a>
                  </td>
                </tr>

               
                <div class="modal fade" id="edit<?= (int)$r['id_umetnika'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <form class="modal-content" method="post" action="umetnik_update.php">
                      <div class="modal-header">
                        <h5 class="modal-title">Izmena umetnika #<?= (int)$r['id_umetnika'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id_umetnika" value="<?= (int)$r['id_umetnika'] ?>">
                        <div class="mb-3">
                          <label class="form-label">Ime</label>
                          <input class="form-control" name="ime" value="<?= e($r['ime']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Prezime</label>
                          <input class="form-control" name="prezime" value="<?= e($r['prezime']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label">Biografija</label>
                          <textarea class="form-control" name="biografija" rows="3"><?= e($r['biografija']) ?></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Otkaži</button>
                        <button class="btn btn-primary">Sačuvaj</button>
                      </div>
                    </form>
                  </div>
                </div>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

   
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title mb-2">Zdravo, <?= e($user['username']) ?> </h5>
          <p class="text-muted mb-3">Zdravo! Sve što ti treba je u gornjem meniju:Umetnička Dela, Galerije, Prodaje.</p>
          <div class="d-grid gap-2">
            <a class="btn btn-outline-primary" href="umetnikPrikaz.php">Otvori: Umetnici (prikaz)</a>
            <a class="btn btn-outline-primary" href="umetnickoPrikaz.php">Otvori: Umetnička dela</a>
            <a class="btn btn-outline-primary" href="galerijaPrikaz.php">Otvori: Galerije</a>
            <a class="btn btn-outline-primary" href="prodajaPrikaz.php">Otvori: Prodaje</a>
          </div>
          <hr>
          <a class="btn btn-outline-danger btn-sm" href="logout.php">Odjavi se</a>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalUmetnik" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="umetnik_create.php">
      <div class="modal-header">
        <h5 class="modal-title">Novi umetnik</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Ime</label>
          <input class="form-control" name="ime" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Prezime</label>
          <input class="form-control" name="prezime" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Biografija</label>
          <textarea class="form-control" name="biografija" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Otkaži</button>
        <button class="btn btn-primary">Sačuvaj</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
