<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------


class ecb2fd_gallery_picker extends ecb2_FieldDefBase 
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
            'dir'           => ['default' => '',    'filter' => FILTER_SANITIZE_STRING],
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
        $dir = $this->options['dir'].'/';    // default dir (needs '/' at end)
        $GalleryModule = cms_utils::get_module('Gallery');
        if (!is_object($GalleryModule)) {
            $this->error = $this->mod->Lang('gallery_module_error');
            return $this->mod->error_msg($this->error);
        }

        $galleries = Gallery_utils::GetGalleries();
        $galleryArray = array('' => $this->mod->Lang('none_selected') );

        foreach ($galleries as $gallery) {
            if ($gallery['filename']!='') {    // ignores default gallery
                if ($dir!='/') {
                // only select sub-galleries of $dir
                $isSubDir = stripos($gallery['filepath'], $dir);

                if ($isSubDir!==FALSE && $isSubDir==0) {
                    $gallery_dir = $gallery['filepath'].rtrim($gallery['filename'], '/');
                    $galleryArray[$gallery_dir] = $gallery['title'];
                }

                } else {
                // select all galleries
                $gallery_dir = $gallery['filepath'].rtrim($gallery['filename'], '/');
                $galleryArray[$gallery_dir] = $gallery['title'];
                }
            }
        }
  
        $smarty = \CmsApp::get_instance()->GetSmarty();
        $tpl = $smarty->CreateTemplate( 'string:'.$this->get_template(), null, null, $smarty );
        $tpl->assign('block_name', $this->block_name );
        $tpl->assign('value', $this->value );
        $tpl->assign('galleryArray', $galleryArray );
        $tpl->assign('description', $this->options['description'] );
        return $tpl->fetch();
   
    }


}