<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016-2023 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/lang/LICENCE.txt or <http://www.gnu.org/licenses/gpl-3.0.html>
#---------------------------------------------------------------------------------------------------


class ecb2fd_admin_module_link extends ecb2_FieldDefBase 
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
     *      FILTER_SANITIZE_STRING, FILTER_VALIDATE_INT, FILTER_VALIDATE_BOOLEAN, FILTER_SANITIZE_EMAIL 
     *      see: https://www.php.net/manual/en/filter.filters.php
     *  $this->restrict_params - optionally allow any other parameters to be included, e.g. module calls
     */
    public function set_field_parameters() 
    {
        $this->parameter_aliases = [
            'default_value' => 'default'
        ];
        $this->default_parameters = [
            'mod'           => ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
            'text'          => ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
            'target'        => ['default' => '_self',    'filter' => FILTER_SANITIZE_STRING],
            'size'          => ['default' => 30,    'filter' => FILTER_SANITIZE_STRING],
            'max_length'    => ['default' => 255,    'filter' => FILTER_SANITIZE_STRING],
            'default'       => ['default' => '',    'filter' => FILTER_SANITIZE_STRING], 
            'admin_groups'  => ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
            'description'   => ['default' => '',    'filter' => FILTER_DEFAULT]
        ];
        // $this->parameter_aliases = [ 'alias' => 'parameter' ];
        // $this->restrict_params = FALSE;    // default: true

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

        $target_mod = '';
        if ( $this->options['mod'] ) {
            $target_mod = cms_utils::get_module( $this->options['mod'] );
            if ( !is_object($target_mod) ) {
                $this->error = $this->mod->Lang('module_error', $this->options['mod']);
                return $this->mod->error_msg($this->error);
            }
        }

        $addtext = 'target="'.$this->options['target'].'"';
    
        $smarty = \CmsApp::get_instance()->GetSmarty();
        $tpl = $smarty->CreateTemplate( 'string:'.$this->get_template(), null, null, $smarty );
        $tpl->assign('target_mod', $target_mod );
        $tpl->assign('target', $this->options['target'] );
        $tpl->assign('text', $this->options['text'] );
        $tpl->assign('addtext', $addtext );
        $tpl->assign('description', $this->options['description'] );
        return $tpl->fetch();
   
    }


}