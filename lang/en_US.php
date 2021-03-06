<?php

$lang['friendlyname'] = 'Extended Content Blocks 2';
$lang['postinstall'] = 'Extended Content Blocks 2 was successful installed';
$lang['postuninstall'] = 'Extended Content Blocks 2 was successful uninstalled';
$lang['really_uninstall'] = 'Really? Are you sure you want to unsinstall this fine module?';
$lang['uninstalled'] = 'Module Uninstalled.';
$lang['installed'] = 'Module version %s installed.';
$lang['upgraded'] = 'Module upgraded to version %s.';
$lang['module_description'] = 'This module adds new content blocks to CMS Made Simple v2+';

$lang['selected'] = 'Selected';
$lang['select'] = 'Select';
$lang['refresh'] = 'Refresh';

$lang['content_block_label_selected'] = 'Selected';
$lang['content_block_label_available'] = 'Available';
$lang['drop_items'] = 'No items selected - drop selected items here';
$lang['drop_required_items'] = 'Drop %s required items here';
$lang['remove'] = 'Remove';

$lang['udt_error'] = 'UDT \'%s\' does not exist';
$lang['customgs_field_error'] = "Please create option '%s' in CustomGS";
$lang['field_error'] = 'Please specify a correct field parameter for the ECB2 content block.';

$lang['gallery_module_error'] = 'Gallery module is not installed.';

$lang['add_line'] = 'Add line';
$lang['remove_line'] = 'Remove line';

$lang['none_selected'] = '--- none ---';








###    ###   #########   ###        #########
###    ###   #########   ###        #########
###    ###   ###         ###        ###   ###
##########   #########   ###        #########
##########   #########   ###        #########
###    ###   ###         ###        ###
###    ###   #########   #########  ###
###    ###   #########   #########  ###



$lang['general'] = 'General';
$lang['general_c'] = '
   <h3>What Does This Do?</h3>
   <p>Module adds aditional content blocks for CMS Made Simple v2+. This is a fork of the original ECB module that worked with CMSMS v1.</p>
   <p>Provides the following field types:<br>
   <ol>
      <li>file_selector</li>
      <li>color_picker</li>
      <li>dropdown</li>
      <li>dropdown_from_udt</li>
      <li>dropdown_from_customgs</li>
      <li>checkbox</li>
      <li>module_link</li>
      <li>link</li>
      <li>timepicker</li>
      <li>datepicker</li>
      <li>input</li>
      <li>textarea</li>
      <li>editor (textarea with wysiwyg)</li>
      <li>text </li>
      <li>pages </li>
      <li>hr (horizontal line)</li>
      <li>sortablelist</li>
      <li>radio</li>
      <li>hidden</li>
      <li>fieldset_start</li>
      <li>fieldset_end</li>
      <li>gallery_picker</li>
      <li>input_repeater</li>
      <li>image <span style="color:red;">new</span></li>
   </ol>
   </p><br>

   <p>If you like this module please <a href="http://www.cmsmadesimple.org/about-link/donations/" target="_blank" style="font-weight:bold;">donate to CMSMS</a></p><br>
   ';

$lang['about'] = 'About';
$lang['about_c'] = '
   <h3>Upgrade from ECB</h3>
   <p>Install ECB2 module and change all "module" parameters, in content_module tags to be module="ECB2" (was "ECB"). Then ECB can be uninstalled.</p><br>


    <h3>Support</h3>
    <p>As per the GPL licence, this software is provided as is. Please read the text of the license for the full disclaimer.
    The module author is not obligated to provide support for this code. However you might get support through the following:</p>
    <ul>
        <li>For support, first <strong>search</strong> the <a href="//forum.cmsmadesimple.org">CMS Made Simple Forum</a>, for issues with the module similar to those you are finding.</li>
        <li>Then, if necessary, open a <strong>new forum topic</strong> to request help, with a thorough description of your issue, and steps to reproduce it.</li>
        <li>If you find a bug you can <a href="http://dev.cmsmadesimple.org/bug/list/1366">submit a Bug Report</a>.</li>
        <li>For any good ideas you can <a href="http://dev.cmsmadesimple.org/feature_request/list/1366">submit a Feature Request</a>.</li>
        <li>If you found the Module useful - shout out to me on Twitter <a href="//twitter.com/KiwiChrisBT">@KiwiChrisBT</a></li>
    </ul><br>

    <h3>Sponsor Development</h3>
    <p>If you would like a new field or feature added to this module, please contact me. You can sponsor development from ??100.</p><br>


   <h3>Copyright &amp; Licence</h3>
   <p>Copyright ?? 2019, Chris Taylor < chris at binnovative dot co dot uk >. All Rights Are Reserved.</p><br>
   <p>This module has been released under the GNU Public License v3. However, as a special exception to the GPL, this software is distributed as an addon module to CMS Made Simple. You may only use this software when there is a clear and obvious indication in the admin section that the site was built with CMS Made Simple!</p><br>
   <br>
