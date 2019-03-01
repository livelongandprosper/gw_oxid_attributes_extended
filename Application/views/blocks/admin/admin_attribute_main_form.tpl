[{assign var="oConfig" value=$oViewConf->getConfig()}]

[{$smarty.block.parent}]

<tr>
    <td class="edittext" width="120">
        Auf Detailseite anzeigen
    </td>
    <td class="edittext">
        <input type="hidden" name="editval[oxattribute__gw_display_on_detailspage]" value='0' [{$readonly}]>
        <input class="edittext" type="checkbox" name="editval[oxattribute__gw_display_on_detailspage]" value='1' [{if $edit->oxattribute__gw_display_on_detailspage->value == 1}]checked[{/if}] [{$readonly}]>
    </td>
</tr>

<tr>
    <td class="edittext" width="120">
        Attribut ID
    </td>
    <td class="edittext">
        <input class="edittext" type="text" name="editval[oxattribute__gw_attribute_id]" value='[{if $edit->oxattribute__gw_attribute_id->value}][{$edit->oxattribute__gw_attribute_id->value}][{/if}]' [{$readonly}]>
    </td>
</tr>
