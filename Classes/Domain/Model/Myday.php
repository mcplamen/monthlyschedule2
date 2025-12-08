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
 * Myday
 */
class Myday extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

	/**
	 * dayname
	 *
	 * @var int
	 */
	protected $dayname = 0;

    /**
     * timeslot
     *
     * @var string
     */
    protected $timeslot = '';

    /**
     * confirm
     *
     * @var bool
     */
    protected $confirm = false;

    /**
     * person
     *
     * @var string
     */
    protected $person = '';

    /**
     * email
     *
     * @var string
     */
    protected $email = '';

    /**
     * topic
     *
     * @var string
     */
    protected $topic = '';

    /**
     * timeslotend
     *
     * @var string
     */
    protected $timeslotend = '';

    /**
     * Returns the dayname
     *
     * @return string
     */
	 
    /**
     * mymonth
     *
     * @var \Mcplamen\Monthlyschedule\Domain\Model\Mymonth
     */
    protected $mymonth = null;
	 
	/**
	 * @return int
	 */
	public function getDayname(): int
	{
		return $this->dayname;
	}

	/**
	 * @param int $dayname
	 */
	public function setDayname(int $dayname): void
	{
		$this->dayname = $dayname;
	}

    /**
     * Returns the timeslot
     *
     * @return string
     */
    public function getTimeslot()
    {
        return $this->timeslot;
    }

    /**
     * Sets the timeslot
     *
     * @param string $timeslot
     * @return void
     */
    public function setTimeslot(string $timeslot)
    {
        $this->timeslot = $timeslot;
    }

    /**
     * Returns the confirm
     *
     * @return bool
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     * Sets the confirm
     *
     * @param bool $confirm
     * @return void
     */
    public function setConfirm(bool $confirm)
    {
        $this->confirm = $confirm;
    }

    /**
     * Returns the boolean state of confirm
     *
     * @return bool
     */
    public function isConfirm()
    {
        return $this->confirm;
    }

    /**
     * Returns the person
     *
     * @return string
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Sets the person
     *
     * @param string $person
     * @return void
     */
    public function setPerson(string $person)
    {
        $this->person = $person;
    }

    /**
     * Returns the email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the email
     *
     * @param string $email
     * @return void
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * Returns the topic
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Sets the topic
     *
     * @param string $topic
     * @return void
     */
    public function setTopic(string $topic)
    {
        $this->topic = $topic;
    }

    /**
     * Returns the timeslotend
     *
     * @return string
     */
    public function getTimeslotend()
    {
        return $this->timeslotend;
    }

    /**
     * Sets the timeslotend
     *
     * @param string $timeslotend
     * @return void
     */
    public function setTimeslotend(string $timeslotend)
    {
        $this->timeslotend = $timeslotend;
    }
	
	public function getWeekdayDe(): string
	{
		$date = sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->dayname);

		$weekdaysDe = [
			'Monday' => 'Montag',
			'Tuesday' => 'Dienstag',
			'Wednesday' => 'Mittwoch',
			'Thursday' => 'Donnerstag',
			'Friday' => 'Freitag',
			'Saturday' => 'Samstag',
			'Sunday' => 'Sonntag',
		];

		$english = date('l', strtotime($date));

		return $weekdaysDe[$english] ?? $english;
	}


	
	
}
