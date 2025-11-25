<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - The Collector</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/style.css">
</head>
<body>
    <nav>
        <div class="nav-container">
            <span class="brand">The Collector</span>
            <div class="user-info">
                <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="<?= BASE_PATH ?>/logout" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="header-actions">
            <h1>My Collection</h1>
            <a href="<?= BASE_PATH ?>/music/add" class="btn btn-primary">Add Music</a>
        </div>

        <form method="GET" action="<?= BASE_PATH ?>/dashboard" class="search-form">
            <input 
                type="text" 
                name="search" 
                placeholder="Search by title, artist, or album..."
                value="<?= htmlspecialchars($search ?? '') ?>"
            >
            <button type="submit" class="btn">Search</button>
            <?php if ($search): ?>
                <a href="<?= BASE_PATH ?>/dashboard" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>

        <div class="music-grid">
            <?php if (empty($musicList)): ?>
                <div class="no-data">
                    <p>No music found in your collection.</p>
                    <?php if ($search): ?>
                        <p>Try a different search term or <a href="<?= BASE_PATH ?>/dashboard">view all</a>.</p>
                    <?php else: ?>
                        <p><a href="<?= BASE_PATH ?>/music/add">Add your first entry</a>!</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($musicList as $music): ?>
                    <div class="music-card">
                        <h3><?= htmlspecialchars($music['title']) ?></h3>
                        <p class="artist"><?= htmlspecialchars($music['artist']) ?></p>
                        <p class="details">
                            <?= htmlspecialchars($music['album'] ?: '-') ?> •
                            <?= htmlspecialchars($music['year'] ?: '-') ?> •
                            <?= htmlspecialchars($music['genre'] ?: '-') ?>
                        </p>
                        <?php if ($music['rating']): ?>
                            <div class="rating">
                                Rating: <?= str_repeat('★', $music['rating']) ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($music['notes']): ?>
                            <p class="notes"><?= htmlspecialchars($music['notes']) ?></p>
                        <?php endif; ?>
                        <div class="actions">
                            <a href="<?= BASE_PATH ?>/music/edit/<?= $music['id'] ?>" class="btn btn-sm">Edit</a>
                            <form method="POST" action="<?= BASE_PATH ?>/music/delete/<?= $music['id'] ?>" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <script src="<?= BASE_PATH ?>/assets/script.js"></script>
</body>
</html>