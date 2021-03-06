<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/lang/LICENCE.txt or <http://www.gnu.org/licenses/>
#---------------------------------------------------------------------------------------------------


if (!isset($gCms)) exit;

// remove permission USE_ECB2
define('USE_ECB2', 'Use Extended Content Blocks 2');
$db = $this->GetDb();
if ( version_compare($oldversion, '1.4') < 0 ) {
    $this->RemovePermission(USE_ECB2);
}
$module_path = $this->GetModulePath();
if ( version_compare($oldversion, '1.8') < 0 ) {
    // remove sub dirs
    $dirsToRemove = ['/lib/js/images', '/icons'];
    foreach ($dirsToRemove as $delDir) {
        foreach (glob($module_path.$delDir.'/*.*') as $filename) unlink($filename);
        rmdir($module_path.$delDir);
    }
    // individual files to remove
    $filesToRemove = ['/lib/js/mColorPicker.min.js', '/changelog.inc', '/lib/js/jquery-ui-timepicker-addon.js', 
        '/lib/js/colpick.js'];
    foreach ($filesToRemove as $delFile) @unlink($module_path.$delFile);
}


?>