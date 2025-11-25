<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Collector</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/style.css">
</head>
<body>
    <div class="auth-container">
        <h1>The Collector</h1>
        <p style="text-align: center; color: #64748b; margin-bottom: 2rem;">Login to your account</p>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="<?= BASE_PATH ?>/login">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="<?= htmlspecialchars($username ?? '') ?>"
                    required 
                    autofocus
                >
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        
        <p style="text-align: center; margin-top: 1rem;">
            Don't have an account? 
            <a href="<?= BASE_PATH ?>/register">Register here</a>
        </p>
    </div>
</body>
</html>