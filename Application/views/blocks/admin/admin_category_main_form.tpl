[{$smarty.block.parent}]
<tr>
    <td class="edittext">
        Variantenfilter deaktivieren
    </td>
    <td class="edittext" colspan="2">
        <input type="hidden" name="editval[oxcategories__gw_deactivate_variant_filter]" value='0' [{$readonly_fields}]>
        <input class="edittext" type="checkbox" name="editval[oxcategories__gw_deactivate_variant_filter]" value='1' [{if $edit->oxcategories__gw_deactivate_variant_filter->value == 1}]checked[{/if}] [{$readonly_fields}]>
    </td>
</tr>
