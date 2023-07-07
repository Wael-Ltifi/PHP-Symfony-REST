<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;

// Create the database connection
$connectionParams = [
    'url' => 'mysql://root:@127.0.0.1:3306/lecon6.2?serverVersion=8&charset=utf8mb4',
];
$connection = DriverManager::getConnection($connectionParams);

// Create 10 dummy articles
for ($i = 1; $i <= 10; $i++) {
    $data = [
        'titre' => "Dummy Article $i",
        'contenu' => "This is the content of dummy article $i.",
        'auteur' => 'Dummy Author',
        'date_publication' => (new DateTime())->format('Y-m-d H:i:s'),
    ];

    $connection->insert('article', $data);
}

echo "10 dummy articles added to the database.\n";
