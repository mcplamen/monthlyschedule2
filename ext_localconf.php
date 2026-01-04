<?php
defined('TYPO3') || die();

(static function() {
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
		'Monthlyschedule',
		'Monthlyschedule',
		[
			// ALL allowed actions
			\Mcplamen\Monthlyschedule\Controller\MymonthController::class => 'index, list, show, new, create, edit, update, delete',
			\Mcplamen\Monthlyschedule\Controller\MydayController::class => 'index, list, show, new, create, edit, update, delete, select, ajaxShow, ajaxUpdate, publicBook'
		],
		[
			// Actions that should NOT be cached
			\Mcplamen\Monthlyschedule\Controller\MymonthController::class => 'index, new, create, edit, update, delete',
			\Mcplamen\Monthlyschedule\Controller\MydayController::class => 'index, new, create, edit, update, delete, select, ajaxShow, ajaxUpdate, publicBook'
		]
	);

    // wizards
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
})();
