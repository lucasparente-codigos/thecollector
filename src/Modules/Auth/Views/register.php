<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - The Collector</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/style.css">
</head>

<body>
    <div class="auth-container">
        <h1>Register</h1>
        <?php if (isset($error) && $error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (isset($success) && $success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>
        <form method="POST" action="<?= BASE_PATH ?>/register">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="<?= BASE_PATH ?>/login">Login here</a>.</p>
    </div>
</body>

</html>