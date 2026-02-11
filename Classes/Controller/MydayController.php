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



    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $mydays = $this->mydayRepository->findAll();
        $this->view->assign('mydays', $mydays);
		$this->assignIsAdminToView();
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
 * Public booking action - when user books an appointment
 *
 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
 * @return void
 */
public function publicBookAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
{
	// Get POST data
	$requestArguments = $this->request->getArguments();
	
	if (isset($requestArguments['data'])) {
		$data = $requestArguments['data'];
		
		// Update fields (but NOT confirm - only admin can confirm)
		if (isset($data['person'])) {
			$myday->setPerson($data['person']);
		}
		
		if (isset($data['email'])) {
			$myday->setEmail($data['email']);
		}
		
		if (isset($data['topic'])) {
			$myday->setTopic($data['topic']);
		}
		
		// Confirm is always FALSE for public bookings
		$myday->setConfirm(false);
		
		// Save
		try {
			$this->mydayRepository->update($myday);
			
			$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
				\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class
			);
			$persistenceManager->persistAll();
			
			// Send notification email to admin
			$adminEmailSent = false;
			if (!empty($myday->getPerson()) && !empty($myday->getEmail())) {
				$adminEmailSent = $this->sendAdminNotificationEmail($myday);
			}
			
			// Send confirmation to user (optional)
			$userEmailSent = false;
			if (!empty($myday->getEmail())) {
				$userEmailSent = $this->sendUserBookingConfirmation($myday);
			}
			
			// Return JSON response
			header('Content-Type: application/json');
			echo json_encode([
				'success' => true, 
				'message' => 'Booking successful',
				'adminEmailSent' => $adminEmailSent,
				'userEmailSent' => $userEmailSent
			]);
			exit;
		} catch (\Exception $e) {
			header('Content-Type: application/json');
			echo json_encode([
				'success' => false, 
				'message' => 'Booking error: ' . $e->getMessage()
			]);
			exit;
		}
	}
	
	// Error response
	header('Content-Type: application/json');
	echo json_encode(['success' => false, 'message' => 'No data provided']);
	exit;
}

/**
 * Send notification email to admin about new booking
 *
 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
 * @return bool
 */
