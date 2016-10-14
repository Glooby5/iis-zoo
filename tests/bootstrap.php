<?php

require __DIR__ . '/../vendor/autoload.php';

//Tester\Environment::setup();
//
//$configurator = new Nette\Configurator;
//$configurator->setDebugMode(FALSE);
////$configurator->setTempDirectory(__DIR__ . '/../temp');
////$configurator->setTempDirectory('/home/vagrant/temp');
//$configurator->createRobotLoader()
//	->addDirectory(__DIR__ . '/../app')
//	->register();
//
//$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
//$configurator->addConfig(__DIR__ . '/../app/config/config.local.neon');
//
//$configurator->addParameters([
//    'appDir' => __DIR__ . '/../app',
//]);
//return $configurator->createContainer();


require __DIR__ . '/../vendor/autoload.php';

Testbench\Bootstrap::setup('/home/vagrant/_temp', function (\Nette\Configurator $configurator) {
    $configurator->createRobotLoader()->addDirectory([
        __DIR__ . '/../app',
    ])->register();

    $configurator->addParameters([
        'appDir' => __DIR__ . '/../app',
    ]);

    $configurator->addConfig(__DIR__ . '/../app/config/config.neon');
    $configurator->addConfig(__DIR__ . '/../app/config/config.local.neon');
//    $configurator->addConfig(__DIR__ . '/tests.neon');
});
