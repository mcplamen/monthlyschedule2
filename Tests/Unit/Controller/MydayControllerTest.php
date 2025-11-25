<?php

declare(strict_types=1);

namespace Mcplamen\Monthlyschedule\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Test case
 *
 * @author Plamen Petkov <mcplamen@gmail.com>
 */
class MydayControllerTest extends UnitTestCase
{
    /**
     * @var \Mcplamen\Monthlyschedule\Controller\MydayController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\Mcplamen\Monthlyschedule\Controller\MydayController::class))
            ->onlyMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllMydaysFromRepositoryAndAssignsThemToView(): void
    {
        $allMydays = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mydayRepository = $this->getMockBuilder(\::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $mydayRepository->expects(self::once())->method('findAll')->will(self::returnValue($allMydays));
        $this->subject->_set('mydayRepository', $mydayRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('mydays', $allMydays);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenMydayToView(): void
    {
        $myday = new \Mcplamen\Monthlyschedule\Domain\Model\Myday();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('myday', $myday);

        $this->subject->showAction($myday);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenMydayToMydayRepository(): void
    {
        $myday = new \Mcplamen\Monthlyschedule\Domain\Model\Myday();

        $mydayRepository = $this->getMockBuilder(\::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $mydayRepository->expects(self::once())->method('add')->with($myday);
        $this->subject->_set('mydayRepository', $mydayRepository);

        $this->subject->createAction($myday);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenMydayToView(): void
    {
        $myday = new \Mcplamen\Monthlyschedule\Domain\Model\Myday();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('myday', $myday);

        $this->subject->editAction($myday);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenMydayInMydayRepository(): void
    {
        $myday = new \Mcplamen\Monthlyschedule\Domain\Model\Myday();

        $mydayRepository = $this->getMockBuilder(\::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $mydayRepository->expects(self::once())->method('update')->with($myday);
        $this->subject->_set('mydayRepository', $mydayRepository);

        $this->subject->updateAction($myday);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenMydayFromMydayRepository(): void
    {
        $myday = new \Mcplamen\Monthlyschedule\Domain\Model\Myday();

        $mydayRepository = $this->getMockBuilder(\::class)
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $mydayRepository->expects(self::once())->method('remove')->with($myday);
        $this->subject->_set('mydayRepository', $mydayRepository);

        $this->subject->deleteAction($myday);
    }
}
