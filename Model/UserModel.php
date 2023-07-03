<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

// Creacion de la clase y se hereda de Database
class UserModel extends Database
{
    // Metodo que valida usario y cclave asociada
	public function getSession($user,$password)
    {
        return $this->select("SELECT * FROM usuario_sistema WHERE user = ? AND password = ?", ["ss", $user, $password]);
    }
}