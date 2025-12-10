
<?php
// app/controllers/AuthController.php
require_once '../../system/core/Controller.php';
require_once '../app/models/Usuario.php';

class AuthController extends Controller {
    private Usuario $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showLogin() {
        if (isset($_SESSION['usuario'])) {
            $this->redirect('');
        }
        $this->view('auth/login');
    }

    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->usuarioModel->findByUsername($username);
        if ($user && password_verify($password, $user['password_hash'])) {
            if ($user['activo'] != 1) {
                $this->view('auth/login', ['error' => 'Usuario inactivo']);
                return;
            }
            $_SESSION['usuario'] = [
                'id_usuario' => $user['id_usuario'],
                'username' => $user['username'],
                'id_rol' => $user['id_rol']
            ];

            $this->usuarioModel->updateLastLogin($user['id_usuario']);
            $this->redirect('');
        } else {
            $this->view('auth/login', ['error' => 'Credenciales incorrectas']);
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('login');
    }
}


?>