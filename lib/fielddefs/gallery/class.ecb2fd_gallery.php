<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016-2023 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/docs/LICENSE or <http://www.gnu.org/licenses/gpl-3.0.html>
#---------------------------------------------------------------------------------------------------


namespace ECB2;

class ecb2fd_gallery extends ecb2_FieldDefBase 
{
    const KEY_PATTERN = '/^g[a-f0-9]{12}$/';
    protected $gallery_key = '';    // sub_field only: folder key of the current group row


	public function __construct($mod, $blockName, $value, $params, $adding, $id=0) 
	{	
		parent::__construct( $mod, $blockName, $value, $params, $adding, $id );

        $this->get_values( $value );              // common FieldDefBase method

        $this->set_field_parameters();

        $this->initialise_options( $params );     // common FieldDefBase method
        
        $this->create_sub_fields( $params );      // common FieldDefBase method
	}



    /**
     *  Overides parent class
     *  sets $this->value or $this->values from $value saved for the content block - json or string
     *
     *  @param array $value - saved content block value
     */
    protected function get_values($value) 
    {
        if ( empty($value) ) return;

        $json_data = json_decode($value);
        if ( json_last_error()===JSON_ERROR_NONE && !is_integer($json_data) && 
             isset($json_data->sub_fields) ) {
            // JSON is valid - gallery uses $json_data->sub_fields
            $this->json_data = $json_data;
            $this->use_json_format = TRUE;
            $this->values = $json_data->sub_fields;

        } else { // but JSON not valid
            $this->values[] = $value;

        } 
    }



    /**
     *  as a group sub_field: images only - ignore this gallery's own sub_fields
     */
    public function set_as_subfield($parent_block_name)
    {
        parent::set_as_subfield($parent_block_name);
        $this->sub_fields = [];
        $this->error = '';
    }

    /**
     *  group sub_field: sets this gallery's values for one group row
     *  @param object $fields - all values for the group row
     *  @param integer $row_number
     */
    public function set_sub_field_value($fields, $row_number)
    {
        $this->value = '';
        $this->values = [];
        $this->gallery_key = '';
        $block = $fields->{$this->block_name} ?? NULL;
        if ( is_object($block) ) {
            if ( !empty($block->sub_fields) && is_array($block->sub_fields) ) {
                $this->values = $block->sub_fields;
            }
            if ( !empty($block->dir_key) && preg_match(self::KEY_PATTERN, $block->dir_key) ) {
                $this->gallery_key = $block->dir_key;
            }
        }
        $this->sub_row_number = $row_number;
    }

    /** @return string unique images dir 'name' for one group row's gallery */
    protected function row_dir_name($key)
    {
        return $this->sub_parent_block.'_'.$this->block_name.'_'.$key;
    }

    /** @return boolean true if posted gallery input contains at least one image */
    public function input_has_items($input)
    {
        if ( !is_array($input) ) return FALSE;
        foreach ($input as $k => $v) {
            if ( preg_match('/^r_[0-9]+$/', $k) && is_array($v) && !empty($v['filename']) ) return TRUE;
        }
        return FALSE;
    }

    /**
     *  group sub_field: validate & normalise one row's posted gallery data
     *  @return array [key, copy_from, filenames, data] - data is in create_field_object format
     */
    public function prepare_row_input($input)
    {
        $input = is_array($input) ? $input : [];
        $key = ( isset($input['dir_key']) && is_string($input['dir_key']) && 
                 preg_match(self::KEY_PATTERN, $input['dir_key']) ) ? $input['dir_key'] : '';
        $copy_from = ( $key === '' && isset($input['copy_from']) && is_string($input['copy_from']) && 
                       preg_match(self::KEY_PATTERN, $input['copy_from']) ) ? $input['copy_from'] : '';
        $is_new = ($key === '');
        if ($is_new) $key = 'g'.bin2hex(random_bytes(6));

        $location = ecb2_FileUtils::ECB2ImagesRelativeUrl( $this->row_dir_name($key), $this->id );
        $data = ['dir_key' => $key];
        $filenames = [];
        $n = 0;
        foreach ($input as $k => $item) {
            if ( !preg_match('/^r_[0-9]+$/', $k) || !is_array($item) ) continue;
            $filename = $item['filename'] ?? '';
            if ( !is_string($filename) || $filename === '' || basename($filename) !== $filename ) continue;
            $n++;
            $data['r_'.$n] = ['filename' => $filename, 'file_location' => $location];
            $filenames[] = $filename;
        }
        if ( $n === 0 ) $data['sub_fields'] = [];

        return ['key' => $key, 'copy_from' => $copy_from, 'is_new' => $is_new,
                'filenames' => $filenames, 'data' => $data];
    }

