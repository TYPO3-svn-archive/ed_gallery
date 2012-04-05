<?php

########################################################################
# Extension Manager/Repository config file for ext "ed_gallery".
#
# Auto generated 05-04-2012 19:47
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Essential Dots DAM gallery',
	'description' => 'Gallery based on dam and ed_damcatsort extensions.',
	'category' => 'plugin',
	'author' => 'Nikola Stojiljkovic',
	'author_email' => 'nikola.stojiljkovic@essentialdots.com',
	'shy' => '',
	'dependencies' => 'extbase,dam,ed_damcatsort,extbase_hijax',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.2.0',
	'constraints' => array(
		'depends' => array(
			'extbase' => '',
			'dam' => '',
			'ed_damcatsort' => '',
			'extbase_hijax' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'pmkshadowbox' => '',
		),
	),
	'_md5_values_when_last_written' => 'a:20:{s:9:"ChangeLog";s:4:"aa12";s:10:"README.txt";s:4:"ee2d";s:12:"ext_icon.gif";s:4:"b280";s:17:"ext_localconf.php";s:4:"3b1c";s:14:"ext_tables.php";s:4:"ed95";s:40:"Classes/Controller/GalleryController.php";s:4:"c630";s:48:"Classes/Domain/Repository/TemplateRepository.php";s:4:"3587";s:36:"Classes/ViewHelpers/IfViewHelper.php";s:4:"90ac";s:39:"Classes/ViewHelpers/ImageViewHelper.php";s:4:"9865";s:49:"Classes/ViewHelpers/Widget/PaginateViewHelper.php";s:4:"6438";s:60:"Classes/ViewHelpers/Widget/Controller/PaginateController.php";s:4:"f5e1";s:36:"Configuration/FlexForms/flexform.xml";s:4:"97b0";s:34:"Configuration/TypoScript/setup.txt";s:4:"2bd5";s:40:"Resources/Private/Language/locallang.xml";s:4:"819a";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"7e84";s:38:"Resources/Private/Layouts/Default.html";s:4:"6dc5";s:54:"Resources/Private/Templates/Default/Gallery/Index.html";s:4:"e697";s:60:"Resources/Private/Templates/Default/Gallery/Index/Pager.html";s:4:"246a";s:42:"Resources/Public/Images/empty-category.png";s:4:"7ab2";s:47:"Resources/Public/Thumbnails/gallery.default.png";s:4:"1b39";}',
	'suggests' => array(
	),
);

?>