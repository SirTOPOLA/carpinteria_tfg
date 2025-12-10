<?php
// system/core/Controller.php
class Controller
{
    public function view(string $view, array $data = [])
    {
        extract($data);
        require_once "../../app/views/{$view}.php";
    }


    public function redirect(string $url)
    {
        header("Location: {$url}");
        exit;
    }


    public function json(array $data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}