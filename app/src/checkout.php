<?php
require 'auth.php';
require_login();
require 'db.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("UPDATE visitors SET check_out = NOW() WHERE id = ? AND check_out IS NULL");
$stmt->execute([$id]);

header('Location: fetch_all.php');
exit;
