<?php
require 'auth.php';
require_login();
require 'db.php';

$page_title = 'Add Visitor';
$errors = [];
$success = false;
$values = ['name' => '', 'mobile' => '', 'email' => '', 'contact_person' => '', 'visit_reason' => 'purchasing'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $values['name'] = trim($_POST['name'] ?? '');
    $values['mobile'] = trim($_POST['mobile'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');
    $values['contact_person'] = trim($_POST['contact_person'] ?? '');
    $values['visit_reason'] = $_POST['visit_reason'] ?? '';

    $allowed_reasons = ['purchasing', 'enquiry', 'dispute', 'meeting', 'presentation', 'others'];

    if ($values['name'] === '' || mb_strlen($values['name']) > 100) {
        $errors['name'] = 'Name is required (max 100 characters).';
    }
    if (!preg_match('/^[0-9+\-\s]{7,15}$/', $values['mobile'])) {
        $errors['mobile'] = 'Enter a valid mobile number.';
    }
    if ($values['email'] !== '' && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if (!in_array($values['visit_reason'], $allowed_reasons, true)) {
        $errors['visit_reason'] = 'Select a valid visit reason.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO visitors (name, mobile, email, contact_person, visit_reason) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $values['name'],
            $values['mobile'],
            $values['email'] ?: null,
            $values['contact_person'] ?: null,
            $values['visit_reason'],
        ]);
        $success = true;
        $values = ['name' => '', 'mobile' => '', 'email' => '', 'contact_person' => '', 'visit_reason' => 'purchasing'];
    }
}

include 'includes/header.php';
?>
<h1>Add New Visitor</h1>
<p class="page-sub">Log a new visitor entry at reception.</p>

<?php if ($success): ?>
<div class="toast">✅ Visitor added successfully!</div>
<?php endif; ?>

<div class="card">
<form method="post" action="" novalidate>
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

    <div>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($values['name']) ?>" required>
        <?php if (isset($errors['name'])): ?><div class="field-error"><?= $errors['name'] ?></div><?php endif; ?>
    </div>

    <div>
        <label for="mobile">Mobile No.</label>
        <input type="tel" id="mobile" name="mobile" value="<?= htmlspecialchars($values['mobile']) ?>" required>
        <?php if (isset($errors['mobile'])): ?><div class="field-error"><?= $errors['mobile'] ?></div><?php endif; ?>
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($values['email']) ?>">
        <?php if (isset($errors['email'])): ?><div class="field-error"><?= $errors['email'] ?></div><?php endif; ?>
    </div>

    <div>
        <label for="contact_person">Contact Person Name</label>
        <input type="text" id="contact_person" name="contact_person" value="<?= htmlspecialchars($values['contact_person']) ?>">
    </div>

    <div>
        <label for="visit_reason">Reason for Visit</label>
        <select id="visit_reason" name="visit_reason">
            <?php foreach (['purchasing', 'enquiry', 'dispute', 'meeting', 'presentation', 'others'] as $reason): ?>
                <option value="<?= $reason ?>" <?= $values['visit_reason'] === $reason ? 'selected' : '' ?>><?= ucfirst($reason) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit">Add Visitor</button>
</form>
</div>

<?php include 'includes/footer.php'; ?>
