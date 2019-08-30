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
				$myConfig = $this->getConfig();
				$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

				$sAttrViewName = getViewName('oxattribute');
				$sViewName = getViewName('oxobject2attribute');

				$sSelect = "select {$sAttrViewName}.*, o2a.* from {$sViewName} as o2a ";
				$sSelect .= "left join {$sAttrViewName} on {$sAttrViewName}.oxid = o2a.oxattrid ";
				$sSelect .= "where o2a.oxobjectid = '%s' and {$sAttrViewName}.gw_display_on_detailspage = 1 and o2a.oxvalue != '' ";
				$sSelect .= "order by {$sAttrViewName}.oxpos, o2a.oxpos";

				$aAttributes = $oDb->getAll(sprintf($sSelect, $sArtId));

				if ($myConfig->getConfigParam('gw_oxid_attributes_extended_merge_parent') && $sParentId) {
					$aParentAttributes = $oDb->getAll(sprintf($sSelect, $sParentId));
					$aAttributes = $this->_mergeAttributes($aAttributes, $aParentAttributes);
				}

				$this->assignArray($aAttributes);
			}
		}

		/**
		 * Load displayable on detailspage attributes by article Id
		 *
		 * @param string $sArtId    article ids
		 * @param string $sParentId parent id
		 */
		public function loadAttributesForArticleSeoUrl($sArtId, $sParentId = null) {
			if ($sArtId) {
				$myConfig = $this->getConfig();
				$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

				$sAttrViewName = getViewName('oxattribute');
				$sViewName = getViewName('oxobject2attribute');

				$sSelect = "select {$sAttrViewName}.*, o2a.* from {$sViewName} as o2a ";
				$sSelect .= "left join {$sAttrViewName} on {$sAttrViewName}.oxid = o2a.oxattrid ";
				$sSelect .= "where o2a.oxobjectid = '%s' and {$sAttrViewName}.gw_use_for_seo = 1 and o2a.oxvalue != '' ";
				$sSelect .= "order by {$sAttrViewName}.oxpos, o2a.oxpos";

				$aAttributes = $oDb->getAll(sprintf($sSelect, $sArtId));

				if ($myConfig->getConfigParam('gw_oxid_attributes_extended_merge_parent') && $sParentId) {
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
		public function loadAttributesByIdent($sArtId, $sParentId = null, $sAttribute_ident, $usecoretable = false) {
			if ($sArtId && $sAttribute_ident) {
				$myConfig = $this->getConfig();
				$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

				$sAttrViewName = getViewName('oxattribute', ($usecoretable?0:\OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage()));
				$sViewName = getViewName('oxobject2attribute', ($usecoretable?0:\OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage()
				));

				$sSelect = "select {$sAttrViewName}.*, o2a.* from {$sViewName} as o2a ";
				$sSelect .= "left join {$sAttrViewName} on {$sAttrViewName}.oxid = o2a.oxattrid ";
				$sSelect .= "where o2a.oxobjectid = '%s' and {$sAttrViewName}.gw_attribute_id = '%s' and {$sAttrViewName}.gw_attribute_id != '' and o2a.oxvalue != '' ";
				$sSelect .= "order by o2a.oxpos, {$sAttrViewName}.oxpos";

				$aAttributes = $oDb->getAll(sprintf($sSelect, $sArtId, $sAttribute_ident));

				if ($myConfig->getConfigParam('gw_oxid_attributes_extended_merge_parent') && $sParentId) {
					$aParentAttributes = $oDb->getAll(sprintf($sSelect, $sParentId, $sAttribute_ident));
					$aAttributes = $this->_mergeAttributes($aAttributes, $aParentAttributes);
				}

				$this->assignArray($aAttributes);
			}
		}

		/**
		 * Extend std functionality so that multiple active values are supported.
		 * If only one filter ist active show all possible values of that filter (multiple values within one filter are connected with OR)
		 * @param $sCategoryId
		 * @param $iLang
		 * @return $this
		 */
		public function getCategoryAttributes($sCategoryId, $iLang) {
			$aSessionFilter = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('session_attrfilter');
			$number_active_filters = sizeof(array_filter($aSessionFilter[$sCategoryId][$iLang]));
			$myConfig = $this->getConfig();

			$oArtList = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
			$oArtList->loadCategoryIDs($sCategoryId, $aSessionFilter);

			// if only one filter is selected we need to get the ids of all articles in category
			$oArtList_single_filter = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
			if($number_active_filters == 1) {
				$oArtList_single_filter->loadCategoryIDs($sCategoryId, array());
			}

			// Only if we have articles
			if (count($oArtList) > 0) {
				$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
				$sArtIds = '';
				foreach (array_keys($oArtList->getArray()) as $sId) {
					if ($sArtIds) {
						$sArtIds .= ',';
					}
					$sArtIds .= $oDb->quote($sId);
				}
				$sArtIds_single_filter = '';
				foreach (array_keys($oArtList_single_filter->getArray()) as $sId) {
					if ($sArtIds_single_filter) {
						$sArtIds_single_filter .= ',';
					}
					$sArtIds_single_filter .= $oDb->quote($sId);
				}

				$sActCatQuoted = $oDb->quote($sCategoryId);
				$sAttTbl = getViewName('oxattribute', $iLang);
				$sO2ATbl = getViewName('oxobject2attribute', $iLang);
				$sC2ATbl = getViewName('oxcategory2attribute', $iLang);

				// get the first active filter
				foreach (array_filter($aSessionFilter[$sCategoryId][$iLang]) as $first_active_cat => $val) break; // in php 7.3+ we could use array_key_first(); array_filter gives us an array of arrays with not empty child arrays
				$first_active_cat_quoted = $oDb->quote($first_active_cat);

				// wenn ich nur innerhalb EINES attributes werte ausgewählt habe, müssen alle werte dieses attributes weiterhin zur auswahl stehen, alle anderen attributes, die zur auswahl stehen sollen nur dann angezeigt werden, wenn es auch artikel gibt, die dazu passen; habe ich einen weiteren filter ausgewählt, schränken sich ab da an alle andere filter ebenfalls ein; der fall das keine artiekl angezeigt werden können tritt so relativ selten ein
				$sSelect = "SELECT DISTINCT att.oxid, att.oxtitle, o2a.oxvalue " .
					"FROM $sAttTbl as att, $sO2ATbl as o2a ,$sC2ATbl as c2a " .
					"WHERE
							# all available filter attribute values of active filter category under consideration of activated filters
							(att.oxid = o2a.oxattrid AND c2a.oxobjectid = $sActCatQuoted AND c2a.oxattrid = att.oxid AND o2a.oxvalue !='' AND o2a.oxobjectid IN ($sArtIds) )" .
						($number_active_filters == 1 && $sArtIds_single_filter?" 
						OR
							# all available filter attribute values of single active filter 
							(att.oxid = o2a.oxattrid AND c2a.oxobjectid = $sActCatQuoted AND c2a.oxattrid = att.oxid AND o2a.oxvalue !='' && att.oxid = $first_active_cat_quoted) AND o2a.oxobjectid IN ($sArtIds_single_filter)"
							:"").
					"ORDER BY c2a.oxsort , att.oxpos, att.oxtitle, o2a.oxvalue";

				$rs = $oDb->select($sSelect);

				if ($rs != false && $rs->count() > 0) {
					while (!$rs->EOF && list($sAttId, $sAttTitle, $sAttValue) = $rs->fields) {

						if (!$this->offsetExists($sAttId)) {
							$oAttribute = oxNew(\OxidEsales\Eshop\Application\Model\Attribute::class);
							$oAttribute->setTitle($sAttTitle);

							$this->offsetSet($sAttId, $oAttribute);
							$iLang = \OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage();

							if (isset($aSessionFilter[$sCategoryId][$iLang][$sAttId])) {
								$oAttribute->setActiveValue($aSessionFilter[$sCategoryId][$iLang][$sAttId]);
							}
						} else {
							$oAttribute = $this->offsetGet($sAttId);
						}

						$oAttribute->addValue($sAttValue);
						$rs->fetchRow();
					}
				}

				// handle variant filters
				if ($myConfig->getConfigParam('gw_oxid_filter_oxvarselect')) {
					$oCat = $this->getConfig()->getTopActiveView()->getActiveCategory();
					if(!$oCat->oxcategories__gw_deactivate_variant_filter->value) {
						$sArticleTable = getViewName('oxarticles', $iLang);

						$sSelect_variantnames = "
							SELECT DISTINCT
								oxvarname 
							FROM
								$sArticleTable
							WHERE
								(
									# all available filter attribute values of active filter category under consideration of activated filters
									(OXID IN ($sArtIds))
								)
								AND
									# restrict to only one dimension variants
									oxvarname NOT LIKE '%|%'
								AND
									oxvarname <> ''
								AND
									OXACTIVE = 1 
								AND
									OXHIDDEN = 0
						;";

						$rs_variantnames = $oDb->select($sSelect_variantnames);
						if ($rs_variantnames != false && $rs_variantnames->count() > 0) {
							while (!$rs_variantnames->EOF && list($varname) = $rs_variantnames->fields) {
								// get possible values for that variantname
								$varname_quotet = $oDb->quote($varname);
								$sSelect_variantselections = "
									SELECT DISTINCT
										oxvarselect
									FROM
										$sArticleTable
									WHERE
											OXPARENTID IN (SELECT OXID FROM $sArticleTable WHERE oxvarname = $varname_quotet)
										AND
											(
												OXPARENTID IN ($sArtIds)".
									($number_active_filters == 1 && $sArtIds_single_filter && $first_active_cat == 'varname@'.$varname?" OR OXPARENTID IN ($sArtIds_single_filter)":'')."
											)
										AND
											oxvarselect <> ''
										AND
											OXACTIVE = 1 
										AND
											OXHIDDEN = 0".
									($myConfig->getConfigParam('gw_oxid_filter_oxvarselect_instock')?"
										AND
											(
													($sArticleTable.oxstockflag = 2 AND $sArticleTable.oxstock > 0)
												OR
													($sArticleTable.oxstockflag = 3 AND $sArticleTable.oxstock > 0 )
											) ":'')."
									ORDER BY
										oxvarselect
								;";
								$rs_variantselections = $oDb->select($sSelect_variantselections);

								if ($rs_variantselections != false && $rs_variantselections->count() > 0) {
									if (!$this->offsetExists('varname@'.$varname)) {
										$oAttribute = oxNew(\OxidEsales\Eshop\Application\Model\Attribute::class);
										$oAttribute->setTitle($varname);

										$this->offsetSet('varname@'.$varname, $oAttribute);
										$iLang = \OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage();

										if (isset($aSessionFilter[$sCategoryId][$iLang]['varname@'.$varname])) {
											$oAttribute->setActiveValue($aSessionFilter[$sCategoryId][$iLang]['varname@'.$varname]);
										}
									} else {
										$oAttribute = $this->offsetGet('varname@'.$varname);
									}

									while (!$rs_variantselections->EOF && list($varariantselect) = $rs_variantselections->fields) {
										$oAttribute->addValue($varariantselect);
										$rs_variantselections->fetchRow();
									}
								}

								$rs_variantnames->fetchRow();
							}
						}
					}
				}
			}
			return $this;
		}
	}
?>
