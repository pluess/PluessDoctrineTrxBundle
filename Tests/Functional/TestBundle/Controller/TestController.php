<?php

namespace Pluess\DoctrineTrxBundle\Tests\Functional\TestBundle\Controller;

use Pluess\DoctrineTrxBundle\Annotation\DoctrineTrx as Transaction;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/transaction")
 */
class TestController extends Controller
{

    /**
     * @Route("/not-wrapped", name = "not_wrapped")
     */
    public function notWrapped()
    {
        return new Response($this->getDoctrine()->getManager()->getConnection()->getTransactionNestingLevel());
    }

    /**
     * @Route("/wrapped", name = "wrapped")
     * @Transaction
     */
    public function wrappedAction()
    {
        return new Response($this->getDoctrine()->getManager()->getConnection()->getTransactionNestingLevel());
    }

    /**
     * @Route("/double-wrapped", name = "double_wrapped")
     * @Transaction
     */
    public function doubleWrappedAction()
    {
        return $this->get('http_kernel')->forward('TestBundle:Test:wrapped');
    }

    /**
     * @Route("/wrapped-args/{arg1}/{arg2}", name = "wrapped_with_arguments")
     * @Transaction
     */
    public function wrappedWithArgumentsAction($arg1, $arg2)
    {
        $level = $this->getDoctrine()->getManager()->getConnection()->getTransactionNestingLevel();
        return new Response("$level:$arg1:$arg2");
    }

}
