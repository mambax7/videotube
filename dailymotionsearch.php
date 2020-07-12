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
//  File:       dailymotionsearch.php                                        //
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

$op = NULL;
$code = NULL;
$title = NULL;
$artist = NULL;
$description = NULL;
$category = NULL;
$embedcode = NULL;
$thumb = NULL;
$kw = NULL;
$dmpage = NULL;

if (isset($_POST['postdm'])) $op = 'postdm';
if (isset($_POST['submitdm'])) $op = 'submitdm';

if (isset($_POST['kw'])) {
  $kw = $myts->addSlashes($_POST['kw']);
}

if(isset($_GET['op'])) $op = $_GET['op'];
if(isset($_POST['op'])) $op = $_POST['op'];

if(isset($_GET['displayorder'])) $displayorder = intval($_GET['displayorder']);
if(isset($_GET['kw'])) $kw = ($_GET['kw']);
if(isset($_GET['dmpage'])) $dmpage = ($_GET['dmpage']);

if ($xoopsUser) {
  $uid = $xoopsUser->getVar('uid');
} else {
  $uid = 0;
}

switch($op) {

  case "searchdm":

	if (($xoopsModuleConfig['anonsubmitvideo'] == 0) && ($uid == 0)) {
	  redirect_header("index.php", 0, _NOPERM);
	  exit();
	}

	include XOOPS_ROOT_PATH."/class/xoopsformloader.php";

	$vsrchmax = $xoopsModuleConfig['videosearchresultsmax'];
	$vdefaultheight = 257;
	$vdefaultwidth = 420;
	$vsrchoverlaysize = $xoopsModuleConfig['videosearchoverlaysize'];
	$vsrchoverlayheight = ($vdefaultheight * ($vsrchoverlaysize / 100));
	$vsrchoverlaywidth = ($vdefaultwidth * ($vsrchoverlaysize / 100));
	$vsrchoverlaywindowheight = ($vsrchoverlayheight + 60);
	$vsrchoverlaywindowwidth = ($vsrchoverlaywidth + 10);
	$vsrchoverlayxpos = $xoopsModuleConfig['videosearchoverlayxpos'];
	$vsrchoverlayypos = $xoopsModuleConfig['videosearchoverlayypos'];
	$vsobkgrdclr = $xoopsModuleConfig['vsobackgroundcolor'];
	$vsobrdrclr = $xoopsModuleConfig['vsobordercolor'];
	$vsobrdrsize = $xoopsModuleConfig['vsobordersize'];
	$dmsearch = _MI_VP_DMSEARCH;
	$keywords = _MI_VP_KEYWORDS;

	$svform = new XoopsThemeForm(_MI_VP_SUBMITHEADER, "submitvideoform", xoops_getenv('PHP_SELF'));
	$svform->addElement(new XoopsFormText(_MI_VP_CODE, 'code', 20, 20, $code), true);
	$svform->addElement(new XoopsFormText(_MI_VP_TITLE, 'title', 50, 4095, $title), true);
	$svform->addElement(new XoopsFormText(_MI_VP_ARTIST, 'artist', 50, 4095, $artist), false);
	$svform->addElement(new XoopsFormTextArea(_MI_VP_DESC, 'description', $description, 4, 50), false);
    $svform->addElement(new XoopsFormText(_MI_VP_EMBEDCODE, 'embedcode', 50, 4095, $embedcode), true);
    $svform->addElement(new XoopsFormText(_MI_VP_THUMB, 'thumb', 50, 4095, $thumb), true);
    $svform->addElement(new XoopsFormHidden('service', '2'));
    
	if ($xoopsModuleConfig['videousecats']) {
	  $mytree = new VideoTubeTree($xoopsDB->prefix("vp_categories"), "cid", "pid");
		ob_start();
		$mytree -> makeMySelBox('title','weight',0, 2, 'cid', "");
		$svform -> addElement(new XoopsFormLabel(_MI_VP_CATEGORY, ob_get_contents()));
		ob_end_clean();
      $svform->addElement($category_select);
	}
    
	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'postdm', _MI_VP_SUBMIT, 'submit'));
	$button_tray->addElement(new XoopsFormButton('', 'searchdm', _MI_VP_RESET, 'reset'));
	$svform->addElement($button_tray);


	$sdmvform = new XoopsThemeForm(_MI_VP_SEARCHDMHEADER, "searchdailymotionvideoform", xoops_getenv('PHP_SELF'));
	$sdmvform->addElement(new XoopsFormText(_MI_VP_KEYWORDS, 'kw', 50, 50, $kw), true);
	$sdmvbutton_tray = new XoopsFormElementTray('' ,'');
	$sdmvbutton_tray->addElement(new XoopsFormButton('', 'submitdm', _MI_VP_SEARCH, 'submit'));
	$sdmvform->addElement($sdmvbutton_tray);

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
          echo '<td class="odd" align="center" valign="top">';
          $sdmvform->display();
          echo '</td>';
          echo '</tr>';
          echo '</table>';
          echo '</div>';
   
	break;

  case "submitdm":

	if (($xoopsModuleConfig['anonsubmitvideo'] == 0) && ($uid == 0)) {
	  redirect_header("index.php", 0, _NOPERM);
	  exit();
	}

    if (isset($_POST['kw'])) {
      $kw = $myts->makeTboxData4Save($_POST['kw']);
    }

    $searchTerms = $kw;
    $kwplus = str_replace(" ","+",$kw);

	include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	
	$vsrchmax = $xoopsModuleConfig['videosearchresultsmax'];
	$vdefaultheight = 257;
	$vdefaultwidth = 420;
	$vsrchoverlaysize = $xoopsModuleConfig['videosearchoverlaysize'];
	$vsrchoverlayheight = ($vdefaultheight * ($vsrchoverlaysize / 100));
	$vsrchoverlaywidth = ($vdefaultwidth * ($vsrchoverlaysize / 100));
	$vsrchoverlaywindowheight = ($vsrchoverlayheight + 60);
	$vsrchoverlaywindowwidth = ($vsrchoverlaywidth + 10);
	$vsrchoverlayxoffset = $xoopsModuleConfig['videosearchoverlayxoffset'];
	$vsrchoverlayyoffset = $xoopsModuleConfig['videosearchoverlayyoffset'];
	$vsobkgrdclr = $xoopsModuleConfig['vsobackgroundcolor'];
	$vsobrdrclr = $xoopsModuleConfig['vsobordercolor'];
	$vsobrdrsize = $xoopsModuleConfig['vsobordersize'];
	$dmsearch = _MI_VP_DMSEARCH;
	$keywords = _MI_VP_KEYWORDS;

	$svform = new XoopsThemeForm(_MI_VP_SUBMITHEADER, "submitvideoform", xoops_getenv('PHP_SELF'));
	$svform->addElement(new XoopsFormText(_MI_VP_CODE, 'code', 20, 20, $code), true);
	$svform->addElement(new XoopsFormText(_MI_VP_TITLE, 'title', 50, 4095, $title), true);
	$svform->addElement(new XoopsFormText(_MI_VP_ARTIST, 'artist', 50, 4095, $artist), false);
	$svform->addElement(new XoopsFormTextArea(_MI_VP_DESC, 'description', $description, 4, 50), false);
    $svform->addElement(new XoopsFormText(_MI_VP_EMBEDCODE, 'embedcode', 50, 4095, $embedcode), true);
    $svform->addElement(new XoopsFormText(_MI_VP_THUMB, 'thumb', 50, 4095, $thumb), true);
    $svform->addElement(new XoopsFormHidden('service', '2'));

	$sdmvform = new XoopsThemeForm(_MI_VP_SEARCHDMHEADER, "searchdailymotionvideoform", xoops_getenv('PHP_SELF'));
	$sdmvform->addElement(new XoopsFormText(_MI_VP_KEYWORDS, 'kw', 50, 50, $kw), true);
	$sdmvbutton_tray = new XoopsFormElementTray('' ,'');
	$sdmvbutton_tray->addElement(new XoopsFormButton('', 'submitdm', _MI_VP_SEARCH, 'submit'));
	$sdmvform->addElement($sdmvbutton_tray);
    
	if ($xoopsModuleConfig['videousecats']) {
	  $mytree = new VideoTubeTree($xoopsDB->prefix("vp_categories"), "cid", "pid");
		ob_start();
		$mytree -> makeMySelBox('title','weight',0, 2, 'cid', "");
		$svform -> addElement(new XoopsFormLabel(_MI_VP_CATEGORY, ob_get_contents()));
		ob_end_clean();
      $svform->addElement($category_select);
	}
    
	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'postdm', _MI_VP_SUBMIT, 'submit'));
	$button_tray->addElement(new XoopsFormButton('', 'submitdm', _MI_VP_RESET, 'reset'));
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
    echo 'px; cursor: move;}</style>';

          echo '<script src="';
          echo 'include/dailymotion.js" type="text/javascript"></script>'; 
          
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
          echo '<div>';
          echo '<div align="center">';
          echo '<table width="100%">';
          echo '<tr>';
          echo '<td class="odd" align="center" valign="top">';
          $sdmvform->display();
          echo '</td>';
          echo '</tr>';
          echo '</table>';
          echo '</div>';
               
    if (!$dmpage) {
      $dmpage = 1;
    }	
    
    $searchresultxml = 'http://www.dailymotion.com/rss/tag/'.$kwplus.'/'.$dmpage;
    if(!$xml=simplexml_load_file($searchresultxml)){
      trigger_error('Error reading XML file',E_USER_ERROR);
    }

    foreach ($xml->channel as $channel) {
      $cdm = $channel->children('http://www.dailymotion.com/dmrss');
      $prevhref = '';
      $nexthref = '';
      foreach ($cdm->link as $link) {
        $attr = $link->attributes();
        $rel = $attr['rel'];
        $href = $attr['href'];
        if ($rel=='prev') {
          $prevhref = $href;
          $dmpagetemp = array();
          $dmpagetemp = explode("/", $prevhref);
          $kw = $dmpagetemp[5];
          $dmpageprev = $dmpagetemp[6];
        }elseif ($rel=='next') {
          $nexthref = $href;
          $dmpagetemp = array();
          $dmpagetemp = explode("/", $nexthref);
          $kw = $dmpagetemp[5];
          $dmpagenext = $dmpagetemp[6];
        }
      }
    }
    
    echo '<table cellspacing=0><tr><td style="border-bottom: 3px double #000000" align=left valign=middle><b>"';
    echo $searchTerms;
    echo '"</b> video results<b> Page ';
    echo $dmpage;
    echo '</td>';
    if ($prevhref){
      echo '<td style="border-bottom: 3px double #000000" align="right">';
      echo '<FORM>';
      echo '<INPUT style="font-size:80%" TYPE="BUTTON" VALUE="'._MI_VP_PREVPAGELABEL.'" ONCLICK="window.location.href=\'dailymotionsearch.php?op=submitdm&dmpage=';
      echo $dmpageprev;
      echo '&kw=';
      echo $kwplus;
      echo '\'"></form></td>';    
    }
    if ($nexthref){
      echo '<td style="border-bottom: 3px double #000000" align="right">';
      echo '<FORM>';
      echo '<INPUT style="font-size:80%" TYPE="BUTTON" VALUE="'._MI_VP_NEXTPAGELABEL.'" ONCLICK="window.location.href=\'dailymotionsearch.php?op=submitdm&dmpage=';
      echo $dmpagenext;
      echo '&kw=';
      echo $kwplus;
      echo '\'"></form></td>';    
    }
    echo '</tr></table>';
        
    foreach ($xml->channel->item as $item) {

      // get nodes in imedia: namespace for imedia information
      $imedia = $item->children('http://search.yahoo.com/mrss');
      $iitunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');
      $idm = $item->children('http://www.dailymotion.com/dmrss');
      
      $vtitle = $item->title;
      $vtitleesc = addslashes($vtitle);
      $publish = $item->pubDate;
      
      $attrs = $imedia->thumbnail->attributes();
      $embedattrs = $imedia->group->content[0]->attributes();
      $embedurl = $embedattrs['url'];
      $thumburl = $attrs['url'];
      $thumbwidth = $attrs['width'];
      $thumbheight = $attrs['height']; 
      
      $desc = $iitunes->summary;
      $author = $iitunes->author;
      
      $views = $idm->views;
      $relative = $idm->relativeDate;
      $vcode = $idm->id;
         
      echo '<table><tr><td width=125px align=center valign=middle style="border: 1px solid #ffffff">';
      echo '<a href="javascript:playVid(\'';
      echo $vcode;
      echo '\',\'';
      echo $embedurl;
      echo '\',\'';
      echo $vtitleesc;
      echo '\',\'';
      echo $thumburl;
      echo '\',';
      echo $vsrchoverlayheight;
      echo ',';
      echo $vsrchoverlaywidth;
	  echo ');" onclick="javascript:getMousePos(event,'.$vsrchoverlayxoffset.','.$vsrchoverlayyoffset.');"><img src="';
      echo $thumburl;
      echo '" width = "120"></a>';
      echo '</td><td align=left valign=top style="border: 1px solid #ffffff">';
      echo '<b>'._MI_VP_TITLE.':</b> ';
      echo $vtitle;
      echo '<br>';
      echo '<b>'._MI_VP_FORMDESC.':</b> ';
      echo $desc;
      echo '<br>';
      echo '<b>'._MI_VI_AUTHOR.':</b> ';
      echo $author;
      echo '<br>';
      echo '<b>'._MI_VI_VIEWS.':</b> ';
      echo $views;
      echo '<br>';
      echo '<b>'._MI_VP_FORMPUB.':</b> ';
      echo $publish;
      echo '<br>';
      echo '<b>'._MI_VP_FORMREL.':</b> ';
      echo $relative;
      echo '<br>';
     echo '</td></tr></table>';
    }
    echo '</div>'; 
    echo '<div id="vidPane" class="vidFrame"></div>';
    echo '</div>';
    echo '<script language="JavaScript" type="text/javascript">';
    echo 'var savedTarget=null;';
    echo 'var orgCursor=null;';
    echo 'var dragOK=true;';
    echo 'var dragXoffset=0;';
    echo 'var dragYoffset=0;';
    echo 'vidPaneID = document.getElementById("vidPane");';
    echo '</script>';

	break;

  case "postdm":
	
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
    $form_embedcode = $myts->addSlashes($_POST['embedcode']);
    $form_thumb = $myts->addSlashes($_POST['thumb']);
    $form_service = $myts->addSlashes($_POST['service']);
	
	if ($xoopsModuleConfig['videousecats']) {
	  $selectedcategory = $myts->addSlashes($_POST['cid']);
	} else {
	  $selectedcategory = 0;
	}
	
    $sqlcommandline = "INSERT INTO ".$xoopsDB->prefix("vp_videos") . "(pub, uid, cid, date, code, title, artist, description, embedcode, thumb, service) VALUES ($pub,$uid,$selectedcategory,$now,'$form_code','$form_title','$form_artist','$form_description','$form_embedcode','$form_thumb','$form_service')";
    $done = $xoopsDB->queryF($sqlcommandline);

	if($done) {
	  if (($xoopsModuleConfig['autoapprovesubmitvideo'] == 1) && ($uid > 0)) {
	    redirect_header("dailymotionsearch.php?op=searchdm",2,_MI_VP_AUTOSUBMITSUCCESS);
	  } else {
	    redirect_header("dailymotionsearch.php?op=searchdm",2,_MI_VP_SUBMITSUCCESS);
	  }
	} else {
	  redirect_header("index.php",2,_MI_VP_SUBMITERROR);
	}
    break;
}

include(XOOPS_ROOT_PATH."/footer.php");

?>