';


$lang['fields'] = 'Fields';
$lang['fields_c'] = <<<'EOD'

<h3>Fields</h3>

<p><strong>file_selector</strong></p>
<p>Example:  {content_module module="ECB2" field="file_selector" block="test10" dir="images" filetypes="jpg,gif,png" excludeprefix="thumb_"}        </p>
<p>Parameters:
filetypes - comma separated<br>
dir (optional) - default uploads/<br>
excludeprefix (optional)<br>
recurse (optional) - default false, recurse=1 will show all files in subfolders
sortfiles (optional)<br>
preview (optional) - only for images<br>
description (optional) - adds additional text explanation for editor
</p><br>


<p><strong>color_picker</strong></p>
<p>Example:  {content_module module="ECB2" field="color_picker" block="test1" label="Color" default_value="#000000"}</p>
<p>Parameters:
default_value (optional)<br>
size (optional) - default 10<br>
description (optional) - adds additional text explanation for editor<br>
clear_css_cache (optional) - if set the css cache is refreshed if the color is changed<br>
no_hash (optional) - if set the rgb value displayed and returned does not include a '#'<br>
</p><br>



<p><strong>dropdown</strong></p>
<p>Creates a dropdown (or select) that the editor can choose from the provided options. Either the 'values' or 'mod' parameter must be used (*).
<p>Parameters:</p>
<ul>
    <li>values (*) - comma separated string of 'Text' or 'Text=value'. Example: 'Apple=apple,Orange=orange,Green=green' <br>
        <pre>{content_module module="ECB2" field="dropdown" block="test5" label="Fruit" values="Apple=apple,Orange=orange" first_value="select fruit"}</pre></li>
    <li>mod (*) - specifies the name of a module to call to get the option values. Returned values should be a comma separated string of 'Text' or 'Text=value' As above. Any additional parameters are also passed onto the specified module. You may want to include 'action' and 'template' parameters<br>
        <pre>{content_module module=ECB2 field=dropdown block=dropdown2 label="Dropdown - from module" mod=LISEProjects template_summary="item_dropdown" pagelimit=2}</pre>
    </li>
    <li>first_value (optional) - sets an text sting for the first blank value, e.g. '--- select one ---'</li>
    <li>multiple (optional) - add multiple option select support</li>
    <li>size (optional) - multiple enabled only</li>
    <li>description (optional) - adds additional text explanation for editor</li>
    <li>compact (optional) - default:false - if set, a summary of the selected options is displayed and the full select is shown/hidden when 'edit/hide' is clicked</li>
</ul>
<p>Sample template for a LISE Instance Summary, to use with the mod parameter:</p>
<pre>
{* item_dropdown - LISE Summary template for ECB2 dropdown or sortablelist *}
{foreach $items as $item}
{$item->title}={$item->alias}{if !$item@last},{/if}
{/foreach}
</pre>
<br><br>



<p><strong>dropdown_from_udt</strong></p>
<p>Example: {content_module module="ECB2" field="dropdown_from_udt" block="test2" label="Gallery" udt="mycustomudt"  first_value="=select="}</p>
<p>Ouput from UDT must be array() - example: return array("label"=>"value", "label 2 "=>"value 2")</p>
<p>Parameters:
udt (required) - udt name<br>
first_value (optional) <br>
multiple (optional) - add multiple option select support<br>
size (optional) - multiple enabled only<br>
description (optional) - adds additional text explanation for editor<br>
compact (optional) - default:false - if set, a summary of the selected options is displayed and the full select is shown/hidden when 'edit/hide' is clicked
</p>
<p><strong>Examples UDT</strong>:
<br>
<a href="https://gist.github.com/kuzmany/6779c193b8104aa6abfe">Gallery list from Gallery module</a> <br>
<a href="https://gist.github.com/kuzmany/464276e16f3b74c07555">Group list from FEU</a> <br>
<a href="https://gist.github.com/kuzmany/51583c6439cb041679a6">Users list from FEU</a>
</p><br>


<p><strong>dropdown_from_customgs</strong></p>
<p>Provides a single or multiple select using the content set in a CustomGS Module field.</p>
<p>Requires the CustomGS module and to have a field of type 'text area'.</p>
<p>Example:
      {content_module module='ECB2' field='dropdown_from_customgs' customgs_field='Section_Styles' block=style1 label='Section 1 Layout Style' assign=style1}</p>
