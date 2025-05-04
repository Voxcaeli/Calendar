<?php

// Класс информации о днях
class DayInfo {
    // Значение дня месяца (1-31)
    public int $value;
    // Статус дня (обычный, выходной, праздник)
    public string $status;
    // Статус месяца (текущий, другой)
    public string $month;

    public function __construct(int $value, string $status, string $month) {
        $this->value = $value;
        $this->status = $status;
        $this->month = $month;
    }
}