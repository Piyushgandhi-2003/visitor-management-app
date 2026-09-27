<?php
require 'auth.php';
require_login();
require 'db.php';

$page_title = 'Filter by Reason';
$visit_reason = $_POST['visit_reason'] ?? '';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filter'])) {
    $allowed_reasons = ['purchasing', 'enquiry', 'dispute', 'meeting', 'presentation', 'others'];
    if (in_array($visit_reason, $allowed_reasons, true)) {
        $stmt = $pdo->prepare("SELECT * FROM visitors WHERE visit_reason = ? ORDER BY check_in DESC");
        $stmt->execute([$visit_reason]);
        $results = $stmt->fetchAll();
    }
}

include 'includes/header.php';
?>
<h1>Filter Visitors by Reason</h1>
<p class="page-sub">Select a reason to see matching visitor records.</p>

<div class="toolbar">
    <form method="post" action="">
        <select id="visit_reason" name="visit_reason">
            <?php foreach (['purchasing', 'enquiry', 'dispute', 'meeting', 'presentation', 'others'] as $reason): ?>
                <option value="<?= $reason ?>" <?= $visit_reason === $reason ? 'selected' : '' ?>><?= ucfirst($reason) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="filter" value="1" class="btn-sm">Filter</button>
    </form>
</div>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
<div class="table-wrap">
    <table>
        <tr>
            <th>Name</th><th>Mobile</th><th>Email</th><th>Contact Person</th><th>Check-in</th><th>Status</th>
        </tr>
        <?php foreach ($results as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['mobile']) ?></td>
            <td><?= htmlspecialchars($row['email'] ?: '—') ?></td>
            <td><?= htmlspecialchars($row['contact_person'] ?: '—') ?></td>
            <td><?= htmlspecialchars($row['check_in']) ?></td>
            <td><?= $row['check_out'] ? '<span class="badge badge-out">Checked out</span>' : '<span class="badge badge-in">On-site</span>' ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (!$results): ?>
        <tr><td colspan="6" style="text-align:center; color:var(--text-muted);">No visitors found for this reason.</td></tr>
        <?php endif; ?>
    </table>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
