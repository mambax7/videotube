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
//  File:       blocks/vp_featured_play.php                                  //
//  Version:    1.81                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.7 RC1  Initial Release 09/14/2008                              //
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

function videotube_featured_play($options) {

  global $xoopsConfig, $xoopsDB;
		$submituid = NULL;
  $block = array();
  
  $block['lang_displaymore'] = _BL_VP_DISPLAYMORE;
  $block['lang_displayless'] = _BL_VP_DISPLAYLESS;
  $block['lang_videotitle'] = _BL_VP_VIDEOTITLE;
  $block['lang_localviews'] = _BL_VP_LOCALVIEWS;
  $block['lang_localsubmitter'] = _BL_VP_LOCALSUBMITTER;
  $block['lang_submitdate'] = _BL_VP_SUBMITDATE;
  $block['lang_duration'] = _BL_VP_DURATION;
  $block['lang_pubdate'] = _BL_VP_PUBDATE;
  $block['lang_author'] = _BL_VP_AUTHOR;
  $block['lang_totviews'] = _BL_VP_TOTALVIEWS;

  $block['videocss'] = XOOPS_URL."/modules/videotube/style/videotube.css";
  
  $videosize = $options[0];
  $result = $xoopsDB->queryF("SELECT id, uid, date, code, title, artist, views, service, embedcode, fullembedcode FROM ".$xoopsDB->prefix()."_vp_videos WHERE id=".$options[1]."");

  $vp_vid = $xoopsDB->fetcharray($result);
  $vp_vid_disp = array();
  $vp_vid_disp['id'] = $vp_vid['id'];
  $vp_vid_disp['uid'] = $vp_vid['uid'];
  $vp_vid_disp['pcode'] = $vp_vid['code'];
  $vp_vid_disp['title'] = $vp_vid['title'];
  $vp_vid_disp['artist'] = $vp_vid['artist'];
  $vp_vid_disp['views'] = $vp_vid['views'];
  $vp_vid_disp['service'] = $vp_vid['service'];
  $vp_vid_disp['embedcode'] = $vp_vid['embedcode'];
  $vp_vid_disp['fullembedcode'] = $vp_vid['fullembedcode'];
    
  if ($vp_vid_disp['service'] == 10) {
    $widthpointer = strpos($vp_vid_disp['fullembedcode'], 'width=');
    $heightpointer = strpos($vp_vid_disp['fullembedcode'], 'height=');
    $widthpointer2 = strpos($vp_vid_disp['fullembedcode'], 'width:');
    $heightpointer2 = strpos($vp_vid_disp['fullembedcode'], 'height:');
    if ($widthpointer > 0) {
      $pwidth = substr($vp_vid_disp['fullembedcode'],($widthpointer+7), 3);
      $pheight = substr($vp_vid_disp['fullembedcode'],($heightpointer+8), 3);
      $vp_vid_disp['pwidth'] = (($videosize*$pwidth)/100);
      $vp_vid_disp['pheight'] = (($videosize*$pheight)/100);
      $fullwidth = 'width="'.$pwidth;
      $newwidth = 'width="'.$vp_vid_disp['pwidth'];
      $fullheight = 'height="'.$pheight;
      $newheight = 'height="'.$vp_vid_disp['pheight'];
      $vp_vid_disp['modifiedfullembedcode'] = str_replace($fullwidth, $newwidth, $vp_vid_disp['fullembedcode']);
      $vp_vid_disp['modifiedfullembedcode'] = str_replace($fullheight, $newheight, $vp_vid_disp['modifiedfullembedcode']);
    }elseif ($widthpointer2 > 0) {
      $pwidth = substr($vp_vid_disp['fullembedcode'],($widthpointer2+6), 3);
      $pheight = substr($vp_vid_disp['fullembedcode'],($heightpointer2+7), 3);
      $vp_vid_disp['pwidth'] = (($videosize*$pwidth)/100);
      $vp_vid_disp['pheight'] = (($videosize*$pheight)/100);
      $fullwidth = 'width:'.$pwidth;
      $newwidth = 'width:'.$vp_vid_disp['pwidth'];
      $fullheight = 'height:'.$pheight;
      $newheight = 'height:'.$vp_vid_disp['pheight'];
      $vp_vid_disp['modifiedfullembedcode'] = str_replace($fullwidth, $newwidth, $vp_vid_disp['fullembedcode']);
      $vp_vid_disp['modifiedfullembedcode'] = str_replace($fullheight, $newheight, $vp_vid_disp['modifiedfullembedcode']);
    }else{
      //
    }  
  }elseif ($vp_vid_disp['service'] == 4) {
  $vp_vid_disp['pwidth'] = (($videosize*400)/100);
  $vp_vid_disp['pheight'] = (($videosize*338)/100);  
  }elseif ($vp_vid_disp['service'] == 3) {
  $vp_vid_disp['pwidth'] = (($videosize*400)/100);
  $vp_vid_disp['pheight'] = (($videosize*345)/100);  
  }elseif ($vp_vid_disp['service'] == 2) {
  $vp_vid_disp['pwidth'] = (($videosize*420)/100);
  $vp_vid_disp['pheight'] = (($videosize*257)/100);  
  }else{
  $vp_vid_disp['pwidth'] = (($videosize*425)/100);
  $vp_vid_disp['pheight'] = (($videosize*344)/100);
  }
  
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

  if ($vp_vid_disp['service'] == 10) {

    $block['un_ft_videotitle'] = $vp_vid_disp['title'];
    $block['un_ft_localviews'] = $vp_vid_disp['views'];
    $block['un_ft_submitter'] = $vp_vid_disp['submitter'];
    $block['un_ft_vsubmitdate'] = $vp_vid_disp['date'];
  
  }elseif ($vp_vid_disp['service'] == 4) {
    $searchresultxml = 'http://blip.tv/file/'.$vp_vid_disp['pcode'].'?skin=rss';
    if(!$xml=simplexml_load_file($searchresultxml)){
      trigger_error('Error reading XML file',E_USER_ERROR);
    }

    foreach ($xml->channel->item as $item) {
      
      // get nodes in blip: namespace for blip information
      $iblip = $item->children('http://blip.tv/dtd/blip/1.0');
      
      $bt_ft_videotitle = $item->title;
      $bt_ft_published = $item->pubDate;
      $bt_ft_videoauthor = $iblip->user;
      $bt_ft_duration = $iblip->runtime;

	  $block['bt_ft_videotitle'] = $bt_ft_videotitle;
	  $block['bt_ft_videoauthor'] = $bt_ft_videoauthor;
	  $block['bt_ft_duration'] = $bt_ft_duration;
	  $block['bt_ft_published'] = $bt_ft_published;
	  $block['bt_ft_localviews'] = $vp_vid_disp['views'];
	  $block['bt_ft_submitter'] = $vp_vid_disp['submitter'];
	  $block['bt_ft_vsubmitdate'] = $vp_vid_disp['date'];
	  $block['bt_ft_vsubmittime'] = $vp_vid_disp['time'];

    }
    
  }elseif ($vp_vid_disp['service'] == 3) {
	$searchresultxml = 'http://www.metacafe.com/api/item/'.$vp_vid_disp['pcode'];
    if(!$xml=simplexml_load_file($searchresultxml)){
      trigger_error('Error reading XML file',E_USER_ERROR);
    }
    
    foreach ($xml->channel->item as $item) {

      // get nodes in media: namespace for media information
      $imedia = $item->children('http://search.yahoo.com/mrss/');
      
      $mc_ft_videotitle = $item->title;
      $mc_ft_published = $item->pubDate;
      $mc_ft_videoauthor = $item->author;
      
      $contentattrs = $imedia->content[0]->attributes();
      $mc_ft_duration = addslashes($contentattrs['duration']);
      
      $description = $item->description;
      $parsedesc = explode('|',$description);
      $parsedesc2 = explode(' ',$parsedesc[1]);
      $mc_ft_videoviews = $parsedesc2[1];

	  $block['mc_ft_videotitle'] = $mc_ft_videotitle;
	  $block['mc_ft_videoauthor'] = $mc_ft_videoauthor;
	  $block['mc_ft_videoviews'] = $mc_ft_videoviews;
	  $block['mc_ft_duration'] = $mc_ft_duration;
	  $block['mc_ft_published'] = $mc_ft_published;
	  $block['mc_ft_localviews'] = $vp_vid_disp['views'];
	  $block['mc_ft_submitter'] = $vp_vid_disp['submitter'];
	  $block['mc_ft_vsubmitdate'] = $vp_vid_disp['date'];
	  $block['mc_ft_vsubmittime'] = $vp_vid_disp['time'];
    }
  }elseif ($vp_vid_disp['service'] == 2) {
	$searchresultxml = 'http://www.dailymotion.com/rss/video/'.$vp_vid_disp['pcode'];
    if(!$xml=simplexml_load_file($searchresultxml)){
      trigger_error('Error reading XML file',E_USER_ERROR);
    }
    
    foreach ($xml->channel->item as $item) {

      // get nodes in imedia: namespace for imedia information
      $imedia = $item->children('http://search.yahoo.com/mrss');
      $iitunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
      $idm = $item->children('http://www.dailymotion.com/dmrss');
      
      $dm_ft_videotitle = $item->title;
      $dm_ft_published = $item->pubDate;
      $dm_ft_videoauthor = $iitunes->author;
      $dm_ft_videoviews = $idm->views;

      $contentattrs = $imedia->group->content[0]->attributes();
      $dm_ft_duration = $contentattrs['duration'];
	
	  $block['dm_ft_videotitle'] = $dm_ft_videotitle;
	  $block['dm_ft_videoauthor'] = $dm_ft_videoauthor;
	  $block['dm_ft_videoviews'] = $dm_ft_videoviews;
	  $block['dm_ft_duration'] = $dm_ft_duration;
	  $block['dm_ft_published'] = $dm_ft_published;
	  $block['dm_ft_localviews'] = $vp_vid_disp['views'];
	  $block['dm_ft_submitter'] = $vp_vid_disp['submitter'];
	  $block['dm_ft_vsubmitdate'] = $vp_vid_disp['date'];
	  $block['dm_ft_vsubmittime'] = $vp_vid_disp['time'];
    }
  }
 return $block;
}

