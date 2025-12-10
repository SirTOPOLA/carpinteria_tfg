<?php
// system/core/View.php
class View {
    public static function render(string $view, array $data = []) {
        extract($data);
        require_once "../../app/views/{$view}.php";
    }
}
