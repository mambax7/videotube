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
//  Date:       03/01/2009                                                   //
//  Module:     Video Tube                                                   //
//  File:       admin/index.php                                              //
//  Version:    1.85                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.0  Initial Release 04/14/2008                                  //
//  ***                                                                      //
//  Version 1.1  04/23/2008                                                  //
//  Bug Fix: Change POST variable assignment for posting an edit to db       //
//  New: Changed syntax of all MySQL queries for 2.2.x compatibility         //
//  ***                                                                      //
//  Version 1.2  05/01/2008                                                  //
//  Bug Fix: Removed $HTTP_GET_VARS segemnt due to potential security risks  //
//  NOTE: Although it really doesn't apply here in the admin portion of      //
//        the module we removed it to remain consistent.                     //
//  ***                                                                      //
//  Version 1.4  05/21/2008                                                  //
//  New: Add submitter column to Video Submissions and Published Videos      //
//  ***                                                                      //
//  Version 1.6  08/01/2008                                                  //
//  Bug Fix: Add $_GET['id'] so publishing (approving) videos works          //
//  New: Add categories                                                      //
//  ***                                                                      //
//  Version 1.63  08/23/2008                                                 //
//  New: Add Daily Motion support                                            //
//  ***                                                                      //
//  Version 1.64  09/06/2008                                                 //
//  New: Add MetaCafe support                                                //
//  New: Add blip.tv support                                                 //
//  New: Add Manual Submission support                                       //
//  ***                                                                      //
//  Version 1.65  09/12/2008                                                 //
//  Bug Fix: Service number comparison correction required to allow edits    //
//           to Daily Motion videos to function properly                     //
//  Bug Fix: View submission not working for metacafe or blip.tv             //
//  New: Add 'Approve All' button on video submissions page                  //
//  ***                                                                      //
//  Version 1.81  01/06/2009                                                 //
//  Code cleanup and housekeeping                                            //
//  Rewrite category management admin interface                              //
//  ***                                                                      //
//  Version 1.82  01/10/2009                                                 //
//  Bug fix passing category value when performing video edit                //
//  ***                                                                      //
//  Version 1.84  01/18/2009                                                 //
//  Add Report Video feature                                                 //
//  ***                                                                      //
//  Version 1.85  03/01/2009                                                 //
//  Add Rate Video feature                                                   //
//  ***                                                                      //

include_once("admin_header.php");
include_once("../include/functions.php");

global $xoopsOption, $xoopsDB, $xoopsUser, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;

$module_handler =& xoops_gethandler('module');
$module         =& $module_handler->getByDirname('videotube');
$config_handler =& xoops_gethandler('config');
$moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

$op = NULL;
$title = NULL;

$vusecats = $moduleConfig['videousecats'];
if ($vusecats) {
  $numcolumns = 9;  
}else{  
  $numcolumns = 8;
}

if (isset($_POST['postedit'])) $op = 'postedit';
if (isset($_POST['catpostedit'])) $op = 'catpostedit';
if (isset($_POST['catpost'])) $op = 'catpost';
if (isset($_POST['approveall'])) $op = 'approveall';
if (isset($_POST['catmod'])) $op = 'catmod';
if (isset($_POST['catmodpost'])) $op = 'catmodpost';
if (isset($_POST['catmove'])) $op = 'catmove';
if (isset($_POST['catmovepost'])) $op = 'catmovepost';
if (isset($_POST['catdel'])) $op = 'catdel';
if (isset($_POST['catdelconfirm'])) $op = 'catdelconfirm';
if (isset($_POST['delreport'])) $op = 'delreport';
if (isset($_POST['delreportconfirm'])) $op = 'delreportconfirm';
if (isset($_POST['delreportvideo'])) $op = 'delreportvideo';
if (isset($_POST['delreportvideoconfirm'])) $op = 'delreportvideoconfirm';

if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_GET['service'])) $id = $_GET['service'];
if (isset($_GET['refid'])) $refid = $_GET['refid'];
if (isset($_GET['ord'])) $id = $_GET['ord'];
if (isset($_GET['dir'])) $id = $_GET['dir'];
if (isset($_GET['repid'])) $repid = $_GET['repid'];
if (isset($_GET['repid'])) $vid = $_GET['vid'];

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

xoops_cp_header();
$myts =& MyTextSanitizer::getInstance();

