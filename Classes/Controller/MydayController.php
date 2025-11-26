<?php

declare(strict_types=1);

namespace Mcplamen\Monthlyschedule\Controller;


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
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Mymonth $mymonth
     * @return \Psr\Http\Message\ResponseInterface
     */
	public function newAction(\McPlamen\Monthlyschedule2\Domain\Model\Mymonth $mymonth)
	{
		$newDay = new \McPlamen\Monthlyschedule2\Domain\Model\Myday();
		$newDay->setMymonth($mymonth);
		$this->view->assign('myday', $newDay);
		$this->view->assign('mymonth', $mymonth);
	}

    /**
     * action create
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $newMyday
     */
	public function createAction(
		\McPlamen\Monthlyschedule2\Domain\Model\Myday $myday,
		\McPlamen\Monthlyschedule2\Domain\Model\Mymonth $mymonth
	) {
		$myday->setMymonth($mymonth);

		$this->mydayRepository->add($myday);
		$this->redirect('show', 'Mymonth', null, ['mymonth' => $mymonth]);
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
