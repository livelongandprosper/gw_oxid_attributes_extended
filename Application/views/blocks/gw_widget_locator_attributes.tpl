[{*$smarty.block.parent*}]
[{if $attributes}]
	<div class="row">
		<div class="col-xs-12">
			<div class="list-filter clearfix">
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
						[{assign var="sActiveValue" value=$oFilterAttr->getActiveValue()}]
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-sm dropdown-toggle[{if $sActiveValue}] active[{/if}]" data-toggle="dropdown">
								[{$oFilterAttr->getTitle()}][{if $sActiveValue}]: [{$sActiveValue}][{/if}]
								<span class="caret"></span>
							</button>

							<ul class="dropdown-menu" role="menu">
								[{foreach from=$oFilterAttr->getValues() item=sValue}]
									[{assign var="sActiveValue" value=$oFilterAttr->getActiveValue($sValue)}]
									[{if $sActiveValue}][{assign var="hasActiveValue" value=$sActiveValue}][{assign var="hasActiveFilter" value=$sActiveValue}][{/if}]
									<li><label><input type="checkbox" name="attrfilter[[{$sAttrID}]][]" value="[{$sValue}]"[{if $sActiveValue == $sValue}] checked="checked"[{/if}]><span>[{$sValue}]</span></label></li>
								[{/foreach}]
								[{if $hasActiveValue}]
									<li>
										<label class="gw-reset-filter" href="#"><input type="checkbox" name="attrfilter[[{$sAttrID}]]" value=""><span>[{oxmultilang ident="GW_RESET_FILTER"}]</span></label>
									</li>
								[{/if}]
							</ul>
						</div>
					[{/foreach}]
                </form>
                [{if $hasActiveFilter}]
					<button type="submit" class="btn btn-default btn-sm ml-2 gw-reset-all-filters">[{oxmultilang ident="GW_RESET_ALL_FILTERS"}]</button>
				[{/if}]
			</div>
		</div>
	</div>
[{/if}]
