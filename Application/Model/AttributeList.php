<?php
	namespace gw\gw_oxid_attributes_extended\Application\Model;

	class AttributeList extends AttributeList_parent {

		/**
		 * Load displayable on detailspage attributes by article Id
		 *
		 * @param string $sArtId    article ids
		 * @param string $sParentId parent id
		 */
		public function loadAttributesDisplayableOnDetailsPage($sArtId, $sParentId = null) {
			if ($sArtId) {
				$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

				$sAttrViewName = getViewName('oxattribute');
				$sViewName = getViewName('oxobject2attribute');

				$sSelect = "select {$sAttrViewName}.*, o2a.* from {$sViewName} as o2a ";
				$sSelect .= "left join {$sAttrViewName} on {$sAttrViewName}.oxid = o2a.oxattrid ";
				$sSelect .= "where o2a.oxobjectid = '%s' and {$sAttrViewName}.gw_display_on_detailspage = 1 and o2a.oxvalue != '' ";
				$sSelect .= "order by o2a.oxpos, {$sAttrViewName}.oxpos";

				$aAttributes = $oDb->getAll(sprintf($sSelect, $sArtId));

				if ($sParentId) {
					$aParentAttributes = $oDb->getAll(sprintf($sSelect, $sParentId));
					$aAttributes = $this->_mergeAttributes($aAttributes, $aParentAttributes);
				}

				$this->assignArray($aAttributes);
			}
		}

		/**
		 * Load attributes by ident
		 *
		 * @param string $sArtId article ids
		 * @param string $sParentId parent id
		 * @param string $sAttribute_ident attribute identifier
		 */
		public function loadAttributesByIdent($sArtId, $sParentId = null, $sAttribute_ident) {
			if ($sArtId && $sAttribute_ident) {
				$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

				$sAttrViewName = getViewName('oxattribute');
				$sViewName = getViewName('oxobject2attribute');

				$sSelect = "select {$sAttrViewName}.*, o2a.* from {$sViewName} as o2a ";
				$sSelect .= "left join {$sAttrViewName} on {$sAttrViewName}.oxid = o2a.oxattrid ";
				$sSelect .= "where o2a.oxobjectid = '%s' and {$sAttrViewName}.gw_attribute_id = '%s' and {$sAttrViewName}.gw_attribute_id != '' and o2a.oxvalue != '' ";
				$sSelect .= "order by o2a.oxpos, {$sAttrViewName}.oxpos";

				$aAttributes = $oDb->getAll(sprintf($sSelect, $sArtId, $sAttribute_ident));

				if ($sParentId) {
					$aParentAttributes = $oDb->getAll(sprintf($sSelect, $sParentId, $sAttribute_ident));
					$aAttributes = $this->_mergeAttributes($aAttributes, $aParentAttributes);
				}

				$this->assignArray($aAttributes);
			}
		}
	}
?>