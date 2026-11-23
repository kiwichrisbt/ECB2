<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016-2023 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/docs/LICENSE or <http://www.gnu.org/licenses/gpl-3.0.html>
#---------------------------------------------------------------------------------------------------

namespace ECB2;

class ecb2_FileUtils 
{

    const ECB2_IMAGE_DIR = '_ecb2_images';
    const ECB2_IMAGE_TEMP_DIR = '_tmp';     // sub dir of ECB2_IMAGE_DIR
    const THUMB_PREFIX = 'thumb_'; 
    const SVG_MAX_BYTES = 1048576;      // 1MB
    const SVG_ELEMENTS = ['svg','g','defs','title','desc','path','rect','circle','ellipse','line','polyline',
        'polygon','text','tspan','lineargradient','radialgradient','stop','clippath','mask','use','symbol',
        'pattern','image','style','filter','fegaussianblur','feoffset','feblend','fecolormatrix','femerge',
        'femergenode','fecomposite','feflood','fedropshadow','marker'];


    /**
     *  @return string path to ECB2 images temp sub dir: /ECB2_IMAGE_DIR/ECB2_IMAGE_TEMP_DIR
     */
    public static function ECB2ImagesTempPath() 
    {
        $config = cmsms()->GetConfig();
        return cms_join_path( $config['image_uploads_path'], self::ECB2_IMAGE_DIR, 
            self::ECB2_IMAGE_TEMP_DIR ).DIRECTORY_SEPARATOR;
    }



    /**
     *  @return string path to unique ECB2 images sub dir: /ECB2_IMAGE_DIR/blockname_module_id
     *  @param string $blockname - name of props blockname
     *  @param string $id - content id - i.e. page id
     *  @param string $module - if not Content page (default) - not actually used yet
     *  @param string $uploads_dir - if set is a subdir of /uploads to use
     */
    public static function ECB2ImagesPath( $blockname, $id='', $module='', $uploads_dir='' ) 
    {
        $config = cmsms()->GetConfig();
        if ( !empty($uploads_dir) ) {
            $imagesPath = cms_join_path( $config['image_uploads_path'], $uploads_dir );
            return $imagesPath.DIRECTORY_SEPARATOR;
        }

        if ( empty($blockname) ) return FALSE;

        $imagesPath = cms_join_path( $config['image_uploads_path'], self::ECB2_IMAGE_DIR );
        $dirname = munge_string_to_url( $blockname. ($module ? '_'.$module : '') . ($id ? '_'.$id : '') );
        return $imagesPath.DIRECTORY_SEPARATOR.$dirname.DIRECTORY_SEPARATOR;
    }



    /**
     *  @return string url to either a subdir of /uploads, or a unique ECB2 images sub dir: 
     *                   /ECB2_IMAGE_DIR/blockname_module_id
     *  @param string $blockname - name of props blockname
     *  @param string $id - content id - i.e. page id
     *  @param string $module - if not Content page (default) - not actually used yet
     *  @param string $uploads_dir - if set is a subdir of /uploads to use
     */
    public static function ECB2ImagesUrl( $blockname, $id, $module='', $uploads_dir='' ) 
    {
        $config = cmsms()->GetConfig();
        if ( !empty($uploads_dir) ) {
            $ecb2Url = cms_join_path( $config['image_uploads_url'], $uploads_dir );
            return $ecb2Url.DIRECTORY_SEPARATOR;
        }

        if ( empty($blockname) ) return FALSE;

        $ecb2Url = cms_join_path( $config['image_uploads_url'], self::ECB2_IMAGE_DIR ).DIRECTORY_SEPARATOR;
        $dirname = munge_string_to_url( $blockname. ($module ? '_'.$module : '') . ($id ? '_'.$id : '') );
        return $ecb2Url.$dirname.DIRECTORY_SEPARATOR;
    }



