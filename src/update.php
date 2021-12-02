<?php

include __DIR__ . "/index.php";

USE \RedBeanPHP\R as R;

$id = $_POST['id'];
$title = $_POST['title'];

$getAllTodos = R::getAll("SELECT * FROM todo WHERE title = :title", [':title' => $title]);

if (empty($getAllTodos)) {
    R::exec("UPDATE todo SET title = :title WHERE id = :id", [':title' => $_POST['title'], ':id' => $id]);
}