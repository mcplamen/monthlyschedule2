<?php
namespace Mcplamen\Monthlyschedule\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class GermanDayViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('day', 'int', 'Day of month (1-31)', true);
        $this->registerArgument('month', 'int', 'Month number (1-12)', true);
        $this->registerArgument('year', 'int', 'Year', true);
    }

    /**
     * @return string
     */
    public function render()
    {
        $day = $this->arguments['day'];
        $month = $this->arguments['month'];
        $year = $this->arguments['year'];
        
        // Създаваме дата
        $dateString = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $timestamp = strtotime($dateString);
        
        if ($timestamp === false) {
            return '';
        }
        
        // Масив с немските дни на седмицата
        $germanDays = [
            'Sunday' => 'Sonntag',
            'Monday' => 'Montag',
            'Tuesday' => 'Dienstag',
            'Wednesday' => 'Mittwoch',
            'Thursday' => 'Donnerstag',
            'Friday' => 'Freitag',
            'Saturday' => 'Samstag'
        ];
        
        $englishDay = date('l', $timestamp);
        
        return $germanDays[$englishDay] ?? '';
    }
}