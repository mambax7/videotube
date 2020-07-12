<?php
//
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//  Author:     tank                                                         //
//  Website:    http://www.customvirtualdesigns.com                          //
//  E-Mail:     tanksplace@comcast.net                                       //
//  Date:       02/21/2009                                                   //
//  Module:     Video Tube                                                   //
//  File:       include/module.php                                           //
//  Version:    1.85                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.6  Initial Release 08/01/2008                                  //
//  ***                                                                      //
//  Version 1.63  08/23/2008                                                 //
//  New: Add fields to vp_videos table to support Daily Motion               //
//  ***                                                                      //
//  Version 1.64  09/06/2008                                                 //
//  New: Add fields to vp_videos table to support MetaCafe, blip.tv and      //
//       manual submissions                                                  //
//  ***                                                                      //
//  Version 1.81  01/06/2009                                                 //
//  New: Add fields to vp_categories table to support subcategories          //
//  ***                                                                      //
//  Version 1.84  01/18/2009                                                 //
//  New: Add table vp_reports                                                //
//  ***                                                                      //
//  Version 1.85  02/21/2009                                                 //
//  New: Add table vp_votedata                                               //
//  ***                                                                      //

// include_once '../../mainfile.php';
// global $xoopsDB;
  
if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

$table_done_1 = NULL;
$table_done_2 = NULL;
$fields_done_1 = NULL;
$fields_done_2 = NULL;
$fields_done_3 = NULL;
$fields_done_4 = NULL;
$fields_done_5 = NULL;

