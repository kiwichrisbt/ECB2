<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------


class ecb2fd_textinput extends ecb2_FieldDefBase 
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
        // $this->restrict_params = FALSE;    // default: true
        // $this->parameter_aliases = [ 'alias' => 'parameter' ];
        $this->default_parameters = [
            'default_value' => ['default' => '',    'filter' => FILTER_SANITIZE_STRING], 
            'size'          => ['default' => 30,    'filter' => FILTER_VALIDATE_INT],
            'max_length'    => ['default' => 255,   'filter' => FILTER_VALIDATE_INT],
            'description'   => ['default' => '',    'filter' => FILTER_SANITIZE_STRING]
        ];
    }


    /**
     *  @return string complete content block 
     */
    public function get_content_block_input() 
    {
        $smarty = \CmsApp::get_instance()->GetSmarty();
        $tpl = $smarty->CreateTemplate( 'string:'.$this->get_template(), null, null, $smarty );
        $tpl->assign('block_name', $this->block_name );
        $tpl->assign('value', $this->value );
        $tpl->assign('size', $this->options['size'] );
        $tpl->assign('max_length', $this->options['max_length'] );
        $tpl->assign('description', $this->options['description'] );
        return $tpl->fetch();
   
    }


}