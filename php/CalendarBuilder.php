<?php

// КЛАСС СТРОИТЕЛЯ КАЛЕНДАРЯ
class CalendarBuilder {
    // Объект даты отображаемого месяца
    private $date;

    // Номер текущего дня
    private $today;

    // Булевое значение, определяющее, отображён ли текущий месяц
    private $is_current_month;

    public function __construct($date, $today, $is_current_month) {
        $this->date = $date;
        $this->today = $today;
        $this->is_current_month = $is_current_month;
    }

    // Отображение табличной структуры календаря
    public function Build() {
        $result = '<table>' . $this->GetCaption() .
                              $this->GetHead() .
                              $this->GetBody() . '</table>';
        return $result;
    }

    // Получение заголовка календаря с названием месяца, годом, и кнопками навигации 
    private function GetCaption() {
        // сокрытие заголовка календаря
        if (!Tool::IsShowCaption()) {
            return '';
        }

        $result = '<caption>' .
                      // кнопка переключения на предыдущий год
                      '<button type="submit"
                               class="change-year"
                               name="prev_year_btn"
                               title="Предыдущий год"
                               onclick="ChangeTool(\'prev_year\')">&#11164;</button>' .
                      // кнопка переключения на предыдущий месяц
                      '<button type="submit"
                               class="change-month"
                               name="prev_month_btn"
                               title="Предыдущий месяц"
                               onclick="ChangeTool(\'prev_month\')">&#11160;</button>' .
                      // название текущих месяца и годы
                      '<span class="title">' .
                          Helper::GetMonthName($this->date) . ' ' . Helper::GetYear($this->date) .
                      '</span>' .
                      // кнопка переключения на следующий месяц
                      '<button type="submit"
                               class="change-month"
                               name="next_month_btn"
                               title="Следующий месяц"
                               onclick="ChangeTool(\'next_month\')">&#11162;</button>' .
                      // кнопка переключения на следующий год
                      '<button type="submit"
                               class="change-year"
                               name="next_year_btn"
                               title="Следующий год"
                               onclick="ChangeTool(\'next_year\')">&#11166;</button>' .
                      // кнопка переключения на текущий месяц
                      '<button type="submit"
                               class="change-month current-month"
                               name="today_btn"
                               title="Текущий месяц"
                               onclick="ChangeTool(\'current\')">&#9055;</button>' .
                      // скрытое поле, указывающее на код настроек календаря
                      '<input type="hidden"
                              name="hidden"
                              value="">' .
                  '</caption>';

        return $result;
    }

    // Получение названий дней недели
    private function GetHead() {
        // сокрытие строки с названиями дней недели
        if (!Tool::IsShowWeekdayName()) {
            return '';
        }
        
        $result = '<thead>';

        if (Tool::IsShowWeekNumber()) {
            // ячейка для колонки номеров недель года
            $result .= '<th class="week"></th>';
        }

        // заполнение ячеек таблицы названиями дней недели
        $result .= $this->GetWeekNames() . '</thead>';

        return $result;
    }

    // Получение списка элементов, соответствующих названиям дней недели
    private function GetWeekNames() {
        // список статусов дней недели
        $ru_day_status = explode(',', 'n,n,n,n,n,w,w');
        $en_day_status = explode(',', 'w,n,n,n,n,n,w');
        
        // список названий дней недели
        $ru_day_names = explode(',', 'Пн,Вт,Ср,Чт,Пт,Сб,Вс');
        $en_day_names = explode(',', 'Вс,Пн,Вт,Ср,Чт,Пт,Сб');

        // определение варианта списка дней недели в зависимости от настроек календаря
        $day_status = Tool::IsStartWeekFromSunday() ? $en_day_status : $ru_day_status;
        $day_names = Tool::IsStartWeekFromSunday() ? $en_day_names : $ru_day_names;

        $result = '';

        // заполнение списка названий дней недели
        for ($i = 0; $i < 7; $i++) {
            $result .= '<th class="' . $this->GetDayStatus($day_status[$i]) . '">' . $day_names[$i] . '</th>';
        }

        return $result;
    }

    // Получение полного названия статуса дня недели (= название CSS-стиля ячейки дня)
    private function GetDayStatus($status) {
        $n = 'normal';
        $w = 'weekend';

        return $$status;
    }

    // Получение номеров недель и дней календаря
    private function GetBody() {
        // номер первой недели месяца
        $week_number = Helper::GetWeekNumber($this->date);

        // массив с числами месяца (включая конец предыдущего месяца и начало следующего месяца)
        $day_collection = (new DayCollection($this->date))->GetDays();

        // количество отображаемых недель
        $week_count = count($day_collection) / 7;

        // индекс массива чисел для отображения результата
        $day_index = 0;

        $result = '<tbody>';
        
        while ($week_count > 0) {
            $result .= '<tr>';

            // отображение номера недели
            if (Tool::IsShowWeekNumber()) {
                $result .= '<td class="week">' . $week_number . '</td>';
                $week_number++;
            }

            // понедельное построение календаря
            for ($i = 0; $i < 7; $i++) {
                // объект DayInfo для каждого отображаемого дня
                $day = $day_collection[$day_index];

                // добавление дня в календарь
                $result .= $this->GetDay($day);

                $day_index++;
            }
            $result .= '</tr>';

            $week_count--;
        }
        $result .= '</tbody>';

        return $result;
    }

    // Получение дня с указанием класса таблицы стилей
    private function GetDay($day) {
        $result = '<td class="';
        
        // стилевое оформление текущего дня
        if (($this->is_current_month) and ($day->value === $this->today)) {
            $result .= 'today ';

        // стилевое оформление дней предыдущего и следующего месяцев
        } elseif ($day->month_status === 'other') {
            $result .= 'other-';
        }

        // окончательное добавление стилей
        $result .= $day->day_status . '">';

        $result .= ($day->value === 0) ? '' : $day->value;

        $result .= '</td>';

        return $result;
    }
}