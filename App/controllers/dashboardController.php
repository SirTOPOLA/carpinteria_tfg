<?php

class DashboardController extends Controller {

    public function index() {
        AuthMiddleware::check();

        $this->view("dashboard/dashboard", [
            "usuario" => $_SESSION['usuario']
        ]);
    }
}
