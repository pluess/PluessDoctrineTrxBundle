<?php

namespace Pluess\DoctrineTrxBundle\HttpKernel;

use Pluess\DoctrineTrxBundle\EventListener\DoctrineTrxWrapper;

use JMS\DiExtraBundle\HttpKernel\ControllerResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * Synfony is relying on the signature of the action function and evaluates it using reflection. Since the
 * DoctrineTrxWrapper is changing the signature of the function this breaks the way arguments are passed
 * to actions.
 *
 * We solve this by passing the original controller class to ControllerResolver::getArguments(). Once the
 * reflection part is done their, the standard parameter passing works fine again.
 */
class TrxControllerResolver extends ControllerResolver
{
    public function getArguments(Request $request, $controller)
    {
        if (!($controller[0] instanceof DoctrineTrxWrapper)) {
            return parent::getArguments($request, $controller);
        } else {
            return parent::getArguments($request, $controller[0]->getOrigController());
        }
    }

}
