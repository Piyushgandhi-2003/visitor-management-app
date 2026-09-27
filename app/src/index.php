<?php
require 'auth.php';
require_login();
require 'db.php';

$page_title = 'Dashboard';

$total = $pdo->query("SELECT COUNT(*) c FROM visitors")->fetch()['c'];
$today = $pdo->query("SELECT COUNT(*) c FROM visitors WHERE DATE(check_in) = CURDATE()")->fetch()['c'];
$currently_in = $pdo->query("SELECT COUNT(*) c FROM visitors WHERE check_out IS NULL")->fetch()['c'];
$top_reason_row = $pdo->query("SELECT visit_reason, COUNT(*) c FROM visitors GROUP BY visit_reason ORDER BY c DESC LIMIT 1")->fetch();
$top_reason = $top_reason_row ? ucfirst($top_reason_row['visit_reason']) : '—';

include 'includes/header.php';
?>
<h1>Dashboard</h1>
<p class="page-sub">Welcome back, <?= htmlspecialchars($_SESSION['admin_username']) ?> 👋</p>

<div class="stat-grid">
    <div class="stat-card">
        <div class="num"><?= $total ?></div>
        <div class="label">Total Visitors</div>
    </div>
    <div class="stat-card">
        <div class="num"><?= $today ?></div>
        <div class="label">Today's Visitors</div>
    </div>
    <div class="stat-card">
        <div class="num"><?= $currently_in ?></div>
        <div class="label">Currently On-site</div>
    </div>
    <div class="stat-card">
        <div class="num"><?= htmlspecialchars($top_reason) ?></div>
        <div class="label">Top Visit Reason</div>
    </div>
</div>

<div class="chart-wrap">
    <h3 style="margin-top:0;">Visits by Reason</h3>
    <canvas id="reasonChart" height="90"></canvas>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
<script>
fetch('api_stats.php')
    .then(function (r) { return r.json(); })
    .then(function (data) {
        var ctx = document.getElementById('reasonChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Visits',
                    data: data.totals,
                    backgroundColor: '#4f46e5',
                    borderRadius: 8,
                    maxBarThickness: 48
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    });
</script>
<?php include 'includes/footer.php'; ?>
