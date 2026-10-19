{* input.ecb2fd_sortablelist.tpl - v1.0 - 29Jun22 

***************************************************************************************************}
{function name=ecbSortablelistTemplate is_sub_field=false fieldid='' fieldname=''}

    <!--
    $is_sub_field: {if $is_sub_field}true{else}false{/if}
    $fieldid: {$fieldid}
    $fieldname: {$fieldname}
    -->
    <input type="hidden" id="{$fieldid}" name="{$fieldname}" value="{$selected_str}" size="100" class="{if $is_sub_field && is_null($sub_row_number)}repeater-field{/if}" {if $is_sub_field && is_null($sub_row_number)}data-repeater="#{$block_name}-repeater" data-field-name="{$block_name}"{/if} />
    <div class="c_full cf ecb2-cb {$name}"> 
        <div class="grid_6 alpha">
            <fieldset>
                <legend>{if $labelLeft!=''}{$labelLeft}{else}{$mod->Lang('content_block_label_selected')}{/if}</legend>
                <div id="selected-items">
                    <ul class="sortable-ecb2-list sortable-list selected-items" data-cmsms-cb-input="{$fieldid}"
                    {if $maxNumber} data-max-number="{$maxNumber}"{/if}{if $requiredNumber} data-required-number="{$requiredNumber}"{/if}>
                        <li class="placeholder no-sort {if $selected|@count>0} hidden{/if}">{if $requiredNumber}{$mod->Lang('drop_required_items', $requiredNumber)}{else}{$mod->Lang('drop_items')}{/if}</li>
                        {foreach $selected as $alias => $name}
                            <li class="ui-state-default cf sortable-item" data-cmsms-item-id="{$alias}">
                                {$name}
                                <a href="#" title="{$mod->Lang('remove')}" class="ui-icon ui-icon-trash sortable-remove">{$mod->Lang('remove')}</a>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </fieldset>
        </div>
        <div class="grid_6 omega draggable-area">
            <fieldset>
                <legend>{if $labelRight!=''}{$labelRight}{else}{$mod->Lang('content_block_label_available')}{/if}</legend>
                <div id="available-items">
                    <ul class="sortable-ecb2-list sortable-list available-items">
                    {foreach $available as $alias => $name}
                            <li class="ui-state-default" data-cmsms-item-id="{$alias}">
                                {$name}
                                <a href="#" title="{$mod->Lang('remove')}" class="ui-icon ui-icon-trash sortable-remove hidden">{$mod->Lang('remove')}</a>
                            </li>
                    {/foreach}
                    </ul>
                </div>
            </fieldset>
        </div>
    </div>
{/function}



{if !empty($description)}
        {$description}<br>
{/if}

{if $is_sub_field}
    {if is_null($sub_row_number)}{* sub_field template field *}
        {ecbSortablelistTemplate is_sub_field=true fieldid='' fieldname=''}
        {* <select id="" name="" class="{$class}" 
        data-repeater="#{$block_name}-repeater" data-field-name="{$block_name}">
            {html_options options=$galleryArray selected=$value}
        </select> *}


    {else}{* sub_field *}
        {ecbSortablelistTemplate is_sub_field=true fieldid=$subFieldId fieldname=$subFieldName}
        {* <select id="{$subFieldId}" name="{$subFieldName}" class="{$class}">
            {html_options options=$galleryArray selected=$value}
        </select> *}

    {/if}

{else}{* standalone field *}
    {ecbSortablelistTemplate is_sub_field=false fieldid=$selectarea_prefix fieldname=$selectarea_prefix}

{/if}
{*
    <input type="hidden" id="{$selectarea_prefix}" name="{$selectarea_prefix}" value="{$selected_str}" size="100"/>
    <div class="c_full cf ecb2-cb {$selectarea_prefix}">
        <div class="grid_6 alpha">
            <fieldset>
                <legend>{if $labelLeft!=''}{$labelLeft}{else}{$mod->Lang('content_block_label_selected')}{/if}</legend>
                <div id="selected-items">
                    <ul class="sortable-ecb2-list sortable-list selected-items" data-cmsms-cb-input="{$selectarea_prefix}"
                    {if $maxNumber} data-max-number="{$maxNumber}"{/if}{if $requiredNumber} data-required-number="{$requiredNumber}"{/if}>
                        <li class="placeholder no-sort {if $selected|@count>0} hidden{/if}">{if $requiredNumber}{$mod->Lang('drop_required_items', $requiredNumber)}{else}{$mod->Lang('drop_items')}{/if}</li>
                        {foreach $selected as $alias => $name}
                            <li class="ui-state-default cf sortable-item" data-cmsms-item-id="{$alias}">
                                {$name}
                                <a href="#" title="{$mod->Lang('remove')}" class="ui-icon ui-icon-trash sortable-remove">{$mod->Lang('remove')}</a>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </fieldset>
        </div>
        <div class="grid_6 omega draggable-area">
            <fieldset>
                <legend>{if $labelRight!=''}{$labelRight}{else}{$mod->Lang('content_block_label_available')}{/if}</legend>
                <div id="available-items">
                    <ul class="sortable-ecb2-list sortable-list available-items">
                    {foreach $available as $alias => $name}
                            <li class="ui-state-default" data-cmsms-item-id="{$alias}">
                                {$name}
                                <a href="#" title="{$mod->Lang('remove')}" class="ui-icon ui-icon-trash sortable-remove hidden">{$mod->Lang('remove')}</a>
                            </li>
                    {/foreach}
                    </ul>
                </div>
            </fieldset>
        </div>
    </div>
*}