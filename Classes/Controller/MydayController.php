<?php
namespace Mcplamen\Monthlyschedule\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Mcplamen\Monthlyschedule\Domain\Repository\MydayRepository;
use Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository;

class MydayController extends ActionController
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
     * Inject myday repository
     * 
     * @param MydayRepository $mydayRepository
     */
    public function injectMydayRepository(MydayRepository $mydayRepository)
    {
        $this->mydayRepository = $mydayRepository;
    }

    /**
     * Inject mymonth repository
     * 
     * @param MymonthRepository $mymonthRepository
     */
    public function injectMymonthRepository(MymonthRepository $mymonthRepository)
    {
        $this->mymonthRepository = $mymonthRepository;
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $mydays = $this->mydayRepository->findAll();
        $this->view->assign('mydays', $mydays);
    }

    /**
     * action show
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
     * @return void
     */
    public function showAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
    {
        $this->view->assign('myday', $myday);
    }

    /**
     * action new
     *
     * @param int $mymonth
     * @return void
     */
    public function newAction($mymonth = 0)
    {
        $mymonthObject = null;
        $mydays = [];
        
        // Debug
        echo '<div style="background: orange; padding: 20px; margin: 20px;">';
        echo 'Mymonth parameter: ' . $mymonth . '<br>';
        echo 'Repository injected: ' . ($this->mymonthRepository ? 'YES' : 'NO') . '<br>';
        echo '</div>';
        
        if ($mymonth > 0 && $this->mymonthRepository !== null) {
            $mymonthObject = $this->mymonthRepository->findByUid($mymonth);
            
            if ($mymonthObject !== null && $this->mydayRepository !== null) {
                // Вземаме всички дни
                $allDays = $this->mydayRepository->findAll();
                
                // Филтрираме по mymonth
                foreach ($allDays as $day) {
                    $dayMymonth = $day->getMymonth();
                    if ($dayMymonth && $dayMymonth->getUid() == $mymonth) {
                        $mydays[] = $day;
                    }
                }
                
                // Сортираме по dayname
                usort($mydays, function($a, $b) {
                    return strcmp($a->getDayname(), $b->getDayname());
                });
            }
        }
        
        $this->view->assignMultiple([
            'mymonth' => $mymonth,
            'mymonthObject' => $mymonthObject,
            'mydays' => $mydays
        ]);
    }

    /**
     * action create
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $newMyday
     * @return void
     */
    public function createAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $newMyday)
    {
        $this->addFlashMessage('The object was created.');
        $this->mydayRepository->add($newMyday);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("myday")
     * @return void
     */
    public function editAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
    {
        $this->view->assign('myday', $myday);
    }

    /**
     * action update
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
     * @return void
     */
    public function updateAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
    {
        $this->addFlashMessage('The object was updated.');
        $this->mydayRepository->update($myday);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
     * @return void
     */
    public function deleteAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
    {
        $this->addFlashMessage('The object was deleted.');
        $this->mydayRepository->remove($myday);
        $this->redirect('list');
    }
}