<?php
require __DIR__ . '/../services/UserService.class.php';

use \RedBeanPHP\R as R;


class Todo
{
    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index()
    {
        if ($this->userService->validateLoggedIn()) {
            $getId = R::getAll("SELECT id FROM user WHERE username= :user", [':user' => $_SESSION['username']]);
            $activeTodo = R::getAll("SELECT * FROM todo WHERE user_id= :id and finished = 0 ORDER BY struc ASC", [':id' => (int)$getId[0]["id"]]);
            $convertedATodo = R::convertToBeans("aTodo", $activeTodo);

            $finishedTodo = R::getAll("SELECT * FROM todo WHERE user_id= :id AND finished = 1", ['id' => (int)$getId[0]["id"]]);
            $convertedFTodo = R::convertToBeans("fTodo", $finishedTodo);


            loadTemp(__DIR__ . "/../views", "todoIndex.html", ['aTodo' => $convertedATodo, 'fTodo' => $convertedFTodo, 'loggedIn' => true]);
        } else {
            header('Location: /user/login');
        }
    }

    public function addPOST()
    {
        header('Acces-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf8');

        $resp = false;

        $output[] = json_decode(file_get_contents('php://input'), true);

        if ($this->userService->validateLoggedIn()) {

            if (!empty($output[0]['title'])) {
                $title = $output[0]['title'];

                $struc = R::getAll("SELECT max(struc) FROM todo");
                $todo = R::dispense("todo");
                $todo->title = $title;
                $todo->user_id = $_SESSION['id'];
                $todo->finished = false;
                $todo->struc = $struc[0]['max(struc)'] + 1;
                R::store($todo);
            }

        } else {
            header('Location: /user/login');
        }

        echo json_encode($resp);


    }

    public function updatePOST()
    {
        header('Acces-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf8');

        $resp = false;

        $output[] = json_decode(file_get_contents('php://input'), true);

        if ($this->userService->validateLoggedIn()) {

            $id = $output[0]['id'];
            $title = $output[0]['title'];

            $uCheck = R::getAll("SELECT * FROM todo where id = :id", [':id' => $id]);

            if ($uCheck[0]["user_id"] == $_SESSION['id']) {
                R::exec("UPDATE todo SET title = :title WHERE id = :id", [':title' => $title, ':id' => $id]);
                $resp = true;
            }
        } else {
            header('Location: /user/login');
        }

        echo json_encode($resp);
    }

    public function finishPOST()
    {
        header('Acces-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf8');

        $resp = false;

        $output[] = json_decode(file_get_contents('php://input'), true);

        if ($this->userService->validateLoggedIn()) {

            $id = $output[0]['id'];

            $uCheck = R::getAll("SELECT user_id from todo where id = :id", [':id' => $id]);

            if ($uCheck[0]["user_id"] == $_SESSION['id']) {

                R::exec("UPDATE todo SET finished = 1 WHERE id = :id", [':id' => $id]);
                $resp = true;

            }

        } else {
            header('Location: /user/login');
        }

        echo json_encode($resp);

    }

    public function deletePOST()
    {
        header('Acces-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf8');

        $resp = false;

        $output[] = json_decode(file_get_contents('php://input'), true);

        if ($this->userService->validateLoggedIn()) {

            $id = $output[0]['id'];

            $uCheck = R::getAll("SELECT user_id from todo where id = :id", [':id' => $id]);

            if ($uCheck[0]["user_id"] == $_SESSION['id']) {

                R::exec("DELETE FROM todo WHERE id = :id", [':id' => $id]);
                $resp = true;

            }

        } else {
            header('Location: /user/login');
        }

        echo json_encode($resp);

    }

    public function orderPOST()
    {
        header('Acces-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf8');

        $resp = false;

        $output[] = json_decode(file_get_contents('php://input'), true);

        if ($this->userService->validateLoggedIn()) {

            $id = $output[0]['id'];
            $struc = $output[0]['struc'];

            $uCheck = R::getAll("SELECT * from todo where id = :id", [':id' => $id]);

            if ($uCheck[0]["user_id"] == $_SESSION['id']) {
                $strucAll = R::getAll("SELECT struc FROM todo");

                $newStruc = R::getAll("SELECT MAX(struc) as struc FROM todo WHERE struc < :struc AND user_id = :uId LIMIT 1", [':struc' => $struc, ':uId' => $_SESSION['id']]);
                $newStrucId = R::getAll('SELECT id FROM todo WHERE struc = :struc and user_id = :uId', [':struc' => $newStruc[0]['struc'], ':uId' => $_SESSION['id']]);

                R::exec('UPDATE todo SET struc = :newStruc WHERE id = :id; UPDATE todo SET struc = struc + 1  WHERE struc >= :newStruc AND id != :id ;', [':newStruc' => $newStruc[0]['struc'], ':id' => $id]);
                $resp = true;

            }

        } else {
            header('Location: /user/login');
        }

        echo json_encode($resp);

    }

}