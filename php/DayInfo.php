<?php

// КЛАСС ИНФОРМАЦИИ О ДНЯХ
class DayInfo {
    // Значение дня месяца (1-31)
    public $value;

    // Статус дня (обычный, выходной, праздник)
    public $day_status;

    // Статус месяца (текущий, другой)
    public $month_status;

    public function __construct(int $value, string $day_status, string $month_status) {
        $this->value = $value;
        $this->day_status = $day_status;
        $this->month_status = $month_status;
    }
}