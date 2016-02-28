<?php

return new \Phalcon\Config(
    array(
        'database' => array(
            'dbname'    => getenv('DB_NAME') . '_dev',
        ),
        'application' => array(
            'domain'    => 'dev.' . getenv('APP_DOMAIN'),
        ),
        'debug' => true,
    )
);