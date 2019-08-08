<?php
namespace gw\gw_oxid_attributes_extended\Application\Model;

/**
 * Article attributes manager.
 * Collects and keeps attributes of chosen article.
 *
 */
class Attribute extends Attribute_parent {
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

}
