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
//  Date:       10/07/2009                                                   //
//  Module:     Video Tube                                                   //
//  File:       index.php                                                    //
//  Version:    1.86                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.0  Initial Release 04/14/2008                                  //
//  ***                                                                      //
//  Version 1.1  04/23/2008                                                  //
//  Bug Fix: Adjust display start record pointer if at last page             //
//  Bug Fix: Change certain includes to include_once                         //
//  New: Change acquisition method $vid parameter for 2.2.x compatibility    //
//  New: Change include paths for 2.2.x compatibility                        //
//  New: Changed syntax of all MySQL queries for 2.2.x compatibility         //
//  New: Added variable initializations for 2.2.x compatibility              //
//  Note: E-mail contact address change                                      //
//  ***                                                                      //
//  Version 1.2  05/01/2008                                                  //
//  Bug Fix: Modify comparison for enabling/disabling Next Page link to      //
//           ensure proper operation when last page appears                  //
//  Bug Fix: Removed $HTTP_GET_VARS segemnt due to potential security risks  //
//  ***                                                                      //
//  Version 1.4  05/21/2008                                                  //
//  Bug Fix: Removed unnecessary extra line break                            //
//  Bug Fix: Change header include to include_once                           //
//  Bug Fix: Add modinfo include_once for some older versions of XOOPS       //
//  New: Add view by user feature                                            //
//  New: Improve page navigation using built-in Pagenav class                //
//  ***                                                                      //
//  Version 1.6  08/01/2008                                                  //
//  New: Add categories                                                      //
//  ***                                                                      //
//  Version 1.62  08/09/2008                                                 //
//  New: Add display of number of videos in each category                    //
//  New: Add Display All Categories preference parameter                     //
//  ***                                                                      //
//  Version 1.63  08/23/2008                                                 //
//  New: Add page navigation option                                          //
//  New: Add global comments                                                 //
//  New: Add Daily Motion                                                    //
//  ***                                                                      //
//  Version 1.64  09/06/2008                                                 //
//  Bug Fix: Correct pagination links for View By User pages                 //
//  New: Add MetaCafe support                                                //
//  New: Add blip.tv support                                                 //
//  New: Add manual submission support                                       //
//  ***                                                                      //
//  Version 1.65  09/12/2008                                                 //
//  New: Add local description capabilities for all video displays           //
//  ***                                                                      //
//  Version 1.7 RC1  09/14/2008                                              //
//  New: Add page menu display                                               //
//  ***                                                                      //
//  Version 1.81  01/06/2009                                                 //
//  Code cleanup and housekeeping                                            //
//  Add language support                                                     //
//  Rewrite category management admin interface                              //
//  ***                                                                      //
//  Version 1.82  01/10/2009                                                 //
//  Update from XoopsTree to VideoTubeTree                                   //
//  ***                                                                      //
//  Version 1.83  01/12/2009                                                 //
//  Add number of inferred sub categories displayed                          //
//  ***                                                                      //
//  Version 1.84  01/18/2009                                                 //
//  Add Report Video feature                                                 //
//  Add Recommend Video feature                                              //
//  ***                                                                      //
//  Version 1.85  08/09/2009                                                 //
//  Bug fix add report and recommend template assignments to view by user    //
//  Add Rate Video feature                                                   //
//  ***                                                                      //
//  Version 1.86  10/07/2009                                                 //
//  Add template assignments to support facebook sharer                      //
//  ***                                                                      //

include_once '../../mainfile.php';
include_once XOOPS_ROOT_PATH.'/header.php';
include 'include/functions.php';
include_once 'class/videotubetree.php';
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

if (file_exists('language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php')) {
  include_once 'language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php';
}else{
  include_once 'language/english/modinfo.php';
}

global $xoopsOption, $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModuleConfig;

settype($vid,"integer");
settype($sid,"integer");
settype($cid,"integer");

$vcatid = 0;
$startvid = 0;
$op ='';
$postnum ='';
$catminerror = NULL;
$categorytitle = NULL;

$mytree = new VideoTubeTree($xoopsDB->prefix("vp_categories"), "cid", "pid");

if (isset($_GET['vid'])) $vid = intval($_GET['vid']);
if (isset($_GET['startvid'])) $startvid = intval($_GET['startvid']);
if (isset($_GET['sid'])) $sid = intval($_GET['sid']);
if (isset($_GET['cid'])) $cid = intval($_GET['cid']);

$vcatid = $cid;

if (isset($HTTP_GET_VARS['op'])) $op=$HTTP_GET_VARS['op'];
if (isset($HTTP_POST_VARS['op'])) $op=$HTTP_POST_VARS['op'];
$PHP_SELF = $_SERVER["PHP_SELF"];

$myts =& MyTextSanitizer::getInstance();

