<?php

namespace Pluess\DoctrineTrxBundle\EventListener;

use Doctrine\ORM\EntityManager;

/**
 * Responsible for the life cycle of DoctrineTrWrapper instances.
 *
 * @see TransactionWrapper
 */
class TrxWrapperFactory
{

    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    private function getManager()
    {
        return $this->entityManager;
    }

    public function createTransactionWrapper(ActionEventListener $eventListener, $controller)
    {
        return new DoctrineTrxWrapper($eventListener, $this->getManager(), $controller);
    }


}
