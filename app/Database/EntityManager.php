<?php

namespace App\Database;

use Doctrine\ORM\EntityManager;

class EntityManagerFactory
{
    private static ?EntityManager $entityManager = null;
    
    public static function create(): EntityManager
    {
        if (self::$entityManager === null) {
            self::$entityManager = require __DIR__ . '/../../config/doctrine.php';
        }
        
        return self::$entityManager;
    }
}