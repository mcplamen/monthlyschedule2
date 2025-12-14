<?php
namespace Mcplamen\Monthlyschedule\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Calendar ViewHelper
 * Generates calendar grid structure with days
 * 
 * Usage:
 * {namespace ms=Mcplamen\Monthlyschedule\ViewHelpers}
 * <ms:calendar month="{month}" year="{year}" days="{days}" />
 */
class CalendarViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;
    
    protected $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('month', 'int', 'Month number (1-12)', true);
        $this->registerArgument('year', 'int', 'Year', true);
        $this->registerArgument('days', 'mixed', 'Array or QueryResult of day objects', false, []);
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return array
     */
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
        
        // Създаваме масив с дните индексирани по dayname
        $daysArray = [];
        if (is_array($days)) {
            foreach ($days as $day) {
                if (is_object($day) && method_exists($day, 'getDayname')) {
                    $dayname = (int)$day->getDayname();
                    $daysArray[$dayname] = $day;
                }
            }
        }
        
        // Първи ден на месеца
        $firstDay = mktime(0, 0, 0, $month, 1, $year);
        $daysInMonth = (int)date('t', $firstDay);
        $dayOfWeek = (int)date('w', $firstDay); // 0 (Sunday) to 6 (Saturday)
        
        // Преобразуваме от US формат (0=Sunday) в EU формат (0=Monday)
        $startDayOfWeek = ($dayOfWeek == 0) ? 6 : $dayOfWeek - 1;
        
        // Немски имена на месеците
        $germanMonths = [
            1 => 'Januar',
            2 => 'Februar',
            3 => 'März',
            4 => 'April',
            5 => 'Mai',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'August',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Dezember'
        ];
        
        $calendar = [];
        $week = [];
        
        // Празни клетки преди първия ден
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $week[] = [
                'day' => null,
                'hasData' => false,
                'dayObject' => null,
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
            $dayObject = $hasData ? $daysArray[$day] : null;
            
            // Определяме дали е днес
            $isToday = ($isCurrentMonth && $day == $today);
            
            // Определяме дали е уикенд (събота или неделя)
            $currentDayOfWeek = ($startDayOfWeek + $day - 1) % 7;
            $isWeekend = ($currentDayOfWeek == 5 || $currentDayOfWeek == 6);
            
            $week[] = [
                'day' => $day,
                'hasData' => $hasData,
                'dayObject' => $dayObject,
                'isToday' => $isToday,
                'isWeekend' => $isWeekend
            ];
            
            // Ако седмицата е пълна, добавяме я
            if (count($week) == 7) {
                $calendar[] = $week;
                $week = [];
            }
        }
        
        // Добавяме празни клетки в края ако е необходимо
        if (count($week) > 0) {
            while (count($week) < 7) {
                $week[] = [
                    'day' => null,
                    'hasData' => false,
                    'dayObject' => null,
                    'isToday' => false,
                    'isWeekend' => false
                ];
            }
            $calendar[] = $week;
        }
        
        return [
            'calendar' => $calendar,
            'month' => $month,
            'year' => $year,
            'monthName' => $germanMonths[$month],
            'daysInMonth' => $daysInMonth,
            'daysWithData' => count($daysArray)
        ];
    }
}