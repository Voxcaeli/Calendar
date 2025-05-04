<?php

// Класс коллекции дней календаря
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
        $this->date = $date;
        $this->SetWeekday();
        $this->FillArrays();
    }

    // Установка дня недели, с которого начинается текущий месяц
    private function SetWeekday() {
        // День недели с которого начинается текущий месяц
        $weekday = Helper::GetWeekDay($this->date);
        if ($weekday === 0) {
            $weekday = 7;
        }
        $this->begin_weekday = $weekday;
    }

    // Заполнение массивов днями предыдущего, текущего и следующего месяцев
    private function FillArrays() {
        $this->prev_month = $this->FillPrevMonthDays();
        [$this->current_month, $end_weekday] = $this->FillCurrentMonthDays();
        $this->next_month = $this->FillNextMonthDays($end_weekday);
    }

    // Определение количества дней в предыдущем месяце
    private function GetPrevMonthDays() {
        // Количество отображаемых дней предыдущего месяца
        $count = $this->begin_weekday - 1;
        // Объект предыдущего месяца
        $prev_month = Helper::GetPrevMonth($this->date);
        // Количество дней в предыдущем месяце
        $prev_month_days_count = Helper::GetDaysCount($prev_month);
        // Начальное число предыдущего месяца
        $start_day = $prev_month_days_count - ($count - 1);

        return [$start_day, $count];
    }

    // Заполнение массива дней предыдущего месяца
    private function FillPrevMonthDays() {
        // Начальное число предыдудщего месяца
        // Количество дней предыдудщего месяца, отображаемых в календаре
        [$day, $count] = $this->GetPrevMonthDays();
        // Номер предыдущего месяца
        $month = Helper::GetMonthNumber($this->date) - 1;
        if ($month < 1) {
            $month = 12;
        }
        // День недели
        $weekday = 0;
        // Массив дней предыдущего месяца
        $days = [];
    
        while ($weekday++ < $count) {
            $days[] = $this->AddDayInArray($month, $day, $weekday, 'other');
            $day++;
        }
        return $days;
    }

    // Заполнение массива дней текущего месяца
    private function FillCurrentMonthDays() {
        // Количество дней текущего месяца
        $count = Helper::GetDaysCount($this->date);
        // Номер текущего месяца
        $month = Helper::GetMonthNumber($this->date);
        // День месяца, с которого создаётся объект дня календаря
        $day = 1;
        // День недели
        $weekday = $this->begin_weekday;
        // Массив дней текущего месяца
        $days = [];
    
        while ($day <= $count) {
            $days[] = $this->AddDayInArray($month, $day, $weekday, 'current');
            $day++;
    
            if (++$weekday > 7) {
                $weekday = 1;
            }
        }
        return [$days, --$weekday];
    }

    // Заполнение массива дней следующего месяца
    private function FillNextMonthDays($end_weekday) {
        // Номер следующего месяца
        $month = Helper::GetMonthNumber($this->date) + 1;
        // Количество дней следующего месяца, отображаемых в календаре
        $count = ($end_weekday > 0) ? (7 - $end_weekday) : 0;
        // День недели с которого начинается следующий месяц
        $weekday = $end_weekday + 1;
        if ($month > 12) {
            $month = 1;
        }
        // День следующего месяца, добавляемый в календарь
        $day = 1;
        // Массив дней следующего месяца
        $days = [];

        while ($count-- > 0) {
            $days[] = $this->AddDayInArray($month, $day, $weekday, 'other');
            $day++;
            $weekday++;
        }
        return $days;
    }

    private function AddDayInArray($month, $day, $weekday, $month_status) {
        if (Helper::IsHoliday($month, -$day)) {
            $day_info = new DayInfo($day, 'normal', $month_status);
        } elseif (Helper::IsHoliday($month, $day)) {
            $day_info = new DayInfo($day, 'holiday', $month_status);
        } elseif ($weekday > 5) {
            $day_info = new DayInfo($day, 'weekend', $month_status);
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