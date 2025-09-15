<?php

require_once __DIR__ . '/../baza/db.php';
require_once __DIR__ . '/../php/galerija.php';


$model = new Galerija($conn);


function e($s){
   return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
   }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $model->create([
            'naziv_galerije' => $_POST['naziv_galerije'],
            'adresa'         => $_POST['adresa']
        ]);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
    if ($action === 'update') {
        $id = (int)$_POST['id_galerije'];
        $model->update($id, [
            'naziv_galerije' => $_POST['naziv_galerije'],
            'adresa'         => $_POST['adresa']
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


$galerije = $conn->query("SELECT * FROM `galerija` ORDER BY `id_galerije` DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="sr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Galerije</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

  
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Dodaj galeriju</h5>
      <form method="post" class="row g-3">
        <input type="hidden" name="action" value="create">
        <div class="col-md-6">
          <label class="form-label">Naziv</label>
          <input type="text" name="naziv_galerije" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Adresa</label>
          <input type="text" name="adresa" class="form-control">
        </div>
        <div class="col-12">
          <button class="btn btn-primary">Sačuvaj</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Lista galerija</h5>
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Naziv</th>
              <th>Adresa</th>
              <th class="text-end">Akcije</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($galerije as $g): ?>
            <tr>
              <td><?= (int)$g['id_galerije'] ?></td>
              <td><?= e($g['naziv_galerije']) ?></td>
              <td><?= e($g['adresa']) ?></td>
              <td class="text-end">
              
                <button class="btn btn-sm btn-outline-secondary"
                        data-bs-toggle="modal"
                        data-bs-target="#editGalerija<?= (int)$g['id_galerije'] ?>">
                  Izmeni
                </button>
                
                <a href="?action=delete&id=<?= (int)$g['id_galerije'] ?>"
                   class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Obriši galeriju?');">
                  Obriši
                </a>
              </td>
            </tr>

          
            <div class="modal fade" id="editGalerija<?= (int)$g['id_galerije'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <form class="modal-content" method="post">
                  <div class="modal-header">
                    <h5 class="modal-title">Izmena galerije #<?= (int)$g['id_galerije'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id_galerije" value="<?= (int)$g['id_galerije'] ?>">

                    <div class="mb-3">
                      <label class="form-label">Naziv</label>
                      <input name="naziv_galerije" class="form-control" value="<?= e($g['naziv_galerije']) ?>" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Adresa</label>
                      <input name="adresa" class="form-control" value="<?= e($g['adresa']) ?>">
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
