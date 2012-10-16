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
class Tx_EdGallery_Controller_DamController extends Tx_EdGallery_Controller_AbstractController {

	/**
	 * Show action
	 *
	 * @param Tx_EdDamcatsort_Domain_Model_AbstractDam $dam
	 * @return void
	 */
	public function showAction(Tx_EdDamcatsort_Domain_Model_AbstractDam $dam = null) {
		if (!$dam) {
			$dam = $this->damRepository->findByUid($this->getSetting('media'));
		}

		// tracking media
		$this->trackingManager->trackObjectOnPage($dam);

		$this->view->assign('media', $dam);

		$data = $this->request->getContentObjectData();
		$this->view->assign('data', $data);
	}

	/**
	 * Show action
	 *
	 * @param Tx_EdDamcatsort_Domain_Model_AbstractDam $dam
	 * @return void
	 */
	public function relatedListAction(Tx_EdDamcatsort_Domain_Model_AbstractDam $dam = null) {

			// tracking media
		$this->trackingManager->trackRepositoryOnPage($this->damRepository);
		$this->trackingManager->trackRepositoryOnPage($this->damCategoryRepository);

		if (!$dam) {
			$dam = $this->damRepository->findByUid($this->getSetting('media'));
		}

		$this->view->assign('media', $dam);

		if ($dam) {
			/* @var $medias ArrayObject */
			$medias = t3lib_div::makeInstance('ArrayObject');
			$mediaUids = array();
			foreach ($dam->getCategories() as $category) {
				/* @var $category Tx_EdDamcatsort_Domain_Model_DamCategory */
				$sortedMedias = $category->getMedias();
				foreach ($sortedMedias as $sortedMedia) {
					/* @var $sortedMedia Tx_EdDamcatsort_Domain_Model_Media */
					if ($sortedMedia && $sortedMedia->getDam()) {
						if ($dam->getUid() != $sortedMedia->getDam()->getUid() && !in_array($sortedMedia->getDam()->getUid(), $mediaUids)) {
							$mediaUids[] = $sortedMedia->getDam()->getUid();
							$medias->append($sortedMedia->getDam());
						}
					}
				}
			}
			$this->view->assign('medias', $medias);
		}


		$data = $this->request->getContentObjectData();
		$this->view->assign('data', $data);
	}

	/**
	 * List action
	 *
	 * @param string $uids
	 * @return void
	 */
	public function listAction($uids = '') {
		if (!$uids) {
			$uids = $this->getSetting('medias');
		}

		/* @var $medias ArrayObject */
		$medias = t3lib_div::makeInstance('ArrayObject');
		foreach (t3lib_div::trimExplode(',', $uids, true) as $uid) {
			$media = $this->damRepository->findByUid($uid);
			if ($media) {
				$medias->append($media);
				$this->trackingManager->trackObjectOnPage($media);
			}
		}
		$this->view->assign('medias', $medias);

		$data = $this->request->getContentObjectData();
		$this->view->assign('data', $data);
	}
}

?>