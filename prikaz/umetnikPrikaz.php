<?php

require_once __DIR__ . '/../baza/db.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/crud.php';
require_once __DIR__ . '/../php/umetnik.php';


$auth = new Auth($conn);
if (!$auth->isLoggedIn()) { header('Location: login.php'); exit; }

$model = new Umetnik($conn);


function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $data = [
            'ime'        => $_POST['ime'] ?? '',
            'prezime'    => $_POST['prezime'] ?? '',
            'biografija' => $_POST['biografija'] ?? '',
        ];
        $model->create($data);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    if ($action === 'update') {
        $id   = (int)($_POST['id_umetnika'] ?? 0);
        $data = [
            'ime'        => $_POST['ime'] ?? '',
            'prezime'    => $_POST['prezime'] ?? '',
            'biografija' => $_POST['biografija'] ?? '',
        ];
        $model->update($id, $data);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}


if (($_GET['action'] ?? '') === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $model->delete($id);
    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}


$umetnici = $conn->query("SELECT * FROM umetnik ORDER BY id_umetnika DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="sr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Umetnici</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Dodaj umetnika</h5>
      <form method="post" class="row g-3">
        <input type="hidden" name="action" value="create">
        <div class="col-md-6">
          <label class="form-label">Ime</label>
          <input type="text" name="ime" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Prezime</label>
          <input type="text" name="prezime" class="form-control" required>
        </div>
        <div class="col-12">
          <label class="form-label">Biografija</label>
          <textarea name="biografija" class="form-control" rows="3"></textarea>
        </div>
        <div class="col-12">
          <button class="btn btn-primary">Sačuvaj</button>
        </div>
      </form>
    </div>
  </div>


  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="card-title mb-0">Lista umetnika</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Ime</th>
              <th>Prezime</th>
              <th>Biografija</th>
              <th class="text-end">Akcije</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($umetnici as $u): ?>
            <tr>
              <td><?= (int)$u['id_umetnika'] ?></td>
              <td><?= e($u['ime']) ?></td>
              <td><?= e($u['prezime']) ?></td>
              <td><?= nl2br(e($u['biografija'])) ?></td>
              <td class="text-end">
                
                <button class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#editUmetnik<?= (int)$u['id_umetnika'] ?>">
                  Izmeni
                </button>

                <a class="btn btn-sm btn-outline-danger"
                   href="?action=delete&id=<?= (int)$u['id_umetnika'] ?>"
                   onclick="return confirm('Da li sigurno želiš da obrišeš ovog umetnika?');">
                  Obriši
                </a>
              </td>
            </tr>

          
            <div class="modal fade" id="editUmetnik<?= (int)$u['id_umetnika'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <form class="modal-content" method="post">
                  <div class="modal-header">
                    <h5 class="modal-title">Izmena umetnika #<?= (int)$u['id_umetnika'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id_umetnika" value="<?= (int)$u['id_umetnika'] ?>">

                    <div class="mb-3">
                      <label class="form-label">Ime</label>
                      <input name="ime" class="form-control" value="<?= e($u['ime']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Prezime</label>
                      <input name="prezime" class="form-control" value="<?= e($u['prezime']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Biografija</label>
                      <textarea name="biografija" class="form-control" rows="3"><?= e($u['biografija']) ?></textarea>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-primary">Sačuvaj izmene</button>
                  </div>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
