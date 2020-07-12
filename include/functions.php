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
//  Date:       04/23/2008                                                   //
//  Module:     Video Tube                                                   //
//  File:       include/functions.php                                        //
//  Version:    1.1                                                          //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.0  Initial Release 04/14/2008                                  //
//  ***                                                                      //
//  Version 1.1  04/23/2008                                                  //
//  Bug Fix: userIsAdmin modified to correct syntax                          //
//  New: Changed syntax of all MySQL queries for 2.2.x compatibility         //
//  Note: E-mail contact address change                                      //
//  ***                                                                      //

function updateVideoViews($vid,$views){
	global $xoopsDB;
	if ($vid) {
	  $views++;
	  $sql = "UPDATE ".$xoopsDB->prefix("vp_videos")." SET views = $views WHERE id = $vid";
		if ( !$result = $xoopsDB->queryF($sql) ) {
			exit("$sql > SQL Error in function :: updateVideoViews($vid,$views)");
		} else {
			return 1;
		}
	}
}

function userIsAdmin($xoopsUser){
	if ($xoopsUser){
		$uid = $xoopsUser->uid();
		$module_handler =& xoops_gethandler('module');
		$videotubemodule =& $module_handler->getByDirname('videotube');
		$moduleid = $videotubemodule->getVar('mid');
		if ($xoopsUser->isAdmin($moduleid)){
			return 1;
		} else return 0;
	}
}

function vt_get_servicename($servicenum){
	if ($servicenum == 10) {
      return $row['servicename'];
    }elseif ($servicenum == 4) {
      return 'blip.tv';
    }elseif ($servicenum == 3) {
      return 'MetaCafe';
    }elseif ($servicenum == 2) {
      return 'DailyMotion';
    }else{
      return 'YouTube';
    }
}

function vp_getTotalItems($mytree, $sel_id = 0, $get_child = 0)
{
	global $xoopsDB, $xoopsModule;

	$count = 0;
	$arr = array();
	$query = "SELECT * FROM " . $xoopsDB->prefix("vp_videos") . " WHERE pub=1 && cid=" . $sel_id . "";
	$result = $xoopsDB -> query($query);
	$count = mysql_num_rows($result);

	$thing = 0;
	if ($get_child == 1)
	{
		$arr = $mytree -> getAllChildId($sel_id);
		$size = count($arr);
		for($i = 0;$i < count($arr);$i++)
		{
			$query2 = "SELECT * FROM " . $xoopsDB->prefix("vp_videos") . " WHERE pub=1 AND cid=" . $arr[$i] . "";
			$result2 = $xoopsDB -> query($query2);
			//$result2 = $xoopsDB -> query($query2);
            $count += mysql_num_rows($result2);
		}
	}

	return $count;
}	

?>