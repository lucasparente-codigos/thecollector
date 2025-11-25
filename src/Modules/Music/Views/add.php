<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Music - The Collector</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/style.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="<?= BASE_PATH ?>/dashboard" class="brand">The Collector</a>
            <div class="user-info">
                <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="<?= BASE_PATH ?>/logout" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <h1>Add Music</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_PATH ?>/music/store" class="music-form">
            <div class="form-group">
                <label for="title">Title *</label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="<?= htmlspecialchars($music['title'] ?? '') ?>"
                    required
                    autofocus
                >
            </div>
            
            <div class="form-group">
                <label for="artist">Artist *</label>
                <input 
                    type="text" 
                    id="artist" 
                    name="artist" 
                    value="<?= htmlspecialchars($music['artist'] ?? '') ?>"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="album">Album</label>
                <input 
                    type="text" 
                    id="album" 
                    name="album"
                    value="<?= htmlspecialchars($music['album'] ?? '') ?>"
                >
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input 
                        type="text" 
                        id="genre" 
                        name="genre"
                        value="<?= htmlspecialchars($music['genre'] ?? '') ?>"
                    >
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input 
                        type="number" 
                        id="year" 
                        name="year" 
                        min="1900" 
                        max="2099"
                        value="<?= htmlspecialchars($music['year'] ?? '') ?>"
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label for="rating">Rating (1-5)</label>
                <select id="rating" name="rating">
                    <option value="">Select...</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>" <?= isset($music['rating']) && $music['rating'] == $i ? 'selected' : '' ?>>
                            <?= $i ?> <?= str_repeat('★', $i) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" rows="4"><?= htmlspecialchars($music['notes'] ?? '') ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?= BASE_PATH ?>/dashboard" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </main>
</body>
</html>