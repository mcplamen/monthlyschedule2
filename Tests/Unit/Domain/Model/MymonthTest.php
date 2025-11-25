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
class MymonthTest extends UnitTestCase
{
    /**
     * @var \Mcplamen\Monthlyschedule\Domain\Model\Mymonth|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \Mcplamen\Monthlyschedule\Domain\Model\Mymonth::class,
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
    public function getMonthReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getMonth()
        );
    }

    /**
     * @test
     */
    public function setMonthForIntSetsMonth(): void
    {
        $this->subject->setMonth(12);

        self::assertEquals(12, $this->subject->_get('month'));
    }

    /**
     * @test
     */
    public function getYearReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getYear()
        );
    }

    /**
     * @test
     */
    public function setYearForIntSetsYear(): void
    {
        $this->subject->setYear(12);

        self::assertEquals(12, $this->subject->_get('year'));
    }

    /**
     * @test
     */
    public function getDaysReturnsInitialValueForMyday(): void
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getDays()
        );
    }

    /**
     * @test
     */
    public function setDaysForObjectStorageContainingMydaySetsDays(): void
    {
        $day = new \Mcplamen\Monthlyschedule\Domain\Model\Myday();
        $objectStorageHoldingExactlyOneDays = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneDays->attach($day);
        $this->subject->setDays($objectStorageHoldingExactlyOneDays);

        self::assertEquals($objectStorageHoldingExactlyOneDays, $this->subject->_get('days'));
    }

    /**
     * @test
     */
    public function addDayToObjectStorageHoldingDays(): void
    {
        $day = new \Mcplamen\Monthlyschedule\Domain\Model\Myday();
        $daysObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $daysObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($day));
        $this->subject->_set('days', $daysObjectStorageMock);

        $this->subject->addDay($day);
    }

    /**
     * @test
     */
    public function removeDayFromObjectStorageHoldingDays(): void
    {
        $day = new \Mcplamen\Monthlyschedule\Domain\Model\Myday();
        $daysObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $daysObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($day));
        $this->subject->_set('days', $daysObjectStorageMock);

        $this->subject->removeDay($day);
    }
}
