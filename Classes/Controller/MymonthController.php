<?php

declare(strict_types=1);

namespace Mcplamen\Monthlyschedule\Controller;

use Mcplamen\Monthlyschedule\Domain\Model\Mymonth;
use Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository;
use Mcplamen\Monthlyschedule\Domain\Repository\MydayRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;


class MymonthController extends ActionController
{

    /**
     * mymonthRepository
     *
     * @var \Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository
     */
    protected $mymonthRepository = null;
	
	 /**
     * @var MydayRepository
     */
    protected $mydayRepository; 

    /**
     * @param \Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository $mymonthRepository
     */
    public function injectMymonthRepository(\Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository $mymonthRepository)
    {
        $this->mymonthRepository = $mymonthRepository;
    }
	
	/**
     * @param MydayRepository $mydayRepository
     */
    public function injectMydayRepository(MydayRepository $mydayRepository)  // ДОБАВИ ТОЗИ МЕТОД
    {
        $this->mydayRepository = $mydayRepository;
    }

	/**
	 * action index
	 *
	 * @return void
	 */
	public function indexAction()
	{
		$currentMonth = (int)date('n');  // 12
		$currentYear = (int)date('Y');   // 2025
		
		$nextMonth = $currentMonth + 1;  // 1
		$nextYear = $currentYear;        // 2025
		if ($nextMonth > 12) {
			$nextMonth = 1;
			$nextYear++;  // 2026
		}
		
		// DEBUG - Виж всички месеци в базата
		$allMonths = $this->mymonthRepository->findAll();
		echo "<h3>Всички месеци в базата:</h3><ul>";
		foreach ($allMonths as $m) {
			echo "<li>Month: {$m->getMonth()}, Year: {$m->getYear()}, UID: {$m->getUid()}</li>";
		}
		echo "</ul>";
		
		// DEBUG - Търсим конкретните месеци
		echo "<p>Търся: Month={$currentMonth}, Year={$currentYear}</p>";
		echo "<p>Търся: Month={$nextMonth}, Year={$nextYear}</p>";
		
		$currentMymonthResult = $this->mymonthRepository->findByMonthAndYear($currentMonth, $currentYear);
		echo "<p>Намерени за текущ месец: " . $currentMymonthResult->count() . "</p>";
		
		$currentMymonth = null;
		if ($currentMymonthResult->count() > 0) {
			$currentMymonth = $currentMymonthResult->getFirst();
			echo "<p>Текущ месец UID: " . $currentMymonth->getUid() . "</p>";
		}
		
		$nextMymonthResult = $this->mymonthRepository->findByMonthAndYear($nextMonth, $nextYear);
		echo "<p>Намерени за следващ месец: " . $nextMymonthResult->count() . "</p>";
		
		$nextMymonth = null;
		if ($nextMymonthResult->count() > 0) {
			$nextMymonth = $nextMymonthResult->getFirst();
		}
		
		$currentDays = [];
		if ($currentMymonth) {
			$currentDays = $this->mydayRepository->findByMymonth($currentMymonth->getUid());
			echo "<p>Дни за текущ месец: " . $currentDays->count() . "</p>";
		}
		
		$nextDays = [];
		if ($nextMymonth) {
			$nextDays = $this->mydayRepository->findByMymonth($nextMymonth->getUid());
		}
		
		// Добави mymonths за admin panel
		$mymonths = $this->mymonthRepository->findAll();
		
		// Check if user is logged in
		$isAdmin = $this->isLoggedInFrontendUser();
		
		$this->view->assignMultiple([
			'currentMonth' => $currentMonth,
			'currentYear' => $currentYear,
			'currentMymonth' => $currentMymonth,
			'currentDays' => $currentDays,
			'nextMonth' => $nextMonth,
			'nextYear' => $nextYear,
			'nextMymonth' => $nextMymonth,
			'nextDays' => $nextDays,
			'mymonths' => $mymonths,
			'isAdmin' => $isAdmin  // ДОБАВЕНО
		]);
	}

    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        $mymonths = $this->mymonthRepository->findAll();
        $this->view->assign('mymonths', $mymonths);
        return $this->htmlResponse();
    }

    /**
     * action show
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Mymonth $mymonth
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(\Mcplamen\Monthlyschedule\Domain\Model\Mymonth $mymonth): \Psr\Http\Message\ResponseInterface
    {
        $this->view->assign('mymonth', $mymonth);
        return $this->htmlResponse();
    }

    /**
     * action new
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function newAction(): \Psr\Http\Message\ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * action create
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Mymonth $newMymonth
     */
    public function createAction(\Mcplamen\Monthlyschedule\Domain\Model\Mymonth $newMymonth)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->mymonthRepository->add($newMymonth);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Mymonth $mymonth
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("mymonth")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function editAction(\Mcplamen\Monthlyschedule\Domain\Model\Mymonth $mymonth): \Psr\Http\Message\ResponseInterface
    {
        $this->view->assign('mymonth', $mymonth);
        return $this->htmlResponse();
    }

    /**
     * action update
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Mymonth $mymonth
     */
    public function updateAction(\Mcplamen\Monthlyschedule\Domain\Model\Mymonth $mymonth)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->mymonthRepository->update($mymonth);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Mymonth $mymonth
     */
    public function deleteAction(\Mcplamen\Monthlyschedule\Domain\Model\Mymonth $mymonth)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->mymonthRepository->remove($mymonth);
        $this->redirect('list');
    }
	
	/**
	 * Public index action - for visitors (no admin panel)
	 *
	 * @return void
	 */
	public function publicIndexAction()
	{
		// Same as indexAction but with isAdmin = false
		$currentMonth = (int)date('n');
		$currentYear = (int)date('Y');
		
		$nextMonth = $currentMonth + 1;
		$nextYear = $currentYear;
		if ($nextMonth > 12) {
			$nextMonth = 1;
			$nextYear++;
		}
		
		// Find current month
		$currentMymonthResult = $this->mymonthRepository->findByMonthAndYear($currentMonth, $currentYear);
		$currentMymonth = null;
		if ($currentMymonthResult->count() > 0) {
			$currentMymonth = $currentMymonthResult->getFirst();
		}
		
		// Find next month
		$nextMymonthResult = $this->mymonthRepository->findByMonthAndYear($nextMonth, $nextYear);
		$nextMymonth = null;
		if ($nextMymonthResult->count() > 0) {
			$nextMymonth = $nextMymonthResult->getFirst();
		}
		
		// Get days for current month
		$currentDays = [];
		if ($currentMymonth) {
			$currentDays = $this->mydayRepository->findByMymonth($currentMymonth->getUid());
		}
		
		// Get days for next month
		$nextDays = [];
		if ($nextMymonth) {
			$nextDays = $this->mydayRepository->findByMymonth($nextMymonth->getUid());
		}
		
		$this->view->assignMultiple([
			'currentMonth' => $currentMonth,
			'currentYear' => $currentYear,
			'currentMymonth' => $currentMymonth,
			'currentDays' => $currentDays,
			'nextMonth' => $nextMonth,
			'nextYear' => $nextYear,
			'nextMymonth' => $nextMymonth,
			'nextDays' => $nextDays,
			'isAdmin' => false,  // ВИНАГИ false за публичен plugin
			'isPublicView' => true  // Флаг че е публичен изглед
		]);
	}
	
	/**
	 * Check if current user is logged in frontend user
	 *
	 * @return bool
	 */
	protected function isLoggedInFrontendUser()
	{
		if (isset($GLOBALS['TSFE']) && $GLOBALS['TSFE']->fe_user) {
			return (bool)$GLOBALS['TSFE']->fe_user->user['uid'];
		}
		return false;
	}
}
