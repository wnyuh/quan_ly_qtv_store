<?php

require_once "vendor/autoload.php";

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

// Retrieve the entity manager
$entityManager = require __DIR__ . '/config/doctrine.php';

ConsoleRunner::run(
    new SingleManagerProvider($entityManager)
);