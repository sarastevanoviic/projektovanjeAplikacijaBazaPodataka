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

$ime       = trim($_POST['ime'] ?? '');
$prezime   = trim($_POST['prezime'] ?? '');
$biografija= trim($_POST['biografija'] ?? '');

if ($ime === '' || $prezime === '') {
  // minimalna validacija
  header('Location: index.php?err=Unesi+ime+i+prezime');
  exit;
}

$um = new Umetnik($conn);
$ok = $um->create([
  'ime' => $ime,
  'prezime' => $prezime,
  'biografija' => $biografija
]);

header('Location: index.php?'.($ok ? 'ok=1' : 'err=Gre%C5%A1ka+pri+upisu'));
exit;
