<?php
defined('TYPO3') || die();

// DEBUG - This will create a file if ext_localconf.php is loaded
file_put_contents('/tmp/ext_localconf_loaded.txt', date('Y-m-d H:i:s') . " - File loaded\n", FILE_APPEND);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Monthlyschedule',  // FIXED: Changed from 'Mcplamen.Monthlyschedule'
    'Monthlyschedule',
    [
        \Mcplamen\Monthlyschedule\Controller\MymonthController::class => 'index, list, show',
        \Mcplamen\Monthlyschedule\Controller\MydayController::class => 'index, list, show'
    ],
    [
        \Mcplamen\Monthlyschedule\Controller\MymonthController::class => 'new, create, edit, update, delete',
        \Mcplamen\Monthlyschedule\Controller\MydayController::class => 'new, create, edit, update, delete, select'
    ]
);

// DEBUG - Check if configurePlugin succeeded
file_put_contents('/tmp/ext_localconf_loaded.txt', "configurePlugin called\n", FILE_APPEND);
file_put_contents('/tmp/ext_localconf_loaded.txt', print_r($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['Monthlyschedule'] ?? 'NOT SET', true) . "\n", FILE_APPEND);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    'mod {
        wizards.newContentElement.wizardItems.plugins {
            elements {
                monthlyschedule {
                    iconIdentifier = monthlyschedule-plugin-monthlyschedule
                    title = LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_monthlyschedule.name
                    description = LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_monthlyschedule.description
                    tt_content_defValues {
                        CType = list
                        list_type = monthlyschedule_monthlyschedule
                    }
                }
            }
            show = *
        }
    }'
);