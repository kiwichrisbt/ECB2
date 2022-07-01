<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------


class ecb2fd_file_selector extends ecb2_FieldDefBase 
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
        $this->default_parameters = [
            'filetypes'     => ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
            'excludeprefix' => ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
            'recurse'       => ['default' => '',    'filter' => FILTER_VALIDATE_BOOLEAN],
            'sortfiles'     => ['default' => '',    'filter' => FILTER_VALIDATE_BOOLEAN],
            'dir'           => ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
            'preview'       => ['default' => '',    'filter' => FILTER_VALIDATE_BOOLEAN],
            'description'   => ['default' => '',    'filter' => FILTER_SANITIZE_STRING]
        ];
        // $this->parameter_aliases = [ 'alias' => 'parameter' ];
        // $this->restrict_params = FALSE;    // default: true

    }


    /**
     *  @return string complete content block 
     */
    public function get_content_block_input() 
    {
        $config = cmsms()->GetConfig();
        $adddir = get_site_preference('contentimage_path');
        if ($this->options['dir']) $adddir = $this->options['dir'];
        $uploads_url = $config['uploads_url'];

        // Get the directory contents
        $dir = cms_join_path( $config['uploads_path'], $adddir );
        $filetypes = $this->options['filetypes'];
        if ($filetypes != '') {
            $filetypes = explode(',', $filetypes);
        }

        $excludes = $this->options['excludeprefix'];
        if ($excludes != '') {
            $excludes = explode(',', $excludes);
            for ($i = 0; $i < count($excludes); $i++) {
                $excludes[$i] = $excludes[$i] . '*';
            }
        }
        $maxdepth = !empty($this->options['recurse']) ? -1 : 0;   // default
        $fl = get_recursive_file_list( $dir, $excludes, $maxdepth, 'FILES' );

        // Remove prefix
        $filelist = array();
        for ($i = 0; $i < count($fl); $i++) {
            if ( in_array( pathinfo($fl[$i], PATHINFO_EXTENSION), $filetypes ) )
                $filelist[] = str_replace($dir, '', $fl[$i]);
        }

        // Sort
        if (is_array($filelist) && $this->options['sortfiles']) {
            sort($filelist);
        }

        // create select options
        $opts = array();
        $url_prefix = $adddir;
        for ($i = 0; $i < count($filelist); $i++) {
            $opts[$url_prefix.$filelist[$i]] = $filelist[$i];
        }
        $opts = array('' => '') + $opts;

        $smarty = \CmsApp::get_instance()->GetSmarty();
        $tpl = $smarty->CreateTemplate( 'string:'.$this->get_template(), null, null, $smarty );
        $tpl->assign('block_name', $this->block_name );
        $tpl->assign('value', $this->value );

        $tpl->assign('opts', $opts );
        $tpl->assign('preview', $this->options['preview'] );
        $tpl->assign('uploads_url', $uploads_url );

        $tpl->assign('description', $this->options['description'] );
        return $tpl->fetch();
   
    }


}