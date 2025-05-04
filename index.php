<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Календарь</title>
</head>
<body onload="SetHiddenValue('current');">
    <form action="index.php" method="post">
        <section class="calendar">
            <h1>Календарь</h1>
            <?php
                require_once("php/Calendar.php");

                // Сохранение в сессии текущей даты, её копии и номера текущего дня
                if (!isset($_SESSION['date']) or !isset($_SESSION['today']) or !isset($_SESSION['current_date'])) {
                    [$_SESSION['date'], $_SESSION['today']] = Helper::GetToday();
                    $_SESSION['current_date'] = Helper::CopyDate($_SESSION['date']);
                }

                // Заполнение скрытого поля формы указанием месяца отображаемой даты
                if (isset($_POST['hidden'])) {
                    if ($_POST['hidden'] === 'previous') {
                        $_SESSION['date'] = Helper::GetPrevMonth($_SESSION['date']);
                    } elseif ($_POST['hidden'] === 'next') {
                        $_SESSION['date'] = Helper::GetNextMonth($_SESSION['date']);
                    } elseif ($_POST['hidden'] === 'current') {
                        $_SESSION['date'] = Helper::GetCurrentMonth($_SESSION['date']);
                    }
                }

                // Определение соответствия отображаемого месяца текущему
                $is_current_date = Helper::IsEqualDate($_SESSION['date'], $_SESSION['current_date']);

                // Отображение календаря
                echo (new Calendar())->Build($_SESSION['date'],
                                             $_SESSION['today'],
                                             $is_current_date);
                ?>
        </section>
    </form>
    
    <script src="js/script.js"></script>
</body>
</html>