    /**
     *  @return string url relative to the website root to either a subdir of /uploads, or a unique 
     *                 ECB2 images sub dir:  /ECB2_IMAGE_DIR/blockname_module_id
     *  @param string $blockname - name of props blockname
     *  @param string $id - content id - i.e. page id
     *  @param string $module - if not Content page (default) - not actually used yet
     *  @param string $uploads_dir - if set is a subdir of /uploads to use
     */
    public static function ECB2ImagesRelativeUrl( $blockname, $id, $module='', $uploads_dir='' ) 
    {
        $config = cmsms()->GetConfig();
        $relativeUploadsDir = str_replace(CMS_ROOT_URL, '', $config['image_uploads_url']);
        if ( !empty($uploads_dir) ) {
            $ecb2Url = cms_join_path( $relativeUploadsDir, $uploads_dir );
            return $ecb2Url.DIRECTORY_SEPARATOR;
        }

        if ( empty($blockname) ) return FALSE;

        $ecb2Url = cms_join_path( $relativeUploadsDir, self::ECB2_IMAGE_DIR ).DIRECTORY_SEPARATOR;
        $dirname = munge_string_to_url( $blockname. ($module ? '_'.$module : '') . ($id ? '_'.$id : '') );
        return $ecb2Url.$dirname.DIRECTORY_SEPARATOR;
    }



    /**
     *  creates the unique ECB2 images sub dir - if it doesn't already exist: 
     *          /ECB2_IMAGE_DIR/blockname_module_id
     *      or the ECB2_IMAGE_DIR/ECB2_IMAGE_TEMP_DIR if params all empty
     *  @param string $blockname - name of props blockname
     *  @param string $id - content id - i.e. page id
     *  @param string $module - if not Content page (default) - not actually used yet
     *  @param string $uploads_dir - if set is a subdir of /uploads/images to use (default)
     */
    public static function CreateImagesDir( $blockname='', $id='', $module='', $uploads_dir='' )
    {
        $success = false;
        if ( !empty($uploads_dir) || !empty($blockname) ) {
            $ecb2_images_dir = self::ECB2ImagesPath( $blockname, $id, $module, $uploads_dir );
        } else {
            $ecb2_images_dir = self::ECB2ImagesPath( self::ECB2_IMAGE_TEMP_DIR );
        }

        if ( $ecb2_images_dir && !file_exists($ecb2_images_dir) ) {
            $success = mkdir($ecb2_images_dir, 0755, true);
        }
        return $success;
    }



    /** NB: NOT CURRENTLY USED...
     *  unique filename for ECB2 images, if filename already exists, it has _x 
     *      appended before the suffix, e.g. 'new_image_1.jpg', then 'new_image_2.jpg' 
     *
     *  @return string unique filename 
     *  @param string $uploaded_filename - original filename to make unique
     *  @param string $dir - directory to check for unique filename
     */
    public static function ECB2UniqueFilename( $uploaded_filename, $dir='' ) 
    {
        if ( empty($uploaded_filename) ) return;

        $dir_to_check = ( !empty($dir) ) ? $dir : self::ECB2ImagesPath();
        $tmp_filename = basename($uploaded_filename);   // may prevent filesystem traversal attacks
        $filename_only = pathinfo($tmp_filename, PATHINFO_FILENAME);
        $extension_only =  pathinfo($tmp_filename, PATHINFO_EXTENSION);
        $FileCounter = 0;

        while ( file_exists($dir_to_check.$tmp_filename) ) {
            $FileCounter++;
            $tmp_filename = $filename_only.'_'.$FileCounter.'.'.$extension_only;
        }

        return $tmp_filename;
    }



    /**
     *  slugify an uploaded filename so urls never need %20: lowercased, and any run of spaces
     *  (with adjacent hyphens/underscores) becomes a single hyphen, e.g. 'My  Photo 1.JPG' => 'my-photo-1.jpg'
     *  @return string the sanitised filename
     */
    public static function sanitise_upload_filename( $filename )
    {
        $filename = strtolower( basename($filename) );
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/[\s_-]*\s[\s_-]*/', '-', $name);   // any run containing a space => one hyphen
        $name = trim($name, '-');
        return ($ext === '') ? $name : $name.'.'.$ext;
    }



