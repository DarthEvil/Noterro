<?php

define("DEBUG", false);

$database_config = array(
    'server'    => 'localhost',
    'port'      => 27017,
    'database'  => 'notes',
    'collections' => array(
        'messages'  => 'notes',
        'tags'      => 'tags',
    ),
);

$mailbox_config = array(
    'imap'      => array(
        'host' => 'imap.gmail.com',
        'port' => 993
    ),
    'auth' => array(
        'login'     => '',
        'password'  => '',
    ),
    'folders'   => array('My notes'),
);

$base_config = array(
    'separater'     => '!',
    'key_phrase'    => 'заметка'
);
