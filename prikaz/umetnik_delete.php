<?php
require_once __DIR__ . '/../baza/db.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/umetnik.php';

$auth = new Auth($conn);
if (!$auth->isLoggedIn()) { header('Location: login.php'); exit; }

$id = (int)($_GET['id_umetnika'] ?? 0);
if ($id <= 0) {
  header('Location: index.php?err=ID+nije+prosleđen');
  exit;
}

$mdl = new Umetnik($conn);
$ok  = $mdl->delete($id);

header('Location: index.php?'.($ok ? 'ok=1' : 'err=Greška+pri+brisanju'));
exit;
?>