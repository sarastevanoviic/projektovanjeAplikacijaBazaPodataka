<?php
require_once __DIR__ . '/../baza/db.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/umetnickoDelo.php';

$model = new UmetnickoDelo($conn);

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $model->create([
            'naziv'      => $_POST['naziv'],
            'opis'       => $_POST['opis'],
            'godina'     => (int)$_POST['godina'],
            'idumetnik'  => (int)$_POST['idumetnik'],
            'idgalerije' => (int)$_POST['idgalerije'],
        ]);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
    if ($action === 'update') {
        $id = (int)$_POST['idumetnickogdela'];
        $model->update($id, [
            'naziv'      => $_POST['naziv'],
            'opis'       => $_POST['opis'],
            'godina'     => (int)$_POST['godina'],
            'idumetnik'  => (int)$_POST['idumetnik'],
            'idgalerije' => (int)$_POST['idgalerije'],
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

// uzmi sve zapise
$rez = $conn->query("SELECT * FROM umetnicko_delo ORDER BY idumetnickogdela DESC");
$umetnickaDela = $rez->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="sr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Umetnička dela</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  <!-- Forma: Dodaj umetničko delo -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Dodaj umetničko delo</h5>
      <form method="post" class="row g-3">
        <input type="hidden" name="action" value="create">

        <div class="col-md-6">
          <label class="form-label">Naziv</label>
          <input name="naziv" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Godina</label>
          <input type="number" name="godina" class="form-control">
        </div>
        <div class="col-12">
          <label class="form-label">Opis</label>
          <textarea name="opis" class="form-control" rows="3"></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">ID umetnika</label>
          <input type="number" name="idumetnik" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">ID galerije</label>
          <input type="number" name="idgalerije" class="form-control">
        </div>
        <div class="col-12">
          <button class="btn btn-primary">Sačuvaj</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabela sa delima -->
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Lista umetničkih dela</h5>
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Naziv</th>
              <th>Godina</th>
              <th>Opis</th>
              <th>ID umetnika</th>
              <th>ID galerije</th>
              <th class="text-end">Akcije</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($umetnickaDela as $d): ?>
            <tr>
              <td><?= (int)$d['idumetnickogdela'] ?></td>
              <td><?= e($d['naziv']) ?></td>
              <td><?= e($d['godina']) ?></td>
              <td><?= nl2br(e($d['opis'])) ?></td>
              <td><?= (int)$d['idumetnik'] ?></td>
              <td><?= (int)$d['idgalerije'] ?></td>
              <td class="text-end">
                <!-- Dugme Izmeni -->
                <button class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#editDelo<?= (int)$d['idumetnickogdela'] ?>">
                  Izmeni
                </button>
                <!-- Link Obriši -->
                <a href="?action=delete&id=<?= (int)$d['idumetnickogdela'] ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Da li sigurno brišeš delo?');">
                   Obriši
                </a>
              </td>
            </tr>

            <!-- Modal za izmenu -->
            <div class="modal fade" id="editDelo<?= (int)$d['idumetnickogdela'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <form class="modal-content" method="post">
                  <div class="modal-header">
                    <h5 class="modal-title">Izmena dela #<?= (int)$d['idumetnickogdela'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="idumetnickogdela" value="<?= (int)$d['idumetnickogdela'] ?>">

                    <div class="mb-3">
                      <label class="form-label">Naziv</label>
                      <input name="naziv" class="form-control" value="<?= e($d['naziv']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Godina</label>
                      <input type="number" name="godina" class="form-control" value="<?= e($d['godina']) ?>">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Opis</label>
                      <textarea name="opis" class="form-control" rows="3"><?= e($d['opis']) ?></textarea>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">ID umetnika</label>
                      <input type="number" name="idumetnik" class="form-control" value="<?= (int)$d['idumetnik'] ?>">
                    </div>
                    <div class="mb-3">
                      <label class="form-label">ID galerije</label>
                      <input type="number" name="idgalerije" class="form-control" value="<?= (int)$d['idgalerije'] ?>">
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