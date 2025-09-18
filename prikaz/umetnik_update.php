<?php
require_once __DIR__ . '/../baza/db.php';
require_once __DIR__ . '/../php/auth.php';
require_once __DIR__ . '/../php/umetnik.php';

$auth = new Auth($conn);
if (!$auth->isLoggedIn()) {
   header('Location: login.php'); 
   exit;
   }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: umetnici.php'); exit; }

$id        = (int)($_POST['id_umetnika'] ?? 0);
$ime       = trim($_POST['ime'] ?? '');
$prezime   = trim($_POST['prezime'] ?? '');
$biografija= trim($_POST['biografija'] ?? '');

if ($id <= 0 || $ime === '' || $prezime === '') {
  header('Location: umetnici.php?err='.urlencode('Neispravan unos')); exit;
}

$model = new Umetnik($conn);
$ok = $model->update($id, [
  'ime' => $ime,
  'prezime' => $prezime,
  'biografija' => $biografija
]);

header('Location: umetnici.php?'.($ok ? 'ok=1' : 'err='.urlencode('Greška pri izmeni')));
exit;
