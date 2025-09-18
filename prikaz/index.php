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

function e($s){ 
  return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
 }


$umetnici = $conn->query("SELECT * FROM umetnik ORDER BY id_umetnika DESC")->fetch_all(MYSQLI_ASSOC);

$counts = [
  'umetnici' => (int)$conn->query("SELECT COUNT(*) n FROM umetnik")->fetch_assoc()['n'],
  'dela'     => (int)$conn->query("SELECT COUNT(*) n FROM umetnicka_dela")->fetch_assoc()['n'],
  'galerije' => (int)$conn->query("SELECT COUNT(*) n FROM galerija")->fetch_assoc()['n'],
  'prodaje'  => (int)$conn->query("SELECT COUNT(*) n FROM prodaja")->fetch_assoc()['n'],
];

$poslednjeProdaje = $conn->query("
  SELECT p.id_prodaje, p.datum, p.kupac, p.cena,
         d.naziv_dela,
         g.naziv_galerije
  FROM prodaja p
  LEFT JOIN umetnicka_dela d ON d.id_umetnickogDela = p.umetnicko_delo_id
  LEFT JOIN galerija g      ON g.id_galerije       = p.galerija_id
  ORDER BY p.datum DESC, p.id_prodaje DESC
  LIMIT 5
")->fetch_all(MYSQLI_ASSOC);
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
      <span class="navbar-text text-white me-3">Korisnik: <?= e($user['username']) ?></span>
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
                <?php foreach ($umetnici as $r): ?>
                  <tr>
                    <td><?= (int)$r['id_umetnika'] ?></td>
                    <td><?= e($r['ime']) ?></td>
                    <td><?= e($r['prezime']) ?></td>
                    <td><?= e($r['biografija']) ?></td>
                    <td class="text-end">
                      <button
                        class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#editUmetnik"
                        data-id="<?= (int)$r['id_umetnika'] ?>"
                        data-ime="<?= e($r['ime']) ?>"
                        data-prezime="<?= e($r['prezime']) ?>"
                        data-bio="<?= e($r['biografija']) ?>"
                      >Izmeni</button>

                      <a class="btn btn-sm btn-outline-danger"
                         href="umetnik_delete.php?id=<?= (int)$r['id_umetnika'] ?>"
                         onclick="return confirm('Obriši umetnika?');">Obriši</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title mb-2">Zdravo, <?= e($user['username']) ?></h5>
          <p class="text-muted mb-3">Kratak pregled sistema.</p>

          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between align-items-center">
              Umetnici <span class="badge bg-secondary rounded-pill"><?= $counts['umetnici'] ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              Dela <span class="badge bg-secondary rounded-pill"><?= $counts['dela'] ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              Galerije <span class="badge bg-secondary rounded-pill"><?= $counts['galerije'] ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              Prodaje <span class="badge bg-secondary rounded-pill"><?= $counts['prodaje'] ?></span>
            </li>
          </ul>

          <h6 class="text-muted">Poslednje prodaje</h6>
          <div class="list-group mb-3">
            <?php if ($poslednjeProdaje): ?>
              <?php foreach ($poslednjeProdaje as $p): ?>
                <div class="list-group-item d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fw-semibold"><?= e($p['naziv_dela'] ?? '—') ?></div>
                    <small class="text-muted">
                      <?= e($p['datum']) ?>
                      <?php if (!empty($p['naziv_galerije'])): ?>&middot; <?= e($p['naziv_galerije']) ?><?php endif; ?>
                      &middot; <?= e($p['kupac']) ?>
                    </small>
                  </div>
                  <span class="badge bg-light text-dark ms-2">
                    <?= number_format((float)$p['cena'], 2, ',', '.') ?>
                  </span>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="list-group-item text-muted">Još uvek nema prodaja.</div>
            <?php endif; ?>
          </div>

          <div class="d-grid gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUmetnik">+ Novi umetnik</button>
            <a class="btn btn-outline-primary" href="umetnickoPrikaz.php#add">+ Novo delo</a>
            <a class="btn btn-outline-primary" href="galerijaPrikaz.php#add">+ Nova galerija</a>
            <a class="btn btn-outline-primary" href="prodajaPrikaz.php#add">+ Nova prodaja</a>
          </div>

          <hr class="my-3">
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

<div class="modal fade" id="editUmetnik" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="post" action="umetnik_update.php">
      <div class="modal-header">
        <h5 class="modal-title">Izmena umetnika</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id_umetnika" id="m-id">
        <div class="mb-3">
          <label class="form-label">Ime</label>
          <input name="ime" id="m-ime" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Prezime</label>
          <input name="prezime" id="m-prezime" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Biografija</label>
          <textarea name="biografija" id="m-bio" class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Otkaži</button>
        <button class="btn btn-primary" type="submit">Sačuvaj</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

document.getElementById('editUmetnik').addEventListener('show.bs.modal', function (ev) {
  const btn = ev.relatedTarget;
  document.getElementById('m-id').value      = btn.getAttribute('data-id') || '';
  document.getElementById('m-ime').value     = btn.getAttribute('data-ime') || '';
  document.getElementById('m-prezime').value = btn.getAttribute('data-prezime') || '';
  document.getElementById('m-bio').value     = btn.getAttribute('data-bio') || '';
});
</script>
</body>
</html>
