<?php

declare(strict_types=1);

namespace Mcplamen\Monthlyschedule\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Plamen Petkov <mcplamen@gmail.com>
 */
class MydayTest extends UnitTestCase
{
    /**
     * @var \Mcplamen\Monthlyschedule\Domain\Model\Myday|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \Mcplamen\Monthlyschedule\Domain\Model\Myday::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getDaynameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getDayname()
        );
    }

    /**
     * @test
     */
    public function setDaynameForStringSetsDayname(): void
    {
        $this->subject->setDayname('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('dayname'));
    }

    /**
     * @test
     */
    public function getTimeslotReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTimeslot()
        );
    }

    /**
     * @test
     */
    public function setTimeslotForStringSetsTimeslot(): void
    {
        $this->subject->setTimeslot('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('timeslot'));
    }

    /**
     * @test
     */
    public function getTimeslotendReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTimeslotend()
        );
    }

    /**
     * @test
     */
    public function setTimeslotendForStringSetsTimeslotend(): void
    {
        $this->subject->setTimeslotend('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('timeslotend'));
    }

    /**
     * @test
     */
    public function getConfirmReturnsInitialValueForBool(): void
    {
        self::assertFalse($this->subject->getConfirm());
    }

    /**
     * @test
     */
    public function setConfirmForBoolSetsConfirm(): void
    {
        $this->subject->setConfirm(true);

        self::assertEquals(true, $this->subject->_get('confirm'));
    }

    /**
     * @test
     */
    public function getPersonReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getPerson()
        );
    }

    /**
     * @test
     */
    public function setPersonForStringSetsPerson(): void
    {
        $this->subject->setPerson('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('person'));
    }

    /**
     * @test
     */
    public function getEmailReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getEmail()
        );
    }

    /**
     * @test
     */
    public function setEmailForStringSetsEmail(): void
    {
        $this->subject->setEmail('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('email'));
    }

    /**
     * @test
     */
    public function getTopicReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTopic()
        );
    }

    /**
     * @test
     */
    public function setTopicForStringSetsTopic(): void
    {
        $this->subject->setTopic('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('topic'));
    }
}
