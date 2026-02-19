<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        getenv('POSTGRES_HOST') ?: 'localhost',
        getenv('POSTGRES_PORT') ?: '5432',
        getenv('POSTGRES_DB') ?: 'loan_request'
    ),
    'username' => getenv('POSTGRES_USER') ?: 'postgres',
    'password' => getenv('POSTGRES_PASSWORD') ?: 'postgres',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
