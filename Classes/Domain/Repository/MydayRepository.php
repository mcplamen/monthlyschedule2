<?php
declare(strict_types=1);

namespace Mcplamen\Monthlyschedule\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;




/**
 * Repository for Myday objects
 */
class MydayRepository extends Repository
{
    // Тук можеш да добавяш собствени методи за заявки,
    // например findByMymonth($mymonth) и др.
	/**
	 * Find all days by mymonth
	 *
	 * @param \Mcplamen\Monthlyschedule\Domain\Model\Mymonth $mymonth
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByMymonth($mymonth)
	{
		$query = $this->createQuery();
		$query->matching(
			$query->equals('mymonth', $mymonth)
		);
		$query->setOrderings(['dayname' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING]);
		return $query->execute();
	}
	
	public function findByMyMonthSorted(\Mcplamen\Monthlyschedule\Domain\Model\MyMonth $month)
	{
		$query = $this->createQuery();

		$query->matching(
			$query->equals('mymonth', $month)
		);

		$query->setOrderings([
			'daynumber' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
		]);

		return $query->execute();
	}

}
