<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO submissions (name, email, message) VALUES (:name, :email, :message)'
            );
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':message' => $message,
            ]);
            $success = 'Form submitted successfully. Data saved in the database!';
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = 'Could not save the form. Check database settings and try again.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP Form Demo</title>
  <style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: Arial, sans-serif; background: #f3f6fb; color: #172033; }
    main { max-width: 560px; margin: 48px auto; padding: 28px; background: #fff; border-radius: 14px; box-shadow: 0 8px 28px #15284a14; }
    h1 { margin-top: 0; }
    p { color: #536078; line-height: 1.5; }
    label { display: block; margin: 16px 0 6px; font-weight: 700; }
    input, textarea { width: 100%; padding: 12px; border: 1px solid #cbd3e1; border-radius: 8px; font: inherit; }
    textarea { min-height: 120px; resize: vertical; }
    button { margin-top: 18px; padding: 12px 18px; border: 0; border-radius: 8px; background: #175cd3; color: white; font-weight: 700; cursor: pointer; }
    .notice { padding: 12px; border-radius: 8px; margin: 16px 0; }
    .success { background: #e8f8ef; color: #17663a; }
    .error { background: #fff0f0; color: #a32121; }
  </style>
</head>
<body>
<main>
  <h1>PHP Contact Form</h1>
  <p>Submit this form to test whether your PHP application can connect to MySQL.</p>

  <?php if ($success !== ''): ?>
    <div class="notice success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>
  <?php if ($error !== ''): ?>
    <div class="notice error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
  <?php endif; ?>

  <form method="post" action="">
    <label for="name">Name</label>
    <input id="name" name="name" maxlength="100" required
           value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <label for="email">Email</label>
    <input id="email" name="email" type="email" maxlength="190" required
           value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <label for="message">Message</label>
    <textarea id="message" name="message" maxlength="5000" required><?= htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

    <button type="submit">Save to database</button>
  </form>
</main>
</body>
</html>
