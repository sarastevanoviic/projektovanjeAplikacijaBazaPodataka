<?php
require_once 'db.php';
require_once 'auth.php';
require_once 'umetnik.php';

$auth = new Auth($conn);
if (!$auth->isLoggedIn()) { header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php');
  exit;
}

$id        = (int)($_POST['id'] ?? 0);
$ime       = trim($_POST['ime'] ?? '');
$prezime   = trim($_POST['prezime'] ?? '');
$biografija= trim($_POST['biografija'] ?? '');

if ($id <= 0 || $ime === '' || $prezime === '') {
  header('Location: index.php?err=Neispravan+unos');
  exit;
}

$mdl = new Umetnik($conn);
$ok  = $mdl->update($id, [
  'ime' => $ime,
  'prezime' => $prezime,
  'biografija' => $biografija
]);

header('Location: index.php?'.($ok ? 'ok=1' : 'err=Greška+pri+izmeni'));
exit;
