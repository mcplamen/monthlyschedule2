<?php

declare(strict_types=1);

namespace Mcplamen\Monthlyschedule\Controller;

use Mcplamen\Monthlyschedule\Domain\Model\Mymonth;
use Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository;
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
     * @param \Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository $mymonthRepository
     */
    public function injectMymonthRepository(\Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository $mymonthRepository)
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
        $mymonths = $this->mymonthRepository->findAll();
        $this->view->assign('mymonths', $mymonths);
		return $this->htmlResponse();
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
}
