<?php
// КЛАСС НАСТРОЙКИ КАЛЕНДАРЯ
class Tool {
    // Отображение заголовка таблицы календаря
    private static $is_show_caption = true;

    // Отображение номера недели
    private static $is_show_week_number = true;

    // Отображение строки с названиями дней недели
    private static $is_show_weekday_name = true;

    // Отображение начала недели с "воскресенья"
    private static $is_start_week_from_sunday = false;

    // Отображение чисел предыдущего и следующего месяцев
    private static $is_show_other_months_days = true;

    // Список праздников в году в формате: [номер месяца[праздничные дни месяца]], где:
    // [day] - выходные и/или праздничные дни
    // -[day] - рабочие выходные дни (рабочие Сб или Вс)
    private static $default_holidays = [1 => [1,2,3,4,5,6,7,8],
                                        2 => [23],
                                        3 => [8],
                                        4 => [],
                                        5 => [1,9],
                                        6 => [12],
                                        7 => [],
                                        8 => [],
                                        9 => [],
                                        10 => [],
                                        11 => [4],
                                        12 => []];

    private static $holidays = [2025 => [1 => [1,2,3,4,5,6,7,8],
                                         2 => [23],
                                         3 => [8],
                                         4 => [],
                                         5 => [1,2,8,9],
                                         6 => [12,13],
                                         7 => [],
                                         8 => [],
                                         9 => [],
                                         10 => [],
                                         11 => [-1,3,4],
                                         12 => [31]],

                                2026 => [1 => [1,2,3,4,5,6,7,8],
                                         2 => [23],
                                         3 => [8,9],
                                         4 => [],
                                         5 => [1,4,5,9,11],
                                         6 => [12],
                                         7 => [],
                                         8 => [],
                                         9 => [],
                                         10 => [],
                                         11 => [4],
                                         12 => []]];

    // Получение данных о праздниках в году (по умолчанию)
    public static function GetDefaultHolidays() {
        return self::$default_holidays;
    }

    // Получение (зафиксированных) данных о праздниках в году
    public static function GetHolidays() {
        return self::$holidays;
    }

    // Отображение заголовка таблицы календаря
    public static function IsShowCaption() {
        return self::$is_show_caption;
    }

    // Отображение номера недели
    public static function IsShowWeekNumber() {
        return self::$is_show_week_number;
    }

    // Отображение строки с названиями дней недели
    public static function IsShowWeekdayName() {
        return self::$is_show_weekday_name;
    }

    // Отображение начала недели с "воскресенья"
    public static function IsStartWeekFromSunday() {
        return self::$is_start_week_from_sunday;
    }

    // Отображение чисел предыдущего и следующего месяцев
    public static function IsShowOtherMonthsDays() {
        return self::$is_show_other_months_days;
    }
}