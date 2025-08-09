<?php

// КЛАСС "ПОМОЩНИКА" ПРОГРАММЫ
class Helper {
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
        // список названий месяцев
        $ru_month_names = explode(',', 'Январь,Февраль,Март,Апрель,Май,Июнь,Июль,Август,Сентябрь,Октябрь,Ноябрь,Декабрь');
        $en_month_names = explode(',', 'January,February,March,April,May,June,July,August,September,October,November,December');

        // определение варианта списка названий месяцев
        $month_names = Tool::IsEnLang() ? $en_month_names : $ru_month_names;

        $index = self::GetMonthNumber($date) - 1;
        return $month_names[$index];
    }

    // Получение количества дней в месяце (с 28 до 31)
    public static function GetDaysCount($date) {
        $days_count = $date->format('t');
        return intval($days_count);
    }

    // Получение дня месяца (с 1 до 31)
    public static function GetDayOfMonth($date) {
        $month_day = $date->format('j');
        return intval($month_day);
    }

    // Получение номера дня недели
    public static function GetWeekDay($date) {
        $weekday = $date->format('w');
        return intval($weekday);
    }

    // Получение номера недели в году
    public static function GetWeekNumber($date) {
        $week_number = $date->format('W');
        return intval($week_number);
    }

    // Проверка на равенство дат (года и месяца)
    public static function IsEqualDate($date_1, $date_2) {
        $is_equal_year = self::GetYear($date_1) === self::GetYear($date_2);
        $is_equal_month = self::GetMonthNumber($date_1) === self::GetMonthNumber($date_2);
        return $is_equal_year and $is_equal_month;
    }

    // Получение объекта текущей даты
    public static function GetToday() {
        $date = new DateTime();

        $day = self::GetDayOfMonth($date);
        $month = self::GetMonthNumber($date);
        $year = self::GetYear($date);

        // объект даты отображаемого месяца
        $displayed_date = date_date_set(new DateTime(), $year, $month, 1);
        
        // объект даты текущего месяца
        $current_date = date_date_set(new DateTime(), $year, $month, 1);

        return [$displayed_date, $current_date, $day];
    }

    // Получение объекта даты текущего месяца
    public static function GetCurrentMonth() {
        [$_1, $date, $_2] = self::GetToday();
        return $date;
    }

    // Получение объекта даты предыдущего месяца
    public static function GetPrevMonth($date) {
        return $date->modify('-1 month');
    }

    // Получение объекта даты следующего месяца
    public static function GetNextMonth($date) {
        return $date->modify('+1 month');
    }

    // Получение объекта даты предыдущего года
    public static function GetPrevYear($date) {
        return $date->modify('-1 year');
    }

    // Получение объекта даты следующего года
    public static function GetNextYear($date) {
        return $date->modify('+1 year');
    }

    // Определение отношения указанного дня к праздникам
    public static function IsHoliday($year, $month, $day) {
        // по каждому элементу массива праздников по годам:
        foreach (Tool::GetHolidays() as $holi_year => $holi_months) {
            // если год указанного дня имеется в списке годов массива праздников
            if ($holi_year == $year) {
                // по каждому элементу массива праздников по месяцам указанного года:
                foreach ($holi_months as $holi_month => $holidays) {
                    // если месяц указанного дня имеется в списке месяцев массива праздников по указанному году
                    if ($holi_month == $month) {
                        // если число указанного дня имеется в списке дней массива праздников по указанному месяцу и году
                        if (in_array($day, $holidays)) {
                            return true;
                        }
                    }
                }
            }
        }

        // проверка наличия указанного дня в списке праздничных дней массива праздников по умолчанию
        return in_array($day, Tool::GetDefaultHolidays()[$month]);
    }
}
