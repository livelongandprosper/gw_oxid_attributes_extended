<?php
namespace gw\gw_oxid_attributes_extended\Application\Model;

/**
 * @see OxidEsales\Eshop\Application\Model\Article
 */
class Article extends Article_parent {

	/**
	 * Object holding the list of attributes and attribute values associated with this article and displayable on details page
	 * @var \OxidEsales\Eshop\Application\Model\AttributeList
	 */
	protected $detailsAttributeList = null;

	/**
	 * Object holding the list of attributes and attribute values that should be used for seo url generation
	 * @var \OxidEsales\Eshop\Application\Model\AttributeList
	 */
	protected $seoAttributeList = null;

	/**
	 * Array holding objects holding the list of attributes and attribute values associated with this article and displayable on details page
	 * @var \OxidEsales\Eshop\Application\Model\AttributeList
	 */
	protected $attributesByIdent = array();

	/**
	 * @var array
	 */
	protected $oModelArticlesList = null;


	private $_colorIcon = null;

	/**
	 * Loads and returns attribute list for display in basket
	 *
	 * @return \OxidEsales\Eshop\Application\Model\AttributeList
	 */
	public $has_function_getAttributesDisplayableOnDetailsPage = true;
	public function getAttributesDisplayableOnDetailsPage() {
		if ($this->detailsAttributeList === null) {
			$this->detailsAttributeList = oxNew(\OxidEsales\Eshop\Application\Model\AttributeList::class);
			$this->detailsAttributeList->loadAttributesDisplayableOnDetailsPage($this->getId(), $this->getParentId());
		}

		return $this->detailsAttributeList;
	}

	/**
	 * Loads and returns attribute list for seo url generation
	 *
	 * @return \OxidEsales\Eshop\Application\Model\AttributeList
	 */
	public function getSeoAttributeList() {
		if ($this->seoAttributeList === null) {
			$this->seoAttributeList = oxNew(\OxidEsales\Eshop\Application\Model\AttributeList::class);
			$this->seoAttributeList->loadAttributesForArticleSeoUrl($this->getId(), $this->getParentId());
		}

		return $this->seoAttributeList;
	}

	/**
	 * Loads and returns attribute list for display in basket
	 *
	 * @return \OxidEsales\Eshop\Application\Model\AttributeList
	 */
	public $has_function_getAttributesByIdent = true;

	/**
	 * @param $attribute_ident
	 * @param mixed $return_text bool|string - delimit concatenated strings of attributes with more than 1 value
	 * @param bool $usecoretable
	 * @return mixed
	 */
	public function getAttributesByIdent($attribute_ident, $return_text = false, $usecoretable = false) {
		if (!isset($this->attributesByIdent[$attribute_ident])) {
			$this->attributesByIdent[$attribute_ident] = oxNew(\OxidEsales\Eshop\Application\Model\AttributeList::class);
			$this->attributesByIdent[$attribute_ident]->loadAttributesByIdent($this->getId(), $this->getParentId(), $attribute_ident, $usecoretable);
		}
		if($return_text) {
			$return_value = "";
			if(sizeof($this->attributesByIdent[$attribute_ident]) > 0) {
				foreach($this->attributesByIdent[$attribute_ident] as $oAttribute) {
					if($return_value && is_string($return_value) && $oAttribute->oxattribute__oxvalue) {
						$return_value .= (string)$return_text;
					}
					$return_value .= $oAttribute->oxattribute__oxvalue;
				}
			}
			return $return_value;
		} else {
			return $this->attributesByIdent[$attribute_ident];
		}
	}

	public $has_function_getModelArticles = true;

	/**
	 * Loads all articles that has the same model number e.g. 855.xxx
	 * @return |null
	 */
	public function getModelArticles() {
		if($this->oModelArticlesList === null) {
			$myConfig = $this->getConfig();
			$article_number_db_field = $myConfig->getConfigParam('gw_oxid_attributes_extended_model_dbfield');
			$article_number = $this->{'oxarticles__'.$article_number_db_field}->value;
			$model_number_separator = $myConfig->getConfigParam('gw_oxid_attributes_extended_model_separator');
			$model_number = "";
			$this->oModelArticlesList = null;

			if($article_number && $model_number_separator && $separator_string_position = strpos($article_number, $model_number_separator)) {
				$model_number = substr( $article_number, 0, $separator_string_position+1 );
			}

			if($model_number) {
				$sArticleTable = $this->getViewName();

				// $sFieldList = $this->getSelectFields();
				$sFieldList = "OXID";
				$sSearch = "
					select 
						$sFieldList
					from
						$sArticleTable
					where 
							" . $this->getSqlActiveSnippet() . "
						and $sArticleTable.$article_number_db_field LIKE '$model_number%'
						and $sArticleTable.OXPARENTID = ''
						# and $sArticleTable.OXID != '".$this->getId()."' # uncomment this line if the current article should not be listed
				";

				// TODO: order by what??
				// $sSearch .= ' order by rand() ';

				$this->oModelArticlesList = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
				$this->oModelArticlesList->selectString($sSearch);
			}
		}
		return $this->oModelArticlesList;
	}

	/**
	 * @return string
	 */
	public function getColorIcon() {
		if($this->_colorIcon === null) {
			$color1 = "#999999";
			$color2 = "#999999";
			$color3 = "#999999";
			$colors = array();
			$myConfig = $this->getConfig();
			$color_attributes = $this->getAttributesByIdent( $myConfig->getConfigParam('gw_oxid_attributes_extended_color_attr'), false, true );

			$color_mapping_array = $myConfig->getConfigParam('gw_oxid_attributes_extended_color_mapping');
			array_change_key_case($color_mapping_array, CASE_LOWER);

			if(sizeof($color_attributes)) {
				foreach($color_attributes as $color_attribute) {
					$colors = explode("/", $color_attribute->oxattribute__oxvalue->value);
					$colors = array_map('trim', $colors);
					$colors = array_map('strtolower', $colors);
					//print_r($color_attribute);
				}
				/*
				print_r($colors);
				print_r($color_mapping_array);
				*/

				if(sizeof($colors) >= 1) {
					if($color_mapping_array[$colors[0]]) {
						$color1 = $color_mapping_array[$colors[0]];
					}
				}
				if(sizeof($colors) >= 2) {
					if($color_mapping_array[$colors[1]]) {
						$color2 = $color_mapping_array[$colors[1]];
					}
				} else {
					$color2 = $color1;
				}
				if(sizeof($colors) >= 3) {
					if($color_mapping_array[$colors[2]]) {
						$color3 = $color_mapping_array[$colors[2]];
					}
				} else {
					$color3 = $color2;
				}

				$this->_colorIcon = '
					<span style="background: '.$color1.';"></span>
					<span style="background: '.$color2.';"></span>
					<span style="background: '.$color3.';"></span>
				';

			}
		}
		return $this->_colorIcon;
	}

	/**
	 *
	 */
	public function getSimilarProductsBySpecificAttributes() {
		$articleList = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
		// use $articleList->getSimilarProductsByAttribute()

		return $articleList;
	}
}
?>
