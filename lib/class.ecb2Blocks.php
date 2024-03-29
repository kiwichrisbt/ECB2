<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016-2023 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/lang/LICENCE.txt or <http://www.gnu.org/licenses/gpl-3.0.html>
#---------------------------------------------------------------------------------------------------

class ecb2Blocks 
{

    // private $content_id;
    // private $properties;


    /**
     *  to be used for module install/upgrade only
     */
    public function create_database()
    {
        //  create module_ecb2_blocks table
        $db = CmsApp::get_instance()->GetDb();
        $dict = NewDataDictionary($db);
        $taboptarray = array( 'mysql' => 'TYPE=MyISAM' );
        $fields = "
            id       I KEY AUTO,
            type	 C(25) NOTNULL,
            name	 C(255) NOTNULL,
            group_id I,
            attribs  X,
            position I
        ";
        $sqlarray = $dict->CreateTableSQL(CMS_DB_PREFIX.'module_ecb2_blocks', $fields, $taboptarray);
        $res = $dict->ExecuteSQLArray($sqlarray);
    }



    /**
     *  to be used for module uninstall only
     */
    public function remove_database()
    {
        $db = CmsApp::get_instance()->GetDb();
        $dict = NewDataDictionary( $db );
        $sqlarray = $dict->DropTableSQL( CMS_DB_PREFIX.'module_ecb2_blocks');
        $dict->ExecuteSQLArray($sqlarray);
    }





}