switch($op) {
  case "submission":
    OpenTable();
    echo "<table border=0 cellpadding=2 cellspacing=2 width='100%'><tr><td colspan=10><div align='center'>";
    echo "<b>"._AD_VP_SUBMISSION_HEADER."</b></div></td></tr>";
    echo "<tr class=even>";
    echo "<td><i>"._AD_VP_ID_CHEADING."</i></td>";
    echo "<td><i>"._AD_VP_CODE_CHEADING."</i></td>";
    echo "<td><i>"._AD_VP_TITLE_CHEADING."</i></td>";      
    echo "<td><i>"._AD_VP_ARTIST_CHEADING."</i></td>";
    if ($vusecats) {
      echo "<td><i>"._AD_VP_CATEGORY_CHEADING."</i></td>";
    }
    echo "<td><i>"._AD_VP_SUBMITTER_CHEADING."</i></td>";
    echo "<td><i>"._AD_VP_VIEW_CHEADING."</i></td>";
    echo "<td><i>"._AD_VP_APPROVE_CHEADING."</i></td>";
    echo "<td><i>"._AD_VP_DENY_CHEADING."</i></td>";
    echo "</tr>";

    $result = $xoopsDB->queryF("SELECT id, uid, cid, code, title, artist, service FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub='0' ORDER BY id");
    $numsubmissions = mysql_num_rows($result);
     
    if ($numsubmissions) {
    	while($vp_video = $xoopsDB->fetcharray($result)) {
	      $suid = $vp_video['uid'];
	      if ($suid){
	         $result2 = $xoopsDB->queryF("SELECT uname FROM ".$xoopsDB->prefix("users")." WHERE uid=$suid");
             $vp_submitter = $xoopsDB->fetcharray($result2);
             $sname = $vp_submitter['uname'];
          } else {
             $sname = $xoopsConfig['anonymous'];
          }
      		echo "<tr class=odd><td>".$vp_video['id']."</td>
      		<td>".($vp_video['code'])."</td>
      		<td>".($vp_video['title'])."</td>
	  		<td>".$vp_video['artist']."</td>";
            if ($vusecats) {
              $categoryid = $vp_video['cid'];
              if ($categoryid) {
                $result3 = $xoopsDB->queryF("SELECT title FROM ".$xoopsDB->prefix("vp_categories")." WHERE cid=$categoryid");
                $vp_category = $xoopsDB->fetcharray($result3);
                $categorytitle = $vp_category['title'];
              }else{
                $categorytitle = "No Category";
              }
              echo "<td>".$categorytitle."</td>";
            }
	  		echo "<td>".$sname."</td>";
      		if ($vp_video['service'] == 10) {
      		  echo "<td>&nbsp;</td>";
      		}elseif ($vp_video['service'] == 4) {
      		  echo "<td><a href='http://www.blip.tv/file/".$vp_video['code']."' target='_blank'>"._AD_VP_VIEW."</a></td>";
      		}elseif ($vp_video['service'] == 3) {
      		  echo "<td><a href='http://www.metacafe.com/watch/".$vp_video['code']."/' target='_blank'>"._AD_VP_VIEW."</a></td>";
      		}elseif ($vp_video['service'] == 2) {
      		  echo "<td><a href='http://www.dailymotion.com/video/".$vp_video['code']."' target='_blank'>"._AD_VP_VIEW."</a></td>";
      		}else{
      		  echo "<td><a href='http://www.youtube.com/watch?v=".$vp_video['code']."' target='_blank'>"._AD_VP_VIEW."</a></td>";
            }
     		echo "<td><a href='./index.php?op=publish&id=".$vp_video['id']."'>"._AD_VP_APPROVE."</a></td>";
      		echo "<td><a href='./index.php?op=denyconfirm&id=".$vp_video['id']."'>"._AD_VP_DENY."</a></td></tr>";
    	}
    	echo '<tr><td colspan=".$numcolumns." align="center">';
        echo '<FORM>';
        echo '<INPUT style="font-size:80%" TYPE="BUTTON" VALUE="Approve All" ONCLICK="window.location.href=\'index.php?op=approveall';
        echo '\'"></form>';    

    } else {
    echo "<tr class=odd><td colspan=".$numcolumns." align=center>There are no submissions to review at this time</td></tr>";
    }
    echo "</table>";
	
	CloseTable();

    break;
    
  case "approveall":
  
    $result = $xoopsDB->queryF("SELECT id, uid, cid, code, title, artist, service FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub='0' ORDER BY id");
    $numsubmissions = mysql_num_rows($result);
    $failflag = 0; 
    if ($numsubmissions) {
      while($vp_video = $xoopsDB->fetcharray($result)) {
	    $id = $vp_video['id'];
	    $sqlcommandline = "UPDATE ".$xoopsDB->prefix("vp_videos")." SET pub=1 WHERE id='".$id."'";
	    $done = $xoopsDB->queryF($sqlcommandline);
	    if (!$done) {
	      $failflag = 1;
	    }
	  }
	}
	if(!$failflag) {
	  redirect_header("index.php?op=submission",2,_AD_VP_APPROVEALLSUCCESS);
	} else {
	  redirect_header("index.php",2,_AD_VP_APPROVEALLERROR);
	}
    break;
   
  case "publish":
    $sqlcommandline = "UPDATE ".$xoopsDB->prefix("vp_videos")." SET pub=1 WHERE id='".$id."'";
	$done = $xoopsDB->queryF($sqlcommandline);
	
	if($done) {
	  redirect_header("index.php?op=submission",2,_AD_VP_PUBLISHSUCCESS);
	} else {
	  redirect_header("index.php",2,_AD_VP_PUBLISHERROR);
	}
    break;
    
  case "deny":

    $done = $xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("vp_videos")." WHERE id=".$id."");
	
	if($done) {
	  redirect_header("index.php?op=submission",2,_AD_VP_DENYSUCCESS);
	} else {
	  redirect_header("index.php",2,_AD_VP_DENYERROR);
	}
    break;

  case "denyconfirm":

    OpenTable();
    $result = $xoopsDB->queryF("SELECT id, cid, code, title, artist FROM ".$xoopsDB->prefix("vp_videos")." WHERE id='".$id."'",1);
    $vp_video = $xoopsDB->fetcharray($result);

    echo "<center><b>"._AD_VP_DENY_REALLY."</b></center><br><br>";
    echo "<table col=2 width=200px align=center>";
    echo "<tr><td align=left><b>"._AD_VP_ID.": </b></td><td align=left>".$vp_video['id']."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_CODE.":</b> </td><td align=left>".$vp_video['code']."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_TITLE.":</b> </td><td align=left>".$vp_video['title']."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_ARTIST.":</b> </td><td align=left>".$vp_video['artist']."</td></tr>";
    if ($vusecats) {
      $categoryid = $vp_video['cid'];
      if ($categoryid) {
        $result3 = $xoopsDB->queryF("SELECT title FROM ".$xoopsDB->prefix("vp_categories")." WHERE cid=$categoryid");
        $vp_category = $xoopsDB->fetcharray($result3);
        $categorytitle = $vp_category['title'];
      }else{
        $categorytitle = "No Category";
      }
      echo "<tr><td align=left><b>"._AD_VP_CATEGORY.":</b> </td><td align=left>".$categorytitle."</td></tr>";
    }
    echo "</table>";
    echo "<br><br><center><a href='./index.php?op=deny&id=".$id."'><b>"._AD_VP_DENY."</b></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='./index.php?op=submission'><b>"._AD_VP_CANCEL."</b></a>";
    CloseTable();
    break;

  case "edit":
    $result     = $xoopsDB->queryF("SELECT id, code, title, artist, cid, embedcode, fullembedcode, description, thumb, service, servicename FROM ".$xoopsDB->prefix("vp_videos")." WHERE id='".$id."'");
    $vp_video   = $xoopsDB->fetcharray($result);

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
	$evform = new XoopsThemeForm(_AD_VP_EDITHEADER, "editvideoform", xoops_getenv('PHP_SELF'));
	$evform->addElement(new XoopsFormHidden('id', $id));
	$evform->addElement(new XoopsFormHidden('service', $service));
	if ($service < 2) {
	  $evform->addElement(new XoopsFormText(_AD_VP_CODE, 'code', 20, 20, $code), true);
	  $evform->addElement(new XoopsFormText(_AD_VP_TITLE, 'title', 50, 50, $title), true);
	  $evform->addElement(new XoopsFormText(_AD_VP_ARTIST, 'artist', 40, 40, $artist), false);
	  $evform->addElement(new XoopsFormTextArea(_AD_VP_DESC, 'description', $description, 4, 45), false);
	}elseif ($service > 1 && $service < 10) {
	  $evform->addElement(new XoopsFormText(_AD_VP_CODE, 'code', 20, 20, $code), true);
	  $evform->addElement(new XoopsFormText(_AD_VP_TITLE, 'title', 50, 50, $title), true);
	  $evform->addElement(new XoopsFormText(_AD_VP_ARTIST, 'artist', 40, 40, $artist), false);
	  $evform->addElement(new XoopsFormTextArea(_AD_VP_DESC, 'description', $description, 4, 45), false);
	  $evform->addElement(new XoopsFormText(_AD_VP_EMBEDCODE, 'embedcode', 50, 100, $embedcode), true);
	  $evform->addElement(new XoopsFormText(_AD_VP_THUMB, 'thumb', 50, 100, $thumb), false);
	}else{
	  $evform->addElement(new XoopsFormText(_AD_VP_TITLE, 'title', 50, 50, $title), true);
	  $evform->addElement(new XoopsFormText(_AD_VP_ARTIST, 'artist', 40, 40, $artist), false);
	  $evform->addElement(new XoopsFormTextArea(_AD_VP_FULLEMBEDCODE, 'fullembedcode', $fullembedcode, 5, 45), true);
	  $evform->addElement(new XoopsFormTextArea(_AD_VP_DESC, 'description', $description, 4, 45), false);
	  $evform->addElement(new XoopsFormText(_AD_VP_THUMB, 'thumb', 50, 100, $thumb), false);
	  $evform->addElement(new XoopsFormText(_AD_VP_SERVICENAME, 'servicename', 30, 30, $servicename), false);
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
	$button_tray->addElement(new XoopsFormButton('', 'postedit', _AD_VP_EDITSUBMITADD, 'submit'));
		
	$button_cancel = new XoopsFormButton('', '', _AD_VP_EDITSUBMITCANCEL, 'button');
	$button_cancel->setExtra('onclick="history.go(-1)"');
	$button_tray->addElement($button_cancel);
	
	$evform->addElement($button_tray);
	$evform->display();	
    break;

  case "postedit":

    $id = $_POST['id'];
    $service = $_POST['service'];
    $form_code = $myts->makeTboxData4Save($_POST['code']);
    $form_title = $myts->makeTboxData4Save($_POST['title']);
    $form_artist = $myts->makeTboxData4Save($_POST['artist']);
    $selectedcategory = $myts->makeTboxData4Save($_POST['cid']);
    $form_embedcode = $myts->makeTboxData4Save($_POST['embedcode']);
    $form_fullembedcode = $myts->makeTboxData4Save($_POST['fullembedcode']);
    $form_description = $myts->makeTboxData4Save($_POST['description']);
    $form_thumb = $myts->makeTboxData4Save($_POST['thumb']);
    $form_servicename = $myts->makeTboxData4Save($_POST['servicename']);
    
	$sqlcommandline = "UPDATE ".$xoopsDB->prefix("vp_videos")." SET code='".$form_code."', title='".$form_title."', artist='".$form_artist."', cid='".$selectedcategory."', embedcode='".$form_embedcode."', fullembedcode='".$form_fullembedcode."', description='".$form_description."', thumb='".$form_thumb."', servicename='".$form_servicename."' WHERE id='".$id."'";
	$done = $xoopsDB->queryF($sqlcommandline);
	
	if($done) {
	  redirect_header("index.php?op=published",2,_AD_VP_EDITSUCCESS);
	} else {
	  redirect_header("index.php",2,_AD_VP_EDITERROR);
	}
    break;


  case "del":

    $done1 = $xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("vp_videos")." WHERE id=".$id."");
	$done2 = $xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("vp_votedata")." WHERE vid=".$id."");
	xoops_comment_delete($xoopsModule->getVar('mid'), $id);
	
	if($done1 && $done2) {
	  redirect_header("index.php?op=published",2,_AD_VP_DELSUCCESS);
	} else {
	  redirect_header("index.php",2,_AD_VP_DELERROR);
	}
    break;

  case "delconfirm":

    OpenTable();
    $result = $xoopsDB->queryF("SELECT id, code, title, artist FROM ".$xoopsDB->prefix("vp_videos")." WHERE id='".$id."'",1);
    $vp_video = $xoopsDB->fetcharray($result);

    echo "<center><b>"._AD_VP_DEL_REALLY."</b></center><br><br>";
    echo "<table col=2 width=200px align=center>";
    echo "<tr><td align=left><b>"._AD_VP_ID.": </b></td><td align=left>".$vp_video['id']."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_CODE.":</b> </td><td align=left>".$vp_video['code']."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_TITLE.":</b> </td><td align=left>".$vp_video['title']."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_ARTIST.":</b> </td><td align=left>".$vp_video['artist']."</td></tr>";
    if ($vusecats) {
      $categoryid = $vp_video['cid'];
      if ($categoryid) {
        $result3 = $xoopsDB->queryF("SELECT title FROM ".$xoopsDB->prefix("vp_categories")." WHERE cid=$categoryid");
        $vp_category = $xoopsDB->fetcharray($result3);
        $categorytitle = $vp_category['title'];
      }else{
        $categorytitle = "No Category";
      }
      echo "<tr><td align=left><b>"._AD_VP_CATEGORY.":</b> </td><td align=left>".$categorytitle."</td></tr>";
    }
    echo "</table>";
    echo "<br><br><center><a href='./index.php?op=del&id=".$id."'><b>"._AD_VP_DELETE."</b></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='./index.php?op=published'><b>"._AD_VP_CANCEL."</b></a>";
    CloseTable();
    break;

  case "catmanage":

	$mytree = new VideoTubeTree($xoopsDB->prefix("vp_categories"), "cid", "pid");
	$mcform = new XoopsThemeForm(_AD_VP_CATMODHEADER, "categorymodform", xoops_getenv('PHP_SELF'));
		ob_start();
		$mytree -> makeMySelBox('title','weight', 0, 0, "", "");
		$mcform -> addElement(new XoopsFormLabel(_AD_VP_CATEGORY_TITLE, ob_get_contents()));
		ob_end_clean();

	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'catmod', _AD_VP_MODIFY, 'submit'));
	$button_tray->addElement(new XoopsFormButton('', 'catmove', _AD_VP_MOVE, 'submit'));
	$button_tray->addElement(new XoopsFormButton('', 'catdelconfirm', _AD_VP_DELETE, 'submit'));
	$mcform->addElement($button_tray);
	$mcform->display();	

	$acform = new XoopsThemeForm(_AD_VP_CATADDHEADER, "categoryaddform", xoops_getenv('PHP_SELF'));
		ob_start();
		$mytree -> makeMySelBox('title','weight', 0, 1, "", "");
		$acform -> addElement(new XoopsFormLabel(_AD_VP_PARENT_CATEGORY, ob_get_contents()));
		ob_end_clean();

	$acform->addElement(new XoopsFormText(_AD_VP_CATEGORY_TITLE, 'title', 50, 50, $title), true);
	$acform->addElement(new XoopsFormText(_AD_VP_CATEGORY_WEIGHT, 'weight', 10, 10, $weight=0), true);

	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'catpost', _AD_VP_CATADDSUBMITADD, 'submit'));
	$button_tray->addElement(new XoopsFormButton('', 'catmanage', _AD_VP_CATADDSUBMITRESET, 'reset'));
	$acform->addElement($button_tray);
	$acform->display();	

    OpenTable();

    echo "<table border=0 cellpadding=2 cellspacing=2 width='100%'><tr><td colspan=10><div align='center'>
      <b>"._AD_VP_CATEGORY_OVERVIEW_HEADER."</b></div></td></tr>
      <tr class=even>
        <td width=10%><i>"._AD_VP_CATID_CHEADING."</i></td>
        <td width=10%><i>"._AD_VP_CATPID_CHEADING."</i></td>
        <td width=10%><i>"._AD_VP_CATCID_CHEADING."</i></td>
        <td width=25%><i>"._AD_VP_CATTITLE_CHEADING."</i></td>
        <td width=10%><i>"._AD_VP_CATWEIGHT_CHEADING."</i></td>
        <td width=15%><i>"._AD_VP_CATVIDEOS_CHEADING."</i></td>      
      </tr>";

    $result = $xoopsDB->queryF("SELECT refid, pid, cid, title, weight FROM ".$xoopsDB->prefix("vp_categories")." WHERE pid = 0 ORDER BY weight ASC");
    $numcats = mysql_num_rows($result);
	
    if ($vusecats) {
	    //while ($vp_category = $xoopsDB->fetchRow($result) ) {
		  $arr = $mytree->getChildTreeArray(0,'weight');
		  foreach ( $arr as $item ) {
			if ($item['pid']){
			  $item['prefix'] = str_replace(".","-",$item['prefix']);
			}else{
			  $item['prefix'] = str_replace(".","",$item['prefix']);
			}
			$catpath = $item['prefix']."&nbsp;".$myts->htmlspecialchars($item['title']);
	        echo "<tr class=odd><td>".$item['refid']."</td>
	          <td>".($item['pid'])."</td>
		      <td>".($item['cid'])."</td>
		      <td>".$catpath."</td>
	          <td>".($item['weight'])."</td>";
	        $result2 = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE cid = ".$item['cid']." && pub=1");
		    $numvids = mysql_num_rows($result2);
		    echo "<td>".$numvids."</td></tr>";
          }
		  echo "<tr class=odd><td>0</td>
	          <td>0</td>
		      <td>0</td>
		      <td>&nbsp;No Category</td>
	          <td>0</td>";
	        $result2 = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE cid=0 && pub=1");
		    $numvids = mysql_num_rows($result2);
		    echo "<td>".$numvids."</td></tr>";

	    //}
    } else {
    echo "<tr class=odd><td colspan=".$numcolumns." align=center>Categories are not enabled at this time</td>";
    }
	    
    echo "</table>";
	
	CloseTable();

	break;

  case "catmod":
    $result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_categories")." WHERE cid=".$_POST['cid']."");
    $vp_cat = $xoopsDB->fetcharray($result);
	$cid = $vp_cat['cid'];
	$pid = $vp_cat['pid'];
	$title = $vp_cat['title'];
	$weight = $vp_cat['weight'];
	
	$mytree = new VideoTubeTree($xoopsDB->prefix("vp_categories"), "cid", "pid");
	$modcatform = new XoopsThemeForm(_AD_VP_CATMODHEADER, "categorymodform", xoops_getenv('PHP_SELF'));
		ob_start();
		$mytree -> makeMySelBox('title','weight', $pid, 1, 'pid', "");
		$modcatform -> addElement(new XoopsFormLabel(_AD_VP_PARENT_CATEGORY, ob_get_contents()));
		ob_end_clean();

	$modcatform->addElement(new XoopsFormText(_AD_VP_CATEGORY_TITLE, 'title', 50, 50, $title), true);
	$modcatform->addElement(new XoopsFormText(_AD_VP_CATEGORY_WEIGHT, 'weight', 10, 10, $weight), true);
    $modcatform->addElement(new XoopsFormHidden('cid', $cid));
	
	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'catmodpost', _AD_VP_MODIFY, 'submit'));
	$button_cancel = new XoopsFormButton('', '', _AD_VP_CANCEL, 'button');
	$button_cancel->setExtra('onclick="history.go(-1)"');
	$button_tray->addElement($button_cancel);
	$modcatform->addElement($button_tray);
	$modcatform->display();	
	break;
	
  case "catpost":
   
    $form_title = $myts->makeTboxData4Save($_POST['title']);
	$form_pid = $_POST['cid'];

    $result = $xoopsDB->queryF("SELECT refid, cid, title, disporder FROM ".$xoopsDB->prefix("vp_categories")." WHERE refid > '0' ORDER BY disporder");
    $numcats = mysql_num_rows($result);
    $disporder = $numcats;
	$lastcidused = 0;
	while($vp_category = $xoopsDB->fetcharray($result)) {
	  $currentcid = $vp_category['cid'];
	  if ($currentcid > $lastcidused) {
	    $lastcidused = $currentcid;
	  }
	}
	$cid = $lastcidused + 1;
    $sqlcommandline = "INSERT INTO ".$xoopsDB->prefix("vp_categories")."(cid, title, pid, disporder) VALUES ($cid,'$form_title', $form_pid, $disporder)";
    $done = $xoopsDB->queryF($sqlcommandline);

	if($done) {
	  redirect_header("index.php?op=catmanage",2,_AD_VP_CATADDSUCCESS);
	} else {
	  redirect_header("index.php",2,_AD_VP_CATADDERROR);
	}
    break;

  case "catmodpost":

    $cid = $_POST['cid'];
	$pid = $_POST['pid'];
    $form_title = $myts->makeTboxData4Save($_POST['title']);
	$weight = $_POST['weight'];
    
	$sqlcommandline = "UPDATE ".$xoopsDB->prefix("vp_categories")." SET pid='".$pid."', title='".$form_title."', weight='".$weight."' WHERE cid='".$cid."'";
	$done = $xoopsDB->queryF($sqlcommandline);
	
	if($done) {
	  redirect_header("index.php?op=catmanage",2,_AD_VP_CATEDITSUCCESS);
	} else {
	  redirect_header("index.php",2,_AD_VP_CATEDITERROR);
	}
    break;

  case "catmove":
    $result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_categories")." WHERE cid=".$_POST['cid']."");
    $vp_cat = $xoopsDB->fetcharray($result);
	$cid = $vp_cat['cid'];
	$pid = $vp_cat['pid'];
	$title = $vp_cat['title'];
	$weight = $vp_cat['weight'];

    $result2 = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE cid=".$_POST['cid']."");
    $vp_numcatvideos = mysql_num_rows($result2);
    $vp_catvideos = $xoopsDB->fetcharray($result2);
	
    $result3 = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_categories")." WHERE pid=".$_POST['cid']."");
    $vp_numcatsubs = mysql_num_rows($result3);
	$vp_catsubs = $xoopsDB->fetcharray($result3);
	
    echo "<table col=4 align=center style='border: 1px solid #000000;'>";
    echo "<tr><th align=center colspan=4><b>"._AD_VP_CAT_MOVE_REQUEST."</b></th></tr>";
    echo "<tr><td width='20%'>&nbsp;</td><td align=left><b>"._AD_VP_CATTITLE_MOVE.":</b> </td><td align=left>".$title."</td><td width='20%'>&nbsp;</td></tr>";
    echo "<tr><td width='20%'>&nbsp;</td><td align=left><b>"._AD_VP_CATCID_MOVE.":</b> </td><td align=left>".$cid."</td><td width='20%'>&nbsp;</td></tr>";
    echo "<tr><td width='20%'>&nbsp;</td><td align=left><b>"._AD_VP_CATPID_MOVE.":</b> </td><td align=left>".$pid."</td><td width='20%'>&nbsp;</td></tr>";

    echo "<tr><td width='20%'>&nbsp;</td><td align=left><b>"._AD_VP_CATVIDEO_ASSIGNED.":</b> </td><td align=left>".$vp_numcatvideos."</td><td width='20%'>&nbsp;</td></tr>";
    echo "<tr><td width='20%'>&nbsp;</td><td align=left><b>"._AD_VP_CATSUBS_ASSIGNED.":</b> </td><td align=left>".$vp_numcatsubs."</td><td width='20%'>&nbsp;</td></tr></table>";

	$selectedcid = $cid;
	$mytree = new VideoTubeTree($xoopsDB->prefix("vp_categories"), "cid", "pid");
	$movecatform = new XoopsThemeForm(_AD_VP_CAT_MOVE_DEST, "categorymovedestform", xoops_getenv('PHP_SELF'));
		ob_start();
		$mytree -> makeMySelBox('title','weight', 0, 1, 'cid', "");
		$movecatform -> addElement(new XoopsFormLabel(_AD_VP_CATMOVEDEST, ob_get_contents()));
		ob_end_clean();
        $movecatform->addElement(new XoopsFormHidden('selectedcid', $selectedcid));
	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'catmovepost', _AD_VP_CATMOVE, 'submit'));
	$button_cancel = new XoopsFormButton('', '', _AD_VP_CANCEL, 'button');
	$button_cancel->setExtra('onclick="history.go(-1)"');
	$button_tray->addElement($button_cancel);
	$movecatform->addElement($button_tray);
	$movecatform->display();	

    break;

  case "catmovepost":
    
    $selectedcid = $_POST['selectedcid'];
	$destcid = $_POST['cid'];
	
	if($selectedcid == $destcid) {
	  redirect_header("index.php?op=catmanage",3,_AD_VP_MOVEDUPLERROR);
	}

    $result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE cid=".$selectedcid."");
    $vp_numvideos = mysql_num_rows($result);

	if($vp_numvideos == 0) {
	  redirect_header("index.php?op=catmanage",3,_AD_VP_MOVENOVIDEOSERROR);
	}else{
	  $failflag=0;
      while($vp_video = $xoopsDB->fetcharray($result)) {
	    $id = $vp_video['id'];
	    $sqlcommandline = "UPDATE ".$xoopsDB->prefix("vp_videos")." SET cid='".$destcid."' WHERE id='".$id."'";
	    $done = $xoopsDB->queryF($sqlcommandline);
	    if (!$done) {
	      $failflag++;
	    }
      }
	} 
	if(!$failflag) {
	  redirect_header("index.php?op=catmanage",2,_AD_VP_CATMOVESUCCESS);
	} else {
	  redirect_header("index.php?op=catmanage",2,_AD_VP_CATMOVEERROR);
	}

    break;
	
  case "catdel":

    $done = $xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("vp_categories")." WHERE cid=".$_POST['cid']."");

	if($done) {
	  redirect_header("index.php?op=catmanage",2,_AD_VP_CATDELSUCCESS);
	} else {
	  redirect_header("index.php?op=catmanage",2,_AD_VP_CATDELERROR);
	}

    break;
	
  case "catdelconfirm":

    $selectedcid = $_POST['cid'];
    $result = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE cid=".$selectedcid."");
    $vp_numvideos = mysql_num_rows($result);
    $result2 = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_categories")." WHERE pid=".$selectedcid."");
    $vp_numsubs = mysql_num_rows($result2);

	if($vp_numvideos > 0) redirect_header("index.php?op=catmanage",3,_AD_VP_CATDELVIDEMPTYERROR);
	if($vp_numsubs > 0) redirect_header("index.php?op=catmanage",3,_AD_VP_CATDELSUBEMPTYERROR);

    $result3 = $xoopsDB->queryF("SELECT * FROM ".$xoopsDB->prefix("vp_categories")." WHERE cid=".$selectedcid."");	
	$vp_category = $xoopsDB->fetcharray($result3);
	$cattitle = $vp_category['title'];

	xoops_confirm(array('op' => 'catdel', 'cid' => $_POST['cid'], 'confirm' => 1),
	  'index.php?op=catdel', _AD_VL_CATDELETECONFIRM . "<br /><br />Category: ".$cattitle."<br />CID: " . $_POST['cid'], _AD_VL_CATDELETE, true);

    break;

  case "published":
    OpenTable();

    echo "<table border=0 cellpadding=2 cellspacing=2 width='100%'><tr><td colspan=10><div align='center'>
      <b>"._AD_VP_PUBLISHED_HEADER."</b></div></td></tr>
      <tr class=even>
        <td><i>"._AD_VP_ID_CHEADING."</i></td>
        <td><i>"._AD_VP_CODE_CHEADING."</i></td>
        <td><i>"._AD_VP_TITLE_CHEADING."</i></td>       
        <td><i>"._AD_VP_ARTIST_CHEADING."</i></td>";
        if ($vusecats) {
          echo "<td><i>"._AD_VP_CATEGORY_CHEADING."</i></td>";
        }
        echo "<td><i>"._AD_VP_SUBMITTER_CHEADING."</i></td>
        <td><i>"._AD_VP_EDIT_CHEADING."</i></td>
        <td><i>"._AD_VP_DELETE_CHEADING."</i></td>
      </tr>";

    $result = $xoopsDB->queryF("SELECT id, uid, cid, code, title, artist, service FROM ".$xoopsDB->prefix("vp_videos")." WHERE pub='1' ORDER BY id");
    $numsubmissions = mysql_num_rows($result);
     
    if ($numsubmissions) {
	    while($vp_video = $xoopsDB->fetcharray($result)) {
	      $suid = $vp_video['uid'];
	      if ($suid){
	         $result2 = $xoopsDB->queryF("SELECT uname FROM ".$xoopsDB->prefix("users")." WHERE uid=$suid");
             $vp_submitter = $xoopsDB->fetcharray($result2);
             $sname = $vp_submitter['uname'];
          } else {
             $sname = $xoopsConfig['anonymous'];
          }
	      echo "<tr class=odd><td>".$vp_video['id']."</td>
	      <td>".($vp_video['code'])."</td>
	      <td>".($vp_video['title'])."</td>
		  <td>".$vp_video['artist']."</td>";
          if ($vusecats) {
            $categoryid = $vp_video['cid'];
            if ($categoryid) {
              $result3 = $xoopsDB->queryF("SELECT title FROM ".$xoopsDB->prefix("vp_categories")." WHERE cid=$categoryid");
              $vp_category = $xoopsDB->fetcharray($result3);
              $categorytitle = $vp_category['title'];
            }else{
              $categorytitle = "No Category";
            }
            echo "<td>".$categorytitle."</td>";
          }
		  echo "<td>".$sname."</td>";
          echo "<td><a href='./index.php?op=edit&id=".$vp_video['id']."'>"._AD_VP_EDIT."</a></td>";
	      echo "<td><a href='./index.php?op=delconfirm&id=".$vp_video['id']."'>"._AD_VP_DELETE."</a></td></tr>";
	    }
    } else {
    echo "<tr class=odd><td colspan=".$numcolumns." align=center><td colspan=6 align=center>There are no published videos at this time</td>";
    }
	    
    echo "</table>";
	
	CloseTable();

    break;

  case "videoreport":
    OpenTable();

    echo "<table border=0 cellpadding=2 cellspacing=2 width='100%'><tr><td colspan=8><div align='center'>
      <b>"._AD_VP_VIDEOREPORT_HEADER."</b></div></td></tr>
      <tr>
        <td class=even align=center><i>"._AD_VP_ID_CHEADING."</i></td>
        <td class=even align=center><i>"._AD_VP_CODE_CHEADING."</i></td>
        <td class=even align=center><i>"._AD_VP_TITLE_CHEADING."</i></td>       
        <td class=even align=center><i>"._AD_VP_REASON_CHEADING."</i></td>
        <td class=even align=center><i>"._AD_VP_NUMREPORTS_CHEADING."</i></td>
		<td class=even align=center><i>"._AD_VP_VIEW_CHEADING."</i></td>
        <td class=even align=center><i>"._AD_VP_DELETEREPORT_CHEADING."</i></td>
		<td class=even align=center><i>"._AD_VP_DELETEREPORT_VIDEO_CHEADING."</i></td>
      </tr>";

    $result = $xoopsDB->queryF("SELECT repid, vid, reasontext, numreports FROM ".$xoopsDB->prefix("vp_reports")." ORDER BY repid");
    $numrecords = mysql_num_rows($result);
     
    if ($numrecords) {
	    while($vp_report = $xoopsDB->fetcharray($result)) {
	      $vid = $vp_report['vid'];
		  $result2 = $xoopsDB->queryF("SELECT code, title FROM ".$xoopsDB->prefix("vp_videos")." WHERE id=".$vid."");
		  $vp_video = $xoopsDB->fetcharray($result2);
	      echo "<tr><td class=odd  align=left>".$vid."</td>
	                <td class=odd align=left>".($vp_video['code'])."</td>
	                <td class=odd align=left>".($vp_video['title'])."</td>
		            <td class=odd align=left>".$vp_report['reasontext']."</td>
		            <td class=odd align=center>".$vp_report['numreports']."</td>";
   		  echo "<td class=odd align=center><a href='".XOOPS_URL."/modules/videotube/index.php?vid=".$vid."' target='_blank'>"._AD_VP_VIEW."</a></td>";
	      echo "<td class=odd align=center><a href='./index.php?op=delreportconfirm&repid=".$vp_report['repid']."'>"._AD_VP_DELETE."</a></td>";
		  echo "<td class=odd align=center><a href='./index.php?op=delreportvideoconfirm&repid=".$vp_report['repid']."'>"._AD_VP_DELETE."</a></td></tr>";
	    }
    } else {
    echo "<tr class=odd><td colspan=8 align=center>There are no video reports at this time</td></tr>";
    }
	    
    echo "</table>";
	
	CloseTable();

    break;

  case "delreport":

    $done = $xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("vp_reports")." WHERE repid=".$repid."");
	
	if($done) {
	  redirect_header("index.php?op=videoreport",2,_AD_VP_DELREPORTSUCCESS);
	} else {
	  redirect_header("index.php",2,_AD_VP_DELREPORTERROR);
	}
    break;

  case "delreportconfirm":

    OpenTable();
    $result = $xoopsDB->queryF("SELECT repid, vid, reasontext FROM ".$xoopsDB->prefix("vp_reports")." WHERE repid='".$repid."'",1);
    $vp_report = $xoopsDB->fetcharray($result);
	$repid = $vp_report['repid'];
	$vid = $vp_report['vid'];
    $result2 = $xoopsDB->queryF("SELECT code, title FROM ".$xoopsDB->prefix("vp_videos")." WHERE id=".$vid."");
	$vp_video = $xoopsDB->fetcharray($result2);

    echo "<center><b>"._AD_VP_DELREPORT_REALLY."</b></center><br><br>";
    echo "<table col=2 width=200px align=center>";
    echo "<tr><td align=left><b>"._AD_VP_ID.": </b></td><td align=left>".$vid."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_CODE.":</b> </td><td align=left>".$vp_video['code']."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_TITLE.":</b> </td><td align=left>".$vp_video['title']."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_REASON_CHEADING.":</b> </td><td align=left>".$vp_report['reasontext']."</td></tr>";
    echo "</table>";
    echo "<br><br><center><a href='./index.php?op=delreport&repid=".$repid."'><b>"._AD_VP_DELETE."</b></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='./index.php?op=videoreport'><b>"._AD_VP_CANCEL."</b></a>";
    CloseTable();
    break;

  case "delreportvideo":

    $done1 = $xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("vp_reports")." WHERE repid=".$repid."");
    $done2 = $xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("vp_videos")." WHERE id=".$vid."");
	$done3 = $xoopsDB->queryF("DELETE FROM ".$xoopsDB->prefix("vp_votedata")." WHERE vid=".$vid."");
	xoops_comment_delete($xoopsModule->getVar('mid'), $vid);
	
	if($done2) {
	  redirect_header("index.php?op=videoreport",2,_AD_VP_DELREPORTVIDEOSUCCESS);
	} else {
	  redirect_header("index.php",2,_AD_VP_DELREPORTVIDEOERROR);
	}
    break;

  case "delreportvideoconfirm":

    OpenTable();
    $result = $xoopsDB->queryF("SELECT repid, vid, reasontext FROM ".$xoopsDB->prefix("vp_reports")." WHERE repid='".$repid."'",1);
    $vp_report = $xoopsDB->fetcharray($result);
	$repid = $vp_report['repid'];
	$vid = $vp_report['vid'];
    $result2 = $xoopsDB->queryF("SELECT code, title FROM ".$xoopsDB->prefix("vp_videos")." WHERE id=".$vid."");
	$vp_video = $xoopsDB->fetcharray($result2);

    echo "<center><b>"._AD_VP_DELREPORTVIDEO_REALLY."</b></center><br><br>";
    echo "<table col=2 width=200px align=center>";
    echo "<tr><td align=left><b>"._AD_VP_ID.": </b></td><td align=left>".$vid."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_CODE.":</b> </td><td align=left>".$vp_video['code']."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_TITLE.":</b> </td><td align=left>".$vp_video['title']."</td></tr>";
    echo "<tr><td align=left><b>"._AD_VP_REASON_CHEADING.":</b> </td><td align=left>".$vp_report['reasontext']."</td></tr>";
    echo "</table>";
    echo "<br><br><center><a href='./index.php?op=delreportvideo&repid=".$repid."&vid=".$vid."'><b>"._AD_VP_DELETE."</b></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='./index.php?op=videoreport'><b>"._AD_VP_CANCEL."</b></a>";
    CloseTable();
    break;

  default:
    OpenTable();
    
    echo "<table width='35%' border='0' cellspacing='1' align='center'>";
    echo "<tr><td class='even' align='center'><b>" . _AD_VP_ADMINTITLE . "</b></td></tr>";
    echo "<tr><td class='odd' align='left'><a href='" . XOOPS_URL . "/modules/videotube/admin/index.php'>" . _MI_VP_ADMENU1 . "</a></td></tr>";
    echo "<tr><td class='odd' align='left'><a href='" . XOOPS_URL . "/modules/videotube/admin/index.php?op=submission'>" . _MI_VP_ADMENU2 . "</a></td></tr>";
    echo "<tr><td class='odd' align='left'><a href='" . XOOPS_URL . "/modules/videotube/admin/index.php?op=published'>" . _MI_VP_ADMENU3 . "</a></td></tr>";
    echo "<tr><td class='odd' align='left'><a href='" . XOOPS_URL . "/modules/videotube/admin/index.php?op=catmanage'>" . _MI_VP_ADMENU4 . "</a></td></tr>";
	echo "<tr><td class='odd' align='left'><a href='" . XOOPS_URL . "/modules/videotube/admin/index.php?op=videoreport'>" . _MI_VP_ADMENU5 . "</a></td></tr>";
    echo "<tr><td class='odd' align='left'><a href='" . XOOPS_URL . "/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule -> getVar( 'mid' ) . "'>" . _AD_VP_PREFERENCES . "</a></td></tr>";
    echo"</table>";
    
    CloseTable();

    break;

}

include("admin_footer.php");

?>
