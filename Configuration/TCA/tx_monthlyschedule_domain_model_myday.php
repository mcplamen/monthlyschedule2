<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday',
        'label' => 'dayname',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'dayname,timeslot,timeslotend,person,email,topic',
        'iconfile' => 'EXT:monthlyschedule/Resources/Public/Icons/tx_monthlyschedule_domain_model_myday.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'dayname, timeslot, timeslotend, confirm, person, email, topic, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_monthlyschedule_domain_model_myday',
                'foreign_table_where' => 'AND {#tx_monthlyschedule_domain_model_myday}.{#pid}=###CURRENT_PID### AND {#tx_monthlyschedule_domain_model_myday}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'dayname' => [
            'exclude' => true,
            'label' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.dayname',
            'description' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.dayname.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'timeslot' => [
            'exclude' => true,
            'label' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.timeslot',
            'description' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.timeslot.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'timeslotend' => [
            'exclude' => true,
            'label' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.timeslotend',
            'description' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.timeslotend.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'confirm' => [
            'exclude' => true,
            'label' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.confirm',
            'description' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.confirm.description',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                    ]
                ],
                'default' => 0,
            ]
        ],
        'person' => [
            'exclude' => true,
            'label' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.person',
            'description' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.person.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.email',
            'description' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.email.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'topic' => [
            'exclude' => true,
            'label' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.topic',
            'description' => 'LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_domain_model_myday.topic.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
    
        'mymonth' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