protected function sendAdminNotificationEmail(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday): bool
{
	try {
		$mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
		
		$mymonth = $myday->getMymonth();
		$monthName = $this->getGermanMonthName((int)$mymonth->getMonth());
		$year = $mymonth->getYear();
		$dayName = $myday->getDayname();
		$timeSlot = $myday->getTimeslot();
		$timeSlotEnd = $myday->getTimeslotend();
		$person = $myday->getPerson();
		$email = $myday->getEmail();
		$topic = $myday->getTopic();
		
		$adminEmail = 'plpetkov@abv.bg';
		$subject = 'Neue Terminbuchung - ' . $dayName . '. ' . $monthName . ' ' . $year;
		
		$body = '
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<style>
		body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
		.container { max-width: 600px; margin: 0 auto; padding: 20px; }
		.header { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
		.content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
		.booking-details { background: white; padding: 20px; border-left: 4px solid #f39c12; margin: 20px 0; }
		.detail-row { padding: 10px 0; border-bottom: 1px solid #eee; }
		.detail-label { font-weight: bold; color: #666; display: inline-block; width: 120px; }
		.detail-value { color: #333; }
		.footer { text-align: center; margin-top: 20px; color: #999; font-size: 12px; }
		.pending-badge { background: #f39c12; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block; margin: 20px 0; font-weight: bold; }
		.action-button { display: inline-block; margin: 10px 5px; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
	</style>
</head>
<body>
	<div class="container">
		<div class="header">
			<h1>⏱ Neue Terminbuchung</h1>
		</div>
		<div class="content">
			<p>Es wurde ein neuer Termin gebucht, der auf Ihre Bestätigung wartet.</p>
			<div class="pending-badge">WARTET AUF BESTÄTIGUNG</div>
			<div class="booking-details">
				<h3 style="margin-top: 0; color: #f39c12;">Buchungsdetails</h3>
				<div class="detail-row">
					<span class="detail-label">Datum:</span>
					<span class="detail-value">' . $dayName . '. ' . $monthName . ' ' . $year . '</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Uhrzeit:</span>
					<span class="detail-value">' . $timeSlot . ($timeSlotEnd ? ' - ' . $timeSlotEnd : '') . '</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Name:</span>
					<span class="detail-value"><strong>' . htmlspecialchars($person) . '</strong></span>
				</div>
				<div class="detail-row">
					<span class="detail-label">E-Mail:</span>
					<span class="detail-value"><a href="mailto:' . htmlspecialchars($email) . '">' . htmlspecialchars($email) . '</a></span>
				</div>
				' . ($topic ? '
				<div class="detail-row">
					<span class="detail-label">Thema:</span>
					<span class="detail-value">' . htmlspecialchars($topic) . '</span>
				</div>
				' : '') . '
			</div>
			<p style="text-align: center; margin-top: 30px;">
				<a href="https://upgrade.panacea.de/appointment-admin" class="action-button">
					→ Termin bestätigen
				</a>
			</p>
			<p style="color: #666; font-size: 14px; margin-top: 30px;">
				Bitte bestätigen Sie den Termin im Admin-Bereich. Der Kunde wird automatisch per E-Mail benachrichtigt.
			</p>
		</div>
		<div class="footer">
			<p>Diese E-Mail wurde automatisch generiert.</p>
		</div>
	</div>
</body>
</html>';
		
		$plainText = 
			"NEUE TERMINBUCHUNG\n\n" .
			"Es wurde ein neuer Termin gebucht, der auf Ihre Bestätigung wartet.\n\n" .
			"BUCHUNGSDETAILS:\n" .
			"---------------\n" .
			"Datum: " . $dayName . ". " . $monthName . " " . $year . "\n" .
			"Uhrzeit: " . $timeSlot . ($timeSlotEnd ? " - " . $timeSlotEnd : "") . "\n" .
			"Name: " . $person . "\n" .
			"E-Mail: " . $email . "\n" .
			($topic ? "Thema: " . $topic . "\n" : "") .
			"\n" .
			"Bitte bestätigen Sie den Termin im Admin-Bereich.\n\n" .
			"Admin-Link: https://upgrade.panacea.de/appointment-admin\n\n" .
			"---\n" .
			"Diese E-Mail wurde automatisch generiert.";
		
		$fromAddress = new \Symfony\Component\Mime\Address('noreply@panacea.de', 'Panacea Booking System');
		$toAddress = new \Symfony\Component\Mime\Address($adminEmail, 'Administrator');
		$replyToAddress = new \Symfony\Component\Mime\Address($email, $person);
		
		$mail
			->from($fromAddress)
			->to($toAddress)
			->replyTo($replyToAddress)
			->subject($subject)
			->html($body)
			->text($plainText);
		
		$mail->send();
		return true;
		
	} catch (\Exception $e) {
		$logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
		$logger->error('Failed to send admin notification email', [
			'error' => $e->getMessage()
		]);
		return false;
	}
}

/**
 * Send booking confirmation to user (pending admin approval)
 *
 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
 * @return bool
 */
protected function sendUserBookingConfirmation(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday): bool
{
	try {
		$mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
		
		$mymonth = $myday->getMymonth();
		$monthName = $this->getGermanMonthName((int)$mymonth->getMonth());
		$year = $mymonth->getYear();
		$dayName = $myday->getDayname();
		$timeSlot = $myday->getTimeslot();
		$timeSlotEnd = $myday->getTimeslotend();
		$person = $myday->getPerson();
		$email = $myday->getEmail();
		$topic = $myday->getTopic();
		
		$subject = 'Terminanfrage erhalten - ' . $dayName . '. ' . $monthName . ' ' . $year;
		
		$body = '
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<style>
		body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
		.container { max-width: 600px; margin: 0 auto; padding: 20px; }
		.header { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
		.content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
		.booking-details { background: white; padding: 20px; border-left: 4px solid #3498db; margin: 20px 0; }
		.detail-row { padding: 10px 0; border-bottom: 1px solid #eee; }
		.detail-label { font-weight: bold; color: #666; display: inline-block; width: 120px; }
		.detail-value { color: #333; }
		.footer { text-align: center; margin-top: 20px; color: #999; font-size: 12px; }
		.info-box { background: #e8f4f8; padding: 15px; border-radius: 5px; margin: 20px 0; }
	</style>
</head>
<body>
	<div class="container">
		<div class="header">
			<h1>📅 Terminanfrage erhalten</h1>
		</div>
		<div class="content">
			<p>Liebe/r ' . htmlspecialchars($person) . ',</p>
			<p>Vielen Dank für Ihre Terminanfrage!</p>
			<div class="booking-details">
				<h3 style="margin-top: 0; color: #3498db;">Ihre Anfrage</h3>
				<div class="detail-row">
					<span class="detail-label">Datum:</span>
					<span class="detail-value">' . $dayName . '. ' . $monthName . ' ' . $year . '</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Uhrzeit:</span>
					<span class="detail-value">' . $timeSlot . ($timeSlotEnd ? ' - ' . $timeSlotEnd : '') . '</span>
				</div>
				<div class="detail-row">
					<span class="detail-label">Name:</span>
					<span class="detail-value">' . htmlspecialchars($person) . '</span>
				</div>
				' . ($topic ? '
				<div class="detail-row">
					<span class="detail-label">Thema:</span>
					<span class="detail-value">' . htmlspecialchars($topic) . '</span>
				</div>
				' : '') . '
			</div>
			<div class="info-box">
				<strong>ℹ️ Wichtig:</strong> Ihre Terminanfrage wurde an uns weitergeleitet und wartet auf Bestätigung. 
				Sie erhalten eine separate E-Mail, sobald Ihr Termin bestätigt wurde.
			</div>
			<p>Bei Fragen können Sie uns gerne kontaktieren.</p>
		</div>
		<div class="footer">
			<p>Diese E-Mail wurde automatisch generiert.</p>
		</div>
	</div>
</body>
</html>';
		
		$plainText = 
			"TERMINANFRAGE ERHALTEN\n\n" .
			"Liebe/r " . $person . ",\n\n" .
			"Vielen Dank für Ihre Terminanfrage!\n\n" .
			"IHRE ANFRAGE:\n" .
			"---------------\n" .
			"Datum: " . $dayName . ". " . $monthName . " " . $year . "\n" .
			"Uhrzeit: " . $timeSlot . ($timeSlotEnd ? " - " . $timeSlotEnd : "") . "\n" .
			"Name: " . $person . "\n" .
			($topic ? "Thema: " . $topic . "\n" : "") .
			"\n" .
			"WICHTIG: Ihre Terminanfrage wurde an uns weitergeleitet und wartet auf Bestätigung.\n" .
			"Sie erhalten eine separate E-Mail, sobald Ihr Termin bestätigt wurde.\n\n" .
			"Bei Fragen können Sie uns gerne kontaktieren.\n\n" .
			"---\n" .
			"Diese E-Mail wurde automatisch generiert.";
		
		$fromAddress = new \Symfony\Component\Mime\Address('info@panacea.de', 'Panacea Terminverwaltung');
		$toAddress = new \Symfony\Component\Mime\Address($email, $person);
		
		$mail
			->from($fromAddress)
			->to($toAddress)
			->subject($subject)
			->html($body)
			->text($plainText);
		
		$mail->send();
		return true;
		
	} catch (\Exception $e) {
		$logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
		$logger->error('Failed to send user booking confirmation', [
			'email' => $myday->getEmail(),
			'error' => $e->getMessage()
		]);
		return false;
	}
}

	/**
	 * Get admin edit URL for the appointment
	 *
	 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
	 * @return string
	 */
	protected function getAdminEditUrl(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday): string
	{
		// Замени с правилния URL на твоята admin страница
		return 'https://upgrade.panacea.de/appointment-admin';
		
		// Или ако искаш директен линк към събитието:
		// return 'https://upgrade.panacea.de/appointment-admin#event-' . $myday->getUid();
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
	
	/**
	 * AJAX action to update event details
	 *
	 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
	 * @return void
	 */
	public function ajaxUpdateAction(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday)
	{
		// Get POST data from tx_monthlyschedule_monthlyschedule[data]
		$requestArguments = $this->request->getArguments();
		
		if (isset($requestArguments['data'])) {
			$data = $requestArguments['data'];
			
			// Store old confirm status BEFORE updating
			$wasConfirmed = $myday->getConfirm();
			
			// Update fields
			if (isset($data['person'])) {
				$myday->setPerson($data['person']);
			}
			
			if (isset($data['email'])) {
				$myday->setEmail($data['email']);
			}
			
			if (isset($data['topic'])) {
				$myday->setTopic($data['topic']);
			}
			
			// Update confirm (only for admins)
			if (isset($data['confirm'])) {
				$myday->setConfirm((bool)$data['confirm']);
			}
			
			// Save
			try {
				$this->mydayRepository->update($myday);
				
				$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
					\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class
				);
				$persistenceManager->persistAll();
				
				// Check if confirm changed from false to true
				$isNowConfirmed = $myday->getConfirm();
				$justConfirmed = !$wasConfirmed && $isNowConfirmed;
				
				// Send email if just confirmed and email exists
				$emailSent = false;
				if ($justConfirmed && !empty($myday->getEmail())) {
					$emailSent = $this->sendConfirmationEmail($myday);
				}
				
				// Return JSON response
				header('Content-Type: application/json');
				echo json_encode([
					'success' => true, 
					'message' => 'Updated successfully',
					'emailSent' => $emailSent
				]);
				exit;
			} catch (\Exception $e) {
				header('Content-Type: application/json');
				echo json_encode([
					'success' => false, 
					'message' => 'Database error: ' . $e->getMessage()
				]);
				exit;
			}
		}
		
		// Error response
		header('Content-Type: application/json');
		echo json_encode(['success' => false, 'message' => 'No data provided']);
		exit;
	}

	/**
	 * Send confirmation email to user
	 *
	 * @param \Mcplamen\Monthlyschedule\Domain\Model\Myday $myday
	 * @return bool
	 */
	protected function sendConfirmationEmail(\Mcplamen\Monthlyschedule\Domain\Model\Myday $myday): bool
	{
		try {
			// Create mail message
			$mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Mail\MailMessage::class);
			
			// Get appointment details
			$mymonth = $myday->getMymonth();
			$monthName = $this->getGermanMonthName((int)$mymonth->getMonth());
			$year = $mymonth->getYear();
			$dayName = $myday->getDayname();
			$timeSlot = $myday->getTimeslot();
			$timeSlotEnd = $myday->getTimeslotend();
			$person = $myday->getPerson();
			$topic = $myday->getTopic();
			$email = $myday->getEmail();
			
			// Email subject
			$subject = 'Terminbestätigung - ' . $dayName . '. ' . $monthName . ' ' . $year;
			
			// Email body (HTML)
			$body = '
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="UTF-8">
		<style>
			body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
			.container { max-width: 600px; margin: 0 auto; padding: 20px; }
			.header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
			.content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
			.appointment-details { background: white; padding: 20px; border-left: 4px solid #27ae60; margin: 20px 0; }
			.detail-row { padding: 10px 0; border-bottom: 1px solid #eee; }
			.detail-label { font-weight: bold; color: #666; display: inline-block; width: 120px; }
			.detail-value { color: #333; }
			.footer { text-align: center; margin-top: 20px; color: #999; font-size: 12px; }
			.confirmed-badge { background: #27ae60; color: white; padding: 10px 20px; border-radius: 5px; display: inline-block; margin: 20px 0; font-weight: bold; }
		</style>
	</head>
	<body>
		<div class="container">
			<div class="header">
				<h1>✓ Termin bestätigt</h1>
			</div>
			<div class="content">
				<p>Liebe/r ' . htmlspecialchars($person) . ',</p>
				
				<p>Ihr Termin wurde bestätigt!</p>
				
				<div class="confirmed-badge">BESTÄTIGT</div>
				
				<div class="appointment-details">
					<h3 style="margin-top: 0; color: #667eea;">Termindetails</h3>
					
					<div class="detail-row">
						<span class="detail-label">Datum:</span>
						<span class="detail-value">' . $dayName . '. ' . $monthName . ' ' . $year . '</span>
					</div>
					
					<div class="detail-row">
						<span class="detail-label">Uhrzeit:</span>
						<span class="detail-value">' . $timeSlot . ($timeSlotEnd ? ' - ' . $timeSlotEnd : '') . '</span>
					</div>
					
					<div class="detail-row">
						<span class="detail-label">Name:</span>
						<span class="detail-value">' . htmlspecialchars($person) . '</span>
					</div>
					
					' . ($topic ? '
					<div class="detail-row">
						<span class="detail-label">Thema:</span>
						<span class="detail-value">' . htmlspecialchars($topic) . '</span>
					</div>
					' : '') . '
				</div>
				
				<p>Wir freuen uns auf Ihren Besuch!</p>
				
				<p style="color: #666; font-size: 14px; margin-top: 30px;">
					Bei Fragen oder wenn Sie den Termin absagen müssen, kontaktieren Sie uns bitte.
				</p>
			</div>
			
			<div class="footer">
				<p>Diese E-Mail wurde automatisch generiert.</p>
			</div>
		</div>
	</body>
	</html>';
			
			// Plain text version
			$plainText = 
				"TERMIN BESTÄTIGT\n\n" .
				"Liebe/r " . $person . ",\n\n" .
				"Ihr Termin wurde bestätigt!\n\n" .
				"TERMINDETAILS:\n" .
				"---------------\n" .
				"Datum: " . $dayName . ". " . $monthName . " " . $year . "\n" .
				"Uhrzeit: " . $timeSlot . ($timeSlotEnd ? " - " . $timeSlotEnd : "") . "\n" .
				"Name: " . $person . "\n" .
				($topic ? "Thema: " . $topic . "\n" : "") .
				"\n" .
				"Wir freuen uns auf Ihren Besuch!\n\n" .
				"Bei Fragen oder wenn Sie den Termin absagen müssen, kontaktieren Sie uns bitte.\n\n" .
				"---\n" .
				"Diese E-Mail wurde automatisch generiert.";
			
			// ФИКСИРАНО: Използваме new Address() вместо масив
			$fromAddress = new \Symfony\Component\Mime\Address('info@panacea.de', 'Panacea Terminverwaltung');
			$toAddress = new \Symfony\Component\Mime\Address($email, $person);
			
			// Configure mail
			$mail
				->from($fromAddress)
				->to($toAddress)
				->subject($subject)
				->html($body)
				->text($plainText);
			
			// Send
			$mail->send();
			
			return true;
			
		} catch (\Exception $e) {
			// Log error
			$logger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)->getLogger(__CLASS__);
			$logger->error('Failed to send confirmation email', [
				'email' => $myday->getEmail(),
				'error' => $e->getMessage()
			]);
			
			return false;
		}
	}

	/**
	 * Get German month name
	 *
	 * @param int $month
	 * @return string
	 */
	protected function getGermanMonthName(int $month): string
	{
		$months = [
			1 => 'Januar', 2 => 'Februar', 3 => 'März', 4 => 'April',
			5 => 'Mai', 6 => 'Juni', 7 => 'Juli', 8 => 'August',
			9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Dezember'
		];
		
		return $months[$month] ?? 'Unknown';
	}
	
	/**
	 * Helper method to assign isAdmin to view
	 */
	protected function assignIsAdminToView()
	{
		if ($this->view) {
			$isAdmin = $this->isLoggedInFrontendUser();
			$this->view->assign('isAdmin', $isAdmin);
		}
	}
	
}