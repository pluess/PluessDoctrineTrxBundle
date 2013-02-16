<?php

namespace Pluess\DoctrineTrxBundle\EventListener;

use Pluess\DoctrineTrxBundle\Annotation\DoctrineTrx;;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\Common\Annotations\Reader;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Listens for controller action calls. All actions covered with @DoctrineTrx are called via DocrineTrxWrapper.
 * This makes sure the action is always executed within a doctrine transaction and it's rolled back in case of an
 * exception.
 *
 * @see DoctrineTrWrapper
 * @see Pluess\DoctrineTrxBundle\Annotation\DoctrineTrx
 */
class ActionEventListener
{
    /** @var Reader */
    private $annotationReader;

    /** @var TrxWrapperFactory */
    private $factory;

    /** @var DoctrineTrxWrapper */
    private $wrapper = null;

    public function __construct(Reader $annotationReader, TrxWrapperFactory $factory)
    {
        $this->annotationReader = $annotationReader;
        $this->factory = $factory;
    }

    public function onControllerEvent(FilterControllerEvent $event)
    {
        if ($this->wrapper) {
            // don't start nested transactions
            return;
        }

        $cArray = $event->getController();
        if (count($cArray)==2 && is_object($cArray[0]) && is_string($cArray[1])) {
            $controller = $cArray[0];
            $methodName = $cArray[1];
            $className = ClassUtils::getClass($controller);

            $reflectionClass = new \ReflectionClass($className);
            $reflectionMethod = $reflectionClass->getMethod($methodName);

            $allAnnotations = $this->annotationReader->getMethodAnnotations($reflectionMethod);

            $trxAnnotations = array_filter($allAnnotations, function($annotation) {
                return $annotation instanceof DoctrineTrx;
            });

            if (count($trxAnnotations)>0) {
                $this->wrapper = $this->factory->createTransactionWrapper($this, $event->getController());
                $event->setController(array($this->wrapper, 'wrappedExecution'));
            }
        } else {
            throw new \UnexpectedValueException($event);
        }
    }

    public function done()
    {
        $this->wrapper = null;
    }
 }
