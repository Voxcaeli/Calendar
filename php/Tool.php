<?php

// Класс настройки календаря
class Tool {
    // Отображение заголовка таблицы календаря
    private static $show_caption = true;

    // Отображение номера недели
    private static $show_week_number = true;

    // Отображение строки с названиями дней недели
    private static $show_weekday_name = true;

    // Список праздников в году в формате: [номер месяца[праздничные дни месяца]], где:
    // day - выходные и/или праздничные дни
    // -day - рабочие выходные дни
    private static $holidays = [1 => [1,2,3,4,5,6,7,8],
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
                                12 => [31]];
    // 2026
    // private static $holidays = [1 => [1,2,3,4,5,6,7,8],
    //                             2 => [23],
    //                             3 => [8,9],
    //                             4 => [],
    //                             5 => [1,4,5,9,11],
    //                             6 => [12],
    //                             7 => [],
    //                             8 => [],
    //                             9 => [],
    //                             10 => [],
    //                             11 => [4],
    //                             12 => []];

    public static function GetShowCaption() {
        return self::$show_caption;
    }

    public static function GetShowWeekNumber() {
        return self::$show_week_number;
    }

    public static function GetShowWeekdayName() {
        return self::$show_weekday_name;
    }

    public static function GetHolidays() {
        return self::$holidays;
    }
}