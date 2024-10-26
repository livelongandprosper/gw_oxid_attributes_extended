<?php
namespace gw\gw_oxid_attributes_extended\Application\Model;

/**
 * Article attributes manager.
 * Collects and keeps attributes of chosen article.
 *
 */
class Attribute extends Attribute_parent {

	private $_gw_filter_name = null;
	private $_gw_attribute_id = null;

	/**
	 * make oxattribute__gw_filter_name a multilang field
	 * @param $fieldName
	 * @return bool
	 */
	public function isMultilingualField($fieldName) {
		if(
			$fieldName == 'gw_filter_name'
		 || $fieldName == 'gw_alt_title'
		) {
			return true;
		}
		return parent::isMultilingualField($fieldName);
	}

    /**
     * Set attribute selected value
     *
     * @param string $sValue - attribute value
     */
    public function setActiveValue($sValue) {
    	if(is_array($sValue)) {
			$this->_sActiveValue = $sValue;
		} else {
			$this->_sActiveValue = getStr()->htmlspecialchars($sValue);
		}
    }

	/**
	 * Get attribute Selected value
	 *
	 * @return String
	 */
	public function getActiveValue($value_to_be_checked_is_active = null) {
		if(is_array($this->_sActiveValue)) {
			if($value_to_be_checked_is_active) {
				if(in_array($value_to_be_checked_is_active, $this->_sActiveValue)) {
					return $value_to_be_checked_is_active;
				} else {
					return "";
				}
			} else {
				// return list of active values
				return implode(", ", $this->_sActiveValue);
			}
		}
		return $this->_sActiveValue;
	}

	/**
	 * @return int
	 */
	public function getNumberActiveValues() {
		if($this->_sActiveValue !== '') {
			return sizeof($this->_sActiveValue);
		}
		return 0;
	}

	/**
	 * @return int
	 */
	public function getNumberValues() {
		return sizeof($this->getValues());
	}

	/**
	 *
	 * @return |null
	 */
	public function get_gw_filter_name() {
		if($this->_gw_filter_name === null) {
			$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDB();
			$sAttViewName = getViewName('oxattribute');
			$this->_gw_filter_name = $oDb->getOne("select gw_filter_name from $sAttViewName where OXTITLE={$oDb->quote($this->getTitle())}");
			$this->oxattribute__gw_filter_name->value = $this->_gw_filter_name;
		}
		return $this->_gw_filter_name;
	}
	public function get_gw_attribute_id() {
		if($this->_gw_attribute_id === null) {
			$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDB();
			$sAttViewName = getViewName('oxattribute');
			$this->_gw_attribute_id = $oDb->getOne("select gw_attribute_id from $sAttViewName where OXTITLE={$oDb->quote($this->getTitle())}");
			$this->oxattribute__gw_attribute_id->value = $this->_gw_attribute_id;
		}
		return $this->_gw_attribute_id;
	}

}
