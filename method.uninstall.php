<?php
#---------------------------------------------------------------------------------------------------
# Module: ECB2 - Extended Content Blocks 2
# Author: Chris Taylor
# Copyright: (C) 2016-2023 Chris Taylor, chris@binnovative.co.uk
# Licence: GNU General Public License version 3
#          see /ECB2/docs/LICENSE or <http://www.gnu.org/licenses/gpl-3.0.html>
#---------------------------------------------------------------------------------------------------

if ( !defined('CMS_VERSION') ) exit;

// remove the permissions, etc
$this->RemovePermission(ECB2::MANAGE_PERM);
$this->RemovePreference();
$this->DeleteTemplate();



// remove the database tables & index
$blocks_table = new \ECB2\ecb2Blocks();
$blocks_table->remove_database();


