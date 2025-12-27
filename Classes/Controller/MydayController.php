<?php
namespace Mcplamen\Monthlyschedule\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Mcplamen\Monthlyschedule\Domain\Repository\MydayRepository;
use Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository;
use Psr\Log\LoggerInterface;

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
     * @param MydayRepository $mydayRepository
     */
    public function injectMydayRepository(MydayRepository $mydayRepository)
    {
        $this->mydayRepository = $mydayRepository;
    }

    /**
     * @param MymonthRepository $mymonthRepository
     */
    public function injectMymonthRepository(MymonthRepository $mymonthRepository)
    {
        $this->mymonthRepository = $mymonthRepository;
    }
	
	public function initializeCreateAction(): void
	{
		$this->arguments
			->getArgument('newMyday')
			->getPropertyMappingConfiguration()
			        ->allowProperties(
					'dayname',
					'timeslot',
					'timeslotend',
					'confirm',
					'person',
					'email',
					'topic',
					'mymonth'   // 👈 КРИТИЧНО
        );
	}

	
	private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
 * @param int $monthNumber
 * @param int $year
 * @return void
 * @return void
 */
public function newAction($mymonth = 0, $monthNumber = 0, $year = 0)
{

$this->logger->debug('newAction called', [
            'mymonth' => $mymonth,
            'monthNumber' => $monthNumber,
            'year' => $year
        ]);
    $mymonthObject = null;
    $mydays = [];
    
    // Debug
    echo "<h3>DEBUG in controller:</h3>";
    echo "mymonth = " . $mymonth . "<br>";
    echo "monthNumber = " . $monthNumber . "<br>";
    echo "year = " . $year . "<br>";
    
    if ($mymonth > 0) {
        // Зареждаме mymonth обекта
        $mymonthObject = $this->mymonthRepository->findByUid($mymonth);
        
        echo "mymonthObject found: " . ($mymonthObject ? 'YES' : 'NO') . "<br>";
        
        if ($mymonthObject !== null) {
            // Използваме repository метода
            $mydays = $this->mydayRepository->findByMymonth($mymonth);
            
            echo "Days found: " . $mydays->count() . "<br>";
			
			if ($mymonthObject !== null) {
            $newMyday->setMymonth($mymonthObject); // 🔥 КЛЮЧОВО
			}
            
            // Ако не са подадени month и year, вземи от обекта
            if ($monthNumber == 0) {
                $monthNumber = $mymonthObject->getMonth();
                echo "Got monthNumber from object: " . $monthNumber . "<br>";
            }
            if ($year == 0) {
                $year = $mymonthObject->getYear();
                echo "Got year from object: " . $year . "<br>";
            }
        }
    }
    
    $this->view->assignMultiple([
        'mymonth' => $mymonth,
        'mymonthObject' => $mymonthObject,
        'mydays' => $mydays,
        'monthNumber' => $monthNumber,
        'year' => $year
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
    echo '<pre>';
    echo 'CREATE ACTION HIT' . PHP_EOL;
    var_dump($_POST);
    exit;
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