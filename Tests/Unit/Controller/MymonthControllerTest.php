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
class MymonthControllerTest extends UnitTestCase
{
    /**
     * @var \Mcplamen\Monthlyschedule\Controller\MymonthController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\Mcplamen\Monthlyschedule\Controller\MymonthController::class))
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
    public function listActionFetchesAllMymonthsFromRepositoryAndAssignsThemToView(): void
    {
        $allMymonths = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mymonthRepository = $this->getMockBuilder(\Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $mymonthRepository->expects(self::once())->method('findAll')->will(self::returnValue($allMymonths));
        $this->subject->_set('mymonthRepository', $mymonthRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('mymonths', $allMymonths);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenMymonthToView(): void
    {
        $mymonth = new \Mcplamen\Monthlyschedule\Domain\Model\Mymonth();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('mymonth', $mymonth);

        $this->subject->showAction($mymonth);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenMymonthToMymonthRepository(): void
    {
        $mymonth = new \Mcplamen\Monthlyschedule\Domain\Model\Mymonth();

        $mymonthRepository = $this->getMockBuilder(\Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $mymonthRepository->expects(self::once())->method('add')->with($mymonth);
        $this->subject->_set('mymonthRepository', $mymonthRepository);

        $this->subject->createAction($mymonth);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenMymonthToView(): void
    {
        $mymonth = new \Mcplamen\Monthlyschedule\Domain\Model\Mymonth();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('mymonth', $mymonth);

        $this->subject->editAction($mymonth);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenMymonthInMymonthRepository(): void
    {
        $mymonth = new \Mcplamen\Monthlyschedule\Domain\Model\Mymonth();

        $mymonthRepository = $this->getMockBuilder(\Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $mymonthRepository->expects(self::once())->method('update')->with($mymonth);
        $this->subject->_set('mymonthRepository', $mymonthRepository);

        $this->subject->updateAction($mymonth);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenMymonthFromMymonthRepository(): void
    {
        $mymonth = new \Mcplamen\Monthlyschedule\Domain\Model\Mymonth();

        $mymonthRepository = $this->getMockBuilder(\Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository::class)
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $mymonthRepository->expects(self::once())->method('remove')->with($mymonth);
        $this->subject->_set('mymonthRepository', $mymonthRepository);

        $this->subject->deleteAction($mymonth);
    }
}
