<?php

namespace App\Modules\Music\Controllers;

use App\Core\Controller;
use App\Modules\Music\Repositories\MusicRepository;

class MusicController extends Controller
{
    private $musicRepo;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->musicRepo = new MusicRepository();
    }

    public function index()
    {
        $search = $_GET['search'] ?? null;
        $musicList = $this->musicRepo->getAll($_SESSION['user_id'], $search);
        $this->view('Music/Views/dashboard', ['musicList' => $musicList, 'search' => $search]);
    }

    public function create()
    {
        $this->view('Music/Views/add');
    }

    public function store()
    {
        $data = [
            'title' => trim($_POST['title']),
            'artist' => trim($_POST['artist']),
            'album' => trim($_POST['album']),
            'genre' => trim($_POST['genre']),
            'year' => (int) $_POST['year'],
            'rating' => (int) $_POST['rating'],
            'notes' => trim($_POST['notes'])
        ];

        if (empty($data['title']) || empty($data['artist'])) {
            $this->view('Music/Views/add', ['error' => 'Title and Artist are required.']);
            return;
        }

        if ($this->musicRepo->create($_SESSION['user_id'], $data)) {
            $this->redirect('/dashboard');
        } else {
            $this->view('Music/Views/add', ['error' => 'Failed to add music.']);
        }
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $music = $this->musicRepo->getById($id, $_SESSION['user_id']);

        if (!$music) {
            $this->redirect('/dashboard');
        }

        $this->view('Music/Views/edit', ['music' => $music]);
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;
        $data = [
            'title' => trim($_POST['title']),
            'artist' => trim($_POST['artist']),
            'album' => trim($_POST['album']),
            'genre' => trim($_POST['genre']),
            'year' => (int) $_POST['year'],
            'rating' => (int) $_POST['rating'],
            'notes' => trim($_POST['notes'])
        ];

        if (empty($data['title']) || empty($data['artist'])) {
            // In a real app we'd pass data back to repopulate form
            $this->redirect("/music/edit?id=$id&error=required");
            return;
        }

        if ($this->musicRepo->update($id, $_SESSION['user_id'], $data)) {
            $this->redirect('/dashboard');
        } else {
            $this->redirect("/music/edit?id=$id&error=failed");
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->musicRepo->delete($id, $_SESSION['user_id']);
        }
        $this->redirect('/dashboard');
    }
}
