<?php

namespace AppBundle\Repository;

use AppBundle\Entity\LegislativeDistrictZone;
use Doctrine\ORM\EntityRepository;

class LegislativeDistrictZoneRepository extends EntityRepository
{
    public function findAllGrouped(): array
    {
        $zones = $this
            ->createQueryBuilder('dz')
            ->orderBy('dz.areaCode', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        /** @var $zone LegislativeDistrictZone */
        foreach ($zones as $zone) {
            $groupedZones[$zone->getAreaTypeLabel()][] = $zone;
        }

        return $groupedZones ?? [];
    }
}