function featured_edit_play($options)
{
    global $xoopsConfig, $xoopsDB;

	$form  = "<table border='0'>";
	$form .= "<tr><td>"._BL_VP_FTP_VIDEOSIZE."</td><td>";
	$form .= "<input type='text' name='options[0]' size='16' value='".$options[0]."' /></td></tr>";
	$form .= "</td></tr>";
		$form .= "<tr><td>"._BL_VP_FTP_VIDEO1."</td><td><select name='options[]'>";
	if ($options[1] == 0) {
	  $form .= "<option value=0 selected='selected'>Not selected</option>";
	}else{
	  $form .= "<option value=0>Not selected</option>";
	}
    $result = $xoopsDB->queryF("SELECT id, title FROM ".$xoopsDB->prefix()."_vp_videos WHERE pub='1' ORDER BY id");
	  while($vp_vid = $xoopsDB->fetcharray($result)) {
	    $verify = $vp_vid['id']." ".$vp_vid['title'];
	    $form .= "<option value=".$vp_vid['id']."";
	    if ($options[1] == $vp_vid['id']) {
		  $form .= " selected='selected'";
		}
	    $form .= ">$verify</option>";
	  }
    $form .= "</select>";
	$form .= "</td></tr>";
	$form .= "</table>";
	return $form;
}
?>