    /** group sub_field: copy images from the source row's dir (or _tmp) into a copied row's new dir */
    public function copy_row_images($prep)
    {
        if ( empty($prep['copy_from']) || empty($prep['filenames']) ) return;

        $src = ecb2_FileUtils::ECB2ImagesPath( $this->row_dir_name($prep['copy_from']), $this->id );
        $dest = ecb2_FileUtils::ECB2ImagesPath( $this->row_dir_name($prep['key']), $this->id );
        $tmp = ecb2_FileUtils::ECB2ImagesTempPath();
        if ( !is_dir($dest) ) mkdir($dest, 0755, true);
        foreach ($prep['filenames'] as $filename) {
            if ( file_exists($dest.$filename) ) continue;
            if ( is_file($src.$filename) ) {
                copy( $src.$filename, $dest.$filename );
            } elseif ( is_file($tmp.$filename) ) {
                copy( $tmp.$filename, $dest.$filename );
            }
        }
    }

    /** group sub_field: move uploads from _tmp, create thumbnails, remove unused files for one row */
    public function finalise_row_images($prep)
    {
        $dir = ecb2_FileUtils::ECB2ImagesPath( $this->row_dir_name($prep['key']), $this->id );
        if ( empty($prep['filenames']) && !is_dir($dir) ) return;
        ecb2_FileUtils::updateGalleryDir( $prep['filenames'], $dir, $this->options['auto_add_delete'],
            $this->options['thumbnail_width'], $this->options['thumbnail_height'] );
    }



    /**
     *  sets the allowed parameters for this field type
     *
     *  $this->default_parameters - array of parameter_names => [ default_value, filter_type ]
     *      self::ECB2_SANITIZE_STRING, FILTER_VALIDATE_INT, FILTER_VALIDATE_BOOLEAN, FILTER_SANITIZE_EMAIL 
     *      see: https://www.php.net/manual/en/filter.filters.php
     *  $this->restrict_params - optionally allow any other parameters to be included, e.g. module calls
     */
    public function set_field_parameters() 
    {
        $this->default_parameters = [
            'dir'               => ['default' => '',     'filter' => self::ECB2_SANITIZE_STRING],
            'resize_width'      => ['default' => 0,      'filter' => FILTER_VALIDATE_INT],
            'resize_height'     => ['default' => 0,      'filter' => FILTER_VALIDATE_INT],
            'resize_method'     => ['default' => '',     'filter' => self::ECB2_SANITIZE_STRING],
            'thumbnail_width'   => ['default' => 0,      'filter' => FILTER_VALIDATE_INT],
            'thumbnail_height'  => ['default' => 0,      'filter' => FILTER_VALIDATE_INT],
            'max_files'         => ['default' => 0,      'filter' => FILTER_VALIDATE_INT],
            'auto_add_delete'   => ['default' => true,   'filter' => FILTER_VALIDATE_BOOLEAN],
            'default_value'     => ['default' => '',     'filter' => self::ECB2_SANITIZE_STRING], 
            'admin_groups'      => ['default' => '',     'filter' => self::ECB2_SANITIZE_STRING],
            'description'       => ['default' => '',     'filter' => FILTER_DEFAULT]
        ];
        // $this->parameter_aliases = [ 'alias' => 'parameter' ];
        $this->restrict_params = FALSE;    // default: true
        $this->use_json_format = TRUE;     // default: FALSE - can override e.g. 'groups' type
        $this->searchable_content = TRUE;
        $this->allowed_sub_fields = [
            'textinput',
            'textarea',
            'dropdown',
            'checkbox',
            'radio',
            'color_picker',
            'date_time_picker',
            'file_selector',
            'file_picker',
            'page_picker',
            'gallery_picker',
            'module_picker'     
        ];
        $this->sub_fields_ignored_params = [
            'assign',
            'repeater',
            'max_blocks'
        ];
        $this->sub_fields_ignored_names = [
            'filename',
            'file_location'
        ];


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

        if ( $this->is_sub_field ) {   // inside a group row: own folder per row, 'dir' option ignored
            $is_template = is_null($this->sub_row_number);      // hidden row template - JS fills in names/ids
            $name_prefix = $is_template ? '' : $this->sub_parent_block.'[r_'.$this->sub_row_number.']['.$this->block_name.']';
            $id_prefix = $is_template ? '' : $this->sub_parent_block.'_r_'.$this->sub_row_number.'_'.$this->block_name;
            $has_dir = ( $this->gallery_key !== '' );
            $dir_name = $has_dir ? $this->row_dir_name($this->gallery_key) : '';
            $location = $has_dir ? ecb2_FileUtils::ECB2ImagesUrl( $dir_name, $this->id ) : '';
            $dir = $has_dir ? ecb2_FileUtils::ECB2ImagesPath( $dir_name, $this->id ) : '';
        } else {
            $is_template = FALSE;
            $name_prefix = $id_prefix = $this->block_name;
            $location = ecb2_FileUtils::ECB2ImagesUrl( $this->block_name, $this->id, '', $this->options['dir'] );
            $dir = ecb2_FileUtils::ECB2ImagesPath( $this->block_name, $this->id, '', $this->options['dir'] );
        }
        if ( $this->options['auto_add_delete'] && $dir ) {
            ecb2_FileUtils::autoAddDirImages( $this->values, $dir, $this->options['thumbnail_width'], $this->options['thumbnail_height']);
        }
        $resize_method = ($this->options['resize_method']=='crop') ? 'crop' : ''; // default: 'contain'
        $thumbnail_width = $this->options['thumbnail_width'];
        $thumbnail_height = $this->options['thumbnail_height'];
        ecb2_FileUtils::get_required_thumbnail_size( $thumbnail_width, $thumbnail_height );
        $max_files = $this->options['max_files'];
        if ( $max_files>0 ) {
            $max_files_text = $this->mod->Lang( 'max_files_text', $max_files );
        } else {
            $max_files_text = $this->mod->Lang( 'max_files_unlimited_text' );
        }

        $actionparms = [];
        $action_url = $this->mod->create_url( 'm1_', 'do_UploadFiles', '', $actionparms);
        $filenames = [];
        foreach ($this->values as $gallery_item) {
            if ( !empty($gallery_item->filename) ) $filenames[] = $gallery_item->filename;
        }
        $json_filenames = json_encode($filenames, JSON_HEX_APOS);

        if ($this->error) return $this->mod->error_msg($this->error);

        $smarty = \CmsApp::get_instance()->GetSmarty();
        $tpl = $smarty->CreateTemplate( 'string:'.$this->get_template(), null, null, $smarty );
        $tpl->assign( 'block_name', $this->block_name );
        $tpl->assign( 'remove_confirm', $this->mod->Lang('remove_image_confirm') );
        $tpl->assign( 'name_prefix', $name_prefix );
        $tpl->assign( 'id_prefix', $id_prefix );
        $tpl->assign( 'is_sub_field', $this->is_sub_field );
        $tpl->assign( 'is_template', $is_template );
        $tpl->assign( 'gallery_key', $this->gallery_key );
        $tpl->assign( 'values', $this->values );
        $tpl->assign( 'json_filenames', $json_filenames );
        $tpl->assign( 'sub_fields', $this->sub_fields );
        $tpl->assign( 'location', $location );
        $tpl->assign( 'resize_width', $this->options['resize_width'] );
        $tpl->assign( 'resize_height', $this->options['resize_height'] );
        $tpl->assign( 'resize_method', $resize_method );
        $tpl->assign( 'thumbnail_width', $thumbnail_width );
        $tpl->assign( 'thumbnail_height', $thumbnail_height );
        $tpl->assign( 'max_files', $max_files );
        $tpl->assign( 'max_files_text', $max_files_text );
        $tpl->assign( 'thumbnail_prefix', ecb2_FileUtils::THUMB_PREFIX );
        $tpl->assign( 'type', $this->field );
        $tpl->assign( 'action_url', $action_url );
        $tpl->assign( 'description', $this->options['description'] );
        return $tpl->fetch();
   
    }



