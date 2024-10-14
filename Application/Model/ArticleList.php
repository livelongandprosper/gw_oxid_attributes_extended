<?php
namespace gw\gw_oxid_attributes_extended\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use oxRegistry;
use Exception;
use oxDb;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;

/**
 * Article list manager.
 * Collects list of article according to collection rules (categories, etc.).
 *
 */
class ArticleList extends ArticleList_parent {
	/**
	 * Extend _getFilterIdsSql so that multiple values are supported
	 * @param $sCatId
	 * @param $aFilter
	 * @return string
	 */
	protected function _getFilterIdsSql($sCatId, $aFilter) {

		$myConfig = $this->getConfig();
		$sO2CView = getViewName('oxobject2category');
		$sO2AView = getViewName('oxobject2attribute');

		$sFilter = '';
		$iCnt = 0;
		$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();


		$has_real_oxattribute_filter = false;
		$has_variant_filter = false;

		foreach ($aFilter as $sAttrId => $sValue) {
			if(is_array($sValue)) {
				// support multiple values
				if($sValue[0]) { // only do this if first value is not empty

					$sFilter_currentAttribute = "";
					if(strpos($sAttrId,'varname@') === 0) {
						// handle variant filter
						if ($sFilter) {
							$sFilter .= ' AND '; // variant filter has to be combined with logical AND
						}
						$iLang = \OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage();
						$sArticleTable = getViewName('oxarticles', $iLang);
						$oArticle_empty = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);

						foreach($sValue as $single_value) {
							if($single_value) { // Filter values must not be empty
								if ($sFilter_currentAttribute) {
									$sFilter_currentAttribute .= ' OR ';
								}
								$single_value = $oDb->quote($single_value);
								$sFilter_currentAttribute .= "
									(
										oc.oxobjectid IN (
											SELECT DISTINCT
												OXPARENTID
											FROM
												$sArticleTable
											WHERE
													OXVARSELECT = $single_value
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
													) ":'').
										")
									)";
								//echo $sFilter_currentAttribute;
							}
						}

						$has_variant_filter = true;
					} elseif(strpos($sAttrId,'gw_sale') === 0) {
						// handle sale filter
						if ($sFilter) {
							$sFilter .= ' AND '; // variant filter has to be combined with logical AND
						}
						$iLang = \OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage();
						$sArticleTable = getViewName('oxarticles', $iLang);
						$oArticle_empty = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
						$sFilter_currentAttribute .= "
							(
								oc.oxobjectid IN (
									SELECT DISTINCT
										OXID
									FROM
										$sArticleTable
									WHERE
											OXPARENTID = '' 
										AND
											OXTPRICE > 0 
										AND
											OXTPRICE > OXPRICE 
										AND
											OXACTIVE = 1 
										AND
											OXHIDDEN = 0".
								")
							)";
						// echo $sFilter_currentAttribute;
					} else {
						// handle oxattribute filter
						if ($sFilter) {
							$sFilter .= ' OR ';
						}
						$sAttrId = $oDb->quote($sAttrId); // do this outside of foreach otherwise this is quoted multiple times
						foreach($sValue as $single_value) {
							if($single_value) { // Filter values must not be empty
								if ($sFilter_currentAttribute) {
									$sFilter_currentAttribute .= ' OR ';
								}
								$single_value = $oDb->quote($single_value);

								$sFilter_currentAttribute .= "( oa.oxattrid = {$sAttrId} and oa.oxvalue = {$single_value} )";
							}
						}
						$has_real_oxattribute_filter = true;
						$iCnt++;
					}
					$sFilter .= '('.$sFilter_currentAttribute.')';
				}
			} else {
				// std oxid behavior
				if ($sValue) {
					if ($sFilter) {
						$sFilter .= ' OR ';
					}
					$sValue = $oDb->quote($sValue);
					$sAttrId = $oDb->quote($sAttrId);

					$sFilter .= "( oa.oxattrid = {$sAttrId} and oa.oxvalue = {$sValue} )";
					$has_real_oxattribute_filter = true;
					$iCnt++;
				}
			}
		}
		if ($sFilter) {
			$sFilter = "WHERE $sFilter ";
		}

		$sFilterSelect = "select oc.oxobjectid as oxobjectid, count(*) as cnt from ";
		$sFilterSelect .= "(SELECT * FROM $sO2CView WHERE $sO2CView.oxcatnid = '$sCatId' GROUP BY $sO2CView.oxobjectid, $sO2CView.oxcatnid) as oc ";

		// TODO: es wäre zu prüfen, was das wegfallen von HAVING cnt = $iCnt ggf. für negative auswirkungen hat
		if($has_real_oxattribute_filter) {
			$sFilterSelect .= "INNER JOIN $sO2AView as oa ON ( oa.oxobjectid = oc.oxobjectid ) ";
			if($has_variant_filter) {
				return $sFilterSelect . "{$sFilter} GROUP BY oa.oxobjectid, oc.oxobjectid";
			} else {
				return $sFilterSelect . "{$sFilter} GROUP BY oa.oxobjectid, oc.oxobjectid HAVING cnt = $iCnt ";
			}
		} else {
			return $sFilterSelect . "{$sFilter} GROUP BY oc.oxobjectid";
		}
	}

	public function getSimilarProductsByAttribute($similarAttributes, $attributesDifferent) {

	}
}
