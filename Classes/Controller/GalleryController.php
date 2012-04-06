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
class Tx_EdGallery_Controller_GalleryController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_EdDamcatsort_Domain_Repository_DamRepository
	 */
	protected $damRepository;

	/**
	 * @var Tx_EdDamcatsort_Domain_Repository_DamCategoryRepository
	 */
	protected $damCategoryRepository;
	
	/**
	 * @var Tx_EdDamcatsort_Domain_Repository_MediaRepository
	 */
	protected $mediaRepository;
	
	/**
	 * Dependency injection of the DAM Repository
 	 *
	 * @param Tx_EdDamcatsort_Domain_Repository_DamRepository $damRepository
 	 * @return void
	 */
	public function injectDamRepository(Tx_EdDamcatsort_Domain_Repository_DamRepository $damRepository) {
		$this->damRepository = $damRepository;
	}

 	/**
	 * Dependency injection of the DAM Category Repository
 	 *
	 * @param Tx_EdDamcatsort_Domain_Repository_DamCategoryRepository $damCategoryRepository
 	 * @return void
	 */
	public function injectDamCategoryRepository(Tx_EdDamcatsort_Domain_Repository_DamCategoryRepository $damCategoryRepository) {
		$this->damCategoryRepository = $damCategoryRepository;
	}

 	/**
	 * Dependency injection of the DAM media Repository
 	 *
	 * @param Tx_EdDamcatsort_Domain_Repository_MediaRepository $mediaRepository
 	 * @return void
	 */
	public function injectMediaRepository(Tx_EdDamcatsort_Domain_Repository_MediaRepository $mediaRepository) {
		$this->mediaRepository = $mediaRepository;
	}

	/**
	 * Index action for this controller. Displays a list of categories and medias.
	 *
	 * @return void
	 */
	public function indexAction(Tx_EdDamcatsort_Domain_Model_DamCategory $category = null) {
		if (empty($category)) {
			$category = $this->damCategoryRepository->findByUid($this->settings['rootCategory']);
		}
		
		$categories = $this->damCategoryRepository->findByParentId($category); /* @var $categories Tx_Extbase_Persistence_QueryResult */
		//$categories->getQuery()->getQuerySettings()->setStoragePageIds(array($category->getPid()));
		
		$medias = $this->mediaRepository->findByCategory($category); /* @var $medias Tx_Extbase_Persistence_QueryResult */
		//$medias->getQuery()->getQuerySettings()->setStoragePageIds(array($category->getPid()));
		
		$this->view->assign('rootCategory', $category);
		$this->view->assign('categories', $categories);
		$this->view->assign('medias', $medias);
		$this->view->assign('hasResult', count($categories)>0 || count($medias)>0);
		
		$data = $this->request->getContentObjectData();
		$this->view->assign('data', $data);
	}
	
	/**
	 * @param Tx_Extbase_MVC_View_ViewInterface $view
	 * @return void
	 */
	protected function setViewConfiguration(Tx_Extbase_MVC_View_ViewInterface $view) {
		parent::setViewConfiguration($view);

			// Template Path Override
		if (isset($this->settings['template']) && !empty($this->settings['template'])) {
			$templateRootPath = t3lib_div::getFileAbsFileName($this->settings['template'], TRUE);
			if (t3lib_div::isAllowedAbsPath($templateRootPath)) {
				$view->setTemplateRootPath($templateRootPath);
			}
		}
	}	
}

?>