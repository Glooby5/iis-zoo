<?php

namespace Test;

use Doctrine\ORM\Tools\SchemaValidator;
use Kdyby\Doctrine\EntityManager;
use Testbench\TCompiledContainer;
use Tester;
use Tester\Assert;

require __DIR__ . '/bootstrap.php';


class DatabaseTest extends Tester\TestCase
{
    use TCompiledContainer;

    /***
     * @var EntityManager
     */
    private $entityManager;

	function __construct()
	{
		$this->entityManager = $this->getService(EntityManager::class);
	}

	function testSomething()
	{
		Assert::true(TRUE);

		$validator = new SchemaValidator($this->entityManager);
        $errors = $validator->validateMapping();

        Assert::null($errors);

        if (count($errors) > 0) {
            echo implode("\n\n", $errors);
        }
	}
}


$test = new DatabaseTest();
$test->run();
