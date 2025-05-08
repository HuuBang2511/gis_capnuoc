<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;dbname=gis_capnuoc;port=5433',
    'username' => 'postgres',
    'password' => 'khongbiet',
    'charset' => 'utf8',

    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
