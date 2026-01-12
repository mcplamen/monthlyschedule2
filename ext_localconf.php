<?php
defined('TYPO3') || die();

// Plugin 1 - Admin Management (original)
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Monthlyschedule',
    'Monthlyschedule',
    [
        \Mcplamen\Monthlyschedule\Controller\MymonthController::class => 'index, list, show, new, create, edit, update, delete',
        \Mcplamen\Monthlyschedule\Controller\MydayController::class => 'index, list, show, new, create, edit, update, delete, select, ajaxShow, ajaxUpdate, publicBook'
    ],
    [
        \Mcplamen\Monthlyschedule\Controller\MymonthController::class => 'index, create, new, edit, update, delete',
        \Mcplamen\Monthlyschedule\Controller\MydayController::class => 'index, create, new, edit, update, delete, select, ajaxShow, ajaxUpdate, publicBook'
    ]
);

// Plugin 2 - Public Booking (new)
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Monthlyschedule',
    'PublicBooking',
    [
        \Mcplamen\Monthlyschedule\Controller\MymonthController::class => 'publicIndex, ajaxShow, ajaxShowAction',
        \Mcplamen\Monthlyschedule\Controller\MydayController::class => 'ajaxShow, publicBook'
    ],
    [
        \Mcplamen\Monthlyschedule\Controller\MymonthController::class => 'publicIndex, ajaxShow, ajaxShowAction',
        \Mcplamen\Monthlyschedule\Controller\MydayController::class => 'ajaxShow, publicBook'
    ]
);

// PageTS Config
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
                publicbooking {
                    iconIdentifier = monthlyschedule-plugin-publicbooking
                    title = LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_publicbooking.name
                    description = LLL:EXT:monthlyschedule/Resources/Private/Language/locallang_db.xlf:tx_monthlyschedule_publicbooking.description
                    tt_content_defValues {
                        CType = list
                        list_type = monthlyschedule_publicbooking
                    }
                }
            }
            show = *
        }
    }'
);