    /**
     *  upload file into ECB2ImagesTempPath
     *      allowed types: jpg, jpeg, png, gif, webp, mp3, mp4
     *
     *  @param string $original_filename - original filename of uploaded file
     *  @param string $tmp_filename - temp filename of the file on the server
     *  @return boolean $success
     */
    /**
     *  @return string filename with a short random suffix, e.g. 'photo.jpg' => 'photo-3fa91.jpg'
     */
    public static function add_filename_suffix( $filename )
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $name = pathinfo($filename, PATHINFO_FILENAME);
        return $name.'-'.substr(bin2hex(random_bytes(3)), 0, 5).( $ext === '' ? '' : '.'.$ext );
    }



    public static function ECB2MoveUploadedFile( $original_filename, $tmp_filename, $target_filename=NULL ) 
    {
           if ( !is_string($original_filename) || $original_filename === '' ||
               !is_string($tmp_filename) || $tmp_filename === '' ) return false;

           if ( basename($original_filename) !== $original_filename ||
               preg_match('#[\x00-\x1F\x7F\\\\/]#', $original_filename) ) return false;

        if ( !preg_match('/\A[a-z0-9][a-z0-9 _-]*\.(?:jpe?g|png|gif|webp|svg|mp3|mp4)\z/i', $original_filename) ) {
            return false;
        }

           $allowed_types = [
              'jpg' => 'image/jpeg',
              'jpeg' => 'image/jpeg',
              'png' => 'image/png',
              'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'mp3' => ['audio/mpeg', 'audio/mp3'],
            'mp4' => ['video/mp4', 'application/mp4']
           ];
           $extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
           if ( !isset($allowed_types[$extension]) ) return false;

        $target = $target_filename ? $target_filename : self::sanitise_upload_filename($original_filename);
        if ( basename($target) !== $target || strtolower(pathinfo($target, PATHINFO_EXTENSION)) !== $extension ) return false;

        if ( $extension === 'svg' ) {
            // svg can contain scripts - only accept if it can be sanitised (file is rewritten in place)
            if ( filesize($tmp_filename) > self::SVG_MAX_BYTES || !self::sanitize_svg_file($tmp_filename) ) return false;
        } elseif ( in_array($extension, ['mp3', 'mp4'], true) ) {
            if ( !class_exists('finfo') ) return false;
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $detected_type = $finfo->file($tmp_filename);
            if ( !in_array($detected_type, $allowed_types[$extension], true) ) return false;
        } else {
            $image_info = @getimagesize($tmp_filename);
            if ( !$image_info || !isset($image_info['mime']) ||
                 $image_info['mime'] !== $allowed_types[$extension] ) return false;
        }

        $ecb2_images_path = self::ECB2ImagesTempPath();
        $success = cms_move_uploaded_file( $tmp_filename, $ecb2_images_path.$target );
        return $success;

    }



    /**
     *  move files from _tmp into $dir, deleting any unwanted files
     *  existing files with same name will not be overwritten
     *
     *  @param array $values - filenames of all files in the gallery
     *  @param string $dir - directory to be updated with set gallery images
     *  @param boolean $auto_add_delete - if set delete any unused images & thumbnails
     *  @param boolean $thumb_width - thumbnail width set for this content block
     *  @param boolean $thumb_height - thumbnail height set for this content block
     */
    public static function updateGalleryDir( $values, $dir, $auto_add_delete, $thumb_width=0, 
        $thumb_height=0 )
    {
        if ( empty($values) && !$auto_add_delete ) return;

        $tmp_dir = self::ECB2ImagesTempPath();
        // create $dir if it doesn't exist
        if ( $dir && !file_exists($dir) ) {
            $success = mkdir($dir, 0755, true);
        }
        foreach ($values as $filename) {
            if ( !file_exists($dir.$filename) && file_exists($tmp_dir.$filename)) {
                rename( $tmp_dir.$filename, $dir.$filename );   // moves file from _tmp
            }
        }

        // delete any thumbnails that are not the correct size
        self::get_required_thumbnail_size( $thumb_width, $thumb_height );
        foreach ( glob( $dir.self::THUMB_PREFIX.'*.*' ) as $thumbfilename) {
            $tmp_thumbfilename = basename($thumbfilename);
            list($width, $height) = getimagesize($thumbfilename);
            if ( ($thumb_width>0 && $width!=$thumb_width) || 
                 ($thumb_height>0 && $height!=$thumb_height) ) {
                unlink($thumbfilename);
            }
        }

        // create any new thumbnails
        foreach ( $values as $filename) {
            if ( !file_exists($dir.self::THUMB_PREFIX.$filename) ) {
                self::create_thumbnail( $dir.$filename, $thumb_width, $thumb_height );
            }
        }

        // remove any unused files & their thumbnails 
        if ($auto_add_delete) {    
            $filesAndThumbs = $values;
            foreach ( $values as $filename) {
                $filesAndThumbs[] = self::THUMB_PREFIX.$filename;
            }
            foreach ( glob( $dir.'*.*' ) as $filename) {
                $tmp_filename = basename($filename);
                if ( !in_array($tmp_filename, $filesAndThumbs) ) unlink($filename);
            }
        }
    
    }



    /**
     *  adds any additional files in $dir into $values object ($this->values) 
     *
     *  @param array $values - filenames of already selected files in the dir
     *  @param string $dir - directory to be used for this gallery
     */
    public static function autoAddDirImages( &$values, $dir, $thumbnail_width=0, $thumbnail_height=0)
    {
        $all_filenames = [];
        foreach ($values as $gallery_item) {
            if ( !empty($gallery_item->filename) ) $all_filenames[] = $gallery_item->filename;
        }
        
        foreach ( glob( $dir.'*.*' ) as $filename) {
            $tmp_filename = basename($filename);
            if ( !self::isECB2Thumb($tmp_filename) && !in_array($tmp_filename, $all_filenames) ) {
                $new_item = new \stdClass();
                $new_item->filename = $tmp_filename;
                $values[] = $new_item;
                self::create_thumbnail($filename, $thumbnail_width, $thumbnail_height);
            }
        }

    }



    /**
     * @return boolean true if $haystack starts with self::THUMB_PREFIX ('thumb_')
     */
    public static function isECB2Thumb( $haystack ) 
    {
        $length = strlen( self::THUMB_PREFIX );
        return substr( $haystack, 0, $length ) === self::THUMB_PREFIX;
    }



    /**
     *  Creates a thumbnail for the $src file if it doesn't already exist, or is not the required size
     *
     *  @param string $src - filename of file to create thumbnail for
     *  @param int $thumb_width - width of thumbnail to be created (default: module pref, sitepref)
     *  @param int $thumb_height - height of thumbnail to be created (default: module pref, sitepref)
     *  @param string $dest - alternative filename for new thumbnail
     *  @param boolean $force - if set to TRUE will overwrite any existing thumbnail filename
     *  @return boolean true if new thumbnail created
     *  based on FileManager > class.filemanager_utils
     */
    public static function create_thumbnail($src, $thumb_width=0, $thumb_height=0, $dest=NULL, $force=FALSE)
    {
        if ( !file_exists($src) || !is_file($src) ) return FALSE;
        if ( self::is_svg($src) ) return TRUE;   // vector - the original is used as its own thumbnail
        if ( !$dest ) {
            $bn = basename($src);
            $dn = dirname($src);
            $dest = $dn.DIRECTORY_SEPARATOR.self::THUMB_PREFIX.$bn;
        }

        if ( !$force && (file_exists($dest) && !is_writable($dest) ) ) return FALSE;
        
        $info = getimagesize($src);
        if ( !$info || !isset($info['mime']) ) return FALSE;
        $src_width = $info[0];
        $src_height = $info[1];
        $src_x = 0; 
        $src_y = 0;

        self::get_thumbnail_size($src_width, $src_height, $thumb_width, $thumb_height, $src_x, $src_y);

        // if thumbnail exists and is of correct size - leave as is, unless $force set
        $thumb_info = is_file($dest) ? getimagesize($dest) : FALSE;
        if ( !$force && $thumb_info && isset($thumb_info['mime']) ) {
            if ( $thumb_info[0]==$thumb_width && $thumb_info[1]==$thumb_height ) {
                return true;
            }
        }

        // create new thumbnail
        $i_src = imagecreatefromstring(file_get_contents($src));
        $i_dest = imagecreatetruecolor($thumb_width, $thumb_height);
        imagealphablending($i_dest, FALSE);
        $color = imageColorAllocateAlpha($i_src, 255, 255, 255, 127);
        imagecolortransparent($i_dest, $color);
        imagefill($i_dest, 0, 0, $color);
        imagesavealpha($i_dest, TRUE);
        imagecopyresampled($i_dest, $i_src, 0,0, $src_x, $src_y, $thumb_width, $thumb_height, $src_width, $src_height);

        $res = null;
        switch( $info['mime'] ) {
        case 'image/gif':
            $res = imagegif($i_dest,$dest);
            break;
        case 'image/png':
            $res = imagepng($i_dest,$dest,9);
            break;
        case 'image/jpeg':
            $res = imagejpeg($i_dest,$dest,100);
            break;
        }

        if ( !$res ) return FALSE;
        return TRUE;
    }



    /**
     *  sets provided parameters thumb_width & thumb_height
     *  based on the provided values, defaulting to module preferences, or global siteprefs
     *
     *  @param string $thumb_width - width of thumbnail to be created (default: module pref, sitepref)
     *  @param string $thumb_height - height of thumbnail to be created (default: module pref, sitepref)
     */
    public static function get_required_thumbnail_size( &$thumb_width=0, &$thumb_height=0 )
    {
        $thumb_width = (int) $thumb_width;
        $thumb_height = (int) $thumb_height;
        if ( $thumb_width==0 && $thumb_height==0 ) { 
            $module = \cms_utils::get_module( 'ECB2' );
            $ecb2_thumb_width = $module->GetPreference('thumbnailWidth', '');
            $ecb2_thumb_height = $module->GetPreference('thumbnailHeight', '');
            if ( !empty($ecb2_thumb_width) || !empty($ecb2_thumb_height) ) { // use module preferences
                $thumb_width = (int)$ecb2_thumb_width;
                $thumb_height = (int)$ecb2_thumb_height;

            } else {    // use CMSMS preferences
                $thumb_width = (int)\cms_siteprefs::get('thumbnail_width',96);
                $thumb_height = (int)\cms_siteprefs::get('thumbnail_height',96);
            }
        }
    }



    /**
     *  sets provided parameters thumb_width & thumb_height, src_y, & src_y
     *  based on the provided values, defaulting to module preferences, or global siteprefs
     *  if only width or height set (params or module pref) then height or width 100% (respectively)
     *  thumbnail cropped to retain image ratio
     *
     *  @param array $src_width - width of the src image 
     *  @param array $src_height - height of the src image
     *  @param string $thumb_width - width of thumbnail to be created (default: module pref, sitepref)
     *  @param string $thumb_height - height of thumbnail to be created (default: module pref, sitepref)
     *  @param string $src_x - 0 or set if width cropping required
     *  @param boolean $src_y - 0 or set if height cropping required
     *  based on FileManager > class.filemanager_utils
     */
    public static function get_thumbnail_size( &$src_width, &$src_height, &$thumb_width=0, &$thumb_height=0, &$src_x=0, &$src_y=0 )
    {
        self::get_required_thumbnail_size( $thumb_width, $thumb_height );

        // if one dimension not set calculate width/height ratio
        if ( $thumb_width!=0 && $thumb_height!=0 ) {
            $thumb_width = (int)$thumb_width;
            $thumb_height = (int)$thumb_height;

        } elseif ( $thumb_width==0 ) {  // but not $thumb_height
            $thumb_width = (int)($src_width / $src_height * $thumb_height);

        } else { // $thumb_height==0 but not $thumb_width
            $thumb_height = (int)($src_height / $src_width * $thumb_width);

        }

        // set $src_x/$src_y to crop width/height if required & set $src_width/$src_height
        if ( $src_height==0 || $thumb_height==0) return;
        $ratio_src = $src_width / $src_height;
        $ratio_thumb = $thumb_width / $thumb_height;
        if ($ratio_src > $ratio_thumb) {    // src_wider_than_thumb
            $src_x = (int)(($src_width - $src_height * $ratio_thumb) / 2); 
            $src_width = (int)($src_height * $ratio_thumb); 

        } else {                            // src_taller_than_thumb
            $src_y = (int)(($src_height - $src_width / $ratio_thumb) / 2); 
            $src_height = (int)($src_width / $ratio_thumb);

        }

    }



    /**
     *  converts File system path to relative admin URL
     *  
     *  @return string relative url to access given $file
     */
    public static function admin_path2url($file, $Protocol='https://') 
    {
        $config = cmsms()->GetConfig();
        $relative_admin_path = '..'.str_replace($config['root_path'], '', $file);
        return $relative_admin_path;
    }



    /**
     *  provides thumbnail url for $src file, it's created if it doesn't already exist & correct size
     *
     *  @param string $src - filename of file to create thumbnail for
     *  @param int $thumb_width - width of thumbnail to be created (default: module pref, sitepref)
     *  @param int $thumb_height - height of thumbnail to be created (default: module pref, sitepref)
     *  @param string $dest - alternative filename for new thumbnail
     *  @param boolean $force - if set to TRUE will overwrite any existing thumbnail filename
     *  @return string thumbnail url for given $src image or '' if it doesn't exist
     */
    public static function get_thumbnail_url($src, $thumb_width=0, $thumb_height=0, $dest=NULL, $force=FALSE)
    {
        if ( self::is_svg($src) ) return is_file($src) ? self::admin_path2url($src) : '';

        $created = self::create_thumbnail($src, $thumb_width, $thumb_height, $dest, $force);

        if ( !$created ) return '';

        if ( !$dest ) {
            $bn = basename($src);
            $dn = dirname($src);
            $dest = $dn.DIRECTORY_SEPARATOR.self::THUMB_PREFIX.$bn;
        }
        if ( !file_exists($dest) ) return '';

        return self::admin_path2url($dest);

    }



    /**
     *  @return boolean true if $filename has an svg extension
     */
    public static function is_svg( $filename )
    {
        return strtolower(pathinfo((string)$filename, PATHINFO_EXTENSION)) === 'svg';
    }



    /**
     *  rewrites an svg file with only safe content
     *  @param string $path - svg file path
     *  @return boolean true if file was a valid svg and has been sanitised
     */
    public static function sanitize_svg_file( $path )
    {
        $svg = @file_get_contents($path);
        $clean = self::sanitize_svg_string($svg);
        if ( $clean === FALSE ) return FALSE;
        return file_put_contents($path, $clean) !== FALSE;
    }



    /**
     *  @param string $svg - svg markup
     *  @return string|FALSE svg markup with scripts, event handlers, external references and any element 
     *          not in SVG_ELEMENTS removed - or FALSE if not a valid svg
     */
    public static function sanitize_svg_string( $svg )
    {
        if ( !is_string($svg) || $svg === '' || stripos($svg, '<!ENTITY') !== FALSE ) return FALSE;

        $prev = libxml_use_internal_errors(TRUE);
        $doc = new \DOMDocument();
        $loaded = $doc->loadXML( $svg, LIBXML_NONET | LIBXML_NOBLANKS );
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        $root = $doc->documentElement;
        if ( !$loaded || !$root || strtolower($root->localName) !== 'svg' ) return FALSE;

        self::sanitize_svg_node( $root );
        return $doc->saveXML( $root );
    }



    /**
     *  recursively removes unsafe elements, attributes & style content from an svg element
     */
    protected static function sanitize_svg_node( \DOMElement $el )
    {
        foreach ( iterator_to_array($el->childNodes) as $child ) {
            if ( $child instanceof \DOMElement ) {
                if ( !in_array(strtolower($child->localName), self::SVG_ELEMENTS, TRUE) ||
                     ($child->namespaceURI !== NULL && $child->namespaceURI !== 'http://www.w3.org/2000/svg') ) {
                    $el->removeChild($child);
                } else {
                    self::sanitize_svg_node( $child );
                }
            } elseif ( $child instanceof \DOMText || $child instanceof \DOMCdataSection ) {
                if ( strtolower($el->localName) === 'style' &&
                     preg_match('/@import|expression\s*\(|javascript:|url\s*\(\s*[\'"]?\s*(?!#)/i', $child->nodeValue) ) {
                    $el->removeChild($child);
                }
            } else {
                $el->removeChild($child);   // comments, processing instructions
            }
        }

        foreach ( iterator_to_array($el->attributes) as $attr ) {
            $name = strtolower($attr->nodeName);
            $value = trim($attr->nodeValue);
            $remove = FALSE;
            if ( strpos($name, 'on') === 0 ) {
                $remove = TRUE;
            } elseif ( $name === 'href' || $name === 'xlink:href' ) {
                // internal references or embedded raster images only
                $remove = !( $value !== '' && $value[0] === '#' ) &&
                          !preg_match('#^data:image/(png|jpe?g|gif|webp);base64,[a-z0-9+/=\s]+$#i', $value);
            } elseif ( preg_match('/javascript:|expression\s*\(|@import|url\s*\(\s*[\'"]?\s*(?!#)/i', $value) ) {
                $remove = TRUE;
            }
            if ( $remove ) $el->removeAttributeNode($attr);
        }
    }



}