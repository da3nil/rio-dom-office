<?php

$msg = [];
$msg['success'] = 0;

if (!empty($_POST)) {
    $err = [];

    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $data = $_POST['data'];
    //$city = $_POST['city'];

    if (empty($name)) {
        $err[] = "Поле 'имя' должно быть заполнено";
    }

    if (empty($phone)) {
        $err[] = "Поле 'номер телефона' должно быть заполнено";
    }

    if (empty($data)) {
        $err[] = "Нет данных для расчета";
    }

    if (empty($data["type"]))           { $data["type"] = "Неизвестно";          }
    if (empty($data["repairs"]))        { $data["repairs"] = "Неизвестно";       }
    if (empty($data["view"]))           { $data["view"] = "Неизвестно";          }
    if (empty($data["redevelopment"]))  { $data["redevelopment"] = "Неизвестно"; }
    if (empty($data["rooms"]))          { $data["rooms"] = "Неизвестно";         }
    if (empty($data["area"]))           { $data["area"] = "Неизвестно";          }
    if (empty($data["value"]))          { $data["value"] = "Неизвестно";         }
    if (empty($data["city"]))           { $data["city"] = "Неизвестно";          }

//
    if (count($err) > 0) {
        $msg['msg'] = 'Ошибка отправки формы: ';
        $msg['errors'] = $err;
        echo json_encode($msg);
        die();
    }

    htmlspecialchars_decode($name);
    htmlspecialchars_decode($phone);
    htmlspecialchars_decode($data["type"]);
    htmlspecialchars_decode($data["repairs"]);
    htmlspecialchars_decode($data["view"]);
    htmlspecialchars_decode($data["redevelopment"]);
    htmlspecialchars_decode($data["rooms"]);
    htmlspecialchars_decode($data["area"]);
    htmlspecialchars_decode($data["value"]);
    htmlspecialchars_decode($data["city"]);

    $message = "
        <h1>Заявка на расчет по СМС от rio-dom.ru/office</h1>
        <hr>
        <p>
            <b>Город:</b>
            <span>" . $data["city"] ."</span>
        </p>
        <p>
            <b>Номер телефона:</b>
            <span>$phone</span>
        </p>
        <p>
            <b>Тип помещения: </b>
            <span>" . $data['type'] . "</span>
        </p>
        <p>
            <b>Вид ремонта: </b>
            <span>" . $data['repairs'] . "</span>
        </p>
        <p>
            <b>Перепланировка: </b>
            <span>" . $data['redevelopment'] . "</span>
        </p>
        <p>
            <b>Выравнивание стен: </b>
            <span>" . $data['redevelopment'] . "</span>
        </p>
        <p>
            <b>Комнаты: </b>
            <span>" . $data['rooms'] . "</span>
        </p>
        <p>
            <b>Площадь: </b>
            <span>" . $data['area'] . "м<sup>2</sup></span>
        </p>
        <p>
            <b>Итого: </b>
            <span>" . $data['value'] . " рублей</span>
        </p>
    ";

    $result = mail(
        'd.prytckov@yandex.ru, riodom.info@gmail.com',
        'Получен расчет', $message,
        "From: support@rio-dom.ru\r\n"
        . "Content-type: text/html; charset=utf-8\r\n"
        . "X-Mailer: PHP mail script"
    );

    if ($result) {
        $msg['msg'] = 'Ваша заявка принята. Ожидайте звонка.';
        $msg['success'] = 1;
        echo json_encode($msg);
    } else {
        $msg['msg'] = 'Ошибка отправки письма. Попробуйте позже.';
        echo json_encode($msg);
    }
} else {
    $msg['msg'] = 'Ошибка отправки письма. Данные не получены.';
    echo json_encode($msg);
}
