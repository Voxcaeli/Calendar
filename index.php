<?php session_start();

// Параметры сессии:
// $_SESSION['displayed_date'] - отображаемая дата (актуально: месяц, год)
// $_SESSION['current_date'] - текущая дата (актуально: месяц, год)
// $_SESSION['today'] - текущий день (актуально: день)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <title>Календарь</title>
</head>
<body>
    <form action="index.php" method="post">
        <section class="calendar">
            <h1>Календарь</h1>
            <?php
                require_once("php/Calendar.php");

                // Сохранение в сессии отображаемой даты, текущей даты и числа текущего дня
                if (!isset($_SESSION['displayed_date']) or
                    !isset($_SESSION['current_date']) or
                    !isset($_SESSION['today'])) {

                    [$_SESSION['displayed_date'],
                     $_SESSION['current_date'],
                     $_SESSION['today']] = Helper::GetToday();
                }

                // Расшифровка значения скрытого поля формы указанием месяца отображаемой даты
                if (isset($_POST['hidden'])) {
                    if ($_POST['hidden'] == 'prev_year') {
                        $_SESSION['displayed_date'] = Helper::GetPrevYear($_SESSION['displayed_date']);

                    } elseif ($_POST['hidden'] == 'prev_month') {
                        $_SESSION['displayed_date'] = Helper::GetPrevMonth($_SESSION['displayed_date']);

                    } elseif ($_POST['hidden'] == 'next_month') {
                        $_SESSION['displayed_date'] = Helper::GetNextMonth($_SESSION['displayed_date']);

                    } elseif ($_POST['hidden'] == 'next_year') {
                        $_SESSION['displayed_date'] = Helper::GetNextYear($_SESSION['displayed_date']);

                    } elseif ($_POST['hidden'] == 'current') {
                        $_SESSION['displayed_date'] = Helper::GetCurrentMonth();
                    }

                    $_POST['hidden'] = '';
                }

                // Определение соответствия отображаемого месяца текущему
                $is_current_month = Helper::IsEqualDate($_SESSION['displayed_date'], $_SESSION['current_date']);

                // Отображение календаря
                echo (new Calendar())->Build($_SESSION['displayed_date'],
                                             $_SESSION['today'],
                                             $is_current_month);
                ?>
        </section>
    </form>
</body>
</html>