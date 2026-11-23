{* input.ecb2fd_admin_fieldset_start.tpl - v1.0 - 25Jun22 

***************************************************************************************************}
{if !$is_demo}
    </p>
        </div>
{/if}
            <fieldset name="{$block_name}"{if $collapsible} class="ecb2-fieldset-collapsible"{/if}>
        {if $legend}
            <legend{if $collapsible} tabindex="0" role="button" aria-expanded="true"{/if}>{$legend}</legend>
        {/if}
{if !empty($description)}
            {$description}<br>
{/if}
{if !$is_demo}
        <div>
    <p>
{/if}