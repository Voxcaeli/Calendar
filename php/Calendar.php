<?php

// Установка часового пояса
date_default_timezone_set('Europe/Moscow');

require_once('php/Tool.php');
require_once('php/Helper.php');
require_once('php/DayInfo.php');
require_once('php/DayCollection.php');
require_once('php/CalendarBuilder.php');

// Класс календаря
class Calendar {
    // Построение календаря
    public function Build($date, $today, $is_current_month) {
        return (new CalendarBuilder($date, $today, $is_current_month))->Build();
    }
}