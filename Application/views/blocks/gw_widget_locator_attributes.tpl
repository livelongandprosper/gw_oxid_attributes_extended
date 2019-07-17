[{*$smarty.block.parent*}]

gw_widget_locator_attributes.tpl
	[{if $attributes}]
	<div class="row">
		<div class="col-xs-12">
			<div class="list-filter clearfix">
				<form method="get" action="[{$oViewConf->getSelfActionLink()}]" name="_filterlist" id="filterList" class="pull-left">
					<div class="hidden">
						[{$oViewConf->getHiddenSid()}]
						[{$oViewConf->getNavFormParams()}]
						<input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
						<input type="hidden" name="tpl" value="[{$oViewConf->getActTplName()}]">
						<input type="hidden" name="oxloadid" value="[{$oViewConf->getActContentLoadId()}]">
						<input type="hidden" name="fnc" value="executefilter">
						<input type="hidden" name="fname" value="">
					</div>

					[{if $oView->getClassName() == 'alist'}]
						<strong>[{oxmultilang ident="DD_LISTLOCATOR_FILTER_ATTRIBUTES"}]</strong>
					[{/if}]
					[{foreach from=$attributes item=oFilterAttr key=sAttrID name=attr}]
						[{assign var="sActiveValue" value=$oFilterAttr->getActiveValue()}]
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
								<strong>[{$oFilterAttr->getTitle()}]:</strong>
								[{if $sActiveValue}]
									[{$sActiveValue}]
								[{/if}]
								<span class="caret"></span>
							</button>

                            [{* TODO: hier muss der artikelfilter als array übergeben werden ... dann geht es weiter in ArticleList.php -> _getFilterIdsSql() *}]
							<input type="hidden" name="attrfilter[[{$sAttrID}]]" value="[{$sActiveValue}]">
							<ul class="dropdown-menu" role="menu">
								[{foreach from=$oFilterAttr->getValues() item=sValue}]
									<li><a data-selection-id="[{$sValue}]" href="#" [{if $sActiveValue == $sValue}]class="selected"[{/if}] >[{$sValue}]</a></li>
								[{/foreach}]
								[{if $sActiveValue}]
									<li><a data-selection-id="" href="#">[{oxmultilang ident="GW_RESET_FILTER"}]</a></li>
								[{/if}]
							</ul>
						</div>
						[{if $sActiveValue}][{assign var="hasActiveValue" value=$sActiveValue}][{/if}]
					[{/foreach}]
				</form>
				[{if $hasActiveValue}]
					<button type="submit" class="btn btn-default btn-sm ml-2 gw-reset-all-filters">[{oxmultilang ident="GW_RESET_ALL_FILTERS"}]</button>
				[{/if}]
			</div>
		</div>
	</div>
	[{/if}]
