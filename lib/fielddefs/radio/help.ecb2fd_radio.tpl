{* help.ecb2fd_radio.tpl *}
<p>The radio field creates a simple text input, for storing a single string.</p>

<pre>{literal}{content_module module="ECB2" field="radio" block="test17" label="Fruit" values="Apple=apple,Orange=orange,Kiwifruit=kiwifruit" default_value="Orange"}{/literal}</pre>

<p>Parameters:</p>
<ul>
    <li>field (required) - 'radio'</li>
    <li>values (required) - comma separated list of 'Text' or 'Text=value'. Example: 'Apple=apple,Orange=orange,Kiwifruit=kiwifruit'</li>
    <li>flip_values (optional) - swaps the dropdowns values <-> text</li>
    <li>inline (optional) - if set displays admin radio buttons inline</li>
    <li>default_value (optional) - initial value when creating a new page</li>
    <li>description (optional) - adds additional text explanation for editor</li>
</ul>