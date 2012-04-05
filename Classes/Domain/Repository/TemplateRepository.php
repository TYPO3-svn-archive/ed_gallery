<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Nikola Stojiljkovic <nikola.stojiljkovic(at)essentialdots.com>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_t3lib."class.t3lib_page.php");
require_once(PATH_t3lib."class.t3lib_tsparser_ext.php");

class Tx_EdGallery_Domain_Repository_TemplateRepository {

	/**
	 * @param array $config
	 * @return array The config including the items for the dropdown
	 */
	public function findAllGalleryTemplates($config) {
		return $this->findAllTemplates('gallery', $config);
	}	
	
	/**
	 * @param string $plugin
	 * @param array $config
	 * @return array The config including the items for the dropdown
	 */
	public function findAllTemplates($plugin, $config) {
		$pid = $config['row']['pid'];
		if($pid < 0) {
			$contentUid = str_replace('-','',$pid);
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pid','tt_content','uid='.$contentUid);
			if($res) {
				$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				$pid = $row['pid'];
				$GLOBALS['TYPO3_DB']->sql_free_result($res);
			}
		}
		$ts = $this->loadTS($pid);
		$optionList = array();
	    $tsData = $ts['plugin.']['tx_edgallery.']['settings.']['templates.'][$plugin.'.'];
	    
		foreach($tsData as $key=>$val) {
			$optionList[] = array(0 => $GLOBALS['LANG']->sL($val["name"]), 1 => $val["template"], 2 => $val["thumbnail"]);	
		}
		
		$config['items'] = array_merge($config['items'],$optionList);
		return $config;
	}		
	
	/**
	 * Loads the TypoScript for a page
	 *
	 * @param int $pageUid
	 * @return array The TypoScript setup
	 */
	function loadTS($pageUid) {
		$sysPageObj = t3lib_div::makeInstance('t3lib_pageSelect');
		$rootLine = $sysPageObj->getRootLine($pageUid);
		$TSObj = t3lib_div::makeInstance('t3lib_tsparser_ext');
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();
		return $TSObj->setup;
	}	
}