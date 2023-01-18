{* help.ecb2fd_gallery.tpl *}
<p>The gallery field enables multiple images to added by dragging and dropping or uploading. Thumbnails of the images are displayed and created on the server and images can optionally be automatically resized before they are uploaded.</p>

<fieldset>
    {$fielddef->get_demo_input([])}
</fieldset>

<pre>{literal}{content_module module=ECB2 field=gallery block=test20a label='A sample Gallery' assign=test20a}{/literal}</pre>

<p>Parameters:</p>
<ul>
    <li>field (required) - 'gallery'</li>
    <li>block (required) - the name of the content block</li>
    <li>dir (optional) - a sub directory of the uploads directory, if not set a unique directory is created for this content block.</li>
    <li>resize_width (optional) - if set, images will be resized to this width before being uploaded. If only one of resize_width or resize_height is set, the original aspect ratio of the image is preserved.</li>
    <li>resize_height (optional) - if set, images will be resized to this height before being uploaded. If only one of resize_width or resize_height is set, the original aspect ratio of the image is preserved.</li>
    <li>resize_method (optional) - 'contain' (default), or 'crop' can be used.</li>
    <li>thumbnail_width (optional) - sets thumbnail width for this fields thumbnails. If thumbnail_width is set, but thumbnail_height is not, the ratio of the image will be used to calculate thumbnail_height. These settings will default to the ECB2 Thumbnail Width & Height options, or CMSMS Thumbnail Width & Height settings.</li>
    <li>thumbnail_height (optional) - sets thumbnail height for this fields thumbnails. If thumbnail_height is set, but thumbnail_width is not, the ratio of the image will be used to calculate thumbnail_width. These settings will default to the ECB2 Thumbnail Width & Height options, or CMSMS Thumbnail Width & Height settings.</li>
    <li>max_files (optional) - sets a maximum number of files that can be uploaded</li>
    <li>auto_add_delete (optional) default:true - will automatically delete unused files & thumbnails from the directory</li>
    <li>default_value (optional) - initial value when creating a new page</li>
    <li>admin_groups (optional) - a comma separated list of admin groups that can view & edit this field</li>
    <li>description (optional) - adds additional text explanation for editor</li>
</ul><br>

<p>Output format:</p>
<pre>{literal}
{if !empty($test20a->sub_fields)}{* test if any data exists *}
    {foreach $test20->sub_fields as $sub_field}
        {foreach $sub_field as $name => $value}
            {$name}: {$value}
        {/foreach}
        {* or directly access each sub_field *}
        filename is:{$sub_field->filename}
        file_location is:{$sub_field->file_location}
    {/foreach}
{/if}
{/literal}</pre><br>