function xoops_module_update_videotube(&$module, $oldversion = null) 
{
  global $xoopsDB;

  $table_exists = FALSE;
  $tables = mysql_list_tables(XOOPS_DB_NAME); 
  $tablename = "".$xoopsDB->prefix("vp_categories")."";
  while (list($temp) = mysql_fetch_array ($tables)) {
	if ($temp == $tablename) {
	  $table_exists = TRUE;
	}
  }

  if ($table_exists) {
    $table_done_1 = TRUE;
  } else {
    $sqlcommandline = "CREATE TABLE ".
      $xoopsDB->prefix("vp_categories")." (
          refid int(5) unsigned NOT NULL auto_increment, 
          pid int(11) NOT NULL default 0, 
          cid int(11) NOT NULL default 0, 
          title varchar(50) NOT NULL, 
          disporder int(11) NOT NULL default 0,
          PRIMARY KEY (refid))";
    $table_done_1 = $xoopsDB->queryF($sqlcommandline);
  }

  $table_exists = FALSE;
  $tables = mysql_list_tables(XOOPS_DB_NAME); 
  $tablename = "".$xoopsDB->prefix("vp_reports")."";
  while (list($temp) = mysql_fetch_array ($tables)) {
	if ($temp == $tablename) {
	  $table_exists = TRUE;
	}
  }

  if ($table_exists) {
    $table_done_2 = TRUE;
  } else {
    $sqlcommandline = "CREATE TABLE ".
      $xoopsDB->prefix("vp_reports")." (
          repid int(11) NOT NULL auto_increment, 
          vid int(11) NOT NULL default 0, 
          reasoncode int(11) NOT NULL default 0, 
          reasontext varchar(50) NOT NULL, 
          numreports int(11) NOT NULL default 0,
          PRIMARY KEY (repid))";
    $table_done_2 = $xoopsDB->queryF($sqlcommandline);
  }

  $table_exists = FALSE;
  $tables = mysql_list_tables(XOOPS_DB_NAME); 
  $tablename = "".$xoopsDB->prefix("vp_votedata")."";
  while (list($temp) = mysql_fetch_array ($tables)) {
	if ($temp == $tablename) {
	  $table_exists = TRUE;
	}
  }

  if ($table_exists) {
    $table_done_3 = TRUE;
  } else {
    $sqlcommandline = "CREATE TABLE ".
      $xoopsDB->prefix("vp_votedata")." (
          rid int(11) NOT NULL auto_increment, 
          rvid int(11) NOT NULL default 0, 
          ruid int(11) NOT NULL default 0,
		  rating int(11) NOT NULL default 0, 
          rhostname varchar(60) NOT NULL, 
          rtime int(11) NOT NULL default 0,
          PRIMARY KEY (rid))";
    $table_done_3 = $xoopsDB->queryF($sqlcommandline);
  }

  $field_exists = FALSE;
  $fields = $xoopsDB->queryF("SHOW COLUMNS from ". $xoopsDB->prefix("vp_videos").""); 
  $fieldname = "embedcode";
  while (list($temp) = mysql_fetch_array($fields)) {
	if ($temp == $fieldname) {
	  $field_exists = TRUE;
	}
  }

  if ($field_exists) {
    $fields_done_1 = TRUE;
  } else {
    $sqlcommandline = "ALTER TABLE ".$xoopsDB->prefix("vp_videos")." ADD embedcode varchar(100) NULL";
    $done1 = $xoopsDB->queryF($sqlcommandline);
    $sqlcommandline = "ALTER TABLE ".$xoopsDB->prefix("vp_videos")." ADD thumb varchar(100) NULL";
    $done2 = $xoopsDB->queryF($sqlcommandline);
    $sqlcommandline = "ALTER TABLE ".$xoopsDB->prefix("vp_videos")." ADD service int(11) NULL default 0";
    $done3 = $xoopsDB->queryF($sqlcommandline);
    if ($done1 && $done2 && $done3) {
      $fields_done_1 = TRUE;
    } else {
      $fields_done_1 = FALSE;
    }
  }

   
  $field_exists = FALSE;
  $fields = $xoopsDB->queryF("SHOW COLUMNS from ". $xoopsDB->prefix("vp_videos").""); 
  $fieldname = "fullembedcode";
  while (list($temp) = mysql_fetch_array ($fields)) {
	if ($temp == $fieldname) {
	  $field_exists = TRUE;
	}
  }

  if ($field_exists) {
    $fields_done_2 = TRUE;
  } else {
    $sqlcommandline = "ALTER TABLE ".
      $xoopsDB->prefix("vp_videos")."
          ADD fullembedcode text NOT NULL";
    $done4 = $xoopsDB->queryF($sqlcommandline);
    $sqlcommandline = "ALTER TABLE ".
      $xoopsDB->prefix("vp_videos")."
          ADD description text NOT NULL";
    $done5 = $xoopsDB->queryF($sqlcommandline);
    $sqlcommandline = "ALTER TABLE ".
      $xoopsDB->prefix("vp_videos")."
          ADD servicename varchar(30) NOT NULL";
    $done6 = $xoopsDB->queryF($sqlcommandline);
    if ($done4 && $done5 && $done6) {
      $fields_done_2 = TRUE;
    } else {
      $fields_done_2 = FALSE;
    }
  }
  
  $field_exists = FALSE;
  $fields = $xoopsDB->queryF("SHOW COLUMNS from ". $xoopsDB->prefix("vp_categories").""); 
  $fieldname = "weight";
  while (list($temp) = mysql_fetch_array ($fields)) {
	if ($temp == $fieldname) {
	  $field_exists = TRUE;
	}
  }

  if ($field_exists) {
    $fields_done_3 = TRUE;
  } else {
    $sqlcommandline = "ALTER TABLE ".
      $xoopsDB->prefix("vp_categories")."
          ADD weight int(11) NOT NULL default '0'";
    $done = $xoopsDB->queryF($sqlcommandline);
    if ($done) {
      $fields_done_3 = TRUE;
    } else {
      $fields_done_3 = FALSE;
    }
  }

  $field_exists = FALSE;
  $fields = $xoopsDB->queryF("SHOW COLUMNS from ". $xoopsDB->prefix("vp_categories").""); 
  $fieldname = "pid";
  while (list($temp) = mysql_fetch_array ($fields)) {
	if ($temp == $fieldname) {
	  $field_exists = TRUE;
	}
  }

  if ($field_exists) {
    $fields_done_4 = TRUE;
  } else {
    $sqlcommandline = "ALTER TABLE ".
      $xoopsDB->prefix("vp_categories")."
          ADD pid int(11) NOT NULL default '0'";
    $done = $xoopsDB->queryF($sqlcommandline);
    if ($done) {
      $fields_done_4 = TRUE;
    } else {
      $fields_done_4 = FALSE;
    }
  }

  $sqlcommandline = "ALTER TABLE ".$xoopsDB->prefix("vp_videos")." MODIFY title text";
  $done1 = $xoopsDB->queryF($sqlcommandline);
  $sqlcommandline = "ALTER TABLE ".$xoopsDB->prefix("vp_videos")." MODIFY artist text";
  $done2 = $xoopsDB->queryF($sqlcommandline);
  $sqlcommandline = "ALTER TABLE ".$xoopsDB->prefix("vp_videos")." MODIFY embedcode text";
  $done3 = $xoopsDB->queryF($sqlcommandline);
  $sqlcommandline = "ALTER TABLE ".$xoopsDB->prefix("vp_videos")." MODIFY thumb text";
  $done4 = $xoopsDB->queryF($sqlcommandline);

  if ($done1 && $done2 && $done3 && $done4) {
    $fields_done_5 = TRUE;
  } else {
    $fields_done_5 = FALSE;
  }
  
  if ($table_done_1 && $table_done_2 && $table_done_3 && $fields_done_1 && $fields_done_2 && $fields_done_3 && $fields_done_4 && $fields_done_5) {
    return TRUE;
  } else {
    return FALSE;
 }
}


?>