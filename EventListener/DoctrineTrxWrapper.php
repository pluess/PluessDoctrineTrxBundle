<?php

namespace Pluess\DoctrineTrxBundle\EventListener;

use Doctrine\ORM\EntityManager;

/**
 * Takes the place of the original action and wraps it inside a transaction.
 */
class DoctrineTrxWrapper
{

    /** @var EntityManager */
    private $entityManager;

    /** @var ActionEventListener */
    private $eventListener;

    private $origController;

    public function __construct(ActionEventListener $eventListener, EntityManager $em, $controller)
    {
        $this->origController = $controller;
        $this->eventListener = $eventListener;
        $this->entityManager = $em;
    }

    /**
     * @return EntityManager
     */
    private function getManager()
    {
        return $this->entityManager;
    }

    public function getOrigController()
    {
        return $this->origController;
    }

    public function wrappedExecution()
    {
        $response = null;
        try {
            $this->getManager()->beginTransaction();

            $response = call_user_func_array($this->origController, func_get_args());

            $this->getManager()->flush();
            $this->getManager()->commit();
            $this->eventListener->done();
        } catch (\Exception $e) {
            // @codeCoverageIgnoreStart
            $this->getManager()->rollback();
            $this->eventListener->done();
            throw $e;
            // @codeCoverageIgnoreEnd
        }

        return $response;
    }

}
