<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$msg = [];
$msg['success'] = 0;

if (!empty($_POST)) {
    $err = [];

    $name = $_POST['name'];

    if (empty($name)) {
        $err[] = "Поле 'имя' должно быть заполнено";
    }

    htmlspecialchars_decode($name);

    $name = ucfirst($name);

    //SELECT * FROM `net_city` WHERE `country_id` = 20 AND name_ru LIKE lower("М%") ORDER BY `net_city`.`name_ru` ASC LIMIT 6

    $link = mysqli_connect('localhost', 'admin', 'password', 'riodom2');

    if (!$link) {
        die("Ошибка соединения");
    }
    mysqli_query($link, 'SET NAMES "utf8"');
    $query = "SELECT name FROM city where name LIKE lower('" . $name . "%') LIMIT 6";
//    $query = "SELECT * FROM net_city WHERE country_id = 20 AND name_ru LIKE lower('" . $name . "%') ORDER BY name_ru ASC LIMIT 6";

//    var_dump($query);

    $result = [];

    $sql = mysqli_query($link, $query);

    //var_dump($sql);

    if ($sql !== false) {
        while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
            $result[] = $row['name'];
        }
    }

    mysqli_close($link);

//    var_dump($sql);
//    var_dump($name);

    header('content-type: text/plain; charset=utf-8');
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode([]);
    }
} else {
    $msg['msg'] = 'Ошибка отправки формы.';
    echo json_encode($msg);
}
