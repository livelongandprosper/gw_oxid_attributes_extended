<?php
namespace gw\gw_oxid_attributes_extended\Application\Model;

/**
 * @see OxidEsales\Eshop\Application\Model\SeoEncoderArticle
 */
class SeoEncoderArticle extends SeoEncoderArticle_parent {

	/**
	 * Add attribute values to seo urls of articles
	 * @param $oArticle
	 * @return mixed
	 */
	protected function _prepareArticleTitle($oArticle) {
		$parent_return = parent::_prepareArticleTitle($oArticle);

		// add attribute data
		if(sizeof($oArticle->getSeoAttributeList())) {
			$additional_seo_title_texts = array();
			foreach($oArticle->getSeoAttributeList() as $oAttribute) {
				$additional_seo_title_texts[] = $oAttribute->oxattribute__oxvalue->rawValue;
				/*
				print_r($oAttribute);
				exit;
				*/
			}

			if($additional_seo_title_texts) {
				return str_replace($this->_getUrlExtension(),'-'.($this->_prepareTitle(implode('-', $additional_seo_title_texts))).$this->_getUrlExtension(),$parent_return);
			}
		}

		return $parent_return;
	}

}
?>
