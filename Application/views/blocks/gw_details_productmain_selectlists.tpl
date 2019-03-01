[{$smarty.block.parent}]
[{if $oDetailsProduct}]
    [{if $oDetailsProduct->getParentArticle()}]
        [{assign var="parentArticle" value=$oDetailsProduct->getParentArticle()}]
    [{else}]
        [{assign var="parentArticle" value=$oDetailsProduct}]
    [{/if}]
    [{assign var="config" value=$oViewConf->getConfig()}]

    [{* color picker *}]
    <span class="gw-color-chooser-head">[{oxmultilang ident="GW_COLORS"}]</span>

    <div class="gw-article-color-picker">
        <ul>
            [{foreach from=$oDetailsProduct->getModelArticles() item="model_sibling"}]
                [{if $model_sibling->getAttributesByIdent($config->getConfigParam('gw_oxid_attributes_extended_color_attr'))|count > 0}]
                    [{assign var="color_name_attributes" value=$model_sibling->getAttributesByIdent($config->getConfigParam('gw_oxid_attributes_extended_color_attr'))}]
                    [{foreach from=$color_name_attributes item="color_name_attribute"}]
                        [{assign var="color_name" value=$color_name_attribute->oxattribute__oxvalue->value}]
                    [{/foreach}]
                    <li class="[{if $model_sibling->getId() == $parentArticle->getId()}]active[{/if}]"><a href="[{$model_sibling->getLink()}]" title="[{$color_name}]">[{$model_sibling->getColorIcon()}]</a></li>
                [{/if}]
            [{/foreach}]
        </ul>
    </div>
[{/if}]