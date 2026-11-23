{* input.ecb2fd_admin_text.tpl - v1.0 - 25Jun22 

***************************************************************************************************}
{if !empty($description)}
        {$description}<br>
{/if}
{if $color || $background_color}
        <div class="ecb2-admin-text" style="{if $color}color:{$color};{/if}{if $background_color}background-color:{$background_color}; padding:0.5em 0.75em;{/if}">{$text}</div>
{else}
        {$text}
{/if}