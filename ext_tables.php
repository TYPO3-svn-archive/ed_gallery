<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionName = t3lib_div::underscoredToUpperCamelCase($_EXTKEY);

/**
 * Registers a Plugin to be listed in the Backend. You also have to configure the Dispatcher in ext_localconf.php.
 */
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,// The extension name (in UpperCamelCase) or the extension key (in lower_underscore)
	'Pi1', // A unique name of the plugin in UpperCamelCase
	'Media' // A title shown in the backend dropdown field
);

$pluginSignature = strtolower($extensionName) . '_pi1';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'select_key,pages,recursive';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform,recursive';
t3lib_extMgm::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform.xml');

$tempColumns = array (
	'tstamp' => array (		
		'label' => 'LLL:EXT:dam/locallang_db.xml:tx_dam_item.date_mod',
		'exclude' => '0',
		'l10n_mode' => 'exclude',
		'l10n_display' => 'defaultAsReadonly',
		'config' => array(
			'type' => 'input',
			'size' => '8',
			'max' => '20',
			'eval' => 'date',
			'default' => '0',
		)
	),
	'crdate' => array (		
		'label' => 'LLL:EXT:dam/locallang_db.xml:tx_dam_item.date_cr',
		'exclude' => '0',
		'l10n_mode' => 'exclude',
		'l10n_display' => 'defaultAsReadonly',
		'config' => array(
			'type' => 'input',
			'size' => '8',
			'max' => '20',
			'eval' => 'date',
			'default' => '0',
		)
	),
);

t3lib_div::loadTCA('tx_dam_cat');
t3lib_extMgm::addTCAcolumns('tx_dam_cat',$tempColumns,1);
$TCA['tx_dam_cat']['ctrl']['dividers2tabs'] = 1;
t3lib_extMgm::addToAllTCAtypes('tx_dam_cat','--div--;LLL:EXT:ed_gallery/Resources/Private/Language/locallang_db.xml:tx_dam_cat.date_theader, crdate;;;;1-1-1, tstamp;;;;1-1-1', '', '');

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Essential Dots gallery configuration');

?>