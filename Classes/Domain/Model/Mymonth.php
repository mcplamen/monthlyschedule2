<?php

declare(strict_types=1);

namespace Mcplamen\Monthlyschedule\Domain\Model;


/**
 * This file is part of the "Monthly Schedule" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 Plamen Petkov <mcplamen@gmail.com>, NR OOD
 */

/**
 * Mymonth
 */
class Mymonth extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * month
     *
     * @var int
     */
    protected $month = null;

    /**
     * year
     *
     * @var int
     */
    protected $year = null;

    /**
     * days
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mcplamen\Monthlyschedule\Domain\Model\Myday>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $days = null;

    /**
     * __construct
     */
    public function __construct()
    {

        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->days = $this->days ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Sets the year
     *
     * @param int $year
     * @return void
     */
    public function setYear(int $year)
    {
        $this->year = $year;
    }

    /**
     * Adds a Myday
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $day
     * @return void
     */
    public function addDay(\Mcplamen\Monthlyschedule\Domain\Model\Myday $day)
    {
        $this->days->attach($day);
    }

    /**
     * Removes a Myday
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $dayToRemove The Myday to be removed
     * @return void
     */
    public function removeDay(\Mcplamen\Monthlyschedule\Domain\Model\Myday $dayToRemove)
    {
        $this->days->detach($dayToRemove);
    }

    /**
     * Returns the days
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mcplamen\Monthlyschedule\Domain\Model\Myday>
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Sets the days
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Mcplamen\Monthlyschedule\Domain\Model\Myday> $days
     * @return void
     */
    public function setDays(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $days)
    {
        $this->days = $days;
    }

    /**
     * Returns the month
     *
     * @return int month
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Sets the month
     *
     * @param string $month
     * @return void
     */
    public function setMonth(string $month)
    {
        $this->month = $month;
    }
}