<p>Parameters:</p>
<ul>
   <li>customgs_field (required) - name of customgs_field to retrieve (with underscores not spaces)</li>
   <li>description (optional) - an optional description of the field</li>
   <li>multiple (optional) - an option if set, to ouput a multi-select box rather than a single select drowndown</li>
   <li>size (optional) - an option to set the size of a multi-select box</li>
   <li>compact (optional) - default:false - if set, a summary of the selected options is displayed and the full select is shown/hidden when 'edit/hide' is clicked</li>
</ul>
<br>



<p><strong>checkbox</strong></p>
<p>Example: {content_module module="ECB2" field="checkbox" block="test11" label="Checkbox" default_value="1"}</p>
<p>Parameters:
default_value (optional)<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>module_link</strong></p>
<p>Example: {content_module module="ECB2" field="module_link" label="Module edit" block="test3" mod="Cataloger" text="Edit catalog" }</p>
<p>Parameters:
mod (required) <br>
text (required) <br>
target (optional) - default _self<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>link</strong></p>
<p>Example: {content_module module="ECB2" field="link" label="Search" block="test4" target="_blank" link="http://www.bing.com" text="bing search"}</p>
<p>Parameters:
link (required) <br>
text (required) <br>
target (optional) - default _self<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>timepicker</strong></p>
<p>Example: {content_module module="ECB2" field="timepicker" label="Time" block="test45"}</p>
<p>Parameters:
size (optional) default 100<br>
time_format (optional) default HH::ss<br>
max_length (optional) default 10<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>datepicker</strong></p>
<p>Example: {content_module module="ECB2" field="datepicker" label="Date" block="test44" change_year=1 year_range='1990:2050'}</p>
<p>Parameters:
size (optional) default 100<br>
date_format (optional) default yy-mm-dd<br>
time (optional) - add time picker default 0<br>
time_format (optional) default HH::ss<br>
max_length (optional) default 10 <br>
description (optional) - adds additional text explanation for editor<br>
change_month boolean (optional) - default: false. Whether the month should be rendered as a dropdown instead of text.<br>
change_year boolean (optional) - default: false. Whether the year should be rendered as a dropdown instead of text. Use the year_range option to control which years are made available for selection.<br>
year_range string (optional) - default: "c-10:c+10". The range of years displayed in the year drop-down: either relative to today's year ("-nn:+nn"), relative to the currently selected year ("c-nn:c+nn"), absolute ("nnnn:nnnn"), or combinations of these formats ("nnnn:-nn"). 
</p><br>

<p><strong>input</strong></p>
<p>Example: {content_module module="ECB2" field="input" label="Text" block="test5" size=55 max_length=55 default_value="fill it"}</p>
<p>Parameters:
size (optional) default 30<br>
max_length (optional) default 255 <br>
default_value (optional) - default value for input<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>textarea</strong></p>
<p>Example: {content_module module="ECB2" field="textarea" label="Textarea" block="test6" rows=10 cols=40 default_value="fill it"}</p>
<p>Parameters:
rows (optional) default 20<br>
cols (optional) default 80 <br>
default_value (optional) - default value for textarea<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>editor (textarea with wysiwyg)</strong></p>
<p>Example: {content_module module="ECB2" field="editor" label="Textarea" block="test7" rows=10 cols=40 default_value="fill it"}</p>
<p>Parameters:
rows (optional) default 20<br>
cols (optional) default 80 <br>
default_value (optional) - default value for textarea<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>text </strong></p>
<p>Example: {content_module module="ECB2" field="text" label="Text" block="test8" text="Hello word!"}</p>
<p>Parameters:
text (required) text in admin (add information for users)<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>pages </strong></p>
<p>Example: {content_module module="ECB2" field="pages" label="Page" block="test10"}</p>
<p>Parameters:
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>hr (horizontal line)</strong></p>
<p>Example: {content_module module="ECB2" field="hr" label="Other blocks" block="blockname"}<p>
Parameters:
description (optional) - adds additional text explanation for editor
</p><br>


