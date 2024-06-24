<?php
require('services/DatabaseService.php');

$configService = new ConfigService();
$dbService = new DatabaseService($configService);
$pdo = $dbService->getConnection();

try {
    // Call migration script
    require 'migration.php';
    // Call seeder script
    require 'seeder.php';

    echo "Database initialization completed!\n";
} catch (PDOException $e) {
    die("Error during database initialization: " . $e->getMessage());
}