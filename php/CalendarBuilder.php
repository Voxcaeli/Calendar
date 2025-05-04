<?php

// Класс строителя календаря
class CalendarBuilder {
    // Объект даты отображаемого месяца
    private $date;
    // Текущий день
    private $today;
    // Булевое значение, определяющее, отображён ли текущий месяц
    private $is_current_date;

    public function __construct($date, $today, $is_current_date) {
        $this->date = $date;
        $this->today = $today;
        $this->is_current_date = $is_current_date;
    }

    // Отображение табличной структуры календаря
    public function Build() {
        $result = '<table>' .
                  $this->GetCaption() .
                  $this->GetHead() .
                  $this->GetBody() .
                  '</table>';
        return $result;
    }

    // Получение заголовка календаря с названием месяца, годом, и кнопками навигации 
    public function GetCaption() {
        $result = '';

        if (Tool::GetShowCaption()) {
            $result .= '<caption>' .
                       '<button type="submit"
                                class="change-month"
                                name="prev_btn"
                                title="Предыдущий месяц"
                                onclick="SetHiddenValue(\'previous\')">&#11160;</button>' .
                       '<span class="title">' .
                       Helper::GetMonthName($this->date) . ' ' . Helper::GetYear($this->date) .
                       '</span>' .
                       '<button type="submit"
                                class="change-month"
                                name="next_btn"
                                title="Следующий месяц"
                                onclick="SetHiddenValue(\'next\')">&#11162;</button>' .
                       '<button type="submit"
                                class="change-month current-month"
                                name="today_btn"
                                title="Текущий месяц"
                                onclick="SetHiddenValue(\'current\')">&#9055;</button>' .
                       '<input type="hidden"
                               name="hidden"
                               value="current">' .
                       '</caption>';
        }
        return $result;
    }

    // Получение названий дней недели
    public function GetHead() {
        $result = '<thead>';

        if (Tool::GetShowWeekdayName()) {
            if (Tool::GetShowWeekNumber()) {
                $result .= '<th class="week"></th>';
            }

            $result .= '<th class="normal">Пн</th>' .
                       '<th class="normal">Вт</th>' .
                       '<th class="normal">Ср</th>' .
                       '<th class="normal">Чт</th>' .
                       '<th class="normal">Пт</th>' .
                       '<th class="weekend">Сб</th>' .
                       '<th class="weekend">Вс</th>' .
                       '</thead>';
        }
        return $result;
    }

    // Получение номера недели и дней месяца
    public function GetBody() {
        // Номер недели (за год)
        $week_number = Helper::GetWeekNumber($this->date);
        // Массив с числами месяца (включая конец предыдущего месяца и начало следующего месяца)
        $day_collection = (new DayCollection($this->date))->GetDays();
        // Количество отображаемых недель
        $week_count = count($day_collection) / 7;
        // Индекс массива чисел для отображения результата
        $day_index = 0;

        $result = '<tbody>';
        
        while ($week_count-- > 0) {
            $result .= '<tr>';

            if (Tool::GetShowWeekNumber()) {
                $result .= '<td class="week">' . $week_number++ . '</td>';
            }

            for ($i = 0; $i < 7; $i++) {
                // Объект DayInfo для каждого отображаемого дня
                $day = $day_collection[$day_index++];
                $result .= $this->GetDay($day);
            }
            $result .= '</tr>';
        }
        $result .= '</tbody>';

        return $result;
    }

    // Получение дня с указанием класса таблицы стилей
    private function GetDay($day) {
        $result = '<td class="';
        
        if (($this->is_current_date) and ($day->value === $this->today)) {
            $result .= 'today ';
        } elseif ($day->month === 'other') {
            $result .= 'other-';
        }
        $result .= $day->status . '">' . $day->value . '</td>';

        return $result;
    }
}