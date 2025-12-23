<?php
defined('TYPO3') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_monthlyschedule_domain_model_mymonth', 
    'EXT:monthlyschedule/Resources/Private/Language/locallang_csh_tx_monthlyschedule_domain_model_mymonth.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_monthlyschedule_domain_model_mymonth');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_monthlyschedule_domain_model_myday', 
    'EXT:monthlyschedule/Resources/Private/Language/locallang_csh_tx_monthlyschedule_domain_model_myday.xlf'
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_monthlyschedule_domain_model_myday');