switch($op) {

  case "viewbyuser":

	// Designate Smarty template to be used
		
	$xoopsOption['template_main'] = 'video_display.html';
	include XOOPS_ROOT_PATH.'/header.php';
	
	// Read Preferences configuration values
		
	$vdispformat = $xoopsModuleConfig['videodisplayformat'];
	$vautoplay = $xoopsModuleConfig['videoautoplay'];
	$vdisporder = $xoopsModuleConfig['videodisplayorder'];
	$vdispnumber = $xoopsModuleConfig['videodisplaynumber'];
	$vuseborder = $xoopsModuleConfig['videouseborder'];
    $vpricolor = $xoopsModuleConfig['videoprimarycolor'];
    $vseccolor = $xoopsModuleConfig['videosecondarycolor'];
    $vusecats = $xoopsModuleConfig['videousecats'];
    $vpagenav = $xoopsModuleConfig['videopagenav'];
    $vpagemenuenable = $xoopsModuleConfig['pagemenuenable'];
            
	$result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && uid=$sid");
	$vdisptotal = mysql_num_rows($result);
	mysql_free_result($result);

        if ($vdisptotal == 1) {
	      $zerovideoview = FALSE;
	      $singlevideoview = TRUE;
	    }else{
	      $zerovideoview = FALSE;
	      $singlevideoview = FALSE;
	    }

        $xoopsTpl->assign('zerovideoview', $zerovideoview);
        $xoopsTpl->assign('singlevideoview', $singlevideoview);
	
	if ($vdispnumber) {
		$vdispstart = $startvid;
		if (($vdispstart + 1) == $vdisptotal) {
			$vdispstart = $vdispstart - 1;
		}
		$vdispend = $vdispnumber - 1;
	} else {
		
		// If videos to display per page is set to zero then set limits
		// to display all videos on a single page
			
		$vdispstart = 0;
		$vdispend = $vdisptotal;
	}
		
	$xoopsTpl->assign('sid', $sid);
	
	if ( $vdisptotal > $vdispnumber && $vdispnumber > 0 ) {
        include XOOPS_ROOT_PATH.'/class/pagenav.php';
        $nav = new XoopsPageNav($vdisptotal, $vdispnumber, $vdispstart, "startvid", 'op=viewbyuser&sid='.$sid);
        if ($vpagenav == 1) {
          $xoopsTpl->assign('video_page_nav', $nav->renderImageNav(4));
        } elseif ($vpagenav == 2) {
          $xoopsTpl->assign('video_page_nav', $nav->renderSelect(false));
        } else {
          $xoopsTpl->assign('video_page_nav', $nav->renderNav(4));
        }
    } else {
        $xoopsTpl->assign('video_page_nav', '');
    }

	    include XOOPS_ROOT_PATH.'/modules/videotube/class/videotubedisplay.php';
	    $videodisplay = new VideoTubeDisp(PHP_VERSION,$xoopsModuleConfig['dailymotionenable'],
	                                      $xoopsModuleConfig['metacafeenable'],$xoopsModuleConfig['bliptvenable'],
	                                      $xoopsModuleConfig['manualsubmitenable'],$xoopsModuleConfig['managevideosenable'],
	                                      _MI_VP_PMNAME1,_MI_VP_PMNAME2,_MI_VP_PMNAME3,_MI_VP_PMNAME4,
	                                      _MI_VP_PMNAME5,_MI_VP_PMNAME6,_MI_VP_PMNAME7,_MI_VP_PMNAME8);
        $xoopsTpl->assign('video_pagemenu', $videodisplay->renderPageMenu());
        $xoopsTpl->assign('vpagemenuenable', $vpagemenuenable);
		
	$result3 = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=$sid");
	$row3 = $xoopsDB->fetcharray($result3);
		  
	$pagetitle = _MI_VP_MYPAGETITLE." - ".$row3['uname'];
	$xoopsTpl->assign('listtitle', $pagetitle);
		
	// If a video thumbnail was selected then set this to be the video in play window
	// otherwise determine the video to play based on preference settings and limits
		
	$vidselectionflag = 0;
	
	if ($vid) {
		$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE id=$vid");
		$vidselectionflag = 1;
	} else {
		if ($singlevideoview == TRUE) {
			$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && uid=$sid");
		}elseif ($vdisporder == 1) {
			$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && uid=$sid ORDER BY id DESC LIMIT $vdispstart, $vdispend");
		} elseif ($vdisporder == 2) {
			$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && uid=$sid ORDER BY views DESC LIMIT $vdispstart, $vdispend");
		} elseif ($vdisporder == 3) {
			$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && uid=$sid ORDER BY id ASC LIMIT $vdispstart, $vdispend");
		} else {
			$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && uid=$sid ORDER BY views ASC LIMIT $vdispstart, $vdispend");
		}
	}
	$pvideo = array();
	$row = $xoopsDB->fetcharray($result);
	$pvideo['pcode'] = $row['code'];
	$pvideo['ptitle'] = $row['title'];
	$pvideo['ptitleesc'] = addslashes($row['title']);
	$pvideo['partist'] = $row['artist'];
	$pvideo['partistesc'] = addslashes($row['artist']);
	$pvideo['pdesc'] = $row['description'];
	$pvideo['pref'] = $row['id'];
	$pvideo['pviews'] = $row['views'];
	$pvideo['pwidth'] = 425;
	$pvideo['pheight'] = 344;
    $pvideo['service'] = $row['service'];
	$pvideo['embedcode'] = $row['embedcode'];
	$pvideo['fullembedcode'] = $row['fullembedcode'];
	  
	$submituid = $row['uid'];
		
	// Display submission information based on whether it was submitted by
	// a registered user or anonymous
		
	if ($submituid) {
	  $result2 = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=$submituid");
	  $row2 = $xoopsDB->fetcharray($result2);
		  
	  $pvideo['submitter'] = $row2['uname'];
	} else {
	  $pvideo['submitter'] = $xoopsConfig['anonymous'];
	}

	//  Get the local rating information
    
	$vid = $pvideo['pref'];
	$ratingtotal = 0;
	$result3 = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_votedata")." WHERE rvid=$vid");
	$localraters = mysql_num_rows($result3);
	while ($row = $xoopsDB->fetcharray($result3)) {
	  $ratingtotal = $ratingtotal + $row['rating'];
	}
	$localavgrating = $ratingtotal / $localraters;
	$pvideo['localraters'] = $localraters;
	$pvideo['localrating'] = $localavgrating;
		
	// Extract submission date and convert it into readable format
		
	$tempDate = $dateArray=explode(',',strftime("%Y,%m,%d,%I,%M,%p",$row['date']));
	$pvideo['date'] = date('D M j Y', mktime(0, 0, 0, $tempDate[1], $tempDate[2], $tempDate[0]));
	$pvideo['time'] = $tempDate[3].":".$tempDate[4].strtolower($tempDate[5]);
			
	// Increment the video views
		
	$sqlval = updateVideoViews($pvideo['pref'],$pvideo['pviews']);
	$pvideo['pviews']++;
		
	$playref=$pvideo['pref'];
		
	// Pass the video to play information to the template
		
	$xoopsTpl->append('playvideo', $pvideo);
		
	mysql_free_result($result);
		
	// Get the videos from database to be displayed as selectable thumbnails
		
	if ($singlevideoview == TRUE){
	  //
	}elseif ($vdisporder == 1) {
		$result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref && uid=$sid ORDER BY id DESC LIMIT $vdispstart, $vdispend");
	} elseif ($vdisporder == 2) {
		$result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref && uid=$sid ORDER BY views DESC LIMIT $vdispstart, $vdispend");
	} elseif ($vdisporder == 3) {
		$result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref && uid=$sid ORDER BY id ASC LIMIT $vdispstart, $vdispend");
	} else {
		$result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref && uid=$sid ORDER BY views ASC LIMIT $vdispstart, $vdispend");
	}
		
	$xoopsTpl->assign('displaycolumns', $vdispformat);
	$xoopsTpl->assign('vautoplay', $vautoplay);
	$xoopsTpl->assign('vselected', $vidselectionflag);
	$xoopsTpl->assign('startvid', $startvid);
	$xoopsTpl->assign('vuseborder', $vuseborder);
	$xoopsTpl->assign('vpricolor', $vpricolor);
	$xoopsTpl->assign('vseccolor', $vseccolor);
	$xoopsTpl->assign('vusecats', $vusecats);

	$xoopsTpl->assign('lang_reportbutton', _MI_VI_REPORTBUTTON);
	$xoopsTpl->assign('lang_recommendbutton', _MI_VI_RECOMMENDBUTTON);
	$xoopsTpl->assign('lang_ratebutton', _MI_VI_RATEBUTTON);

	$xoopsTpl->assign('mail_subject', rawurlencode(sprintf(_MI_VI_MAILSUBJECT.' '.$xoopsConfig['sitename'])));
	$xoopsTpl->assign('mail_body', rawurlencode(sprintf(_MI_VI_MAILBODY.' '.XOOPS_URL.'/modules/videotube/index.php?vid='.$pvideo["pref"])));
	
	$xoopsTpl->assign('lang_videotitle', _MI_VI_VIDEOTITLE);
	$xoopsTpl->assign('lang_desc', _MI_VI_DESC);
	$xoopsTpl->assign('lang_displaymore', _MI_VI_DISPLAYMORE);
    $xoopsTpl->assign('lang_displayless', _MI_VI_DISPLAYLESS);
    $xoopsTpl->assign('lang_localviews', _MI_VI_LOCALVIEWS);
    $xoopsTpl->assign('lang_submitter', _MI_VI_SUBMITTER);
    $xoopsTpl->assign('lang_duration', _MI_VI_DURATION);
    $xoopsTpl->assign('lang_submitdate', _MI_VI_SUBMITDATE);
    $xoopsTpl->assign('lang_views', _MI_VI_VIEWS);
    $xoopsTpl->assign('lang_submittime', _MI_VI_SUBMITTIME);
    $xoopsTpl->assign('lang_favorites', _MI_VI_FAVORITES);
    $xoopsTpl->assign('lang_author', _MI_VI_AUTHOR);
    $xoopsTpl->assign('lang_raters', _MI_VI_RATERS);
    $xoopsTpl->assign('lang_pubdate', _MI_VI_PUBDATE);
    $xoopsTpl->assign('lang_avgrating', _MI_VI_AVGRATING);
    $xoopsTpl->assign('lang_pubtime', _MI_VI_PUBTIME);
	$xoopsTpl->assign('lang_pagesel', _MI_VP_PAGESEL);
	$xoopsTpl->assign('lang_catsel', _MI_VP_CATSEL);
	$xoopsTpl->assign('lang_submitted', _MI_VP_SUBMITTED);
	$xoopsTpl->assign('lang_on', _MI_VP_ON);
	$xoopsTpl->assign('lang_at', _MI_VP_AT);
	$xoopsTpl->assign('lang_pleaseselect', _MI_VP_PLEASESELECT);
	$xoopsTpl->assign('lang_novideosfound', _MI_VP_NOVIDEOSFOUND);
	$xoopsTpl->assign('lang_lraters', _MI_VP_LOCALRATERS);
	$xoopsTpl->assign('lang_lrating', _MI_VP_LOCALRATING);

	$xoopsTpl->assign('videocss', XOOPS_URL."/modules/videotube/style/videotube.css");
	
	// Create a temporary array to store the video info from the db
		
	$video_array = array();
	$toprow=1;
	$rownum=1;
		
	// Read the video information
		
	while ($row = $xoopsDB->fetcharray($result)) {
	  $postnum++;
	  $video_array['toprow']=$toprow;
	  $video_array['postnum']=$postnum;
	  $video_array['ref'] = $row['id'];
		
	  $submituid = $row['uid'];
		
	// If the submitter is a registered user then get their user name
		
	if ($submituid) {
	  $result2 = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=$submituid");
	  $row2 = $xoopsDB->fetcharray($result2);
		  
	  $video_array['submitter'] = $row2['uname'];
	} else {
	  $video_array['submitter'] = $xoopsConfig['anonymous'];
	}
	
	$video_array['code'] = $row['code'];
	$video_array['title'] = $row['title'];
	$video_array['artist'] = $row['artist'];
	$video_array['views'] = $row['views'];
	$video_array['service'] = $row['service'];
	$video_array['embedcode'] = $row['embedcode'];
    $video_array['thumb'] = $row['thumb'];
              
    switch($video_array['service']) {
	  case 10:
	    $video_array['servicename'] = $row['servicename'];
		break;
	  case 4:
	    $video_array['servicename'] = 'blip.tv';
		break;
	  case 3:
	    $video_array['servicename'] = 'MetaCafe';
		break;
	  case 2:
	    $video_array['servicename'] = 'DailyMotion';
		break;
	  default:
	    $video_array['servicename'] = 'YouTube';
	}
		  
	  // Extract submission date and convert it into readable format
		  
	  $tempDate = $dateArray=explode(',',strftime("%Y,%m,%d,%I,%M,%p",$row['date']));
	  $video_array['date'] = date('D M j Y', mktime(0, 0, 0, $tempDate[1], $tempDate[2], $tempDate[0]));
	  $video_array['time'] = $tempDate[3].":".$tempDate[4].strtolower($tempDate[5]);
		
	  // Set variable values used as flags to control html table elements
	  // in the template based on how many columns are to be displayed
	  
	  if ($toprow == 1) {
	    $toprow = 0;
	  }
	  if ($postnum == $vdispformat) {
	    $postnum = 0;
	    $rownum++;
	  }
	  if ($vdispformat == 4) {
	    if (($postnum > 0) and ($rownum < 4)) {
	      $postnum = 0;
	      $rownum++;
	    }
	  }
	  if ($vdispformat == 5) {
	    if (($postnum > 1) and ($rownum < 4)) {
	      $postnum = 0;
	      $rownum++;
	    }
	  }
		
	  $video_array['rownum']=$rownum;
		  
	  // Pass the video thumbnail information to the template
		  
	  $xoopsTpl->append('videos', $video_array);
	}

    if ($pvideo['service'] == 10) {
	    
        $unvideotitle = $pvideo['ptitle'];
        $unvideotitle = addslashes($unvideotitle);

        $unvideodesc = $pvideo['pdesc'];
        $unvideodesc = addslashes($unvideodesc);
        $unvideodesc = str_replace(';',':', $unvideodesc);

	    $xoopsTpl->assign('unvideotitle', $unvideotitle);
	    $xoopsTpl->assign('unvideodesc', $unvideodesc);
	    $xoopsTpl->assign('unlocalviews', $pvideo['pviews']);
	    $xoopsTpl->assign('unfullembedcode', $pvideo['fullembedcode']);
	    $xoopsTpl->assign('unsubmitter', $pvideo['submitter']);
	    $xoopsTpl->assign('unvsubmitdate', $pvideo['date']);
	    $xoopsTpl->assign('unvsubmittime', $pvideo['time']);

    }elseif ($pvideo['service'] == 4) {

      $searchresultxml = 'http://blip.tv/file/'.$pvideo['pcode'].'?skin=rss';
      if(!$xml=simplexml_load_file($searchresultxml)){
        trigger_error('Error reading XML file',E_USER_ERROR);
      }
      
      foreach ($xml->channel->item as $item) {

        $imedia = $item->children('http://search.yahoo.com/mrss/');
        $iblip = $item->children('http://blip.tv/dtd/blip/1.0');
  
        $btvideotitle = $item->title;
        $btvideotitle = addslashes($btvideotitle);
        $btvideoauthor = $iblip->user;
        $btpublished = $item->pubDate;
        if ($pvideo['pdesc']) {
          $btvideodesc = $pvideo['pdesc'];
        }else{
          $btvideodesc = trim($iblip->puredescription);
        }
        $btduration = $iblip->runtime;
        $btvideorating = $iblip->rating;

	    $xoopsTpl->assign('btvideotitle', $btvideotitle);
	    $xoopsTpl->assign('btvideodesc', $btvideodesc);
	    $xoopsTpl->assign('btvideoauthor', $btvideoauthor);
	    $xoopsTpl->assign('btvideorating', $btvideorating);
        $xoopsTpl->assign('btduration', $btduration);
        $xoopsTpl->assign('btpublished', $btpublished);
	    $xoopsTpl->assign('btlocalviews', $pvideo['pviews']);
	    $xoopsTpl->assign('btsubmitter', $pvideo['submitter']);
	    $xoopsTpl->assign('btvsubmitdate', $pvideo['date']);
	    $xoopsTpl->assign('btvsubmittime', $pvideo['time']);
	  }
	
	}elseif ($pvideo['service'] == 3) {

	  $searchresultxml = 'http://www.metacafe.com/api/item/'.$pvideo['pcode'];
      if(!$xml=simplexml_load_file($searchresultxml)){
        trigger_error('Error reading XML file',E_USER_ERROR);
      }
    
      foreach ($xml->channel->item as $item) {

        // get nodes in imedia: namespace for imedia information
        $imedia = $item->children('http://search.yahoo.com/mrss/');
      
        $mcvideotitle = $item->title;
        $mcpublished = $item->pubDate;
        if ($pvideo['pdesc']) {
          $mcvideodesc = $pvideo['pdesc'];
        }else{
          $mcvideodesc = $imedia->description;
        }
        $mcvideoauthor = $item->author;
      
        $mcvideorating= $item->rank;
      
        $contentattrs = $imedia->content[0]->attributes();
        $mcduration = addslashes($contentattrs['duration']);
      
        $description = $item->description;
        $parsedesc = explode('|',$description);
        $parsedesc2 = explode(' ',$parsedesc[1]);
        $mcvideoviews = $parsedesc2[1];

      }
      
      $mcvideotitle = addslashes($mcvideotitle);
      $mcvideodesc = addslashes($mcvideodesc);	
	
	  $xoopsTpl->assign('mcvideotitle', $mcvideotitle);
	  $xoopsTpl->assign('mcvideodesc', $mcvideodesc);
	  $xoopsTpl->assign('mcvideoauthor', $mcvideoauthor);
	  $xoopsTpl->assign('mcvideoviews', $mcvideoviews);
	  $xoopsTpl->assign('mcfavorites', $mcfavorites);
	  $xoopsTpl->assign('mcvideorating', $mcvideorating);
	  $xoopsTpl->assign('mcvideovotes', $mcvideovotes);
	  $xoopsTpl->assign('mcduration', $mcduration);
	  $xoopsTpl->assign('mcpublished', $mcpublished);
	  $xoopsTpl->assign('mclocalviews', $pvideo['pviews']);
	  $xoopsTpl->assign('mcsubmitter', $pvideo['submitter']);
	  $xoopsTpl->assign('mcvsubmitdate', $pvideo['date']);
	  $xoopsTpl->assign('mcvsubmittime', $pvideo['time']);
	
	}elseif ($pvideo['service'] == 2) {

	  $searchresultxml = 'http://www.dailymotion.com/rss/video/'.$pvideo['pcode'];
      if(!$xml=simplexml_load_file($searchresultxml)){
        trigger_error('Error reading XML file',E_USER_ERROR);
      }

      $dmvideo_array = array();
    
      foreach ($xml->channel->item as $item) {

        // get nodes in imedia: namespace for imedia information
        $imedia = $item->children('http://search.yahoo.com/mrss');
        $iitunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
        $idm = $item->children('http://www.dailymotion.com/dmrss');
      
        $dmvideotitle = $item->title;
        $dmpublished = $item->pubDate;
        if ($pvideo['pdesc']) {
          $dmvideodesc = $pvideo['pdesc'];
        }else{
          $dmvideodesc = $iitunes->summary;
        }
        $dmvideoauthor = $iitunes->author;
        $dmvideoviews = $idm->views;
        $dmfavorites= $idm->favorites;
        $dmvideorating= $idm->videorating;
        $dmvideovotes = $idm->videovotes;

        $contentattrs = $imedia->group->content[0]->attributes();
        $dmduration = $contentattrs['duration'];
      }	
	  
	  $dmvideotitle = addslashes($dmvideotitle);
      $dmvideodesc = addslashes($dmvideodesc);	

	  $xoopsTpl->assign('dmvideotitle', $dmvideotitle);
	  $xoopsTpl->assign('dmvideodesc', $dmvideodesc);
	  $xoopsTpl->assign('dmvideoauthor', $dmvideoauthor);
	  $xoopsTpl->assign('dmvideoviews', $dmvideoviews);
	  $xoopsTpl->assign('dmfavorites', $dmfavorites);
	  $xoopsTpl->assign('dmvideorating', $dmvideorating);
	  $xoopsTpl->assign('dmvideovotes', $dmvideovotes);
	  $xoopsTpl->assign('dmduration', $dmduration);
	  $xoopsTpl->assign('dmpublished', $dmpublished);
	  $xoopsTpl->assign('dmlocalviews', $pvideo['pviews']);
	  $xoopsTpl->assign('dmsubmitter', $pvideo['submitter']);
	  $xoopsTpl->assign('dmvsubmitdate', $pvideo['date']);
      $xoopsTpl->assign('dmvsubmittime', $pvideo['time']);
	}else{
	  //
	}
	
	if (!$vid) {
	  $_GET['vid']=$playref;
	}
	
	$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->name() . ' - ' .$pvideo['ptitle']);
	$xoopsTpl->assign('xoops_module_header', '<meta name="title" content="'.$pvideo['ptitle'].'" />
<link rel="image_src" href="http://img.youtube.com/vi/'.$pvideo['pcode'].'/default.jpg" />
<link rel="video_src" href="http://www.youtube.com/v/'.$pvideo['pcode'].'&border=0&rel=1"/>
<meta name="description" content="'.$pvideo['pdesc'].'" />
<meta name="video_height" content="'.$pvideo['pheight'].'" />
<meta name="video_width" content="'.$pvideo['pwidth'].'" />
<meta name="video_type" content="application/x-shockwave-flash" />');

    if (is_object($xoTheme)) {
      $xoTheme->addMeta( 'meta', 'keywords',$pvideo['ptitle']);
      $xoTheme->addMeta( 'meta', 'description',$pvideo['pdesc']);
      //$xoTheme->addMeta( 'meta', 'xoops_pagetitle',$this->_title);
    } else {
      $xoopsTpl->assign('xoops_meta_keywords','keywords',$pvideo['ptitle']);
      $xoopsTpl->assign('xoops_meta_description',$pvideo['pdesc']);
    }

    include XOOPS_ROOT_PATH.'/include/comment_view.php';
	include_once XOOPS_ROOT_PATH.'/footer.php';

	break;
	
  default:

	// Determine the total number of published videos in db table
	// If records less than minimum display message screen
	
	$result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1");
	$vdisptotal = mysql_num_rows($result);
	mysql_free_result($result);

    if ($vdisptotal < 0) {
      //
	} else {
		  
		// Designate Smarty template to be used
		
		$xoopsOption['template_main'] = 'video_display.html';
		include XOOPS_ROOT_PATH.'/header.php';
	
		// Read Preferences configuration values
		
		$vdispformat = $xoopsModuleConfig['videodisplayformat'];
		$vautoplay = $xoopsModuleConfig['videoautoplay'];
		$vdisporder = $xoopsModuleConfig['videodisplayorder'];
		$vdispnumber = $xoopsModuleConfig['videodisplaynumber'];
		$vuseborder = $xoopsModuleConfig['videouseborder'];
        $vpricolor = $xoopsModuleConfig['videoprimarycolor'];
        $vseccolor = $xoopsModuleConfig['videosecondarycolor'];
        $vusecats = $xoopsModuleConfig['videousecats'];
        $vpagenav = $xoopsModuleConfig['videopagenav'];
        $vpagemenuenable = $xoopsModuleConfig['pagemenuenable'];
		$vcattype = $xoopsModuleConfig['videocattype'];
        $vcattypecolumns = $xoopsModuleConfig['videocattypecolumns'];
		$videocattypeinferred = $xoopsModuleConfig['videocattypeinferred'];
           
		if ($vcatid) {
		  $result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && cid=$vcatid");
		}else{
		  $result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1");
		}
		$vdisptotal = mysql_num_rows($result);
		mysql_free_result($result);
	
        if ($vdisptotal < 1) {
          $zerovideoview = TRUE;
	    }elseif ($vdisptotal == 1) {
	      $zerovideoview = FALSE;
	      $singlevideoview = TRUE;
	    }else{
	      $zerovideoview = FALSE;
	      $singlevideoview = FALSE;
	    }

        $xoopsTpl->assign('zerovideoview', $zerovideoview);
        $xoopsTpl->assign('singlevideoview', $singlevideoview);

		if ($vdispnumber) {
			$vdispstart = $startvid;
			if (($vdispstart + 1) == $vdisptotal) {
				$vdispstart = $vdispstart - 1;
			}
			$vdispend = $vdispnumber - 1;
		} else {
			
			// If videos to display per page is set to zero then set limits
			// to display all videos on a single page
				
			$vdispstart = 0;
			$vdispend = $vdisptotal;
		}
			
		if ( $vdisptotal > $vdispnumber && $vdispnumber > 0 ) {
	    	include XOOPS_ROOT_PATH.'/class/pagenav.php';
	        if ($vcatid){
	        	$nav = new XoopsPageNav($vdisptotal, $vdispnumber, $vdispstart, "startvid", "cid=".$vcatid);
	        }else{
	            $nav = new XoopsPageNav($vdisptotal, $vdispnumber, $vdispstart, "startvid");
	        }
	        if ($vpagenav == 1) {
	          $xoopsTpl->assign('video_page_nav', $nav->renderImageNav(4));
	        } elseif ($vpagenav == 2) {
	          $xoopsTpl->assign('video_page_nav', $nav->renderSelect(false));
	        } else {
	          $xoopsTpl->assign('video_page_nav', $nav->renderNav(4));
	        }
	    } else {
	        $xoopsTpl->assign('video_page_nav', '');
	    }
	    
	    include XOOPS_ROOT_PATH.'/modules/videotube/class/videotubedisplay.php';
	    $videodisplay = new VideoTubeDisp(PHP_VERSION,$xoopsModuleConfig['dailymotionenable'],
	                                      $xoopsModuleConfig['metacafeenable'],$xoopsModuleConfig['bliptvenable'],
	                                      $xoopsModuleConfig['manualsubmitenable'],$xoopsModuleConfig['managevideosenable'],
	                                      _MI_VP_PMNAME1,_MI_VP_PMNAME2,_MI_VP_PMNAME3,_MI_VP_PMNAME4,
	                                      _MI_VP_PMNAME5,_MI_VP_PMNAME6,_MI_VP_PMNAME7,_MI_VP_PMNAME8);
        $xoopsTpl->assign('video_pagemenu', $videodisplay->renderPageMenu());
        $xoopsTpl->assign('vpagemenuenable', $vpagemenuenable);

		if ($vcatid){
			$xoopsTpl->assign('listtitle', _MI_VP_CATPAGETITLE);
	    }else{
			$xoopsTpl->assign('listtitle', _MI_VP_MAINPAGETITLE);
		}
			
			// If a video thumbnail was selected then set this to be the video in play window
			// otherwise determine the video to play based on preference settings and limits
			
			$vidselectionflag = 0;
		
			if ($vid) {
				$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE id=$vid");
				$vidselectionflag = 1;
				$vidcommentflag = 1;
			} else {
				if ($singlevideoview == TRUE) {
				  if ($vcatid) {
					$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && cid=$vcatid");
				  }else{
					$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1");
				  }
				}elseif ($vdisporder == 1) {
				  if ($vcatid) {
					$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && cid=$vcatid ORDER BY id DESC LIMIT $vdispstart, $vdispend");
				  }else{
					$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 ORDER BY id DESC LIMIT $vdispstart, $vdispend");
				  }
				} elseif ($vdisporder == 2) {
				  if ($vcatid) {
					$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && cid=$vcatid ORDER BY views DESC LIMIT $vdispstart, $vdispend");
				  }else{
					$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 ORDER BY views DESC LIMIT $vdispstart, $vdispend");
				  }
				} elseif ($vdisporder == 3) {
				  if ($vcatid) {
					$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && cid=$vcatid ORDER BY id ASC LIMIT $vdispstart, $vdispend");
				  }else{
					$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 ORDER BY id ASC LIMIT $vdispstart, $vdispend");
				  }
				} else {
				  if ($vcatid) {
					$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && cid=$vcatid ORDER BY views ASC LIMIT $vdispstart, $vdispend");
				  }else{
					$result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 ORDER BY views ASC LIMIT $vdispstart, $vdispend");
				  }
				}
			}
			$pvideo = array();
			$row = $xoopsDB->fetcharray($result);
			$pvideo['pcode'] = $row['code'];
			$pvideo['ptitle'] = $row['title'];
			$pvideo['ptitleesc'] = addslashes($row['title']);
			$pvideo['partist'] = $row['artist'];
			$pvideo['partistesc'] = addslashes($row['artist']);
			$pvideo['pdesc'] = $row['description'];
			$pvideo['pref'] = $row['id'];
			$pvideo['pviews'] = $row['views'];
			$pvideo['pwidth'] = 425;
			$pvideo['pheight'] = 344;
		    $pvideo['service'] = $row['service'];
		    $pvideo['embedcode'] = $row['embedcode'];
		    $pvideo['fullembedcode'] = $row['fullembedcode'];
		    
			$submituid = $row['uid'];
			
			// Display submission information based on whether it was submitted by
			// a registered user or anonymous
			
			if ($submituid) {
			  $result2 = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=$submituid");
			  $row2 = $xoopsDB->fetcharray($result2);
			  
			  $pvideo['submitter'] = $row2['uname'];
			} else {
			  $pvideo['submitter'] = $xoopsConfig['anonymous'];
			}
			
			// Extract submission date and convert it into readable format
			
			$tempDate = $dateArray=explode(',',strftime("%Y,%m,%d,%I,%M,%p",$row['date']));
			$pvideo['date'] = date('D M j Y', mktime(0, 0, 0, $tempDate[1], $tempDate[2], $tempDate[0]));
			$pvideo['time'] = $tempDate[3].":".$tempDate[4].strtolower($tempDate[5]);
				
			//  Get the local rating information
            $vid = $pvideo['pref'];
			$ratingtotal = 0;
			$result3 = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_votedata")." WHERE rvid=$vid");
			$localraters = mysql_num_rows($result3);
			while ($row = $xoopsDB->fetcharray($result3)) {
			  $ratingtotal = $ratingtotal + $row['rating'];
			}
			$localavgrating = $ratingtotal / $localraters;
			$pvideo['localraters'] = $localraters;
			$pvideo['localrating'] = $localavgrating;
			
			// Increment the video views
			
			if (!$catminerror) {
			  $sqlval = updateVideoViews($pvideo['pref'],$pvideo['pviews']);
			  $pvideo['pviews']++;
			
			  $playref=$pvideo['pref'];
			
			  // Pass the video to play information to the template
			
			  $xoopsTpl->append('playvideo', $pvideo);
			
			  mysql_free_result($result);
			
			// Get the videos from database to be displayed as selectable thumbnails
			
			if ($vdisporder == 1) {
			  if ($vcatid) {
			    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref && cid=$vcatid ORDER BY id DESC LIMIT $vdispstart, $vdispend");
			  }else{
		        $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref ORDER BY id DESC LIMIT $vdispstart, $vdispend");
			  }
			} elseif ($vdisporder == 2) {
			  if ($vcatid) {
			    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref && cid=$vcatid ORDER BY views DESC LIMIT $vdispstart, $vdispend");
			  }else{
			    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref ORDER BY views DESC LIMIT $vdispstart, $vdispend");
			  }
			} elseif ($vdisporder == 3) {
			  if ($vcatid) {
			    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref && cid=$vcatid ORDER BY id ASC LIMIT $vdispstart, $vdispend");
			  }else{
			    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref ORDER BY id ASC LIMIT $vdispstart, $vdispend");
			  }
	        } else {
			  if ($vcatid) {
			    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref && cid=$vcatid ORDER BY views ASC LIMIT $vdispstart, $vdispend");
			  }else{
			    $result = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && id<>$playref ORDER BY views ASC LIMIT $vdispstart, $vdispend");
			  }
	
			}

			$xoopsTpl->assign('displaycolumns', $vdispformat);
			}
			$xoopsTpl->assign('vautoplay', $vautoplay);
			$xoopsTpl->assign('vselected', $vidselectionflag);
			$xoopsTpl->assign('startvid', $startvid);
			$xoopsTpl->assign('vuseborder', $vuseborder);
			$xoopsTpl->assign('vpricolor', $vpricolor);
			$xoopsTpl->assign('vseccolor', $vseccolor);
			$xoopsTpl->assign('vusecats', $vusecats);
			$xoopsTpl->assign('vcatid', $vcatid);
			$xoopsTpl->assign('vcattype', $vcattype);
			$xoopsTpl->assign('vcattypecolumns', $vcattypecolumns);

			$xoopsTpl->assign('lang_reportbutton', _MI_VI_REPORTBUTTON);
			$xoopsTpl->assign('lang_recommendbutton', _MI_VI_RECOMMENDBUTTON);
			$xoopsTpl->assign('lang_ratebutton', _MI_VI_RATEBUTTON);
			
			$xoopsTpl->assign('mail_subject', rawurlencode(sprintf(_MI_VI_MAILSUBJECT.' '.$xoopsConfig['sitename'])));
			$xoopsTpl->assign('mail_body', rawurlencode(sprintf(_MI_VI_MAILBODY.' '.XOOPS_URL.'/modules/videotube/index.php?vid='.$pvideo["pref"])));
			
			$xoopsTpl->assign('lang_videotitle', _MI_VI_VIDEOTITLE);
		    $xoopsTpl->assign('lang_desc', _MI_VI_DESC);
		    $xoopsTpl->assign('lang_displaymore', _MI_VI_DISPLAYMORE);
	        $xoopsTpl->assign('lang_displayless', _MI_VI_DISPLAYLESS);
	        $xoopsTpl->assign('lang_localviews', _MI_VI_LOCALVIEWS);
	        $xoopsTpl->assign('lang_submitter', _MI_VI_SUBMITTER);
	        $xoopsTpl->assign('lang_duration', _MI_VI_DURATION);
	        $xoopsTpl->assign('lang_submitdate', _MI_VI_SUBMITDATE);
	        $xoopsTpl->assign('lang_views', _MI_VI_VIEWS);
	        $xoopsTpl->assign('lang_submittime', _MI_VI_SUBMITTIME);
	        $xoopsTpl->assign('lang_favorites', _MI_VI_FAVORITES);
	        $xoopsTpl->assign('lang_author', _MI_VI_AUTHOR);
	        $xoopsTpl->assign('lang_raters', _MI_VI_RATERS);
	        $xoopsTpl->assign('lang_pubdate', _MI_VI_PUBDATE);
	        $xoopsTpl->assign('lang_avgrating', _MI_VI_AVGRATING);
	        $xoopsTpl->assign('lang_pubtime', _MI_VI_PUBTIME);
			$xoopsTpl->assign('lang_pagesel', _MI_VP_PAGESEL);
			$xoopsTpl->assign('lang_catsel', _MI_VP_CATSEL);
			$xoopsTpl->assign('lang_catlist', _MI_VP_CATLIST);
	        $xoopsTpl->assign('lang_submitted', _MI_VP_SUBMITTED);
	        $xoopsTpl->assign('lang_on', _MI_VP_ON);
	        $xoopsTpl->assign('lang_at', _MI_VP_AT);
			$xoopsTpl->assign('lang_pleaseselect', _MI_VP_PLEASESELECT);
			$xoopsTpl->assign('lang_novideosfound', _MI_VP_NOVIDEOSFOUND);
	        $xoopsTpl->assign('lang_lraters', _MI_VP_LOCALRATERS);
	        $xoopsTpl->assign('lang_lrating', _MI_VP_LOCALRATING);

			$xoopsTpl->assign('videocss', XOOPS_URL."/modules/videotube/style/videotube.css");
            			
		    if ($vusecats) {
                if ($cid == 0){
				  $pathstring = _MI_VP_MAIN_DISPLAY;
				  $result5 =$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_categories")." WHERE pid=0");
		          $catcount = mysql_num_rows($result5);
				}else{ 
		          $pathstring = "<a href='index.php'>" . _MI_VP_MAIN . "</a>&nbsp;:&nbsp;";
                  $pathstring .= $mytree->getNicePathFromId($cid, "title", "index.php?cid=".$cid);
				  $result5 =$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_categories")." WHERE pid=".$cid);
		          $catcount = mysql_num_rows($result5);
                }
				$xoopsTpl->assign('category_path', $pathstring);
                $xoopsTpl->assign('catcount', $catcount);
				
				$arr = $mytree->getFirstChild($cid, "weight");

                if (is_array($arr) > 0)
                {
	              $scount = 1;
	              foreach($arr as $ele)
	              {
					$sub_arr = array();
			        $sub_arr = $mytree->getFirstChild($ele['cid'], "weight");
			        $space = 0;
			        $chcount = 0;
			        $infercategories = "";
			        $title = $myts->htmlSpecialChars($ele['title']);

			       $chcount = 1;
				   foreach($sub_arr as $sub_ele)
			       {
					 if ($chcount > $videocattypeinferred)
					 {
					   $infercategories .= "...";
					   break;
					 }
					 if ($space > 0) $infercategories .= ", ";
					 $infercategories .= "<a href='" . XOOPS_URL . "/modules/videotube/index.php?cid=" . $sub_ele['cid'] . "'>" . $myts->htmlSpecialChars($sub_ele['title']) . "</a>";
					 $space++;
					 $chcount++;
			       }
			       //$result6 =$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && cid=".$ele['cid']."");
		           
				   $vciddisptotal = vp_getTotalItems($mytree, $ele['cid'], 1);

				   $xoopsTpl->append('subcategories', array('title' => $title,
			       'id' => $ele['cid'], 'infercategories' => $infercategories, 'totallinks' => $vciddisptotal, 'count' => $scount));
			       $scount++;
		         }
               }
				

				$numcategories = 1;
			    $result5 = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_categories")." WHERE refid > 0 ORDER BY weight");
			    $result7 = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub > 0");
			    $vnumvideos = mysql_num_rows($result7);
			    $vcategories = array();
			    $vcategories['cid'] = 0;
			    $vcategories['title'] = "All Videos";
			    $vcategories['numvideos'] = $vnumvideos;
			    $xoopsTpl->append('vcategories', $vcategories);
			    while ($row = $xoopsDB->fetcharray($result5)) {
	              $testcid = $row['cid'];
	              $result6 =$xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1 && cid=$testcid");
		          $vciddisptotal = mysql_num_rows($result6);
		          mysql_free_result($result6);
	              if (($vciddisptotal > 1) || ($vcatsshowall > 0)) {
	                $numcategories++;
	                $vcategories['cid'] = $row['cid'];
	                $vcategories['title'] = $row['title'];
	                $vcategories['numvideos'] = $vciddisptotal;
	                if ($vcatid == $vcategories['cid']) {
	                  $categorytitle = $vcategories['title'];
	                }
			        $xoopsTpl->append('vcategories', $vcategories);
			      }
			    }
		        $xoopsTpl->assign('categorytitle', $categorytitle);
			    $xoopsTpl->assign('numcategories', $numcategories);

				
		          ob_start();
		          $mytree -> makeMySelBoxWithCount('title','weight', $cid, 1, "", 'location.href="?startvid=0&amp;cid="+this.options[this.selectedIndex].value;');
		          $categorylist = new XoopsFormLabel(_MI_VP_CATEGORY_TITLE, ob_get_contents());
		          ob_end_clean();
				  
				$xoopsTpl->assign('categorylist', $categorylist);
				
				
			}
		    			
			// Create a temporary array to store the video info from the db
			
			$video_array = array();
			$toprow=1;
			$rownum=1;
			
			// Read the video information
			
			while ($row = $xoopsDB->fetcharray($result)) {
			  $postnum++;
			  $video_array['toprow']=$toprow;
			  $video_array['postnum']=$postnum;
              $video_array['rownum']=$rownum;
			  $video_array['ref'] = $row['id'];
			
			  $submituid = $row['uid'];
			
			// If the submitter is a registered user then get their user name
			
			if ($submituid) {
			  $result2 = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=$submituid");
			  $row2 = $xoopsDB->fetcharray($result2);
			  
			  $video_array['submitter'] = $row2['uname'];
			} else {
			  $video_array['submitter'] = $xoopsConfig['anonymous'];
			}
			  $video_array['code'] = $row['code'];
			  $video_array['title'] = $row['title'];
			  $video_array['artist'] = $row['artist'];
			  $video_array['views'] = $row['views'];
			  $video_array['service'] = $row['service'];
		      $video_array['embedcode'] = $row['embedcode'];
              $video_array['thumb'] = $row['thumb'];
              
            switch($video_array['service']) {
	          case 10:
	            $video_array['servicename'] = $row['servicename'];
		        break;
	          case 4:
	            $video_array['servicename'] = 'blip.tv';
		        break;
	          case 3:
	            $video_array['servicename'] = 'MetaCafe';
		        break;
	          case 2:
	            $video_array['servicename'] = 'DailyMotion';
		        break;
	          default:
	            $video_array['servicename'] = 'YouTube';
	          }
			  
			  // Extract submission date and convert it into readable format
			  
			  $tempDate = $dateArray=explode(',',strftime("%Y,%m,%d,%I,%M,%p",$row['date']));
			  $video_array['date'] = date('D M j Y', mktime(0, 0, 0, $tempDate[1], $tempDate[2], $tempDate[0]));
			  $video_array['time'] = $tempDate[3].":".$tempDate[4].strtolower($tempDate[5]);
			
			  // Set variable values used as flags to control html table elements
			  // in the template based on how many columns are to be displayed
			  
			  if ($toprow == 1) {
			    $toprow = 0;
			  }
			  if ($postnum == $vdispformat) {
			    $postnum = 0;
			    $rownum++;
			  }
			  if ($vdispformat == 4) {
			    if (($postnum > 0) and ($rownum < 4)) {
			      $postnum = 0;
			      $rownum++;
			    }
			  }
			  if ($vdispformat == 5) {
			    if (($postnum > 1) and ($rownum < 4)) {
			      $postnum = 0;
			      $rownum++;
			    }
			  }
			
			  //$video_array['rownum']=$rownum;
			  
			  // Pass the video thumbnail information to the template
			  
			  $xoopsTpl->append('videos', $video_array);
			}
        }
	
    if ($pvideo['service'] == 10) {
	    
        $unvideotitle = $pvideo['ptitle'];
        $unvideotitle = addslashes($unvideotitle);

        $unvideodesc = $pvideo['pdesc'];
        $unvideodesc = addslashes($unvideodesc);
        $unvideodesc = str_replace(';',' ', $unvideodesc);

	    $xoopsTpl->assign('unvideotitle', $unvideotitle);
	    $xoopsTpl->assign('unvideodesc', $unvideodesc);
	    $xoopsTpl->assign('unlocalviews', $pvideo['pviews']);
	    $xoopsTpl->assign('unfullembedcode', $pvideo['fullembedcode']);
	    $xoopsTpl->assign('unsubmitter', $pvideo['submitter']);
	    $xoopsTpl->assign('unvsubmitdate', $pvideo['date']);
	    $xoopsTpl->assign('unvsubmittime', $pvideo['time']);

    }elseif ($pvideo['service'] == 4) {

      $searchresultxml = 'http://blip.tv/file/'.$pvideo['pcode'].'?skin=rss';
      if(!$xml=simplexml_load_file($searchresultxml)){
        trigger_error('Error reading XML file',E_USER_ERROR);
      }
      
      foreach ($xml->channel->item as $item) {

        $imedia = $item->children('http://search.yahoo.com/mrss/');
        $iblip = $item->children('http://blip.tv/dtd/blip/1.0');
  
        $btvideotitle = $item->title;
        $btvideotitle = addslashes($btvideotitle);
        $btvideoauthor = $iblip->user;
        $btpublished = $item->pubDate;
        if ($pvideo['pdesc']) {
          $btvideodesc = $pvideo['pdesc']; 
        }else{
          $btvideodesc = addslashes(trim($iblip->puredescription));
        }
        $btduration = $iblip->runtime;
        $btvideorating = $iblip->rating;

	    $xoopsTpl->assign('btvideotitle', $btvideotitle);
	    $xoopsTpl->assign('btvideodesc', $btvideodesc);
	    $xoopsTpl->assign('btvideoauthor', $btvideoauthor);
	    $xoopsTpl->assign('btvideorating', $btvideorating);
        $xoopsTpl->assign('btduration', $btduration);
        $xoopsTpl->assign('btpublished', $btpublished);
	    $xoopsTpl->assign('btlocalviews', $pvideo['pviews']);
	    $xoopsTpl->assign('btsubmitter', $pvideo['submitter']);
	    $xoopsTpl->assign('btvsubmitdate', $pvideo['date']);
	    $xoopsTpl->assign('btvsubmittime', $pvideo['time']);
	  }
	
	}elseif ($pvideo['service'] == 3) {

	  $searchresultxml = 'http://www.metacafe.com/api/item/'.$pvideo['pcode'];
      if(!$xml=simplexml_load_file($searchresultxml)){
        trigger_error('Error reading XML file',E_USER_ERROR);
      }
    
      foreach ($xml->channel->item as $item) {

        // get nodes in imedia: namespace for imedia information
        $imedia = $item->children('http://search.yahoo.com/mrss/');
      
        $mcvideotitle = $item->title;
        $mcpublished = $item->pubDate;
        if ($pvideo['pdesc']) {
          $mcvideodesc = $pvideo['pdesc'];
        }else{
          $mcvideodesc = $imedia->description;
        }
        $mcvideoauthor = $item->author;
      
        $mcvideorating= $item->rank;
      
        $contentattrs = $imedia->content[0]->attributes();
        $mcduration = addslashes($contentattrs['duration']);
      
        $description = $item->description;
        $parsedesc = explode('|',$description);
        $parsedesc2 = explode(' ',$parsedesc[1]);
        $mcvideoviews = $parsedesc2[1];

      }
      
      $mcvideotitle = addslashes($mcvideotitle);
      $mcvideodesc = addslashes($mcvideodesc);	
	
	  $xoopsTpl->assign('mcvideotitle', $mcvideotitle);
	  $xoopsTpl->assign('mcvideodesc', $mcvideodesc);
	  $xoopsTpl->assign('mcvideoauthor', $mcvideoauthor);
	  $xoopsTpl->assign('mcvideoviews', $mcvideoviews);
	  $xoopsTpl->assign('mcfavorites', $mcfavorites);
	  $xoopsTpl->assign('mcvideorating', $mcvideorating);
	  $xoopsTpl->assign('mcvideovotes', $mcvideovotes);
	  $xoopsTpl->assign('mcduration', $mcduration);
	  $xoopsTpl->assign('mcpublished', $mcpublished);
	  $xoopsTpl->assign('mclocalviews', $pvideo['pviews']);
	  $xoopsTpl->assign('mcsubmitter', $pvideo['submitter']);
	  $xoopsTpl->assign('mcvsubmitdate', $pvideo['date']);
	  $xoopsTpl->assign('mcvsubmittime', $pvideo['time']);
	
	}elseif ($pvideo['service'] == 2) {

	  $searchresultxml = 'http://www.dailymotion.com/rss/video/'.$pvideo['pcode'];
      if(!$xml=simplexml_load_file($searchresultxml)){
        trigger_error('Error reading XML file',E_USER_ERROR);
      }

      $dmvideo_array = array();
    
      foreach ($xml->channel->item as $item) {

        // get nodes in imedia: namespace for imedia information
        $imedia = $item->children('http://search.yahoo.com/mrss');
        $iitunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
        $idm = $item->children('http://www.dailymotion.com/dmrss');
      
        $dmvideotitle = $item->title;
        $dmpublished = $item->pubDate;
        if ($pvideo['pdesc']) {
          $dmvideodesc = $pvideo['pdesc'];
        }else{
          $dmvideodesc = $iitunes->summary;
        }
        $dmvideoauthor = $iitunes->author;
        $dmvideoviews = $idm->views;
        $dmfavorites= $idm->favorites;
        $dmvideorating= $idm->videorating;
        $dmvideovotes = $idm->videovotes;

        $contentattrs = $imedia->group->content[0]->attributes();
        $dmduration = $contentattrs['duration'];
      }	
	
	  $dmvideotitle = addslashes($dmvideotitle);
      $dmvideodesc = addslashes($dmvideodesc);	

	  $xoopsTpl->assign('dmvideotitle', $dmvideotitle);
	  $xoopsTpl->assign('dmvideodesc', $dmvideodesc);
	  $xoopsTpl->assign('dmvideoauthor', $dmvideoauthor);
	  $xoopsTpl->assign('dmvideoviews', $dmvideoviews);
	  $xoopsTpl->assign('dmfavorites', $dmfavorites);
	  $xoopsTpl->assign('dmvideorating', $dmvideorating);
	  $xoopsTpl->assign('dmvideovotes', $dmvideovotes);
	  $xoopsTpl->assign('dmduration', $dmduration);
	  $xoopsTpl->assign('dmpublished', $dmpublished);
	  $xoopsTpl->assign('dmlocalviews', $pvideo['pviews']);
	  $xoopsTpl->assign('dmsubmitter', $pvideo['submitter']);
	  $xoopsTpl->assign('dmvsubmitdate', $pvideo['date']);
      $xoopsTpl->assign('dmvsubmittime', $pvideo['time']);
	}else{
	  //
	}
	if (!$vid) {
	  $_GET['vid']=$playref;
	}
    
    $xoopsTpl->assign('xoops_pagetitle', $xoopsModule->name() . ' - ' .$pvideo['ptitle']);
	$xoopsTpl->assign('xoops_module_header', '<meta name="title" content="'.$pvideo['ptitle'].'" />
<link rel="image_src" href="http://img.youtube.com/vi/'.$pvideo['pcode'].'/default.jpg" />
<link rel="video_src" href="http://www.youtube.com/v/'.$pvideo['pcode'].'&border=0&rel=1"/>
<meta name="description" content="'.$pvideo['pdesc'].'" />
<meta name="video_height" content="'.$pvideo['pheight'].'" />
<meta name="video_width" content="'.$pvideo['pwidth'].'" />
<meta name="video_type" content="application/x-shockwave-flash" />');

    if (is_object($xoTheme)) {
      $xoTheme->addMeta( 'meta', 'keywords',$pvideo['ptitle']);
      $xoTheme->addMeta( 'meta', 'description',$pvideo['pdesc']);
      //$xoTheme->addMeta( 'meta', 'xoops_pagetitle',$this->_title);
    } else {
      $xoopsTpl->assign('xoops_meta_keywords','keywords',$pvideo['ptitle']);
      $xoopsTpl->assign('xoops_meta_description',$pvideo['pdesc']);
    }
    
    include XOOPS_ROOT_PATH.'/include/comment_view.php';
    include_once XOOPS_ROOT_PATH.'/footer.php';
	break;

}
	
?>
