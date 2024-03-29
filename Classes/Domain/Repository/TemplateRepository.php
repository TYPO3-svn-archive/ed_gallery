<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) Nikola Stojiljkovic <nikola.stojiljkovic(at)essentialdots.com>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
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
	public function findAllPluginTemplates($config) {
		$ts = $this->loadTSfromConfig($config);
		$actionsData = $tsData = $ts['plugin.']['tx_edgallery.']['settings.']['switchableControllerActions.'];
		$selectedAction = $this->findSelectedAction($config['row']['pi_flexform']);
		$enabledTemplates = array();

		if ($selectedAction) {
			foreach ($actionsData as $action) {
				if ($action['value'] == $selectedAction) {
					$enabledTemplates = t3lib_div::trimExplode(',', $action['availableTemplates']);
					break;
				}
			}
		}

		return $this->findAllTemplates($enabledTemplates, $config);
	}

	/**
	 * Find all switchableControllerActions
	 * @param array $config
	 * @return array The config including the items for the dropdown
	 */
	public function findAllSCA($config) {
		$ts = $this->loadTSfromConfig($config);

		$tsData = $tsData = $ts['plugin.']['tx_edgallery.']['settings.']['switchableControllerActions.'];
		$result = array();
		foreach($tsData as $actionInfo) {
			$result[] = array(0 => $GLOBALS['LANG']->sL($actionInfo["name"]), 1 =>$actionInfo["value"], 2 => $actionInfo["thumbnail"]);
		}
		$config['items'] = array_merge($config['items'],$result);

		return $config;
	}

	/**
	 * Extract selected value from switchableControllerAction field of given XML data
	 * @param string $xml
	 * @return string null If not found, otherwise value of the selected action
	 */
	public function findSelectedAction($xml) {
		$result = null;
		$config = $this->parseTemplavoilaFlex($xml);

		if (isset($config['sDEF.']) && is_array($config['sDEF.']) && isset($config['sDEF.']['switchableControllerActions'])) {
			$result = $config['sDEF.']['switchableControllerActions'];
		}

		return $result;
	}

	/**
	 * Convert tx_templavoila_flex xml to array
	 * @param string $xml
	 * @return array
	 */
	protected function parseTemplavoilaFlex($xml) {
		$xmlArray = t3lib_div::xml2array($xml);
		$data = $xmlArray['data'];
		$result = array();
		$languagePointer = 'lDEF';
		$languagesByPrio = array('DEF', 'vDEF');

		foreach((array) $data as $sheet => $languages) {
			$flexFormValues = $this->getFlexFormConfigurationValues((array)$languages[$languagePointer], $languagesByPrio);
			if (count($flexFormValues)) {
				$result[$sheet . '.'] = $flexFormValues;
			}
		}

		return $result;
	}

	/**
	 *
	 * @param array $flexForm
	 * @param array $languagesByPrio
	 */
	protected function getFlexFormConfigurationValues(array $flexForm, array $languagesByPrio) {
		$elementPointer = 'el';

		$config = array();

		foreach ($flexForm as $key => $definition) {
			if (isset($definition[$elementPointer]) && is_array($definition[$elementPointer])) {
				foreach ($definition[$elementPointer] as $sectionContainer) {
					foreach ($sectionContainer as $sectionName => $sectionData) {
						if (isset($sectionData[$elementPointer]) && is_array($sectionData[$elementPointer])) {
							$config[$key . '.'][] = $this->getFlexFormConfigurationValues($sectionData[$elementPointer], $languagesByPrio);
						}
					}
				}
			} else {
				foreach ($languagesByPrio as $lang) {
					if ($definition['v'.$lang]) {
						$config[$key] = $definition['v'.$lang];
						break;
					}
				}
			}
		}

		return $config;
	}

	/**
	 * @param array $enabledTemplates
	 * @param array $config
	 * @return array The config including the items for the dropdown
	 */
	public function findAllTemplates($enabledTemplates, $config) {
		$ts = $this->loadTSfromConfig($config);

		$optionList = array();
		$tsData = $ts['plugin.']['tx_edgallery.']['settings.']['templates.'];

		foreach($tsData as $key=>$val) {
			if (in_array(substr_replace($key, '', -1), $enabledTemplates)) {
				$optionList[] = array(0 => $GLOBALS['LANG']->sL($val["name"]), 1 => $val["template"], 2 => $val["thumbnail"]);
			}
		}

		$config['items'] = array_merge($config['items'],$optionList);
		return $config;
	}

	/**
	 * Loads the TypoScript for a page specified in config
	 * @param unknown_type $config
	 */
	function loadTSfromConfig($config) {
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
		return $this->loadTS($pid);
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