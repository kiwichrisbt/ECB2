<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016-2023 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/docs/LICENSE or <http://www.gnu.org/licenses/gpl-3.0.html>
#---------------------------------------------------------------------------------------------------


namespace ECB2;

class ecb2fd_admin_text extends ecb2_FieldDefBase 
{

	public function __construct($mod, $blockName, $value, $params, $adding, $id=0) 
	{	
		parent::__construct($mod, $blockName, $value, $params, $adding, $id);

        $this->get_values($value);              // common FieldDefBase method

        $this->set_field_parameters();

        $this->initialise_options($params);     // common FieldDefBase method
        
	}



    /**
     *  sets the allowed parameters for this field type
     *
     *  $this->default_parameters - array of parameter_names => [ default_value, filter_type ]
     *      self::ECB2_SANITIZE_STRING, FILTER_VALIDATE_INT, FILTER_VALIDATE_BOOLEAN, 
     *      FILTER_SANITIZE_EMAIL, FILTER_DEFAULT (do nothing)
     *      see: https://www.php.net/manual/en/filter.filters.php
     *  $this->restrict_params - optionally allow any other parameters to be included, e.g. module calls
     */
    public function set_field_parameters() 
    {
        $this->field_aliases = ['text'];
        $this->default_parameters = [
            'text'              => ['default' => '',    'filter' => FILTER_DEFAULT],
            'color'             => ['default' => '',    'filter' => self::ECB2_SANITIZE_STRING],
            'background_color'  => ['default' => '', 'filter' => self::ECB2_SANITIZE_STRING],
            'admin_groups'      => ['default' => '',    'filter' => self::ECB2_SANITIZE_STRING],
            'description'       => ['default' => '',    'filter' => FILTER_DEFAULT]
        ];
        // $this->parameter_aliases = [ 'alias' => 'parameter' ];
        $this->restrict_params = FALSE;    // default: true  - to enable text to include html
        $this->help_category = self::ECB2_HELP_CATEGORY_ADMIN;

    }


    /**
     *  @return string the colour if it is a hex, named, rgb(a) or hsl(a) colour - otherwise empty string
     */
    private function valid_css_color($color)
    {
        $color = trim($color);
        if ( preg_match('/\A(?:#[0-9a-f]{3,8}|[a-z]+|(?:rgb|hsl)a?\([0-9.,%\s\/]+\))\z/i', $color) ) return $color;
        return '';
    }



    /**
     *  @return string complete content block 
     */
    public function get_content_block_input() 
    {
        if ( !empty($this->options['admin_groups']) && 
             !$this->is_valid_group_member($this->options['admin_groups']) ) {
            return $this->ecb2_hidden_field(); 
        }

        $smarty = \CmsApp::get_instance()->GetSmarty();
        $tpl = $smarty->CreateTemplate( 'string:'.$this->get_template(), null, null, $smarty );
        $tpl->assign('block_name', $this->block_name );
        $tpl->assign('text', $this->options['text'] );
        $tpl->assign('color', $this->valid_css_color($this->options['color']) );
        $tpl->assign('background_color', $this->valid_css_color($this->options['background_color']) );
        $tpl->assign('description', $this->options['description'] );
        return $tpl->fetch();
   
    }


}