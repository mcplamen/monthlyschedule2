<?php
namespace Mcplamen\Monthlyschedule\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Mcplamen\Monthlyschedule\Domain\Repository\MydayRepository;
use Mcplamen\Monthlyschedule\Domain\Repository\MymonthRepository;
use Mcplamen\Monthlyschedule\Domain\Model\Myday;
use Psr\Http\Message\ResponseInterface;
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
 //   $mymonthObject = null;
//    $mydays = [];
    
// ✅ ЗАДЪЛЖИТЕЛНО – създаваме обекта
    $newMyday = new Myday();

    // DEBUG – потвърждение
    echo 'newMyday created: ';
    var_dump($newMyday !== null);
    echo '<br>';
    
    if ($mymonth > 0) {
        // Зареждаме mymonth обекта
        $mymonthObject = $this->mymonthRepository->findByUid($mymonth);
        
                echo 'mymonthObject found: ';
        var_dump($mymonthObject !== null);
        echo '<br>';
        
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
//    echo '<pre>';
//    echo 'CREATE ACTION HIT' . PHP_EOL;
//    var_dump($_POST);
//    exit;
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
	
	/**
	 * AJAX action to show event details
	 *
	 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
	 * @return void
	 */
	public function ajaxShowAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
	{
		// Check if user is logged in admin
		$isAdmin = $this->isLoggedInFrontendUser();
		
		error_log('ajaxShowAction - myday UID: ' . $myday->getUid() . ', isAdmin: ' . ($isAdmin ? 'yes' : 'no'));
		
		// Choose template based on user role
		$templateName = $isAdmin ? 'AjaxShow' : 'AjaxShowPublic';
		
		error_log('Using template: ' . $templateName);
		
		// Create standalone view without layout
		$view = $this->objectManager->get(\TYPO3\CMS\Fluid\View\StandaloneView::class);
		
		// Set template paths
		$view->setTemplateRootPaths([
			\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:monthlyschedule/Resources/Private/Templates/')
		]);
		
		// Set specific template file
		$view->setTemplatePathAndFilename(
			\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
				'EXT:monthlyschedule/Resources/Private/Templates/Myday/' . $templateName . '.html'
			)
		);
		
		// Assign variables
		$view->assign('myday', $myday);
		$view->assign('isAdmin', $isAdmin);
		
		// Render without layout
		$content = $view->render();
		
		error_log('Content length: ' . strlen($content));
		
		// Output directly
		header('Content-Type: text/html; charset=utf-8');
		echo $content;
		exit;
	}
	
	/**
	 * AJAX action to update event details
	 *
	 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
	 * @return void
	 */
	public function ajaxUpdateAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
	{
		// DEBUG - Log that action was called
		error_log('===== ajaxUpdateAction called =====');
		error_log('Myday UID: ' . $myday->getUid());
		
		// Get POST data from tx_monthlyschedule_monthlyschedule[data]
		$requestArguments = $this->request->getArguments();
		
		error_log('Request arguments: ' . print_r($requestArguments, true));
		
		if (isset($requestArguments['data'])) {
			$data = $requestArguments['data'];
			
			error_log('Data received: ' . print_r($data, true));
			
			// Update fields
			if (isset($data['person'])) {
				$myday->setPerson($data['person']);
				error_log('Set person: ' . $data['person']);
			}
			
			if (isset($data['email'])) {
				$myday->setEmail($data['email']);
				error_log('Set email: ' . $data['email']);
			}
			
			if (isset($data['topic'])) {
				$myday->setTopic($data['topic']);
				error_log('Set topic: ' . $data['topic']);
			}
			
			// Update confirm (only for admins)
			if (isset($data['confirm'])) {
				$myday->setConfirm((bool)$data['confirm']);
				error_log('Set confirm: ' . ($data['confirm'] ? 'true' : 'false'));
			}
			
			// Save
			try {
				$this->mydayRepository->update($myday);
				error_log('Repository update called');
				
				$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
					\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class
				);
				$persistenceManager->persistAll();
				error_log('PersistAll called');
				
				// Return JSON response
				header('Content-Type: application/json');
				echo json_encode(['success' => true, 'message' => 'Updated successfully']);
				error_log('Returning success response');
				exit;
			} catch (\Exception $e) {
				error_log('ERROR saving: ' . $e->getMessage());
				header('Content-Type: application/json');
				echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
				exit;
			}
		}
		
		// Error response
		error_log('ERROR: No data provided in request');
		header('Content-Type: application/json');
		echo json_encode(['success' => false, 'message' => 'No data provided']);
		exit;
	}
	
	/**
	 * Public booking action (for anonymous users)
	 *
	 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
	 * @return void
	 */
	public function publicBookAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
	{
		$requestArguments = $this->request->getArguments();
		
		// Check if slot is already booked
		if ($myday->getPerson()) {
			header('Content-Type: application/json');
			echo json_encode(['success' => false, 'message' => 'This time slot is already booked']);
			exit;
		}
		
		if (isset($requestArguments['data'])) {
			$data = $requestArguments['data'];
			
			// Update fields
			$myday->setPerson($data['person'] ?? '');
			$myday->setEmail($data['email'] ?? '');
			$myday->setTopic($data['topic'] ?? '');
			$myday->setConfirm(false); // Not confirmed by default
			
			// Save
			$this->mydayRepository->update($myday);
			
			$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
				\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class
			);
			$persistenceManager->persistAll();
			
			// Return success
			header('Content-Type: application/json');
			echo json_encode(['success' => true, 'message' => 'Booking successful']);
			exit;
		}
		
		header('Content-Type: application/json');
		echo json_encode(['success' => false, 'message' => 'No data provided']);
		exit;
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