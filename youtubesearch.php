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
//  File:       youtubesearch.php                                            //
//  Version:    1.82                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.64  Initial Release 09/06/2008                                 //
//  ***                                                                      //
//  Version 1.65  09/12/2008                                                 //
//  Bug Fix: Add passing category through sanitizer(potential security risk) //
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
//  Remove Preview default position parameters                               //
//  Add Preview position offset parameters                                   //
//  ***                                                                      //

include_once '../../mainfile.php';
include_once XOOPS_ROOT_PATH."/header.php";
include 'include/functions.php';
include_once 'class/videotubetree.php';

if (file_exists('language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php')) {
  include_once 'language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php';
}else{
  include_once 'language/english/modinfo.php';
}

global $xoopsOption, $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModuleConfig;

$myts =& MyTextSanitizer::getInstance();

$op= NULL;
$code = NULL;
$title = NULL;
$artist = NULL;
$description = NULL;
$category = NULL;

if (isset($_POST['post'])) $op = 'post';

if(isset($_GET['op'])) $op = $_GET['op'];
if(isset($_POST['op'])) $op = $_POST['op'];

if(isset($_GET['displayorder'])) $displayorder = intval($_GET['displayorder']);

if ($xoopsUser) {
  $uid = $xoopsUser->getVar('uid');
} else {
  $uid = 0;
}

