<?php
require_once 'auth_check.php';
require_once 'src/MusicRepo.php';

$musicRepo = new MusicRepo();
$search = $_GET['search'] ?? null;
$musicList = $musicRepo->getAll($_SESSION['user_id'], $search);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - The Collector</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <nav>
        <div class="nav-container">
            <span class="brand">The Collector</span>
            <div class="user-info">
                <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <main class="container">
        <div class="header-actions">
            <h1>My Collection</h1>
            <a href="add.php" class="btn btn-primary">Add Music</a>
        </div>

        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search by title, artist, or album..."
                value="<?= htmlspecialchars($search ?? '') ?>">
            <button type="submit" class="btn">Search</button>
            <?php if ($search): ?>
                <a href="dashboard.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>

        <div class="music-grid">
            <?php if (empty($musicList)): ?>
                <p class="no-data">No music found in your collection.</p>
            <?php else: ?>
                <?php foreach ($musicList as $music): ?>
                    <div class="music-card">
                        <h3><?= htmlspecialchars($music['title']) ?></h3>
                        <p class="artist"><?= htmlspecialchars($music['artist']) ?></p>
                        <p class="details">
                            <?= htmlspecialchars($music['album'] ?? '-') ?> •
                            <?= htmlspecialchars($music['year'] ?? '-') ?> •
                            <?= htmlspecialchars($music['genre'] ?? '-') ?>
                        </p>
                        <div class="rating">
                            Rating: <?= $music['rating'] ? str_repeat('★', $music['rating']) : 'N/A' ?>
                        </div>
                        <?php if ($music['notes']): ?>
                            <p class="notes"><?= htmlspecialchars($music['notes']) ?></p>
                        <?php endif; ?>
                        <div class="actions">
                            <a href="edit.php?id=<?= $music['id'] ?>" class="btn btn-sm">Edit</a>
                            <a href="delete.php?id=<?= $music['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirmDelete()">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <script src="assets/script.js"></script>
</body>

</html>