<?php
namespace Mcplamen\Monthlyschedule\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Calendar ViewHelper
 * Generates calendar grid structure with days and renders children with calendar data
 * Supports multiple events per day WITH STATUS (free/booked/confirmed)
 */
class CalendarViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;
    
    protected $escapeOutput = false;
    protected $escapeChildren = false;

    public function initializeArguments()
    {
        $this->registerArgument('month', 'int', 'Month number (1-12)', true);
        $this->registerArgument('year', 'int', 'Year', true);
        $this->registerArgument('days', 'mixed', 'Array or QueryResult of day objects', false, []);
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $month = (int)$arguments['month'];
        $year = (int)$arguments['year'];
        $days = $arguments['days'];
        
        // Конвертираме QueryResult в масив ако е необходимо
        if (is_object($days) && method_exists($days, 'toArray')) {
            $days = $days->toArray();
        }
        
        // Създаваме масив с дните - групваме по dayname
        $daysArray = [];
        if (is_array($days)) {
            foreach ($days as $day) {
                if (is_object($day) && method_exists($day, 'getDayname')) {
                    $dayname = (int)$day->getDayname();
                    
                    // Ако още няма масив за този ден, създаваме го
                    if (!isset($daysArray[$dayname])) {
                        $daysArray[$dayname] = [];
                    }
                    
                    // ДОБАВЯМЕ СТАТУС към събитието (БЕЗ workshift - това поле не съществува!)
                    $eventData = [
                        'uid' => $day->getUid(),
                        'timeslot' => $day->getTimeslot(),
                        'timeslotend' => $day->getTimeslotend(),
                        'person' => $day->getPerson(),
                        'email' => $day->getEmail(),
                        'topic' => $day->getTopic(),
                        'confirm' => $day->getConfirm(),
                        'status' => self::determineStatus($day) // Статус базиран на person и confirm
                    ];
                    
                    // Добавяме събитието към масива за този ден
                    $daysArray[$dayname][] = $eventData;
                }
            }
        }
        
        // Сортираме събитията във всеки ден по timeslot
        foreach ($daysArray as $dayname => $events) {
            usort($daysArray[$dayname], function($a, $b) {
                $timeA = $a['timeslot'] ?? '';
                $timeB = $b['timeslot'] ?? '';
                return strcmp($timeA, $timeB);
            });
        }
        
        // Първи ден на месеца
        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $daysInMonth = (int)date('t', $firstDay);
        $dayOfWeek = (int)date('w', $firstDay);
        
        // Преобразуваме от US формат (0=Sunday) в EU формат (0=Monday)
        $startDayOfWeek = ($dayOfWeek == 0) ? 6 : $dayOfWeek - 1;
        
        // Немски имена на месеците
        $germanMonths = [
            1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April',
            5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'August',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember'
        ];
        
        $calendar = [];
        $week = [];
        
        // Празни клетки преди първия ден
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $week[] = [
                'day' => null,
                'hasData' => false,
                'dayEvents' => [],
                'isToday' => false,
                'isWeekend' => false
            ];
        }
        
        // Днешна дата за сравнение
        $today = (int)date('j');
        $currentMonth = (int)date('n');
        $currentYear = (int)date('Y');
        $isCurrentMonth = ($month == $currentMonth && $year == $currentYear);
        
        // Попълваме дните
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $hasData = isset($daysArray[$day]);
            $dayEvents = $hasData ? $daysArray[$day] : [];
            
            // Определяме дали е днес
            $isToday = ($isCurrentMonth && $day == $today);
            
            // Определяме дали е уикенд
            $currentDayOfWeek = ($startDayOfWeek + $day - 1) % 7;
            $isWeekend = ($currentDayOfWeek == 5 || $currentDayOfWeek == 6);
            
            $week[] = [
                'day' => $day,
                'hasData' => $hasData,
                'dayEvents' => $dayEvents,  // Масив от всички събития за този ден (вече със статус!)
                'isToday' => $isToday,
                'isWeekend' => $isWeekend
            ];
            
            // Ако седмицата е пълна, добавяме я
            if (count($week) == 7) {
                $calendar[] = $week;
                $week = [];
            }
        }
        
        // Добавяме празни клетки в края
        if (count($week) > 0) {
            while (count($week) < 7) {
                $week[] = [
                    'day' => null,
                    'hasData' => false,
                    'dayEvents' => [],
                    'isToday' => false,
                    'isWeekend' => false
                ];
            }
            $calendar[] = $week;
        }
        
        // Броим общо колко дни имат данни
        $daysWithData = count($daysArray);
        
        $calendarData = [
            'calendar' => $calendar,
            'month' => $month,
            'year' => $year,
            'monthName' => $germanMonths[$month],
            'daysInMonth' => $daysInMonth,
            'daysWithData' => $daysWithData
        ];
        
        // Assign calendar data to template
        $templateVariableContainer = $renderingContext->getVariableProvider();
        
        // Save current values
        $backup = [];
        foreach ($calendarData as $key => $value) {
            if ($templateVariableContainer->exists($key)) {
                $backup[$key] = $templateVariableContainer->get($key);
            }
        }
        
        // Add calendar data
        foreach ($calendarData as $key => $value) {
            $templateVariableContainer->add($key, $value);
        }
        
        // Render children
        $output = $renderChildrenClosure();
        
        // Remove calendar data
        foreach ($calendarData as $key => $value) {
            $templateVariableContainer->remove($key);
        }
        
        // Restore backed up values
        foreach ($backup as $key => $value) {
            $templateVariableContainer->add($key, $value);
        }
        
        return $output;
    }
    
    /**
     * Determine event status based on person and confirm fields
     * 
     * @param object $day The day object
     * @return string 'free', 'booked', or 'confirmed'
     */
    protected static function determineStatus($day): string
    {
        // Check if day has person (booking exists)
        $hasPerson = method_exists($day, 'getPerson') && !empty($day->getPerson());
        
        if (!$hasPerson) {
            return 'free'; // No booking - GREEN
        }
        
        // Check if booking is confirmed
        $isConfirmed = method_exists($day, 'getConfirm') && $day->getConfirm();
        
        if ($isConfirmed) {
            return 'confirmed'; // Confirmed booking - RED
        }
        
        return 'booked'; // Booked but not confirmed - ORANGE
    }
}