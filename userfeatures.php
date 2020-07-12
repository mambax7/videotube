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
//  Date:       01/10/2009                                                   //
//  Module:     Video Tube                                                   //
//  File:       userfeatures.php                                             //
//  Version:    1.82                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.0  Initial Release 04/14/2008                                  //
//  ***                                                                      //
//  Version 1.1  04/23/2008                                                  //
//  Bug Fix: Change certain includes to include_once                         //
//  New: Change include paths for 2.2.x compatibility                        //
//  New: Add auto approve registered user submission config parameter        //
//  Note: E-mail contact address change                                      //
//  ***                                                                      //
//  Version 1.2  05/01/2008                                                  //
//  Bug Fix: Removed $HTTP_GET_VARS segemnt due to potential security risks  //
//  ***                                                                      //
//  Version 1.4  05/21/2008                                                  //
//  Bug Fix: Added language include_once for some older versions of XOOPS    //
//  New: Added viewuserlist with displayorder sorting parameter              //
//  ***                                                                      //
//  Version 1.5  05/26/2008                                                  //
//  New: Added op=submit and pass form object to new submission template     //
//  ***                                                                      //
//  Version 1.6  08/01/2008                                                  //
//  Bug Fix: Eliminate submit.html template to correct compatibility issues  //
//  with XOOPS 2.2.x                                                         //
//  New: Add categories                                                      //
//  New: Add total search results and search results pagination              //
//  ***                                                                      //
//  Version 1.61  08/02/2008                                                 //
//  Bug Fix: Correct submission so categories enabled/disabled both work     //
//  New: Add portable video search preview overlay                           //
//  ***                                                                      //
//  Version 1.63  08/23/2008                                                 //
//  New: Add DailyMotion                                                     //
//  ***                                                                      //
//  Version 1.64  09/06/2008                                                 //
//  New: Breakout search/submit functions by service from userfeatures.php   //
//  New: Add manual submission support                                       //
//  ***                                                                      //
//  Version 1.65  09/12/2008                                                 //
//  Bug Fix: Add passing category through sanitizer(potential security risk) //
//  New: Add Manage My Videos allowing users to edit and delete their own    //
//  video submissions                                                        //
//  ***                                                                      //
//  Version 1.7 RC1 09/14/2008                                               //
//  New: Add page menu display                                               //
//  ***                                                                      //
//  Version 1.81  01/06/2009                                                 //
//  Code cleanup and housekeeping                                            //
//  Add language support                                                     //
//  Rewrite category management admin interface                              //
//  ***                                                                      //
//  Version 1.82  01/10/2009                                                 //
//  Bug fix passing category value when performing video edit                //
//  ***                                                                      //

include_once '../../mainfile.php';
include_once XOOPS_ROOT_PATH."/header.php";
include 'include/functions.php';
include 'class/videotubetree.php';

if (file_exists('language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php')) {
  include_once 'language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php';
}else{
  include_once 'language/english/modinfo.php';
}

global $xoopsOption, $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModuleConfig;

$myts =& MyTextSanitizer::getInstance();

$module_handler =& xoops_gethandler('module');
$module         =& $module_handler->getByDirname('videotube');
$config_handler =& xoops_gethandler('config');
$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

$op= NULL;
$code = NULL;
$displayorder = NULL;
$title = NULL;
$artist = NULL;
$description = NULL;
$category = NULL;
$fullembedcode = NULL;
$thumb = NULL;
$servicename = NULL;
$vusecats = NULL;

$vusecats = $moduleConfig['videousecats'];

if (isset($_POST['manualpost'])) $op = 'manualpost';
if (isset($_POST['postmyedits'])) $op = 'postmyedits';

if(isset($_GET['id'])) $id = $_GET['id'];
if(isset($_GET['op'])) $op = $_GET['op'];
if(isset($_POST['op'])) $op = $_POST['op'];

if(isset($_GET['displayorder'])) $displayorder = intval($_GET['displayorder']);

if ($xoopsUser) {
  $uid = $xoopsUser->getVar('uid');
} else {
  $uid = 0;
}

