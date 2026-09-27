<?php
require 'auth.php';
require_login();
require 'db.php';

header('Content-Type: application/json');

$stmt = $pdo->query("SELECT visit_reason, COUNT(*) as total FROM visitors GROUP BY visit_reason");
$rows = $stmt->fetchAll();

$labels = [];
$totals = [];
foreach ($rows as $row) {
    $labels[] = ucfirst($row['visit_reason']);
    $totals[] = (int) $row['total'];
}

echo json_encode(['labels' => $labels, 'totals' => $totals]);
