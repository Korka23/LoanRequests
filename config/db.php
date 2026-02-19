<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => sprintf(
        'pgsql:host=%s;port=%s;dbname=%s',
        getenv('POSTGRES_HOST') ?: 'localhost',
        getenv('POSTGRES_PORT') ?: '5432',
        getenv('POSTGRES_DB') ?: 'loans'
    ),
    'username' => getenv('POSTGRES_USER') ?: 'user',
    'password' => getenv('POSTGRES_PASSWORD') ?: 'password',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
