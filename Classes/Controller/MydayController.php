<?php

declare(strict_types=1);

namespace Mcplamen\Monthlyschedule\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Psr\Http\Message\ResponseInterface;
use Mcplamen\Monthlyschedule\Domain\Model\Myday;
use Mcplamen\Monthlyschedule\Domain\Model\Mymonth;
use Mcplamen\Monthlyschedule\Domain\Repository\MydayRepository;
use Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository;

class MydayController extends ActionController
{
    protected MydayRepository $mydayRepository;
    protected MymonthRepository $mymonthRepository;

    public function __construct(
        MydayRepository $mydayRepository,
        MymonthRepository $mymonthRepository
    ) {
        $this->mydayRepository = $mydayRepository;
        $this->mymonthRepository = $mymonthRepository;
    }

    public function indexAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    public function listAction(): ResponseInterface
    {
        $mydays = $this->mydayRepository->findAll();
        $this->view->assign('mydays', $mydays);
        return $this->htmlResponse();
    }

    public function showAction(Myday $myday): ResponseInterface
    {
        $this->view->assign('myday', $myday);
        return $this->htmlResponse();
    }

    /**
     * New action – получава UID и зарежда обекта
     */
    public function newAction(int $mymonthUid): ResponseInterface
    {
        $mymonth = $this->mymonthRepository->findByUid($mymonthUid);
        if ($mymonth === null) {
            $this->addFlashMessage('Month not found');
            return $this->redirect('list');
        }

        $newDay = new Myday();
        $newDay->setMymonth($mymonth);

        $this->view->assignMultiple([
            'myday'   => $newDay,
            'mymonth' => $mymonth,
        ]);

        return $this->htmlResponse();
    }

    public function createAction(Myday $myday, int $mymonthUid): ResponseInterface
    {
        $mymonth = $this->mymonthRepository->findByUid($mymonthUid);
        if ($mymonth) {
            $myday->setMymonth($mymonth);
            $this->mydayRepository->add($myday);
        }

        return $this->redirect('show', 'Mymonth', null, ['mymonth' => $mymonth]);
    }

    public function editAction(Myday $myday): ResponseInterface
    {
        $this->view->assign('myday', $myday);
        return $this->htmlResponse();
    }

    public function updateAction(Myday $myday): ResponseInterface
    {
        $this->mydayRepository->update($myday);
        return $this->redirect('list');
    }

    public function deleteAction(Myday $myday): ResponseInterface
    {
        $this->mydayRepository->remove($myday);
        return $this->redirect('list');
    }

    public function selectAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }
}
