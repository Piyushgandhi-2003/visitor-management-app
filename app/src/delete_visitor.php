<?php
require 'auth.php';
require_login();
require 'db.php';

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("DELETE FROM visitors WHERE id = ?");
$stmt->execute([$id]);

header('Location: fetch_all.php');
exit;
