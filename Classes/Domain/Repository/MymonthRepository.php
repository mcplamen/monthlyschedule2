<?php

declare(strict_types=1);

namespace Mcplamen\Monthlyschedule\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;


/**
 * This file is part of the "Monthly Schedule" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 Plamen Petkov <mcplamen@gmail.com>, NR OOD
 */

/**
 * The repository for Mymonths
 */
class MymonthRepository extends Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = ['sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING];
	
	/**
	 * Find mymonth by month and year
	 *
	 * @param int $month
	 * @param int $year
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByMonthAndYear($month, $year)
	{
		$query = $this->createQuery();
		$query->matching(
			$query->logicalAnd(
				$query->equals('month', $month),
				$query->equals('year', $year)
			)
		);
		return $query->execute();
	}
}
