<?php
require_once __DIR__ . '/../baza/db.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/prodaja.php';

$model = new Prodaja($conn);

// helper za XSS escape
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// ROUTING: create / update / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $model->create([
            'idumetnickogdela' => (int)$_POST['idumetnickogdela'],
            'datum'            => $_POST['datum'],
            'kupac'            => $_POST['kupac'],
            'cena'             => (float)$_POST['cena'],
            'galerija'         => $_POST['galerija'],
        ]);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
    if ($action === 'update') {
        $id = (int)$_POST['idprodaje'];
        $model->update($id, [
            'idumetnickogdela' => (int)$_POST['idumetnickogdela'],
            'datum'            => $_POST['datum'],
            'kupac'            => $_POST['kupac'],
            'cena'             => (float)$_POST['cena'],
            'galerija'         => $_POST['galerija'],
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

// Učitavanje liste prodaja (sa nazivom dela ako postoji)
$q = "
  SELECT p.*, d.naziv AS delo_naziv
  FROM prodaja p
  LEFT JOIN umetnicko_delo d ON d.idumetnickogdela = p.idumetnickogdela
  ORDER BY p.idprodaje DESC
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

  <!-- Forma: Nova prodaja -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Dodaj prodaju</h5>
      <form method="post" class="row g-3">
        <input type="hidden" name="action" value="create">

        <div class="col-md-4">
          <label class="form-label">ID umetničkog dela</label>
          <input type="number" name="idumetnickogdela" class="form-control" required>
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
          <label class="form-label">Galerija</label>
          <input name="galerija" class="form-control" placeholder="npr. Moderna galerija, Beograd">
        </div>
        <div class="col-12">
          <button class="btn btn-primary">Sačuvaj</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabela: Prodaje -->
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
              <th>Galerija</th>
              <th class="text-end">Akcije</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($prodaje as $p): ?>
            <tr>
              <td><?= (int)$p['idprodaje'] ?></td>
              <td><?= (int)$p['idumetnickogdela'] ?></td>
              <td><?= e($p['delo_naziv'] ?? '') ?></td>
              <td><?= e($p['datum']) ?></td>
              <td><?= e($p['kupac']) ?></td>
              <td><?= number_format((float)$p['cena'], 2, ',', '.') ?></td>
              <td><?= e($p['galerija']) ?></td>
              <td class="text-end">
                <!-- Izmeni -->
                <button class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#editProdaja<?= (int)$p['idprodaje'] ?>">
                  Izmeni
                </button>
                <!-- Obriši -->
                <a href="?action=delete&id=<?= (int)$p['idprodaje'] ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Obriši prodaju?');">
                  Obriši
                </a>
              </td>
            </tr>

            <!-- Modal: Izmena prodaje -->
            <div class="modal fade" id="editProdaja<?= (int)$p['idprodaje'] ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <form class="modal-content" method="post">
                  <div class="modal-header">
                    <h5 class="modal-title">Izmena prodaje #<?= (int)$p['idprodaje'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="idprodaje" value="<?= (int)$p['idprodaje'] ?>">

                    <div class="mb-3">
                      <label class="form-label">ID umetničkog dela</label>
                      <input type="number" name="idumetnickogdela" class="form-control" value="<?= (int)$p['idumetnickogdela'] ?>" required>
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
                      <label class="form-label">Galerija</label>
                      <input name="galerija" class="form-control" value="<?= e($p['galerija']) ?>">
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
