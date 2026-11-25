{* ECB2 admin_content_blocks.tpl *}

<div id="ecb_c" class="ecb2-readme">
    <div class="help-toc">
        <h3 class="border-bottom m_top_15 p_bottom_5">{$mod->Lang('field_types')}</h3>
        <ul>
        {$current_category=''}
        {foreach $categorised_field_types as $help_category => $field_types}
            {if $current_category != $help_category}
                <h4 class="border-bottom m_top_25 p_bottom_5">{$mod->Lang($help_category)}</h4>
                {$current_category = $help_category}
            {/if}
            {foreach $field_types as $field_type}
            <li><a class="tab-and-smooth-scroll" href="#{$field_type}">{$field_type}</a></li>   
            {/foreach}
        {/foreach}
        </ul>
    </div>

    <div class="help-content">
    {if isset($readme_contents)}
        {$readme_contents}
    {/if}
    </div>
    <br>

    <div class="help-content">
        {$mod->Lang('usage')}
    </div>
</div>


<div id="fields_c">
    <div class="help-toc">
        <h3 class="border-bottom m_top_15 p_bottom_5">{$mod->Lang('field_types')}</h3>
        <ul>
        {$current_category=''}
        {foreach $categorised_field_types as $help_category => $field_types}
            {if $current_category != $help_category}
                <h5 class="border-bottom m_top_25 p_bottom_5">{$mod->Lang($help_category)}</h5>
                {$current_category = $help_category}
            {/if}
            {foreach $field_types as $field_type}
                <li><a class="smooth-scroll" href="#{$field_type}">{$field_type}</a></li>   
            {/foreach}
        {/foreach}
        </ul>
    </div>

    <div class="help-content">
        <h2 class="border-bottom p_bottom_5 m_bottom_15">{$mod->Lang('field_types')}</h2>

    {$current_category=''}
    {foreach $categorised_field_types as $help_category => $field_types}
        {if $current_category != $help_category}
            <h2 class="border-bottom m_top_25 p_bottom_5">{$mod->Lang($help_category)}</h2>
            {$current_category = $help_category}
        {/if}
        {foreach $field_types as $field_type}
            {if isset($field_help[$field_type])}
                {$help_content = $field_help[$field_type]}
        <h2 id="{$field_type}" class="m_bottom_5">{$field_type}</h2>
        {$help_content}
        <br>
            {/if}
        {/foreach}
    {/foreach}
    </div>
</div>


