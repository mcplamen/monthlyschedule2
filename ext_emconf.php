<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Monthly Schedule',
    'description' => 'Simple schedule for appointments in the month',
    'category' => 'plugin',
    'author' => 'Plamen Petkov',
    'author_email' => 'mcplamen@gmail.com',
    'state' => 'beta',
    'clearCacheOnLoad' => 0,
    'version' => '1.11.5',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
