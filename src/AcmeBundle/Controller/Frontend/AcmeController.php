<?php

namespace AcmeBundle\Controller\Frontend;

use AcmeBundle\Document\Computer;
use AcmeBundle\Document\Processor;
use MongoDB\BSON\ObjectID;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Tequila\MongoDB\ODM\DocumentManager;

class AcmeController extends Controller
{
    public function indexAction()
    {

    }

    public function addAction()
    {
        $computer = new Computer();
        $processor = new Processor();
        $processor->setSpeed('1.1Ghz');
        $processor->setType('intel pentium 4');
        $computer->setCpu($processor);
        $computer->setGpu('nvidia gtx500');
        $computer->setPowerSupply(400);
        $computer->setCreatedAt(new \DateTime());
        /** @var DocumentManager $dm */
        $dm = $this->container->get('tequila_mongodb.dm');
        $dm->addToBulk($computer);
        $dm->flush();

        dump($computer);
        exit;
    }

    public function updateAction(Request $request, $id)
    {
        /** @var DocumentManager $dm */
        $dm = $this->container->get('tequila_mongodb.dm');
        /** @var Computer $computer */
        $computer = $dm->getRepository(Computer::class)->findOneById(new ObjectID($id));
        $processor = new Processor();
        $processor->setSpeed('1.2Ghz');
        $processor->setType('intel core i3');
        $computer->setCpu($processor);
        $dm->flush();

        dump($computer);
        exit;
    }
}
