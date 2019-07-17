<?php
/**
 * @abstract
 * @author 	Gregor Wendland <gregor@gewend.de>
 * @copyright Copyright (c) 2018-2019, Gregor Wendland
 * @package gw
 * @version 2019-01-09
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2'; // see https://docs.oxid-esales.com/developer/en/6.0/modules/skeleton/metadataphp/version20.html

/**
 * Module information
 */
$aModule = array(
    'id'           => 'gw_oxid_attributes_extended',
    'title'        => 'Erweiterte Attribute',
//     'thumbnail'    => 'out/admin/img/logo.jpg',
    'version'      => '1.0.3',
    'author'       => 'Gregor Wendland',
    'email'		   => 'kontakt@gewend.de',
    'url'		   => 'https://www.gewend.de',
    'description'  => array(
    	'de'		=> 'Erweitert die Möglichkeiten von OXID eShop Attributen
							<ul>
								<li>Anzeige des Attributes via Text-Attribut-ID auf Artikel-Detailseite oxarticle::getAttributesByIdent()</li>
								<li>Pro Attribut kann eingestellt werden, ob es auf der Detailseite angezeigt werden soll oder nicht</li>
								<li>Zeigt alle anderen Farben eines Models (erster Teil der Artikelnummer) in der Detailansicht an</li>
								<li>Macht es möglich, Attribute in das Generieren von Artikel-SEO-URLs einzubeziehen</li>
							</ul>
						',
    ),
    'extend'       => array(
		OxidEsales\Eshop\Application\Model\Article::class => gw\gw_oxid_attributes_extended\Application\Model\Article::class,
		OxidEsales\Eshop\Application\Model\SeoEncoderArticle::class => gw\gw_oxid_attributes_extended\Application\Model\SeoEncoderArticle::class,
		OxidEsales\Eshop\Application\Model\ArticleList::class => gw\gw_oxid_attributes_extended\Application\Model\ArticleList::class,
		OxidEsales\Eshop\Application\Model\AttributeList::class => gw\gw_oxid_attributes_extended\Application\Model\AttributeList::class,

    ),
    'settings'		=> array(
		array('group' => 'gw_oxid_attributes_extended', 'name' => 'gw_oxid_attributes_extended_merge_parent', 'type' => 'bool', 'value' => '0'),
		array('group' => 'gw_oxid_attributes_extended', 'name' => 'gw_oxid_attributes_extended_color_attr', 'type' => 'str', 'value' => 'colorname'),
		array('group' => 'gw_oxid_attributes_extended', 'name' => 'gw_oxid_attributes_extended_model_dbfield', 'type' => 'str', 'value' => 'oxmpn'),
		array('group' => 'gw_oxid_attributes_extended', 'name' => 'gw_oxid_attributes_extended_model_separator', 'type' => 'str', 'value' => '.'),
		array('group' => 'gw_oxid_attributes_extended', 'name' => 'gw_oxid_attributes_extended_color_mapping', 'type' => 'aarr', 'value' => array(
			"schwarz" => "#000000",
			"schwarz gewachst" => "#000000",
			"hellgrau" => "#C9C9C9",
			"grau" => "#9C9C9C",
			"dunkelgrau" => "#696969",
			"hellbraun" => "#7B4F2B",
			"braun" => "#8B4513",
			"dunkelbraun" => "#441E17",
			"rotbraun" => "#782B1D",
			"cognac" => "#C17400",
			"hellblau" => "#3883C2",
			"blau" => "#0451DD",
			"dunkelblau" => "#14235B",
			"graublau" => "#9FB6CD",
			"grünblau" => "#304B54",
			"petrol" => "#304B54",
			"rot" => "#C40E00",
			"bordeaux" => "#5E2129",
			"dunkelrot" => "#5E2129",
			"violett" => "#5900B8",
			"pink/rosa" => "#D7A6D7",
			"grün" => "#006400",
			"dunkelgrün" => "#21391A",
			"olive" => "#556B2F",
			"gelb" => "#F5D033",
			"orange" => "#E15501",
			"beige" => "#FFFFEA",
			"cremweiß" => "#F8FAEE",
			"cremeweiß" => "#F8FAEE",
			"cremweß" => "#F8FAEE",
			"cremeweß" => "#F8FAEE",
			"weiß" => "#FFFFFF",
		)),
    ),
    'files'			=> array(
    ),
	'blocks' => array(
		// backend
		array(
			'template' => 'attribute_main.tpl',
			'block' => 'admin_attribute_main_form',
			'file' => 'Application/views/blocks/admin/admin_attribute_main_form.tpl'
		),
		array(
			/*'theme' => 'flow',*/
			'template' => 'page/details/inc/productmain.tpl',
			'block' => 'details_productmain_selectlists',
			'file' => 'Application/views/blocks/gw_details_productmain_selectlists.tpl'
		),
	),
	'events'       => array(
		'onActivate'   => '\gw\gw_oxid_attributes_extended\Core\Events::onActivate',
		'onDeactivate' => '\gw\gw_oxid_attributes_extended\Core\Events::onDeactivate'
	),
	'controllers'  => [
	],
	'templates' => [
	]
);
?>
