<?php
session_start();
require '../vendor/autoload.php';

use \RedBeanPHP\R as R;

R::setup('mysql:host=localhost;dbname=my_todo',
    'root', '');

$cM = array();
if (isset($_SERVER['REQUEST_URI'])) {
    $path = $_SERVER['REQUEST_URI'];

    $cM = explode("/", $path);
} else {
    $path = '/';
}

if (file_exists("./controllers/" . ucfirst($cM[1]) . "Controller.class.php")) {
    require("./controllers/" . ucfirst($cM[1]) . "Controller.class.php");

    $controller = new $cM[1]();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (method_exists($controller, $cM[2] . "POST")) {
            $controller->{$cM[2] . "POST"}();
        }
    } else {
        if (empty($cM[2])) {
            if (method_exists($controller, "index")) {
                $controller->index();
            }
        } else if (is_numeric($cM[2])) {
            if (method_exists($controller, "single")) {
                $controller->single($cM[2]);
            }
        } else if (method_exists($controller, $cM[2])) {
            $controller->{$cM[2]}();
        } else if (array_key_exists(3, $cM) && is_numeric($cM[3])) {
            $controller->single($cM[3]);
        } else {
            echo "Page not found, it doesn't exist. Check your spelling :)";
            header("HTTP/1.0 404 Not Found");
        }
    }
} else {
    if (empty($cM[1])) {
        require("./controllers/TodoController.class.php");
        $controller = new Todo();
        $controller->index();
    } else {
        echo "Page not found, it doesn't exist. Check your spelling :)";
        header("HTTP/1.0 404 Not Found");
    }
}
function loadTemp($vPath, $vFile, $var = [])
{
    $loader = new \Twig\Loader\FilesystemLoader($vPath);
    $twig = new \Twig\Environment($loader, [
        'debug' => true,
    ]);
    $twig->addExtension(new \Twig\Extension\DebugExtension());
    $template = $twig->load($vFile);
    echo $template->render($var);
}