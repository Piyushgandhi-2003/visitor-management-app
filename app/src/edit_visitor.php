<?php
require 'auth.php';
require_login();
require 'db.php';

$page_title = 'Edit Visitor';
$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM visitors WHERE id = ?");
$stmt->execute([$id]);
$visitor = $stmt->fetch();

if (!$visitor) {
    include 'includes/header.php';
    echo '<h1>Not found</h1><p class="page-sub">That visitor record does not exist.</p>';
    echo '<a href="fetch_all.php" class="back-link">← Back to all visitors</a>';
    include 'includes/footer.php';
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $name = trim($_POST['name'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $visit_reason = $_POST['visit_reason'] ?? '';
    $allowed_reasons = ['purchasing', 'enquiry', 'dispute', 'meeting', 'presentation', 'others'];

    if ($name === '') $errors['name'] = 'Name is required.';
    if (!preg_match('/^[0-9+\-\s]{7,15}$/', $mobile)) $errors['mobile'] = 'Enter a valid mobile number.';
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Enter a valid email.';
    if (!in_array($visit_reason, $allowed_reasons, true)) $errors['visit_reason'] = 'Select a valid reason.';

    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE visitors SET name=?, mobile=?, email=?, contact_person=?, visit_reason=? WHERE id=?");
        $stmt->execute([$name, $mobile, $email ?: null, $contact_person ?: null, $visit_reason, $id]);
        header('Location: fetch_all.php');
        exit;
    }
    $visitor = compact('name', 'mobile', 'email', 'contact_person', 'visit_reason') + $visitor;
}

include 'includes/header.php';
?>
<h1>Edit Visitor</h1>
<p class="page-sub">Update details for this visitor record.</p>

<div class="card">
<form method="post" action="">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

    <div>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($visitor['name']) ?>" required>
        <?php if (isset($errors['name'])): ?><div class="field-error"><?= $errors['name'] ?></div><?php endif; ?>
    </div>
    <div>
        <label for="mobile">Mobile No.</label>
        <input type="tel" id="mobile" name="mobile" value="<?= htmlspecialchars($visitor['mobile']) ?>" required>
        <?php if (isset($errors['mobile'])): ?><div class="field-error"><?= $errors['mobile'] ?></div><?php endif; ?>
    </div>
    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($visitor['email'] ?? '') ?>">
        <?php if (isset($errors['email'])): ?><div class="field-error"><?= $errors['email'] ?></div><?php endif; ?>
    </div>
    <div>
        <label for="contact_person">Contact Person Name</label>
        <input type="text" id="contact_person" name="contact_person" value="<?= htmlspecialchars($visitor['contact_person'] ?? '') ?>">
    </div>
    <div>
        <label for="visit_reason">Reason for Visit</label>
        <select id="visit_reason" name="visit_reason">
            <?php foreach (['purchasing', 'enquiry', 'dispute', 'meeting', 'presentation', 'others'] as $reason): ?>
                <option value="<?= $reason ?>" <?= $visitor['visit_reason'] === $reason ? 'selected' : '' ?>><?= ucfirst($reason) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="display:flex; gap:10px;">
        <button type="submit">Save Changes</button>
        <a href="fetch_all.php" class="btn btn-secondary">Cancel</a>
    </div>
</form>
</div>

<?php include 'includes/footer.php'; ?>
