<?php

return new \Phalcon\Config(
    array(
        'database' => array(
            'dbname'    => getenv('DB_NAME') . '_production',
        ),
        'debug' => false,
    )
);