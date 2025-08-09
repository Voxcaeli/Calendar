<?php

// КЛАСС КОЛЛЕКЦИИ ДНЕЙ КАЛЕНДАРЯ
class DayCollection {
    // Объект даты отображаемого месяца
    private $date;

    // День недели с которого начинается текущий месяц
    private $begin_weekday;

    // Массив дней предыдущего месяца
    private $prev_month;

    // Массив дней текущего месяца
    private $current_month;

    // Массив дней следующего месяца
    private $next_month;

    public function __construct($date) {
        // сохранение даты текущего месяца
        $this->date = $date;

        // установка дня недели первого числа текущего месяца
        $this->SetWeekday();

        // заполнение календаря значениями дней
        $this->FillMonthDays();
    }

    // Установка дня недели, с которого начинается текущий месяц
    private function SetWeekday() {
        // день недели с которого начинается текущий месяц
        $weekday = Helper::GetWeekDay($this->date);

        // коррекция номера дня недели
        if (!Tool::IsEnLang() and $weekday == 0) {
            $weekday = 7;
        }

        $this->begin_weekday = $weekday;
    }

    // Заполнение предыдущего, текущего и следующего месяцев значениями дней
    private function FillMonthDays() {
        // заполнение массива чисел предыдущего месяца
        $this->prev_month = $this->FillPrevMonthDays();

        // заполнение массива чисел текущего месяца
        [$this->current_month, $next_month_weekday] = $this->FillCurrentMonthDays();

        // заполнение массива чисел следующего месяца
        $this->next_month = $this->FillNextMonthDays($next_month_weekday);
    }

    // Определение первого отображаемого числа предыдущего месяца
    private function StartDayDetermination($prev_month) {
        // количество дней в предыдущем месяце
        $all_days_count = Helper::GetDaysCount($prev_month);

        // количество отображаемых дней предыдущего месяца
        $displayed_days_count = Tool::IsEnLang() ?
                                $this->begin_weekday :
                                $this->begin_weekday - 1;

        // начальное отображаемое число предыдущего месяца
        $start_day = $all_days_count - ($displayed_days_count - 1);

        return [$start_day, $displayed_days_count];
    }

    // Заполнение массива дней предыдущего месяца
    private function FillPrevMonthDays() {
        // предыдущий месяц
        $prev_month = Helper::GetPrevMonth($this->date);

        // номер предыдущего месяца
        $month_number = Helper::GetMonthNumber($prev_month);

        // год, к которому принадлежит предыдущий месяц
        $year = Helper::GetYear($prev_month);

        // первое отображаемое число предыдущего месяца
        [$day, $days_count] = $this->StartDayDetermination($prev_month);

        // день недели
        $weekday = Tool::IsEnLang() ? 0 : 1;

        // массив отображаемых дней предыдущего месяца
        $days = [];
    
        while ($days_count > 0) {
            // сокрытие значения дня
            if (!Tool::IsShowOtherMonthsDays()) {
                $day = 0;
            }

            // заполнение массива дней предыдущего месяца
            $days[] = $this->DayInfoGenerate($year, $month_number, 'other', $weekday, $day);

            $weekday++;
            $day++;
            $days_count--;
        }

        return $days;
    }

    // Заполнение массива дней текущего месяца
    private function FillCurrentMonthDays() {
        // текущий год
        $year = Helper::GetYear($this->date);
        
        // номер текущего месяца
        $month_number = Helper::GetMonthNumber($this->date);
        
        // количество дней текущего месяца
        $days_count = Helper::GetDaysCount($this->date);

        // текущий день недели
        $weekday = $this->begin_weekday;
        
        // день месяца, с которого создаётся объект дня календаря
        $day = 1;

        // массив дней текущего месяца
        $days = [];
    
        while ($day <= $days_count) {
            // заполнение массива дней текущего месяца
            $days[] = $this->DayInfoGenerate($year, $month_number, 'current', $weekday, $day);

            $weekday++;
            $day++;
    
            // коррекция дня недели
            if (Tool::IsEnLang()) {
                if ($weekday > 6) {
                    $weekday = 0;
                }
            } else {
                if ($weekday > 7) {
                    $weekday = 1;
                }
            }
        }

        return [$days, $weekday];
    }

    // Заполнение массива дней следующего месяца
    private function FillNextMonthDays($weekday) {
        // следующий месяц
        $next_month = Helper::GetNextMonth($this->date);

        // номер следующего месяца
        $month_number = Helper::GetMonthNumber($next_month);

        // год, к которому принадлежит следующий месяц
        $year = Helper::GetYear($next_month);

        // количество отображаемых дней следующего месяца
        $k = Tool::IsEnLang() ? 0 : 1;
        // если следующий месяц начинается с начала недели - следующий месяц не отображать
        $days_count = ($weekday === $k) ? 0 : 7 - $weekday + $k;

        // отображаемый день следующего месяца
        $day = 1;

        // массив отображаемых дней следующего месяца
        $days = [];

        while ($days_count > 0) {
            // сокрытие значения дня
            if (!Tool::IsShowOtherMonthsDays()) {
                $day = 0;
            }

            // заполнение массива отображаемых дней следующего месяца
            $days[] = $this->DayInfoGenerate($year, $month_number, 'other', $weekday, $day);

            $days_count--;
            $day++;
            $weekday++;
        }
        return $days;
    }

    // Генерация объекта дня
    private function DayInfoGenerate($year, $month, $month_status, $weekday, $day) {
        // рабочий выходной день (рабочая Сб или Вс)
        if (Helper::IsHoliday($year, $month, -$day)) {
            $day_info = new DayInfo($day, 'normal', $month_status);

        // праздничный день
        } elseif (Helper::IsHoliday($year, $month, $day)) {
            $day_info = new DayInfo($day, 'holiday', $month_status);

        // выходной день
        } elseif ($weekday < 1 or $weekday > 5) {
            $day_info = new DayInfo($day, 'weekend', $month_status);

        // рабочий день
        } else {
            $day_info = new DayInfo($day, 'normal', $month_status);
        }

        return $day_info;
    }

    // Получение массива всех чисел календаря
    public function GetDays() {
        $days_array = array_merge($this->prev_month, $this->current_month, $this->next_month);
        return $days_array;
    }
}
