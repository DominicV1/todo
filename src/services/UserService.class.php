<?php

use \RedBeanPHP\R as R;

class UserService
{

    public function validateLoggedIn(): bool
    {
        if (isset($_SESSION['token'])) {
            $findToken = R::getAll("SELECT * FROM session WHERE token = :token", [':token' => $_SESSION['token']]);
            if (isset($findToken)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}