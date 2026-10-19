<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016-2023 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/lang/LICENCE.txt or <http://www.gnu.org/licenses/gpl-3.0.html>
#---------------------------------------------------------------------------------------------------


if ( !defined('CMS_VERSION') ) exit;

if ( isset($_FILES['file']) && is_array($_FILES['file']) &&
     isset($_FILES['file']['name'], $_FILES['file']['tmp_name'], $_FILES['file']['error']) ) {

    $result = [];
    $uploads = $_FILES['file'];
    if ( !is_array($uploads['name']) ) {
        foreach ( $uploads as $key => $value ) $uploads[$key] = [$value];
    }

    foreach ($uploads['error'] as $key => $error) {
        $original_filename = $uploads['name'][$key] ?? '';
        $tmp_filename = $uploads['tmp_name'][$key] ?? '';
        $success = false;
        if ( (int)$error === UPLOAD_ERR_OK ) {
            $success = ecb2_FileUtils::ECB2MoveUploadedFile( $original_filename, $tmp_filename );
        }
        $result[] = [
            'name' => $original_filename,
            'success' => $success
        ];
    }

    header('Content-type: application/json');
    echo json_encode($result);
    exit();

}

