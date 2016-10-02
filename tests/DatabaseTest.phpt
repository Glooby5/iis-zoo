<?php

namespace Test;

use Doctrine\ORM\Tools\SchemaValidator;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Tester;
use Tester\Assert;

$container = require __DIR__ . '/bootstrap.php';


class DatabaseTest extends Tester\TestCase
{
	private $container;


	function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}

	function testSomething()
	{
		Assert::true(TRUE);

		$em = $this->container->getByType(EntityManager::class);

		$validator = new SchemaValidator($em);
        $errors = $validator->validateMapping();

        if (count($errors) > 0) {
            echo implode("\n\n", $errors);
        }
	}
}


$test = new DatabaseTest($container);
$test->run();
