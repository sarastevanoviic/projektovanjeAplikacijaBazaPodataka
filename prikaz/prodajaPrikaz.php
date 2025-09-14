<?php
require_once __DIR__ . '/../baza/db.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/prodaja.php';

$model = new Prodaja($conn);

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $model->create([
            'umetnicko_delo_id' => (int)$_POST['umetnicko_delo_id'],
            'datum'             => $_POST['datum'],
            'kupac'             => $_POST['kupac'],
            'cena'              => (float)$_POST['cena'],
            'galerija_id'       => (int)$_POST['galerija_id'],
        ]);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
    if ($action === 'update') {
        $id = (int)$_POST['id_prodaje'];
        $model->update($id, [
            'umetnicko_delo_id' => (int)$_POST['umetnicko_delo_id'],
            'datum'             => $_POST['datum'],
            'kupac'             => $_POST['kupac'],
            'cena'              => (float)$_POST['cena'],
            'galerija_id'       => (int)$_POST['galerija_id'],
        ]);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

if (($_GET['action'] ?? '') === 'delete' && isset($_GET['id'])) {
    $model->delete((int)$_GET['id']);
    header("Location: ". strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}


$q = "
  SELECT p.*, d.naziv_dela AS delo_naziv
  FROM prodaja p
  LEFT JOIN umetnickadela d ON d.id_umetnickogDela = p.umetnicko_delo_id
  ORDER BY p.id_prodaje DESC
";
$prodaje = $conn->query($q)->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="sr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Prodaje</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Dodaj prodaju</h5>
      <form method="post" class="row g-3">
        <input type="hidden" name="action" value="create">

        <div class="col-md-4">
          <label class="form-label">ID umetničkog dela</label>
          <input type="number" name="umetnicko_delo_id" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Datum</label>
          <input type="date" name="datum" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Cena</label>
          <input type="number" step="0.01" name="cena" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Kupac</label>
          <input name="kupac" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">ID galerije</label>
          <input type="number" name="galerija_id" class="form-control">
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
        <h5 class="card-title mb-0">Lista prodaja</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>ID dela</th>
              <th>Naziv dela</th>
              <th>Datum</th>
              <th>Kupac</th>
              <th>Cena</th>
              <th>ID galerije</th>
              <th class="text-end">Akcije</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($prodaje as $p): ?>
            <tr>
              <td><?= (int)$p['id_prodaje'] ?></td>
              <td><?= (int)$p['umetnicko_delo_id'] ?></td>
              <td><?= e($p['delo_naziv'] ?? '') ?></td>
              <td><?= e($p['datum']) ?></td>
              <td><?= e($p['kupac']) ?></td>
              <td><?= number_format((float)$p['cena'], 2, ',', '.') ?></td>
              <td><?= (int)$p['galerija_id'] ?></td>
              <td class="text-end">
              
                <button class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#editProdaja<?= (int)$p['id_prodaje'] ?>">
                  Izmeni
                </button>
                
                <a href="?action=delete&id=<?= (int)$p['id_prodaje'] ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Obriši prodaju?');">
                  Obriši
                </a>
              </td>
            </tr>

       
            <div class="modal fade" id="editProdaja<?= (int)$p['id_prodaje'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <form class="modal-content" method="post">
                  <div class="modal-header">
                    <h5 class="modal-title">Izmena prodaje #<?= (int)$p['id_prodaje'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id_prodaje" value="<?= (int)$p['id_prodaje'] ?>">

                    <div class="mb-3">
                      <label class="form-label">ID umetničkog dela</label>
                      <input type="number" name="umetnicko_delo_id" class="form-control" value="<?= (int)$p['umetnicko_delo_id'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Datum</label>
                      <input type="date" name="datum" class="form-control" value="<?= e($p['datum']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Kupac</label>
                      <input name="kupac" class="form-control" value="<?= e($p['kupac']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Cena</label>
                      <input type="number" step="0.01" name="cena" class="form-control" value="<?= e($p['cena']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">ID galerije</label>
                      <input type="number" name="galerija_id" class="form-control" value="<?= (int)$p['galerija_id'] ?>">
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
