<?php

declare(strict_types=1);

namespace Mcplamen\Monthlyschedule\Controller;

use MCplamen\Monthlyschedule\Domain\Repository\MydayRepository;
use MCplamen\Monthlyschedule\Domain\Repository\MymonthRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;


/**
 * This file is part of the "Monthly Schedule" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 Plamen Petkov <mcplamen@gmail.com>, NR OOD
 */

/**
 * MydayController
 */
class MydayController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	 /**
     * @var MydayRepository
     */
    protected $mydayRepository;

    /**
     * @var MymonthRepository
     */
    protected $mymonthRepository;

    /**
     * Inject the myday repository
     *
     * @param MydayRepository $mydayRepository
     */
    public function injectMydayRepository(MydayRepository $mydayRepository)
    {
        $this->mydayRepository = $mydayRepository;
    }

    /**
     * Inject the mymonth repository
     *
     * @param MymonthRepository $mymonthRepository
     */
    public function injectMymonthRepository(MymonthRepository $mymonthRepository)
    {
        $this->mymonthRepository = $mymonthRepository;
    }

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        $mydays = $this->mydayRepository->findAll();
        $this->view->assign('mydays', $mydays);
        return $this->htmlResponse();
    }

    /**
     * action show
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday): \Psr\Http\Message\ResponseInterface
    {
        $this->view->assign('myday', $myday);
        return $this->htmlResponse();
    }

	/**
	 * action new
	 *
	 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $newMyday
	 * @param int $mymonth
	 * @return void
	 */
	public function newAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $newMyday = NULL, $mymonth = 0)
	{
		// Ако има подаден mymonth UID, зареди обекта
		if ($mymonth > 0 && $newMyday === NULL) {
			$mymonthObject = $this->mymonthRepository->findByUid($mymonth);
			
			// Създай нов Myday обект и задай релацията
			$newMyday = $this->objectManager->get(\Mcplamen\Monthlyschedule\Domain\Model\Myday::class);
			$newMyday->setMymonth($mymonthObject);
		}
		
		$this->view->assign('newMyday', $newMyday);
		$this->view->assign('mymonth', $mymonth);
	}

	/**
	 * action create
	 *
	 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $newMyday
	 * @return void
	 */
	public function createAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $newMyday)
	{
		$this->mydayRepository->add($newMyday);
		
		$this->addFlashMessage('Day was created successfully.');
		
		// Редирект обратно към списъка на месеца
		if ($newMyday->getMymonth()) {
			$this->redirect('list', 'Mymonth');
		} else {
			$this->redirect('list');
		}
	}

    /**
     * action edit
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("myday")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function editAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday): \Psr\Http\Message\ResponseInterface
    {
        $this->view->assign('myday', $myday);
        return $this->htmlResponse();
    }

    /**
     * action update
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
     */
    public function updateAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->mydayRepository->update($myday);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
     */
    public function deleteAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->mydayRepository->remove($myday);
        $this->redirect('list');
    }

    /**
     * action select
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function selectAction(): \Psr\Http\Message\ResponseInterface
    {
        return $this->htmlResponse();
    }
}
