<?php
namespace Mcplamen\Monthlyschedule\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class WeekdayDeViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('date', 'string', 'Date in Y-m-d format', true);
    }

    public function render(): string
    {
        $date = $this->arguments['date'];

        $weekdays = [
            'Monday'    => 'Montag',
            'Tuesday'   => 'Dienstag',
            'Wednesday' => 'Mittwoch',
            'Thursday'  => 'Donnerstag',
            'Friday'    => 'Freitag',
            'Saturday'  => 'Samstag',
            'Sunday'    => 'Sonntag',
        ];

        $english = date('l', strtotime($date));

        return $weekdays[$english] ?? '';
    }
}