<p><strong>sortablelist</strong></p>
<p>The editor can select and reorder items from a list. Either the 'values', 'udt' or 'mod' parameter must be used (*).
<p>Parameters:</p>
<ul>
    <li>values (*) - comma separated string of 'Text' or 'Text=value'. e.g:<br>
        <pre>{content_module module=ECB2 field=sortablelist block="test5" label="Fruit" values="Apple=apple,Orange=orange,Green=green,value=Label" first_value="select fruit"}</pre>
    </li>
    <li>udt (*) - name of udt that returns an array in the format 'value' => 'Label'<br>
        <pre>{content_module module=ECB2 field=sortablelist block="testsortablelist" label="Choose fruit" udt="myudt"}</pre>
    </li>
    <li>mod (*) - specifies the name of a module to call to get the option values. Returned values should be a comma separated string of 'Text' or 'Text=value' As above. Any additional parameters are also passed onto the specified module. You may want to include 'action' and 'template' parameters. e.g:<br>
        <pre>{content_module module=ECB2 field=sortablelist block=sortablelist2 label="Sortable List - from module" mod=LISEProjects template_summary='item_dropdown' pagelimit=2}</pre>
    </li>
    <li>first_value (optional)</li>
    <li>label_left (optional)</li>
    <li>label_right (optional)</li>
    <li>max_number (optional) - limits the maximum number of items that can be selected</li>
    <li>required_number (optional) - sets a specific number of items that must be selected (or none)</li>
description (optional) - adds additional text explanation for editor
</ul>
<br>


<p><strong>radio</strong> </p>
<p>Example: {content_module module="ECB2" field="radio" block="test17" label="Fruit" values="Apple=apple,Orange=orange,Kiwifruit=kiwifruit" default_value="Orange"}</p>
<p>Parameters:
values (required) - comma separated. Example: Apple=apple,Orange=orange,Kiwifruit=kiwifruit<br>
default_value (optional) - default is first choice - set to default value e.g. "Orange"
inline (optional) - if set displays admin radio buttons inline<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>hidden</strong></p>
<p>Example: {content_module module='ECB2' block='test18hidden' assign='testhidden' field='hidden' value='markervalue'}<br>
Can be used to set a page attribute that can then be accessed (e.g. from a Navigator-Template), using {page_attr page=$node->alias key='testhidden'}</p>
<p>Parameters:
value (required) - hidden value to be saved<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>fieldset_start</strong></p>
<p>Example: {content_module module='ECB2' field='fieldset_start' label='& nbsp;' block='test19fieldset' assign='test19fieldset' legend='Fieldset Test Legend' description='Can add a description in here'}<br>
Creates the start of a fieldset for grouping relavant admin fields together. Note: a matching 'fieldset_end' block is required for each fieldset_start.<br>
TIP: set label='& nbsp;' to not show the field label.</p>
<p>Parameters:
legend (optional) - adds an optional legend (default = no legend)<br>
description (optional) - adds additional text explanation for editor
</p><br>

<p><strong>fieldset_end</strong></p>
<p>Example: {content_module module='ECB2' field='fieldset_end' label='& nbsp;' block='test19fieldsetend' assign='test19fieldsetend' }<br>
Creates the end of a fieldset for grouping relavant admin fields together. Note: a matching 'fieldset_start' block is required for each fieldset_end.<br>
TIP: set label='& nbsp;' to not show the field label.
</p><br>


<p><strong>gallery_picker</strong></p>
<p>Provides a gallery picker for the Gallery module.</p>
<p>Example:
      {content_module module='ECB2' field='gallery_picker' block=pageTopGallery label='Page Top Gallery' dir='page-top-galleries'}</p>
<p>Parameters:</p>
<ul>
   <li>dir (optional) - only returns galleries that are sub-galleries of this gallery dir, default is all galleries, excluding default top level gallery</li>
   <li>description (optional) - a description of the field</li>
</ul>
<br>


<p><strong>input_repeater</strong></p>
<p>Provides one or more text inputs that can be added or removed by the editor.</p>
<p>The content block output is a string with each input field's contents delimiter by '||'. To make the output a more useful array use 'explode', e.g. {"||"|explode:$content_block_name}</p>
<p>Example:
      {$input_repeater_test="||"|explode:"{content_module module='ECB2' field='input_repeater' label='Test 22: input_repeater' block='test22' size=50 max_length=255 description='Press (+) and (-) to add and remove additional input fields'}" scope=global}</p>
<p>To use: {$input_repeater_test|print_r}</p>
<br>


<p><strong>image</strong> <span style="color:red;">new</span></p>
<p>Displays an image on an admin page only. Usefully to provide extra guidance to editors.</p>
<p>Example: {content_module module="ECB2" field="image" label="Test 23: image" block="test23" image='images/test-image-here.jpg' description="Test description (optional) can be shown here - this is an admin only image"}</p>
<p>Parameters:
image (required) - path and filename relative to the uploads directory<br>
description (optional) - adds additional text explanation for editor
</p><br>



EOD;


