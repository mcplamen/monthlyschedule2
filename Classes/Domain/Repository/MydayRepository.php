<?php
namespace Mcplamen\Monthlyschedule\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

class MydayRepository extends Repository
{
    /**
     * Find all days for a specific month
     *
     * @param int $mymonthUid
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByMymonth($mymonthUid)
    {
		
        $query = $this->createQuery();
        $query->matching(
            $query->equals('mymonth', $mymonthUid)
        );
        $query->setOrderings(['dayname' => QueryInterface::ORDER_ASCENDING]);
        return $query->execute();
    }
}