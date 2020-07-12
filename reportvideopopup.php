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
//  Date:       01/18/2009                                                   //
//  Module:     Video Tube                                                   //
//  File:       index.php                                                    //
//  Version:    1.84                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.84  Initial Release 01/18/2008                                 //
//  ***                                                                      //

    include_once("../../mainfile.php");

    if (file_exists(XOOPS_ROOT_PATH.'/modules/videotube/language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php')) {
      include_once XOOPS_ROOT_PATH.'/modules/videotube/language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php';
    }else{
      include_once XOOPS_ROOT_PATH.'/modules/videotube/language/english/modinfo.php';
    }
    
	global $xoopsOption, $xoopsDB, $xoopsUser, $xoopsModule, $xoopsConfig, $xoopsModuleConfig;

    $module_handler =& xoops_gethandler('module');
    $module         =& $module_handler->getByDirname('videotube');
    $config_handler =& xoops_gethandler('config');
    $moduleConfig   =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

    $op = NULL;
    $title = NULL;

    if (isset($_GET['vid'])) $vid = $_GET['vid'];

    if (isset($_POST['postreport'])) $op = 'postreport';
	if (isset($_POST['reportclose'])) $op = 'reportclose';
	
	if (isset($_GET['op'])) $op = $_GET['op'];
    if (isset($_POST['op'])) $op = $_POST['op'];
	
	$myts =& MyTextSanitizer::getInstance();

    /**
     * Include Smarty template
     * engine and initialize it.
     */
	require_once XOOPS_ROOT_PATH.'/class/template.php'; 
	$xoopsTpl = new XoopsTpl();
	$xoopsTpl->xoops_setCaching(0);
	/**
	 * Assign important values... :)
	 */
	$xoopsTpl->assign(array('xoops_theme' => $xoopsConfig['theme_set'], 'xoops_imageurl' => XOOPS_THEME_URL.'/'.$xoopsConfig['theme_set'].'/', 'xoops_themecss'=> xoops_getcss($xoopsConfig['theme_set']), 'xoops_requesturi' => htmlspecialchars($GLOBALS['xoopsRequestUri'], ENT_QUOTES), 'xoops_sitename' => htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES), 'xoops_slogan' => htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)));
	$xoopsTpl->assign('xoops_js', '<script type="text/javascript" src="'.XOOPS_URL.'/include/xoops.js"></script>');
	if ($xoopsUser != '') {
		$xoopsTpl->assign(array('xoops_isuser' => true, 'xoops_userid' => $xoopsUser->getVar('uid'), 'xoops_uname' => $xoopsUser->getVar('uname'), 'xoops_isadmin' => $xoopsUserIsAdmin));
	} else {
		$xoopsTpl->assign(array('xoops_isuser' => false, 'xoops_isadmin' => false));
	}
	
	if (is_file('style/videotube.css')) 
	{
		$xoopsTpl->assign('themecss', 'style/videotube.css');
	}else{
		$xoopsTpl->assign('themecss', xoops_getcss());
	}


$xoopsTpl->assign('lang_reportvideotitle', 'Report A Video');

$xoopsTpl->assign('config', $xoopsModuleConfig);

switch($op) {
  case "generateform":

    $result = $xoopsDB->queryF("SELECT id, uid, cid, code, title, artist, service FROM ".$xoopsDB->prefix("vp_videos")." WHERE id=".$vid."");
	$video = $xoopsDB->fetcharray($result);
	
	$xoopsTpl->assign('code', $video['code']);
	$xoopsTpl->assign('title', $video['title']);
	
	$xoopsTpl->assign('codelabel', _MI_VI_VIDEOCODE);
	$xoopsTpl->assign('titlelabel', _MI_VI_VIDEOTITLE);

	include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	$reportform = new XoopsThemeForm(_MI_VP_REPORTHEADER, "reportvideoform", xoops_getenv('PHP_SELF'));
	$reportform->addElement(new XoopsFormHidden('vid', $vid));
	$radio1_ele = new XoopsFormRadio(_MI_VP_REPORTFORMRADIO,'reportreason', 1); 
       $list = array(); 
       $list[1] = _MI_VP_REPORTREASON1."<br />"; 
       $list[2] = _MI_VP_REPORTREASON2."<br />";
	   $list[3] = _MI_VP_REPORTREASON3."<br />";
	   $list[4] = _MI_VP_REPORTREASON4."<br />";
	   $list[5] = _MI_VP_REPORTREASON5; 
       $radio1_ele->addOptionArray($list); 
	$reportform->addElement($radio1_ele);
	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'postreport', _MI_VP_SUBMITREPORT, 'submit'));
		
	$button_cancel = new XoopsFormButton('', '', _MI_VP_CANCEL, 'button');
	$button_cancel->setExtra(' onclick=\'javascript: self.close()\'');
	$button_tray->addElement($button_cancel);
	
	$reportform->addElement($button_tray);

    $xoopsTpl->assign('reportform', $reportform->display());

$xoopsTpl->xoops_setCaching(0);
$xoopsTpl->display('db:reportvideopopup.html');

    break;
	
  case "postreport":

    //echo "made it to post report";
	//exit();
	$vid = $myts->addSlashes($_POST['vid']);
    $reasoncode = $myts->addSlashes($_POST['reportreason']);

    $result = $xoopsDB->queryF("SELECT repid, numreports FROM ".$xoopsDB->prefix("vp_reports")." WHERE vid=".$vid." && reasoncode=".$reasoncode."");
	$numresults = mysql_num_rows($result);
	$video = $xoopsDB->fetcharray($result);

    switch($reasoncode) {
	  case 1:
	   $reasontext = _MI_VP_REPORTREASON1;
	   break;
	  case 2: 
       $reasontext = _MI_VP_REPORTREASON2;
	   break;
	  case 3: 
	   $reasontext = _MI_VP_REPORTREASON3;
	   break;
	  case 4: 
	   $reasontext = _MI_VP_REPORTREASON4;
	   break;
	  default: 
	   $reasontext = _MI_VP_REPORTREASON5; 
    }
	if ($numresults) {
      $numreports = $video['numreports'] + 1;
	  $repid = $video['repid'];
	  $sqlcommandline = "UPDATE ".$xoopsDB->prefix("vp_reports")." SET numreports='".$numreports."' WHERE repid='".$repid."'";
	  $done = $xoopsDB->queryF($sqlcommandline);
	  mysql_free_result($result);
	}else{
      $numreports = 1;
      $sqlcommandline = "INSERT INTO ".$xoopsDB->prefix("vp_reports")."(vid, reasoncode, reasontext, numreports) VALUES ($vid, $reasoncode, '$reasontext', $numreports)";
      $done = $xoopsDB->queryF($sqlcommandline);
	  mysql_free_result($result);
	}
	
	if($done) {
	  redirect_header("reportvideopopup.php?op=reportclose",3,_MI_VP_REPORTSUCCESS);
	} else {
	  redirect_header("reportvideopopup.php?op=reportclose",3,_MI_VP_REPORTERROR);
	}

$xoopsTpl->xoops_setCaching(0);
$xoopsTpl->display('db:reportvideopopuppost.html');
	
    break;
  
  case "reportclose":

	echo "<script>self.close()</script>";

    break;

  default:
    echo "Error occurred";
	
	break;
}
	
?>