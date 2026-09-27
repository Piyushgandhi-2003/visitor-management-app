<?php
require 'auth.php';
require_login();
require 'db.php';

$page_title = 'All Visitors';

$search = trim($_GET['q'] ?? '');
$page = max(1, (int) ($_GET['page'] ?? 1));
$per_page = 10;
$offset = ($page - 1) * $per_page;

$where = '';
$params = [];
if ($search !== '') {
    $where = 'WHERE name LIKE ? OR mobile LIKE ?';
    $params = ["%$search%", "%$search%"];
}

$count_stmt = $pdo->prepare("SELECT COUNT(*) c FROM visitors $where");
$count_stmt->execute($params);
$total_rows = $count_stmt->fetch()['c'];
$total_pages = max(1, (int) ceil($total_rows / $per_page));

$stmt = $pdo->prepare("SELECT * FROM visitors $where ORDER BY check_in DESC LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$visitors = $stmt->fetchAll();

include 'includes/header.php';
?>
<h1>All Visitors</h1>
<p class="page-sub"><?= $total_rows ?> record<?= $total_rows === 1 ? '' : 's' ?> found.</p>

<div class="toolbar">
    <form method="get" action="">
        <input type="text" name="q" placeholder="Search by name or mobile..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn-sm">Search</button>
        <?php if ($search !== ''): ?><a href="fetch_all.php" class="btn btn-secondary btn-sm">Clear</a><?php endif; ?>
    </form>
</div>

<div class="table-wrap">
    <table>
        <tr>
            <th>Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Contact Person</th>
            <th>Reason</th>
            <th>Check-in</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($visitors as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['mobile']) ?></td>
                <td><?= htmlspecialchars($row['email'] ?: '—') ?></td>
                <td><?= htmlspecialchars($row['contact_person'] ?: '—') ?></td>
                <td><span class="badge"><?= htmlspecialchars($row['visit_reason']) ?></span></td>
                <td><?= htmlspecialchars($row['check_in']) ?></td>
                <td>
                    <?php if ($row['check_out']): ?>
                        <span class="badge badge-out">Checked out</span>
                    <?php else: ?>
                        <span class="badge badge-in">On-site</span>
                    <?php endif; ?>
                </td>
                <td style="white-space:nowrap;">
                    <a href="edit_visitor.php?id=<?= $row['id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <?php if (!$row['check_out']): ?>
                        <a href="checkout.php?id=<?= $row['id'] ?>" class="btn btn-sm">Check out</a>
                    <?php endif; ?>
                    <a href="delete_visitor.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm confirm-delete">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$visitors): ?>
            <tr><td colspan="8" style="text-align:center; color:var(--text-muted);">No visitors found.</td></tr>
        <?php endif; ?>
    </table>

    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php for ($p = 1; $p <= $total_pages; $p++): ?>
            <?php if ($p === $page): ?>
                <span class="current"><?= $p ?></span>
            <?php else: ?>
                <a href="?page=<?= $p ?>&q=<?= urlencode($search) ?>"><?= $p ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
