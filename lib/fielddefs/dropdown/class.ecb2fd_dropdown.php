<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------


class ecb2fd_dropdown extends ecb2_FieldDefBase 
{

	public function __construct($mod, $blockName, $value, $params, $adding) 
	{	
		parent::__construct($mod, $blockName, $value, $params, $adding);
        
	}



    /**
     *  sets the allowed parameters for this field type
     *
     *  $this->default_parameters - array of parameter_names => [ default_value, filter_type ]
     *      FILTER_SANITIZE_STRING, FILTER_VALIDATE_INT, FILTER_VALIDATE_BOOLEAN, FILTER_SANITIZE_EMAIL 
     *      see: https://www.php.net/manual/en/filter.filters.php
     *  $this->restrict_params - optionally allow any other parameters to be included, e.g. module calls 
     */
    public function set_field_parameters() 
    {
        $this->restrict_params = FALSE;    // default: TRUE - needed for module call
        $this->parameter_aliases = [
            'gcb' => 'template'
        ];
        $this->default_parameters = [
            'values'        => ['default' => '',    'filter' => FILTER_SANITIZE_STRING], 
            'default_value' => ['default' => '',    'filter' => FILTER_SANITIZE_STRING], 
            'size'          => ['default' => 5,     'filter' => FILTER_VALIDATE_INT],
            'multiple'      => ['default' => FALSE, 'filter' => FILTER_VALIDATE_BOOLEAN],
            'first_value'   => ['default' => FALSE, 'filter' => FILTER_VALIDATE_BOOLEAN],
            'compact'       => ['default' => FALSE, 'filter' => FILTER_VALIDATE_BOOLEAN],
            'flip_values'   => ['default' => FALSE, 'filter' => FILTER_VALIDATE_BOOLEAN],
            'mod'           => ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
            'udt'           => ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
            'template'      => ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
            'customgs_field'=> ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
            'description'   => ['default' => '',    'filter' => FILTER_SANITIZE_STRING]
        ];

    }



//

    /**
     *  @return string complete content block 
     */
    public function get_content_block_input() 
    {
        // get the dropdown values/options
        if ( $this->options['mod'] ) {  
            // call module to get values (comma separated list)
            $exclude_options = ['size','multiple','values','default_value','first_value','description',
                'compact','field','mod','flip_values','template','udt','gbc','customgs_field'];
            $options = $this->get_values_from_module($this->options['mod'], [], $exclude_options);

        } elseif ( $this->options['udt'] ) {  
            // run UDT to get values (array or comma separated list)
            $options = $this->get_values_from_udt( $this->options['udt'] );
            if ($this->error) return $this->mod->error_msg($this->error);

        } elseif ( $this->options['template'] ) {  
            // smarty template to get values (array or comma separated list)
            $options = $this->get_values_from_template( $this->options['template'] );
            if ($this->error) return $this->mod->error_msg($this->error);

        } elseif ( $this->options['customgs_field'] ) {  
            // CustomGS field to get values from (newline or comma separated list)
            $options = $this->get_values_from_customgs( $this->options['customgs_field'] );
            if ($this->error) return $this->mod->error_msg($this->error);

        } else { 
            // use provided 'values' (comma separated list)
            $options = $this->get_array_from_csl( $this->options['values'] );
        
        }

        // apply some other parameters
        if ( $this->options['flip_values'] && !empty($options) ) { 
            $options = array_flip($options);
        }
        if ( !empty($this->options['compact']) ) {
            $this->options['size'] = count($options);
        }
        if ( !empty($this->options['first_value']) ) {
            $options = array( '' => $this->options['first_value'] ) + $options;
        }
        
        $smarty = \CmsApp::get_instance()->GetSmarty();
        $tpl = $smarty->CreateTemplate( 'string:'.$this->get_template(), null, null, $smarty );
        $tpl->assign( 'mod', $this->mod );
        $tpl->assign( 'block_name', $this->block_name );
        $tpl->assign( 'description', $this->options['description'] );
        $tpl->assign( 'multiple', $this->options['multiple'] );
        $tpl->assign( 'compact', $this->options['compact'] );
        $tpl->assign( 'size', $this->options['size'] );
        $tpl->assign( 'selected', $this->value );

        if ( $this->options['multiple'] ) {
            $selected_values = explode(',', $this->value);
            $selected_text = [];
            foreach ($selected_values as $value) {
                $selected_text[] = array_search( $value, $options );
            }
            $selected_text = implode(', ', $selected_text);
            $tpl->assign( 'selected_values', $selected_values );  // array
            $tpl->assign( 'selected_text', $selected_text );      // text
        }

        $tpl->assign('options', $options );
        return $tpl->fetch();
   
    }



}