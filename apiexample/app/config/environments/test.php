<?php

return new \Phalcon\Config(
    array(
        'database' => array(
            'dbname'    => getenv('DB_NAME') . '_test',
        ),
        'application' => array(
            'domain'    => 'test.' . getenv('APP_DOMAIN'),
        ),
    )
);