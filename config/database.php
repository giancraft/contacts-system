<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once __DIR__ . '/../vendor/autoload.php';

function createEntityManager(): EntityManager
{
    $config = ORMSetup::createAttributeMetadataConfiguration(
        paths: [__DIR__ . '/../src/Entity'],
        isDevMode: true
    );

    $connection = DriverManager::getConnection([
        'driver'   => 'pdo_mysql',
        'host'     => $_ENV['DB_HOST']   ?? getenv('DB_HOST')   ?: 'localhost',
        'port'     => (int)($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 3306),
        'dbname'   => $_ENV['DB_NAME']   ?? getenv('DB_NAME')   ?: 'contacts_db',
        'user'     => $_ENV['DB_USER']   ?? getenv('DB_USER')   ?: 'contacts_user',
        'password' => $_ENV['DB_PASS']   ?? getenv('DB_PASS')   ?: 'contacts_pass',
        'charset'  => 'utf8mb4',
    ], $config);

    return new EntityManager($connection, $config);
}
