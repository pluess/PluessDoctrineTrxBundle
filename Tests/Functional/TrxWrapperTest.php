<?php

namespace Pluess\DoctrineTrxBundle\Tests\EventListener;

use Doctrine\Common\Annotations\AnnotationRegistry;

use Pluess\DoctrineTrxBundle\Tests\Functional\BaseTestCase;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class TrxWrapperTest extends BaseTestCase
{
    protected function setUp()
    {
        AnnotationRegistry::registerAutoloadNamespaces(array('Pluess\DoctrineTrxBundle\Annotation\DoctrineTrx' => __DIR__ . '/../../../../'));
    }

    public function testNotWrapped()
    {
        /** @var $client Client */
        $client = $this->createClient();

        /** @var $router Router */
        $router = $client->getContainer()->get('router');

        $client->request('GET', $router->generate('not_wrapped'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $client->getResponse()->getContent());
    }

    public function testWrapped()
    {
        /** @var $client Client */
        $client = $this->createClient();

        /** @var $router Router */
        $router = $client->getContainer()->get('router');

        $client->request('GET', $router->generate('wrapped'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $client->getResponse()->getContent());
    }

    public function testDoubleWrapped()
    {
        /** @var $client Client */
        $client = $this->createClient();

        /** @var $router Router */
        $router = $client->getContainer()->get('router');

        $client->request('GET', $router->generate('double_wrapped'));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $client->getResponse()->getContent());
    }

    public function testWrappedWithArgument()
    {
        /** @var $client Client */
        $client = $this->createClient();

        /** @var $router Router */
        $router = $client->getContainer()->get('router');

        $client->request('GET', '/transaction/wrapped-args/parama/paramb');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('1:parama:paramb', $client->getResponse()->getContent());
    }

}
