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
//  Date:       01/06/2009                                                   //
//  Module:     Video Tube                                                   //
//  File:       blocks/vp_latest_wall.php                                    //
//  Version:    1.81                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.62  Initial Release 08/09/2008                                 //
//  ***                                                                      //
//  Version 1.63  08/23/2008                                                 //
//  New: Add Daily Motion support                                            //
//  ***                                                                      //
//  Version 1.81  01/06/2009                                                 //
//  Code cleanup and housekeeping                                            //
//  Add language support                                                     //
//  ***                                                                      //

if (file_exists(XOOPS_ROOT_PATH."/modules/videotube/language/".$GLOBALS['xoopsConfig']['language']."/blocks.php")) {
  include_once XOOPS_ROOT_PATH."/modules/videotube/language/".$GLOBALS['xoopsConfig']['language']."/blocks.php";
}else{
  include_once XOOPS_ROOT_PATH."/modules/videotube/language/english/blocks.php";
}

function videotube_latest_wall($options) {

        global $xoopsDB, $xoopsConfig;
		$submituid = NULL;
        $block = array();

        $block['videocss'] = XOOPS_URL."/modules/videotube/style/videotube.css";
		
		$matrixsize = $options[0];
		$block['tablewidth'] = ($matrixsize*75)+$matrixsize+1;
		$block['tdwidth'] = 75;
		$numVideos = $matrixsize*$matrixsize;
        $block['matrixsize'] = $matrixsize;
        $block['numvideos'] = $numVideos;

		$result = $xoopsDB->queryF("SELECT id, uid, date, code, title, artist, views, service, thumb FROM ".$xoopsDB->prefix()."_vp_videos WHERE pub='1' ORDER BY date DESC limit 0, $numVideos");

		$vord=0;
		while($vp_vid = $xoopsDB->fetcharray($result)) {
			$vp_vid_disp = array();
			$vp_vid_disp['vord'] = $vord++;
			$vp_vid_disp['vordtag'] = $vord % $matrixsize;
			$vp_vid_disp['id'] = $vp_vid['id'];
			$vp_vid_disp['uid'] = $vp_vid['uid'];
			$vp_vid_disp['code'] = $vp_vid['code'];
			$vp_vid_disp['title'] = $vp_vid['title'];
			$vp_vid_disp['artist'] = $vp_vid['artist'];
			$vp_vid_disp['views'] = $vp_vid['views'];
			$vp_vid_disp['service'] = $vp_vid['service'];
			$vp_vid_disp['thumb'] = $vp_vid['thumb'];
			
              if (isset($vp_vid_disp['uid'])){
			    $submituid = $vp_vid_disp['uid'];
			  }
  
			  if ($submituid) {
			    $result2 = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix()."_users WHERE uid=$submituid");
			    $row2 = $xoopsDB->fetcharray($result2);
			    $vp_vid_disp['submitter'] = $row2['uname'];
			  } else {
			    $vp_vid_disp['submitter'] = $xoopsConfig['anonymous'];
			  }
			
			$tempDate = $dateArray=explode(',',strftime("%Y,%m,%d,%I,%M,%p",$vp_vid['date']));
			$vp_vid_disp['date'] = date('D M j Y', mktime(0, 0, 0, $tempDate[1], $tempDate[2], $tempDate[0]));
			$vp_vid_disp['time'] = $tempDate[3].":".$tempDate[4].strtolower($tempDate[5]);


			$block['vp_vid_disp'][] = $vp_vid_disp;
		}
        return $block;
}


function latest_edit_wall($options)
{
	$form  = "<table border='0'>";
	$form .= "<tr><td>"._BL_VP_LT_VIDEOWALL."</td><td>";
	$form .= "<input type='text' name='options[0]' size='16' value='".$options[0]."' /></td></tr>";
	$form .= "</td></tr>";
	$form .= "</table>";
	return $form;
}
?>