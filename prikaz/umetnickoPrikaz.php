<?php
require_once __DIR__ . '/../baza/db.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/umetnickoDelo.php';

$model = new UmetnickoDelo($conn);

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }


if ($_SERVER['REQUEST_METHOD'] === 'POST'){  
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
    $model->create([
        'naziv_dela'  => $_POST['naziv_dela'],
        'godina'      => (int)$_POST['godina'],
        'tip'         => $_POST['tip'] ?? null,
        'cena'        => $_POST['cena'] ?? null,
        'id_umetnik'  => (int)$_POST['id_umetnik'],
        'galerija_id' => (int)$_POST['galerija_id'],
    ]);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
}
    if ($action === 'update') {
    $id = (int)$_POST['id_umetnickogDela'];
    $model->update($id, [
        'naziv_dela'  => $_POST['naziv_dela'],
        'godina'      => (int)$_POST['godina'],
        'tip'         => $_POST['tip'] ?? null,
        'cena'        => $_POST['cena'] ?? null,
        'id_umetnik'  => (int)$_POST['id_umetnik'],
        'galerija_id' => (int)$_POST['galerija_id'],
    ]);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (($_GET['action'] ?? '') === 'delete' && isset($_GET['id'])) {
    $model->delete((int)$_GET['id']);
    header("Location: ". strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}


$rez = $conn->query("SELECT * FROM `umetnickadela` ORDER BY `id_umetnickogDela` DESC");
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

  
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Dodaj umetničko delo</h5>
      <form method="post" class="row g-3">
        <input type="hidden" name="action" value="create">

       <label class="form-label">Naziv dela</label>
<input name="naziv_dela" class="form-control" required>

<label class="form-label">Godina</label>
<input type="number" name="godina" class="form-control">

<label class="form-label">Tip</label>
<input name="tip" class="form-control">

<label class="form-label">Cena</label>
<input type="number" step="0.01" name="cena" class="form-control">

<label class="form-label">ID umetnika</label>
<input type="number" name="id_umetnik" class="form-control" required>

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
              <td><?= (int)$d['id_umetnickogDela'] ?></td>
              <td><?= e($d['naziv_dela']) ?></td>
              <td><?= e($d['godina']) ?></td>
              <td><?= e($d['tip']) ?></td>
              <td><?= e($d['cena']) ?></td>
              <td><?= (int)$d['id_umetnik'] ?></td>
              <td><?= (int)$d['galerija_id'] ?></td>

              <td class="text-end">
                
                <button class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#editDelo<?= (int)$d['id_umetnickogDela'] ?>">
                  Izmeni
                </button>
                
                <a href="?action=delete&id=<?= (int)$d['id_umetnickogDela'] ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Da li sigurno brišeš delo?');">
                   Obriši
                </a>
              </td>
            </tr>

           
            <div class="modal fade" id="editDelo<?= (int)$d['idumetnickogdela'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <form class="modal-content" method="post">
                  <div class="modal-header">
                    <h5 class="modal-title">Izmena dela #<?= (int)$d['idumetnickogdela'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id_umetnickogDela" value="<?= (int)$d['id_umetnickogDela'] ?>">

                    <label class="form-label">Naziv dela</label>
                    <input name="naziv_dela" class="form-control" value="<?= e($d['naziv_dela']) ?>" required>

                    <label class="form-label">Godina</label>
                    <input type="number" name="godina" class="form-control" value="<?= e($d['godina']) ?>">

                    <label class="form-label">Tip</label>
                    <input name="tip" class="form-control" value="<?= e($d['tip']) ?>">

                    <label class="form-label">Cena</label>
                    <input type="number" step="0.01" name="cena" class="form-control" value="<?= e($d['cena']) ?>">

                    <label class="form-label">ID umetnika</label>
                    <input type="number" name="id_umetnik" class="form-control" value="<?= (int)$d['id_umetnik'] ?>">

                    <label class="form-label">ID galerije</label>
                    <input type="number" name="galerija_id" class="form-control" value="<?= (int)$d['galerija_id'] ?>">
 
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