switch($op) {

  case "viewuserlist":
	
	// Determine the total number of published videos in db table
	
	$result = mysql_query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub=1");
	$vdisptotal = mysql_num_rows($result);
	mysql_free_result($result);

	if ($vdisptotal < 0) {
      //
	} else {
		  
		// Designate Smarty template to be used
		
		$xoopsOption['template_main'] = 'userindexlist.html';
		include (XOOPS_ROOT_PATH."/header.php");
		
        $vpagemenuenable = $xoopsModuleConfig['pagemenuenable'];
	    include XOOPS_ROOT_PATH.'/modules/videotube/class/videotubedisplay.php';
	    $videodisplay = new VideoTubeDisp(PHP_VERSION,$xoopsModuleConfig['dailymotionenable'],
	                                      $xoopsModuleConfig['metacafeenable'],$xoopsModuleConfig['bliptvenable'],
	                                      $xoopsModuleConfig['manualsubmitenable'],$xoopsModuleConfig['managevideosenable'],
	                                      _MI_VP_PMNAME1,_MI_VP_PMNAME2,_MI_VP_PMNAME3,_MI_VP_PMNAME4,
	                                      _MI_VP_PMNAME5,_MI_VP_PMNAME6,_MI_VP_PMNAME7,_MI_VP_PMNAME8);
        $xoopsTpl->assign('video_pagemenu', $videodisplay->renderPageMenu());
        $xoopsTpl->assign('vpagemenuenable', $vpagemenuenable);

		$xoopsTpl->assign('listtitle', _MI_VP_LISTPAGETITLE);
	    $xoopsTpl->assign('listinstructions', _MI_VP_LISTINSTRUCTIONS);
	    $xoopsTpl->assign('column1label', _MI_VP_COLUMN1LABEL);
	    $xoopsTpl->assign('column2label', _MI_VP_COLUMN2LABEL);
	    $xoopsTpl->assign('column3label', _MI_VP_COLUMN3LABEL);
	    $xoopsTpl->assign('column4label', _MI_VP_COLUMN4LABEL);
	    
 		$result = mysql_query("SELECT id, uid, date, views FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub = 1 && uid > 0 ORDER BY uid");
	    $userlist_total = 0;
	    $userlisting = array();
	    $userlistarray = array();
	    
	    while ($row = mysql_fetch_array($result)) {
		   if ($userlist_total) {
		      if ($row['uid'] == $current_uid) {
		         $uservideo_total++;
		         $tempviews = $row['views'];
		         $userviews_total += $tempviews;
		         if ($row['date'] > $current_date) {
		            $current_date = $row['date'];
		         }
		      } else {
		         $userlistarray['uid'] = $current_uid;
		         $tempDate = $dateArray=explode(',',strftime("%Y,%m,%d,%I,%M,%p",$current_date));
		         $datestring = date('D M j Y', mktime(0, 0, 0, $tempDate[1], $tempDate[2], $tempDate[0]));
		         $timestring = $tempDate[3].":".$tempDate[4].strtolower($tempDate[5]);
		         $userlistarray['lastupdate'] = $datestring."  ".$timestring;
                 $userlistarray['videos'] = $uservideo_total;
                 $userlistarray['views'] = $userviews_total;
                 $result2 = mysql_query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=$current_uid");
		         $row2 = mysql_fetch_array($result2);
		         $userlistarray['name'] = $row2['uname'];
		         if ($row2['user_avatar']=='blank.gif' || $row2['user_avatar']==''){
		            $userlistarray['avatar'] = '';
		            $userlistarray['showavatar'] = 0;
		         } else {
		            $userlistarray['avatar'] = $row2['user_avatar'];
		            $userlistarray['showavatar'] = 1;
		         }
		         $userlisting[] = $userlistarray; 
		         
		         $userlist_total++;
		         $uservideo_total = 1;
		         $userviews_total = $row['views'];
		         $current_uid = $row['uid'];
		         $current_date = $row['date'];
		       }  
		   } else {
		      $userlist_total = 1;
		      $uservideo_total = 1;
		      $userviews_total = $row['views'];
		      $current_uid = $row['uid'];
		      $current_date = $row['date'];
		   }
		}

		$userlistarray['uid'] = $current_uid;
		$tempDate = $dateArray=explode(',',strftime("%Y,%m,%d,%I,%M,%p",$current_date));
		$datestring = date('D M j Y', mktime(0, 0, 0, $tempDate[1], $tempDate[2], $tempDate[0]));
		$timestring = $tempDate[3].":".$tempDate[4].strtolower($tempDate[5]);
		$userlistarray['lastupdate'] = $datestring."  ".$timestring;
        $userlistarray['videos'] = $uservideo_total;
        $userlistarray['views'] = $userviews_total;
        $result2 = mysql_query("SELECT * FROM ".$xoopsDB->prefix("users")." WHERE uid=$current_uid");
		$row2 = mysql_fetch_array($result2);
		$userlistarray['name'] = $row2['uname'];
         if ($row2['user_avatar']=='blank.gif' || $row2['user_avatar']==''){
            $userlistarray['avatar'] = '';
            $userlistarray['showavatar'] = 0;
         } else {
            $userlistarray['avatar'] = $row2['user_avatar'];
            $userlistarray['showavatar'] = 1;
         }
		$userlisting[] = $userlistarray; 
	}

    $sort_array = array();
	
	switch ($displayorder):
		case 0:
		   foreach ($userlisting as $key => $row) {
		      $sort_array[$key] = $row['lastupdate'];
		   }
		   array_multisort($sort_array, SORT_DESC, $userlisting);
		   break;
		case 1:
		   foreach ($userlisting as $key => $row) {
		      $sort_array[$key] = $row['name'];
		   }
		   array_multisort($sort_array, SORT_ASC, $userlisting);
		   break;
		case 2:
		   foreach ($userlisting as $key => $row) {
		      $sort_array[$key] = $row['name'];
		   }
		   array_multisort($sort_array, SORT_DESC, $userlisting);
		   break;
		case 3:
		   foreach ($userlisting as $key => $row) {
		      $sort_array[$key] = $row['videos'];
		   }
		   array_multisort($sort_array, SORT_ASC, $userlisting);
		   break;
		case 4:
		   foreach ($userlisting as $key => $row) {
		      $sort_array[$key] = $row['videos'];
		   }
		   array_multisort($sort_array, SORT_DESC, $userlisting);
		   break;
		case 5:
		   foreach ($userlisting as $key => $row) {
		      $sort_array[$key] = $row['views'];
		   }
		   array_multisort($sort_array, SORT_ASC, $userlisting);
		   break;
		case 6:
		   foreach ($userlisting as $key => $row) {
		      $sort_array[$key] = $row['views'];
		   }
		   array_multisort($sort_array, SORT_DESC, $userlisting);
		   break;
		case 7:
		   foreach ($userlisting as $key => $row) {
		      $sort_array[$key] = $row['lastupdate'];
		   }
		   array_multisort($sort_array, SORT_ASC, $userlisting);
		   break;
		default:
		   foreach ($userlisting as $key => $row) {
		      $sort_array[$key] = $row['lastupdate'];
		   }
		   array_multisort($sort_array, SORT_DESC, $userlisting);
		   break;
	endswitch;
	
	foreach ($userlisting as $userlistarray) {
		$xoopsTpl->append('userlist', $userlistarray);
	}
	
    include(XOOPS_ROOT_PATH."/footer.php");

	break;

  case "manualsubmit":

	if (($xoopsModuleConfig['anonsubmitvideo'] == 0) && ($uid == 0)) {
	  redirect_header("index.php", 0, _NOPERM);
	  exit();
	}

	include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	
	$svform = new XoopsThemeForm(_MI_VP_SUBMITHEADER, "submitvideoform", xoops_getenv('PHP_SELF'));
	$svform->addElement(new XoopsFormText(_MI_VP_TITLE, 'title', 50, 4095, $title), true);
	$svform->addElement(new XoopsFormText(_MI_VP_ARTIST, 'artist', 50, 4095, $artist), false);
    $svform->addElement(new XoopsFormTextArea(_MI_VP_FULLEMBEDCODE, 'fullembedcode', $fullembedcode, 5, 50), true);
    $svform->addElement(new XoopsFormTextArea(_MI_VP_DESC, 'description', $description, 4, 50), false);
    $svform->addElement(new XoopsFormText(_MI_VP_THUMB, 'thumb', 50, 4095, $thumb), true);
    $svform->addElement(new XoopsFormText(_MI_VP_SERVICENAME, 'servicename', 30, 30, $servicename), false);
    $svform->addElement(new XoopsFormHidden('service', '10'));

	if ($xoopsModuleConfig['videousecats']) {
	  $mytree = new VideoTubeTree($xoopsDB->prefix("vp_categories"), "cid", "pid");
		ob_start();
		$mytree -> makeMySelBox('title','weight',0, 2, 'cid', "");
		$svform -> addElement(new XoopsFormLabel(_MI_VP_CATEGORY, ob_get_contents()));
		ob_end_clean();
      $svform->addElement($category_select);
	}
    
	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'manualpost', _MI_VP_SUBMIT, 'submit'));
	$button_tray->addElement(new XoopsFormButton('', 'manualsubmit', _MI_VP_RESET, 'reset'));
	$svform->addElement($button_tray);

  include XOOPS_ROOT_PATH.'/modules/videotube/class/videotubedisplay.php';
  $videodisplay = new VideoTubeDisp(PHP_VERSION,$xoopsModuleConfig['dailymotionenable'],
                                    $xoopsModuleConfig['metacafeenable'],$xoopsModuleConfig['bliptvenable'],
                                    $xoopsModuleConfig['manualsubmitenable'],$xoopsModuleConfig['managevideosenable'],
                                    _MI_VP_PMNAME1,_MI_VP_PMNAME2,_MI_VP_PMNAME3,_MI_VP_PMNAME4,
                                    _MI_VP_PMNAME5,_MI_VP_PMNAME6,_MI_VP_PMNAME7,_MI_VP_PMNAME8);
  if ($xoopsModuleConfig['pagemenuenable']) {
    echo $videodisplay->renderPageMenu();
  }

    echo '<div align="center">';
    echo '<table width="100%">';
    echo '<tr>';
    echo '<td class="odd" align="center" valign="top">';
    $svform->display();
    echo '</td>';
    echo '</tr>';
    echo '</table>';
    echo '</div>';
    
	break;

  case "manualpost":
	
    if (($xoopsModuleConfig['autoapprovesubmitvideo'] == 1) && ($uid > 0)) {
	  $pub = 1;
	} else {
	  $pub = 0;
	}
	
    $now = time();

    $form_title = $myts->addSlashes($_POST['title']);
    $form_artist = $myts->addSlashes($_POST['artist']);
    $form_fullembedcode = $myts->addSlashes($_POST['fullembedcode']);
    $form_description = $myts->addSlashes($_POST['description']);
    $form_thumb = $myts->addSlashes($_POST['thumb']);
    $form_service = $myts->addSlashes($_POST['service']);
    $form_servicename = $myts->addSlashes($_POST['servicename']);
	
	if ($xoopsModuleConfig['videousecats']) {
	  $selectedcategory = $myts->addSlashes($_POST['cid']);
	} else {
	  $selectedcategory = 0;
	}
	
    $sqlcommandline = "INSERT INTO ".$xoopsDB->prefix("vp_videos") . "(pub, uid, cid, date, title, artist, fullembedcode, description, thumb, service, servicename) VALUES ($pub,$uid,$selectedcategory,$now,'$form_title','$form_artist','$form_fullembedcode','$form_description','$form_thumb','$form_service','$form_servicename')";
    $done = $xoopsDB->queryF($sqlcommandline);

	if($done) {
	  if (($xoopsModuleConfig['autoapprovesubmitvideo'] == 1) && ($uid > 0)) {
	    redirect_header("userfeatures.php?op=manualsubmit",2,_MI_VP_AUTOSUBMITSUCCESS);
	  } else {
	    redirect_header("userfeatures.php?op=manualsubmit",2,_MI_VP_SUBMITSUCCESS);
	  }
	} else {
	  redirect_header("index.php",2,_MI_VP_SUBMITERROR);
	}
    break;

  case "listmyvideos":
  
	if ($xoopsUser){
		$uid = $xoopsUser->uid();
	}else{
      redirect_header(XOOPS_URL."/",3,_NOPERM);
      exit();
    }
    
    if (!$xoopsModuleConfig['managevideosenable']) {
      redirect_header(XOOPS_URL."/",3,_NOPERM);
      exit();
    }

  include XOOPS_ROOT_PATH.'/modules/videotube/class/videotubedisplay.php';
  $videodisplay = new VideoTubeDisp(PHP_VERSION,$xoopsModuleConfig['dailymotionenable'],
                                    $xoopsModuleConfig['metacafeenable'],$xoopsModuleConfig['bliptvenable'],
                                    $xoopsModuleConfig['manualsubmitenable'],$xoopsModuleConfig['managevideosenable'],
                                    _MI_VP_PMNAME1,_MI_VP_PMNAME2,_MI_VP_PMNAME3,_MI_VP_PMNAME4,
                                    _MI_VP_PMNAME5,_MI_VP_PMNAME6,_MI_VP_PMNAME7,_MI_VP_PMNAME8);
  if ($xoopsModuleConfig['pagemenuenable']) {
    echo $videodisplay->renderPageMenu();
  }

    echo "<table border=0 cellpadding=2 cellspacing=2 width='100%'><tr><td colspan=10><div align='center'>
    <b>"._MI_VP_MANAGE_HEADER."</b></div></td></tr>
    <tr class=even>
      <td><i>"._MI_VP_ID_CHEADING."</i></td>
      <td><i>"._MI_VP_CODE_CHEADING."</i></td>
      <td><i>"._MI_VP_TITLE_CHEADING."</i></td>";
      if ($vusecats) {
        echo "<td><i>"._MI_VP_CATEGORY_CHEADING."</i></td>";
      }
      echo "<td><i>"._MI_VP_EDIT_CHEADING."</i></td>
      <td><i>"._MI_VP_DELETE_CHEADING."</i></td>
    </tr>";

    $result = $xoopsDB->queryF("SELECT id, uid, cid, code, title, artist, service FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub='1' && uid ='".$uid."' ORDER BY id");
    $numsubmissions = mysql_num_rows($result);
     
    if ($numsubmissions) {
	    while($vp_video = $xoopsDB->fetcharray($result)) {
	      if ($vp_video['cid'] > 0) { 
		    $result2 = $xoopsDB->queryF("SELECT title FROM ".$xoopsDB->prefix("vp_categories")." WHERE cid =".$vp_video['cid']."");
		    $vp_category = $xoopsDB->fetcharray($result2);
			$cattitle = $vp_category['title'];
		  }else{
		    $cattitle = 'No Category';
		  }
		  echo "<tr class=odd><td>".$vp_video['id']."</td>
	      <td>".($vp_video['code'])."</td>
	      <td>".($vp_video['title'])."</td>
	      <td>".$cattitle."</td>";
          echo "<td><a href='./userfeatures.php?op=editmyvideos&id=".$vp_video['id']."'>"._MI_VP_EDIT."</a></td>";
	      echo "<td><a href='./userfeatures.php?op=mydelconfirm&id=".$vp_video['id']."'>"._MI_VP_DELETE."</a></td></tr>";
	    }
    } else {
    echo "<tr class=odd><td colspan=".$numcolumns." align=center><td colspan=6 align=center>No videos submitted by you can be found at this time</td>";
    }
	    
    echo "</table>";
    
    break;

  case "editmyvideos":

	if ($xoopsUser){
		$uid = $xoopsUser->uid();
	}else{
      redirect_header(XOOPS_URL."/",3,_NOPERM);
      exit();
    }

    if (!$xoopsModuleConfig['managevideosenable']) {
      redirect_header(XOOPS_URL."/",3,_NOPERM);
      exit();
    }

    $result = $xoopsDB->queryF("SELECT id, code, title, artist, cid, embedcode, fullembedcode, description, thumb, service, servicename FROM ".$xoopsDB->prefix("vp_videos")." WHERE id='".$id."' && uid='".$uid."'");
    $vp_video = $xoopsDB->fetcharray($result);

    $id = $vp_video['id'];
    $code = htmlspecialchars($vp_video['code'], ENT_QUOTES);
    $title = htmlspecialchars($vp_video['title'], ENT_QUOTES);
    $artist = htmlspecialchars($vp_video['artist'], ENT_QUOTES);
    $currentcid = $vp_video['cid'];
    $embedcode = htmlspecialchars($vp_video['embedcode'], ENT_QUOTES);
    $fullembedcode = htmlspecialchars($vp_video['fullembedcode'], ENT_QUOTES);
    $description = htmlspecialchars($vp_video['description'], ENT_QUOTES);
    $thumb = htmlspecialchars($vp_video['thumb'], ENT_QUOTES);
    $service = $vp_video['service'];
    $servicename = htmlspecialchars($vp_video['servicename'], ENT_QUOTES);
            
	include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	$evform = new XoopsThemeForm(_MI_VP_EDITHEADER, "editvideoform", xoops_getenv('PHP_SELF'));
	$evform->addElement(new XoopsFormHidden('id', $id));
	$evform->addElement(new XoopsFormHidden('service', $service));
	if ($service < 2) {
	  $evform->addElement(new XoopsFormText(_MI_VP_CODE, 'code', 20, 20, $code), true);
	  $evform->addElement(new XoopsFormText(_MI_VP_TITLE, 'title', 50, 4095, $title), true);
	  $evform->addElement(new XoopsFormText(_MI_VP_ARTIST, 'artist', 50, 4095, $artist), false);
	  $evform->addElement(new XoopsFormTextArea(_MI_VP_DESC, 'description', $description, 4, 50), false);
	}elseif ($service > 1 && $service < 10) {
	  $evform->addElement(new XoopsFormText(_MI_VP_CODE, 'code', 20, 20, $code), true);
	  $evform->addElement(new XoopsFormText(_MI_VP_TITLE, 'title', 50, 4095, $title), true);
	  $evform->addElement(new XoopsFormText(_MI_VP_ARTIST, 'artist', 50, 4095, $artist), false);
	  $evform->addElement(new XoopsFormTextArea(_MI_VP_DESC, 'description', $description, 4, 50), false);
	  $evform->addElement(new XoopsFormText(_MI_VP_EMBEDCODE, 'embedcode', 50, 4095, $embedcode), true);
	  $evform->addElement(new XoopsFormText(_MI_VP_THUMB, 'thumb', 50, 4095, $thumb), false);
	}else{
	  $evform->addElement(new XoopsFormText(_MI_VP_TITLE, 'title', 50, 4095, $title), true);
	  $evform->addElement(new XoopsFormText(_MI_VP_ARTIST, 'artist', 50, 4095, $artist), false);
	  $evform->addElement(new XoopsFormTextArea(_MI_VP_FULLEMBEDCODE, 'fullembedcode', $fullembedcode, 5, 50), true);
	  $evform->addElement(new XoopsFormTextArea(_MI_VP_DESC, 'description', $description, 4, 50), false);
	  $evform->addElement(new XoopsFormText(_MI_VP_THUMB, 'thumb', 50, 4095, $thumb), false);
	  $evform->addElement(new XoopsFormText(_MI_VP_SERVICENAME, 'servicename', 30, 30, $servicename), false);
    }	

	if ($vusecats) {
	  $mytree = new VideoTubeTree($xoopsDB->prefix("vp_categories"), "cid", "pid");
		ob_start();
		$mytree -> makeMySelBox('title','weight',$currentcid, 2, 'cid', "");
		$evform -> addElement(new XoopsFormLabel(_MI_VP_CATEGORY, ob_get_contents()));
		ob_end_clean();
      $evform->addElement($category_select);
	}

	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'postmyedits', _MI_VP_EDITSUBMITADD, 'submit'));
		
	$button_cancel = new XoopsFormButton('', '', _MI_VP_EDITSUBMITCANCEL, 'button');
	$button_cancel->setExtra('onclick="history.go(-1)"');
	$button_tray->addElement($button_cancel);
	
	$evform->addElement($button_tray);
	$evform->display();	
 
  break;

  case "postmyedits":
    
	if ($xoopsUser){
		$uid = $xoopsUser->uid();
	}else{
      redirect_header(XOOPS_URL."/",3,_NOPERM);
      exit();
    }

    if (!$xoopsModuleConfig['managevideosenable']) {
      redirect_header(XOOPS_URL."/",3,_NOPERM);
      exit();
    }

    $id = $myts->addSlashes($_POST['id']);
    $service = $myts->addSlashes($_POST['service']);
    $form_code = $myts->addSlashes($_POST['code']);
    $form_title = $myts->addSlashes($_POST['title']);
    $form_artist = $myts->addSlashes($_POST['artist']);
    $selectedcategory = $myts->addSlashes($_POST['cid']);
    $form_embedcode = $myts->addSlashes($_POST['embedcode']);
    $form_fullembedcode = $myts->addSlashes($_POST['fullembedcode']);
    $form_description = $myts->addSlashes($_POST['description']);
    $form_thumb = $myts->addSlashes($_POST['thumb']);
    $form_servicename = $myts->addSlashes($_POST['servicename']);
    
	$sqlcommandline = "UPDATE ".$xoopsDB->prefix("vp_videos")." SET code='".$form_code."', title='".$form_title."', artist='".$form_artist."', cid='".$selectedcategory."', embedcode='".$form_embedcode."', fullembedcode='".$form_fullembedcode."', description='".$form_description."', thumb='".$form_thumb."', servicename='".$form_servicename."' WHERE id='".$id."'";
	$done = $xoopsDB->queryF($sqlcommandline);
	
	if($done) {
	  redirect_header("userfeatures.php?op=listmyvideos",2,_MI_VP_EDITSUCCESS);
	} else {
	  redirect_header("index.php",2,_MI_VP_EDITERROR);
	}
    break;

  case "mydel":

	if ($xoopsUser){
		$uid = $xoopsUser->uid();
	}else{
      redirect_header(XOOPS_URL."/",3,_NOPERM);
      exit();
    }

    if (!$xoopsModuleConfig['managevideosenable']) {
      redirect_header(XOOPS_URL."/",3,_NOPERM);
      exit();
    }

    $done = $xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("vp_videos")." WHERE id=".$id."");
	xoops_comment_delete($xoopsModule->getVar('mid'), $id);
	
	if($done) {
	  redirect_header("userfeatures.php?op=listmyvideos",2,_MI_VP_DELSUCCESS);
	} else {
	  redirect_header("index.php",2,_MI_VP_DELERROR);
	}
    break;

  case "mydelconfirm":

	if ($xoopsUser){
		$uid = $xoopsUser->uid();
	}else{
      redirect_header(XOOPS_URL."/",3,_NOPERM);
      exit();
    }

    if (!$xoopsModuleConfig['managevideosenable']) {
      redirect_header(XOOPS_URL."/",3,_NOPERM);
      exit();
    }

    $result = $xoopsDB->queryF("SELECT id, code, title, artist FROM ".$xoopsDB->prefix("vp_videos")." WHERE id='".$id."'",1);
    $vp_video = $xoopsDB->fetcharray($result);

    echo "<center><b>"._MI_VP_DEL_REALLY."</b></center><br><br>";
    echo "<table col=2 width=300px align=center>";
    echo "<tr><td align=left width=100px><b>"._MI_VP_ID.": </b></td><td align=left width=200px>".$vp_video['id']."</td></tr>";
    echo "<tr><td align=left width=100px><b>"._MI_VP_CODE.":</b> </td><td align=left width=200px>".$vp_video['code']."</td></tr>";
    echo "<tr><td align=left width=100px><b>"._MI_VP_TITLE.":</b> </td><td align=left width=200px>".$vp_video['title']."</td></tr>";
    echo "<tr><td align=left width=100px><b>"._MI_VP_ARTIST.":</b> </td><td align=left width=200px>".$vp_video['artist']."</td></tr>";
    if ($vusecats) {
      $categoryid = $vp_video['cid'];
      if ($categoryid) {
        $result3 = $xoopsDB->queryF("SELECT title FROM ".$xoopsDB->prefix("vp_categories")." WHERE cid=$categoryid");
        $vp_category = $xoopsDB->fetcharray($result3);
        $categorytitle = $vp_category['title'];
      }else{
        $categorytitle = "No Category";
      }
      echo "<tr><td align=left><b>"._MI_VP_CATEGORY.":</b> </td><td align=left>".$categorytitle."</td></tr>";
    }
    echo "</table>";
    echo "<br><br><center><a href='./userfeatures.php?op=mydel&id=".$id."'><b>"._MI_VP_DELETE."</b></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='./userfeatures.php?op=listmyvideos'><b>"._MI_VP_CANCEL."</b></a>";

    break;


}

include(XOOPS_ROOT_PATH."/footer.php");

?>
