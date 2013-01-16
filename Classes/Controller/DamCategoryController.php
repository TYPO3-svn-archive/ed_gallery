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

/**
 * The gallery controller 
 */
class Tx_EdGallery_Controller_DamCategoryController extends Tx_EdGallery_Controller_AbstractController {

	/**
	 * Category show action
	 *
	 * @param Tx_EdDamcatsort_Domain_Model_AbstractDamCategory $category
	 * @return void
	 */
	public function showAction(Tx_EdDamcatsort_Domain_Model_AbstractDamCategory $category = null) {
			// tracking repositories in order to allow automatic cache clearing
		$this->trackingManager->trackRepositoryOnPage($this->damCategoryRepository);
		$this->trackingManager->trackRepositoryOnPage($this->mediaRepository);
		$this->trackingManager->trackRepositoryOnPage($this->damRepository);
		
		if (empty($category)) {
			$category = $this->damCategoryRepository->findByUid($this->getSetting('rootCategory'));
		}
		
		$categories = $this->damCategoryRepository->findByParentId($category); /* @var $categories Tx_Extbase_Persistence_QueryResult */
		
		$medias = $this->mediaRepository->findByCategory($category); /* @var $medias Tx_Extbase_Persistence_QueryResult */
		
		$this->view->assign('rootCategory', $category);
		$this->view->assign('categories', $categories);
		$this->view->assign('medias', $medias);
		$this->view->assign('hasResult', count($categories)>0 || count($medias)>0);

		$data = $this->configurationManager->getContentObject();
		$this->view->assign('data', $data);
	}
}

?>