<?php
require __DIR__ . '/../services/UserService.class.php';

use \RedBeanPHP\R as R;

class User
{
    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function signup()
    {
        loadTemp(__DIR__ . "/../views", "signupIndex.html", ['loggedIn' => false]);
    }

    public function signupPOST()
    {
        $findUser = R::getAll("SELECT * FROM user WHERE username = :username", [':username' => $_POST['user_n']]);

        if (empty($_POST['user_n']) || empty($_POST['pass_w'])) {
            loadTemp(__DIR__ . "/../views", "signupIndex.html", ['invalidSignup' => true]);

        } else {
            if (empty($findUser)) {
                R::exec("INSERT INTO user(username, password) VALUES (:username, :pwd)", [':username' => $_POST['user_n'], ':pwd' => password_hash($_POST['pass_w'], PASSWORD_DEFAULT)]);
                header('Location: /user/login');
            } else {
                loadTemp(__DIR__ . "/../views", "signupIndex.html", ['uNameEx' => true]);
            }
        }
    }

    public function login()
    {
        if (isset($_SESSION['token'])) {
            header('Location: /todo/index');
        }
        loadTemp(__DIR__ . "/../views", "userIndex.html", ['loggedIn' => false]);
    }

    public function loginPOST()
    {
        $match = false;
        $users = R::getAll("SELECT * FROM user WHERE username= :user", [':user' => $_POST['user_n']]);
        foreach ($users as $value)
            if ($_POST['user_n'] == $value['username']) {
                $match = true;
                if (password_verify($_POST['pass_w'], $value['password'])) {
                    $username = $value['username'];
                    $_SESSION['username'] = $username;
                    $token = openssl_random_pseudo_bytes(50);
                    $token = bin2hex($token);

                    $temp = R::dispense("session");
                    $temp->username = $username;
                    $temp->token = $token;
                    $id = R::store($temp);

                    $_SESSION['token'] = $token;
                    $_SESSION['id'] = $users[0]["id"];
                    header('Location: /');

                } else {
                    loadTemp(__DIR__ . "/../views", "userIndex.html", ['invalidSignup' => true]);
                }
            } else {
                loadTemp(__DIR__ . "/../views", "userIndex.html", ['invalidSignup' => true]);
            }

        if (!$match) {
            loadTemp(__DIR__ . "/../views", "userIndex.html", ['invalidSignup' => true]);
        }
    }

    public function logout()
    {
        R::exec("DELETE FROM session WHERE token = :token", [':token' => $_SESSION['token']]);
        session_unset();
        session_destroy();
        header('Location: ../');
    }
}