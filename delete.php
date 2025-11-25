<?php
require_once 'auth_check.php';
require_once 'src/MusicRepo.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $musicRepo = new MusicRepo();
    $musicRepo->delete($id, $_SESSION['user_id']);
}

header('Location: dashboard.php');
exit;
