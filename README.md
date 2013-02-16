PluessDoctrineTrxBundle
=======================

Adds container managed transactions for doctrine base persistence in controller actions.

#Usage

All you need to do to get an action covered by a transaction, is to add the annotation:

	<?php
	
	use Pluess\DoctrineTrxBundle\Annotation\DoctrineTrx as ContainerTransaction;
	
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
	
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	
	use Symfony\Component\HttpFoundation\Request;
	
	/**
	 * @Route("/my_route")
	 */
	class MyController extends Controller
	{
	
	    /**
	     * @Route("/do-something", name = "do_something")
	     * @ContainerTransaction
	     */
	    public function applyAction(Request $request)
	    {
	    	$em = $this->getDoctrine()->getManager()
	    	
	    	// Do whatever you want to do via doctrine.
	    	// The annotation makes sure it's properly covered by a transaction.
	    }
	    
	}

#Installation

1. Add this to your `composer.json` and do a `composer update`:
  
	   "pluess/doctrine-trx-bundle": "dev-master"

2. Add this line to your `AppKernel.php`:

       new Pluess\DoctrineTrxBundle\PluessDoctrineTrxBundle()
       
#Credits

There are a lot of people I'm learning from. The main learnings for this bundle are coming from

* Matthias Noback: Thanks for the [excellent post about annotations and event listeners](http://php-and-symfony.matthiasnoback.nl/2012/12/prevent-controller-execution-with-annotations-and-return-a-custom-response/). This was kind of a door opener for this bundle.
* Johannes Schmitt: Thanks for showing how to write standalone functional test for symfony.

#Support
Feel free to open issues if you have problems with the bundle.

