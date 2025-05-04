<?php

// Класс "Помощника" программы
class Helper {
    // Получение объекта даты текущего месяца и номера текущего дня месяца
    public static function GetToday() {
        $date = new DateTime();

        $today = self::GetDayOfMonth($date);
        $month = self::GetMonthNumber($date);
        $year = self::GetYear($date);

        $date->setDate($year, $month, 1);

        return [$date, $today];
    }

    // Копирование даты
    public static function CopyDate($date) {
        $year = self::GetYear($date);
        $month = self::GetMonthNumber($date);

        $new_date = new DateTime();
        $new_date->setDate($year, $month, 1);

        return $new_date;
    }

    // Проверка на равенство дат (года и месяца)
    public static function IsEqualDate($date1, $date2) {
        $is_equal_year = self::GetYear($date1) === self::GetYear($date2);
        $is_equal_month = self::GetMonthNumber($date1) === self::GetMonthNumber($date2);
        return $is_equal_year and $is_equal_month;
    }

    // Получение объекта даты какого-либо месяца
    private static function SetMonth($date, $other_type) {
        $new_date = new DateTime();

        $day = self::GetDayOfMonth($date);
        $month = self::GetMonthNumber($date);
        $year = self::GetYear($date);

        if ($other_type === 'prev') {
            $new_date->setDate($year, $month - 1, $day);
        } elseif ($other_type === 'next') {
            $new_date->setDate($year, $month + 1, $day);
        } elseif ($other_type === 'current') {
            [$new_date, $_] = self::GetToday();
        }
        return $new_date;
    }

    // Получение объекта даты предыдущего месяца
    public static function GetPrevMonth($date) {
        return self::SetMonth($date, 'prev');
    }

    // Получение объекта даты следующего месяца
    public static function GetNextMonth($date) {
        return self::SetMonth($date, 'next');
    }

    // Получение объекта даты текущего месяца
    public static function GetCurrentMonth($date) {
        return self::SetMonth($date, 'current');
   }

    // Получение года
    public static function GetYear($date) {
        $year = $date->format('Y');
        return intval($year);
    }

    // Получение номера месяца в году (с 1 до 12)
    public static function GetMonthNumber($date) {
        $month_number = $date->format('m');
        return intval($month_number);
    }

    // Получение названия месяца
    public static function GetMonthName($date) {
        $month_names = explode(',', 'Январь,Февраль,Март,Апрель,Май,Июнь,Июль,Август,Сентябрь,Октябрь,Ноябрь,Декабрь');
        $month_index = self::GetMonthNumber($date) - 1;
        return $month_names[$month_index];
    }

    // Получение количества дней в месяце (с 28 до 31)
    public static function GetDaysCount($date) {
        $days_count = $date->format('t');
        return intval($days_count);
    }

    // Получение номера недели в году
    public static function GetWeekNumber($date) {
        $week_number = $date->format('W');
        return intval($week_number);
    }

    // Получение номера дня недели
    public static function GetWeekDay($date) {
        $weekday = $date->format('w');
        return intval($weekday);
    }

    // Получение дня месяца (с 1 до 31)
    public static function GetDayOfMonth($date) {
        $month_day = $date->format('j');
        return intval($month_day);
    }

    // Определение отношения указанного дня к праздникам
    public static function IsHoliday($month, $day) {
        return in_array($day, Tool::GetHolidays()[$month]);
    }
}