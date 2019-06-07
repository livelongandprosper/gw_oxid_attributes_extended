<?php
	namespace gw\gw_oxid_attributes_extended\Core;

	use OxidEsales\Eshop\Core\DbMetaDataHandler;
	use OxidEsales\Eshop\Core\DatabaseProvider;

	class Events {
		/**
		 * add_db_key function.
		 *
		 * @access private
		 * @static
		 * @param mixed $table_name
		 * @param mixed $keyname
		 * @param mixed $column_names
		 * @param bool $unique (default: false)
		 * @return void
		 */
		private static function add_db_key($table_name, $keyname, $column_names, $unique=false) {
			// create key
			if($unique) {
				DatabaseProvider::getDb()->execute("
					ALTER TABLE  `$table_name` ADD UNIQUE  `$keyname` (  `".implode('`,`', $column_names)."` );
				");
			} else {
				DatabaseProvider::getDb()->execute("
					ALTER TABLE  `$table_name` ADD INDEX `$keyname` (  `".implode('`,`', $column_names)."` ) ;
				");
			}
		}

		/**
		 * @param $table_name
		 * @param $column_name
		 * @param $datatype
		 */
		private static function add_db_field($table_name, $column_name, $datatype) {
			$gw_head_exists = DatabaseProvider::getDb()->GetOne("SHOW COLUMNS FROM `$table_name` LIKE '$column_name'");
			if(!$gw_head_exists) {
				DatabaseProvider::getDb()->execute(
					"ALTER TABLE `$table_name` ADD `$column_name` $datatype;"
				);
			}
		}

		public static function onActivate() {
			self::add_db_field('oxattribute', 'gw_display_on_detailspage', "TINYINT(1) UNSIGNED DEFAULT 1 NOT NULL COMMENT 'defines if attribute should be shown on details page'");
			self::add_db_field('oxattribute', 'gw_use_for_seo', "TINYINT(1) UNSIGNED DEFAULT 0 NOT NULL COMMENT 'defines if attribute should be used for article seo url generation'");

			self::add_db_field('oxattribute', 'gw_attribute_id', "VARCHAR(20) NOT NULL COMMENT 'defines an id which allows to load a specific attribute at a certain position in template'");

			try {
				self::add_db_key('oxarticles', 'gw_OXMPN_OXPARENTID', array("OXMPN", "OXPARENTID"));
				self::add_db_key('oxattribute', 'gw_key_ident', array("gw_attribute_id"));
				self::add_db_key('oxattribute', 'gw_key_detailspage', array("gw_display_on_detailspage"));
				self::add_db_key('oxattribute', 'gw_key_seo', array("gw_use_for_seo"));

			}	catch (OxidEsales\Eshop\Core\Exception\DatabaseErrorException $e) {
				// do nothing... php will ignore and continue
			}

			$oDbMetaDataHandler = oxNew(DbMetaDataHandler::class);
			$oDbMetaDataHandler->updateViews();
		}
		public static function onDeactivate() {
			$config = \OxidEsales\Eshop\Core\Registry::getConfig();
			DatabaseProvider::getDb()->execute("DELETE FROM oxtplblocks WHERE oxshopid='".$config->getShopId()."' AND oxmodule='gw_oxid_attributes_extended';");
			exec( "rm -f " .$config->getConfigParam( 'sCompileDir' )."/smarty/*" );
			exec( "rm -Rf " .$config->getConfigParam( 'sCompileDir' )."/*" );
			$oDbMetaDataHandler = oxNew(DbMetaDataHandler::class);
			$oDbMetaDataHandler->updateViews();
		}
	}
?>
