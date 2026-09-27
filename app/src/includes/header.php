<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' — VMS' : 'Visitor Management System' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="layout">
    <?php if (!empty($_SESSION['admin_id'])): ?>
    <aside class="sidebar">
        <div class="brand">
            <span class="brand-icon">🛡️</span>
            <div>
                <div class="brand-title">Sycatel VMS</div>
                <div class="brand-sub">Visitor Management</div>
            </div>
        </div>
        <nav>
            <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>">📊 Dashboard</a>
            <a href="add_visitor.php" class="<?= basename($_SERVER['PHP_SELF']) === 'add_visitor.php' ? 'active' : '' ?>">➕ New Visitor</a>
            <a href="fetch_all.php" class="<?= basename($_SERVER['PHP_SELF']) === 'fetch_all.php' ? 'active' : '' ?>">📋 All Visitors</a>
            <a href="fetch_by_reason.php" class="<?= basename($_SERVER['PHP_SELF']) === 'fetch_by_reason.php' ? 'active' : '' ?>">🔍 Filter by Reason</a>
        </nav>
        <div class="sidebar-footer">
            <button id="theme-toggle" type="button" title="Toggle dark mode">🌙</button>
            <a href="logout.php" class="logout-link">⏻ Logout</a>
        </div>
    </aside>
    <?php endif; ?>
    <main class="content">