    /**
     *  Data entered by the editor is processed before its saved in props table
     *  Method can be overidden by this class (usually also calls parent class)
     *  
     *  @return string formatted json containing all field data ready to be saved & output
     */
    public function get_content_block_value( $inputArray ) 
    {
        if ( is_array($inputArray) ) {
            // if a gallery item does not include filename (should not happen) or
            //      key is 'empty' remove that gallery item
            foreach ($inputArray as $row_number => $galleryArray) {
                if ( empty($galleryArray['filename']) || $row_number=='empty' ) {
                    unset( $inputArray[$row_number] );
                }
            }
        }
        if ( empty($inputArray) ) $inputArray = array('sub_fields' => []);

        $this->field_object = $this->create_field_object( $inputArray );
        // add 'file_location' field to all gallery items
        if ( !empty($this->field_object->sub_fields) ) {
            $galleryRelativeUrl = ecb2_FileUtils::ECB2ImagesRelativeUrl( $this->block_name, $this->id, '', 
                $this->options['dir'] );
            foreach ($this->field_object->sub_fields as &$row) {
                if ( !empty($row['filename']) ) $row['file_location'] = $galleryRelativeUrl;
            }
        }

        // handle moving files from _tmp into galleryDir, create thumbnails & delete any unwanted files
        $galleryDir = ecb2_FileUtils::ECB2ImagesPath( $this->block_name, $this->id, '', 
            $this->options['dir'] );
        $filenames = [];
        foreach ($this->field_object->sub_fields as $fileArray) {
            if ( !empty($fileArray['filename']) ) $filenames[] = $fileArray['filename'];
        }
        ecb2_FileUtils::updateGalleryDir( $filenames, $galleryDir, $this->options['auto_add_delete'], 
            $this->options['thumbnail_width'], $this->options['thumbnail_height'] );
    
        return $this->ECB2_json_encode_field_object(); 
    }



}