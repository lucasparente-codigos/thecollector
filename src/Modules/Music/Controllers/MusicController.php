<?php

namespace App\Modules\Music\Controllers;

use App\Core\Controller;
use App\Modules\Music\Repositories\MusicRepository;

class MusicController extends Controller
{
    private $musicRepo;

    public function __construct()
    {
        $this->requireAuth();
        $this->musicRepo = new MusicRepository();
    }

    /**
     * Show dashboard with music collection
     */
    public function index()
    {
        $search = $this->get('search');
        $musicList = $this->musicRepo->getAll($_SESSION['user_id'], $search);
        
        $this->view('Music/Views/dashboard', [
            'musicList' => $musicList,
            'search' => $search
        ]);
    }

    /**
     * Show add music form
     */
    public function create()
    {
        $this->view('Music/Views/add');
    }

    /**
     * Store new music
     */
    public function store()
    {
        $this->validateCsrfToken();

        $data = [
            'title' => trim($this->post('title', '')),
            'artist' => trim($this->post('artist', '')),
            'album' => trim($this->post('album', '')),
            'genre' => trim($this->post('genre', '')),
            'year' => (int) $this->post('year', 0),
            'rating' => $this->validateRating($this->post('rating', 0)),
            'notes' => trim($this->post('notes', ''))
        ];

        // Validate required fields
        if (empty($data['title']) || empty($data['artist'])) {
            $this->view('Music/Views/add', [
                'error' => 'Title and Artist are required.',
                'music' => $data
            ]);
            return;
        }

        // Create music entry
        if ($this->musicRepo->create($_SESSION['user_id'], $data)) {
            $this->redirect('/dashboard');
        } else {
            $this->view('Music/Views/add', [
                'error' => 'Failed to add music.',
                'music' => $data
            ]);
        }
    }

    /**
     * Show edit music form
     */
    public function edit($id)
    {
        $music = $this->musicRepo->getById($id, $_SESSION['user_id']);

        if (!$music) {
            $this->redirect('/dashboard');
        }

        $this->view('Music/Views/edit', ['music' => $music]);
    }

    /**
     * Update music entry
     */
    public function update($id)
    {
        $this->validateCsrfToken();

        $data = [
            'title' => trim($this->post('title', '')),
            'artist' => trim($this->post('artist', '')),
            'album' => trim($this->post('album', '')),
            'genre' => trim($this->post('genre', '')),
            'year' => (int) $this->post('year', 0),
            'rating' => $this->validateRating($this->post('rating', 0)),
            'notes' => trim($this->post('notes', ''))
        ];

        // Validate required fields
        if (empty($data['title']) || empty($data['artist'])) {
            $music = $this->musicRepo->getById($id, $_SESSION['user_id']);
            $this->view('Music/Views/edit', [
                'error' => 'Title and Artist are required.',
                'music' => array_merge($music, $data)
            ]);
            return;
        }

        // Update music entry
        if ($this->musicRepo->update($id, $_SESSION['user_id'], $data)) {
            $this->redirect('/dashboard');
        } else {
            $music = $this->musicRepo->getById($id, $_SESSION['user_id']);
            $this->view('Music/Views/edit', [
                'error' => 'Failed to update music.',
                'music' => array_merge($music, $data)
            ]);
        }
    }

    /**
     * Delete music entry
     */
    public function delete($id)
    {
        $this->validateCsrfToken();

        if ($id) {
            $this->musicRepo->delete($id, $_SESSION['user_id']);
        }
        
        $this->redirect('/dashboard');
    }

    /**
     * Validate rating value
     * 
     * @param mixed $rating
     * @return int|null
     */
    private function validateRating($rating)
    {
        $rating = (int) $rating;
        
        if ($rating < 1 || $rating > 5) {
            return null;
        }
        
        return $rating;
    }
}