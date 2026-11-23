{* help.ecb2fd_admin_text.tpl *}
<p>The admin_text field just adds text into the admin page.</p>

<fieldset>
    {$fielddef->get_demo_input(['text'=>'Simply add some text into the admin pages to give some guidance to the editors, or whatever else you want really :).'])}
</fieldset>

<fieldset>
    <legend>Sample with color & background_color</legend>
    {$fielddef->get_demo_input(['text'=>'Important guidance for the editors.', 'color'=>'#7a1f1f', 'background_color'=>'#fde8e8'])}
</fieldset>

<pre>{literal}{content_module module=ECB2 field=admin_text text='Simply add some text into the admin pages to give some guidance to the editors, or whatever else you want really :).' block=test16 label='16: Text'}{/literal}</pre>

<p>Parameters:</p>
<ul>
    <li>field (required) - 'admin_text', alias: '<b>text</b>'</li>
    <li>block (required) - the name of the content block</li>
    <li>text (required) - text in admin, can include html (add information for users)</li>
    <li>color (optional) - text colour, e.g. 'red', '#c00' or 'rgb(204,0,0)'</li>
    <li>background_color (optional) - background colour behind the text, same formats as color</li>
    <li>admin_groups (optional) - a comma separated list of admin groups that can view & edit this field</li>
    <li>description (optional) - adds additional text explanation for editor</li>
</ul>