switch($op) {

  case "submit":

	if (($xoopsModuleConfig['anonsubmitvideo'] == 0) && ($uid == 0)) {
	  redirect_header("index.php", 0, _NOPERM);
	  exit();
	}

	include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	
	$vsrchmax = $xoopsModuleConfig['videosearchresultsmax'];
	$vsrchfrmt = $xoopsModuleConfig['videosearchresultsformat'];
	$vdefaultheight = $xoopsModuleConfig['videodefaultheight'];
	$vdefaultwidth = $xoopsModuleConfig['videodefaultwidth'];
	$vsrchoverlaysize = $xoopsModuleConfig['videosearchoverlaysize'];
	$vsrchoverlayheight = ($vdefaultheight * ($vsrchoverlaysize / 100));
	$vsrchoverlaywidth = ($vdefaultwidth * ($vsrchoverlaysize / 100));
	$vsrchoverlaywindowheight = ($vsrchoverlayheight + 60);
	$vsrchoverlaywindowwidth = ($vsrchoverlaywidth + 10);
	$vsrchoverlayxoffset = $xoopsModuleConfig['videosearchoverlayxoffset'];
	$vsrchoverlayyoffset = $xoopsModuleConfig['videosearchoverlayyoffset'];
	$vdesccopyenable = $xoopsModuleConfig['descriptioncopyenable'];
	$vsobkgrdclr = $xoopsModuleConfig['vsobackgroundcolor'];
	$vsobrdrclr = $xoopsModuleConfig['vsobordercolor'];
	$vsobrdrsize = $xoopsModuleConfig['vsobordersize'];
	$youtubesearch = _MI_VP_YOUTUBESEARCH;
	$keywords = _MI_VP_KEYWORDS;
	
	$label_01 = _MI_VP_CLOSEVIDEO;
	$label_02 = _MI_VP_PREVIEW;
	$label_03 = _MI_VP_RESULTS;
	$label_04 = _MI_VP_TO;
	$label_05 = _MI_VP_ABOUT;
	$label_06 = _MI_VP_NEXTPAGELABEL;
	$label_07 = _MI_VP_PREVPAGELABEL;
	$label_08 = _MI_VI_VIDEOTITLE;
	$label_09 = _MI_VI_DESC;
	$label_10 = _MI_VI_AUTHOR;
	$label_11 = _MI_VI_VIEWS;
	$label_12 = _MI_VI_DURATION;
	$label_13 = _MI_VI_PUBDATE;
	$label_14 = _MI_VI_PUBTIME;

	$svform = new XoopsThemeForm(_MI_VP_SUBMITHEADER, "submitvideoform", xoops_getenv('PHP_SELF'));
	$svform->addElement(new XoopsFormText(_MI_VP_CODE, 'code', 20, 20, $code), true);
	$svform->addElement(new XoopsFormText(_MI_VP_TITLE, 'title', 50, 4095, $title), true);
	$svform->addElement(new XoopsFormText(_MI_VP_ARTIST, 'artist', 50, 4095, $artist), false);
	$svform->addElement(new XoopsFormTextArea(_MI_VP_DESC, 'description', $description, 4, 50), false);

	if ($xoopsModuleConfig['videousecats']) {
	  $mytree = new VideoTubeTree($xoopsDB->prefix("vp_categories"), "cid", "pid");
		ob_start();
		$mytree -> makeMySelBox('title','weight',0, 2, 'cid', "");
		$svform -> addElement(new XoopsFormLabel(_MI_VP_CATEGORY, ob_get_contents()));
		ob_end_clean();
      $svform->addElement($category_select);
	}
    
	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'post', _MI_VP_SUBMIT, 'submit'));
	$button_tray->addElement(new XoopsFormButton('', 'submit', _MI_VP_RESET, 'reset'));
	$svform->addElement($button_tray);
	
    echo "<style>
          .vidFrame { 
             position: absolute;
             display: none;
             background-color: #";
    echo $vsobkgrdclr;         
    echo ';border: ';
    echo $vsobrdrsize;
    echo 'px solid #';
    echo $vsobrdrclr;
    echo ';width: ';
    echo $vsrchoverlaywindowwidth;
    echo 'px;height: ';
    echo $vsrchoverlaywindowheight;
    echo "px; cursor: move;}
          #youtubelist{
	        margin: 0;
	        padding: 5px;
	        list-style: none;
	        clear: both;
	        display: block;
          }
          #youtubebox{
	        clear: right;
	        display: inline;
	        padding: 0;
	        margin: 2px;
          }
          #youtubethumb {
	        margin-bottom: 5px;
	        width: 125px;
	        border: 2px solid #333;
          }
          #youtubecontent {
			height: 520px;
			width: 430px;
			margin-left: 70px;
			top: -95px;
			position: absolute;
			left: 50%;
          }
          #youtubeoverlay {
			background-color: #fff;
			overflow: visible;
			position: absolute;
			height: 0px;
			width: 100%;
			top: 50%;
          }
          #youtubeclose {
			background-color: #000;
			width:200px;
			color:#fff;
			margin-right: 5px;
			text-align: right;
          }

          div#youtubeclose a {color:#ffffff;}
          div#youtubeclose a:hover {color:#ffffff;}
          div#youtubeclose a.visited {color:#ffffff;}
          div#youtubeclose a.highlight {color:#ffffff;}
          </style>";

          echo '<script src="';
          echo 'include/youtube.js" type="text/javascript" charset="utf-8"></script>';
         
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
          echo '<div align="center">';
	      echo '<table width="100%">';
	      echo '<tr>';
	      echo '<td class="odd" align="center" valign="top"><b>';
	      echo $youtubesearch;
	      echo '</b></td>';
	      echo '</tr>';
	      echo '<tr>';
	      echo '<td class="odd" align="center" valign="top"><b>';
          echo $keywords;
          echo '</b><input type="text" size="45" onblur="insertVideos(\'youtubeDivSearch\',\'search\',this.value,1,';
          echo $vsrchmax;
          echo ',';
          echo $vsrchfrmt;
          echo ',';
          echo $vsrchoverlayheight;
          echo ',';
          echo $vsrchoverlaywidth;
          echo ',';
          echo $vsrchoverlayxoffset;
          echo ',';
          echo $vsrchoverlayyoffset;
          echo ',';
          echo $vdesccopyenable;
          echo ',';        
		  echo '\''.$label_01.'\'';
          echo ',';
          echo '\''.$label_02.'\'';		  
          echo ',';
          echo '\''.$label_03.'\'';		  
          echo ',';
          echo '\''.$label_04.'\'';		  
          echo ',';
          echo '\''.$label_05.'\'';		  
           echo ',';
          echo '\''.$label_06.'\'';		  
          echo ',';
          echo '\''.$label_07.'\'';		  
          echo ',';
          echo '\''.$label_08.'\'';
          echo ',';
          echo '\''.$label_09.'\'';		  
          echo ',';
          echo '\''.$label_10.'\'';		  
          echo ',';
          echo '\''.$label_11.'\'';		  
          echo ',';
          echo '\''.$label_12.'\'';		  
           echo ',';
          echo '\''.$label_13.'\'';		  
          echo ',';
          echo '\''.$label_14.'\'';		  
          echo ');"/><input name="searchButton" type="submit" value="  Search  " />';
	      echo '</td>';
	      echo '</tr>';
	      echo '</table>';
	      echo '<div id="youtubeDivSearch" align="center" style="display: block; clear: both">';
          echo '</div>';
          echo '<div id="vidPane" class="vidFrame"></div>';
          echo '</div>';
          echo '<script language="JavaScript" type="text/javascript">';
          echo 'var savedTarget=null;';
          echo 'var orgCursor=null;';
          echo 'var dragOK=false;';
          echo 'var dragXoffset=0;';
          echo 'var dragYoffset=0;';
          echo 'vidPaneID = document.getElementById("vidPane");';
          echo '</script>';
       
	break;

  case "post":
	
    if (($xoopsModuleConfig['autoapprovesubmitvideo'] == 1) && ($uid > 0)) {
	  $pub = 1;
	} else {
	  $pub = 0;
	}
	
    $now = time();

	$form_code = $myts->addSlashes($_POST['code']);
    $form_title = $myts->addSlashes($_POST['title']);
    $form_artist = $myts->addSlashes($_POST['artist']);
    $form_description = $myts->addSlashes($_POST['description']);
	
	if ($xoopsModuleConfig['videousecats']) {
      $selectedcategory = $myts->makeTboxData4Save($_POST['cid']);
	} else {
	  $selectedcategory = 0;
	}
	
    $sqlcommandline = "INSERT INTO ".$xoopsDB->prefix("vp_videos") . "(pub, uid, cid, date, code, title, artist, description) VALUES ($pub,$uid,$selectedcategory,$now,'$form_code','$form_title','$form_artist','$form_description')";
    $done = $xoopsDB->queryF($sqlcommandline);

	if($done) {
	  if (($xoopsModuleConfig['autoapprovesubmitvideo'] == 1) && ($uid > 0)) {
	    redirect_header("youtubesearch.php?op=submit",2,_MI_VP_AUTOSUBMITSUCCESS);
	  } else {
	    redirect_header("youtubesearch.php?op=submit",2,_MI_VP_SUBMITSUCCESS);
	  }
	} else {
	  redirect_header("index.php",2,_MI_VP_SUBMITERROR);
	}

    break;
}

include(XOOPS_ROOT_PATH."/footer.php");

?>
