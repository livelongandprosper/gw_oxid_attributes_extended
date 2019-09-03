[{*$smarty.block.parent*}]
[{assign var="config" value=$oViewConf->getConfig()}]
[{if $attributes}]
    <div class="row">
        <div class="col-xs-12">
            <div class="gw-filter-btn-wrapper">
                <button type="submit" class="btn btn-default gw-filter-btn">[{oxmultilang ident="GW_FILTER"}]</button>
            </div>
            <div class="list-filter clearfix gw-fullscreen-wrapper">
                <div class="gw-dimmer"></div>
                <div class="list-filter-aside col-xs-2">
                    <span class="gw-close-filter-list gw-close-icon">
                        <span class="gw-icon-bar"></span>
                        <span class="gw-icon-bar"></span>
                    </span>
                </div>
                <div class="list-filter-main col-xs-10 col-sm-12">
                    <form method="get" action="[{$oViewConf->getSelfActionLink()}]" name="_filterlist" id="gw-filterList" class="pull-left">
                        <div class="hidden">
                            [{$oViewConf->getHiddenSid()}]
                            [{$oViewConf->getNavFormParams()}]
                            <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
                            <input type="hidden" name="tpl" value="[{$oViewConf->getActTplName()}]">
                            <input type="hidden" name="oxloadid" value="[{$oViewConf->getActContentLoadId()}]">
                            <input type="hidden" name="fnc" value="executefilter">
                            <input type="hidden" name="fname" value="">
                        </div>

                        [{assign var="hasActiveFilter" value=false}]
                        [{foreach from=$attributes item=oFilterAttr key=sAttrID name=attr}]
                            [{assign var="hasActiveValue" value=false}]
                            [{assign var="dActiveValueCount" value=$oFilterAttr->getNumberActiveValues()}]

                            [{if !$config->getConfigParam('gw_oxid_filter_hideifonlyone') || $config->getConfigParam('gw_oxid_filter_hideifonlyone') && ($dActiveValueCount || $oFilterAttr->getNumberValues() > 1)}]
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle[{if $dActiveValueCount}] active[{/if}]" data-toggle="dropdown">
                                        <span class="filter-name">[{if $oFilterAttr->get_gw_filter_name()}][{$oFilterAttr->get_gw_filter_name()}][{else}][{$oFilterAttr->getTitle()}][{/if}]</span>[{*if $sActiveValue}]<span class="colon">:&nbsp;</span><span class="active-value">[{$sActiveValue}]</span>[{/if*}]
                                        <span class="caret"></span>
                                    </button>

                                    <ul class="dropdown-menu" role="menu">
                                        [{foreach from=$oFilterAttr->getValues() item=sValue}]
                                            [{assign var="sActiveValue" value=$oFilterAttr->getActiveValue($sValue)}]
                                            [{if $sActiveValue}][{assign var="hasActiveValue" value=$sActiveValue}][{assign var="hasActiveFilter" value=$sActiveValue}][{/if}]
                                            <li><label><input type="checkbox" name="attrfilter[[{$sAttrID}]][]" value="[{$sValue}]"[{if $sActiveValue == $sValue}] checked="checked"[{/if}]><span>[{$sValue}]</span></label></li>
                                        [{/foreach}]
                                        [{if $dActiveValueCount}]
                                            <li>
                                                <label class="gw-reset-filter" href="#"><input type="checkbox" name="attrfilter[[{$sAttrID}]]" value=""><span>[{oxmultilang ident="GW_RESET_FILTER"}]</span></label>
                                            </li>
                                        [{/if}]
                                    </ul>
                                </div>
                            [{/if}]
                        [{/foreach}]
                    </form>
                    <div class="list-filter-actions col-xs-10">
                        [{if $hasActiveFilter}]
                            <button type="submit" class="btn btn-default btn-sm ml-2 gw-reset-all-filters">[{oxmultilang ident="GW_RESET_ALL_FILTERS"}]</button>
                        [{/if}]
                    </div>
                </div>
            </div>
        </div>
    </div>
[{/if}]