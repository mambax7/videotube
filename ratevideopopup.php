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
//  Date:       01/25/2009                                                   //
//  Module:     Video Tube                                                   //
//  File:       ratevideopopup.php                                           //
//  Version:    1.85                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.85  Initial Release 01/25/2008                                 //
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

    if (isset($_POST['postrating'])) $op = 'postrating';
	if (isset($_POST['ratingformclose'])) $op = 'ratingformclose';
	
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


$xoopsTpl->assign('lang_ratevideotitle', 'Rate Video');

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
	$rateform = new XoopsThemeForm(_MI_VP_RATEHEADER, "ratevideoform", xoops_getenv('PHP_SELF'));
	$rateform->addElement(new XoopsFormHidden('vid', $vid));
	
	if ($xoopsUser) {
      $uid = $xoopsUser->getVar('uid');
    } else {
      $uid = 0;
    }

    $rateform->addElement(new XoopsFormHidden('uid', $uid));
	
	$radio1_ele = new XoopsFormRadio(_MI_VP_RATEFORMRADIO,'raterank', 3); 
       $list = array(); 
       $list[1] = _MI_VP_RATERANK1."<br />"; 
       $list[2] = _MI_VP_RATERANK2."<br />";
	   $list[3] = _MI_VP_RATERANK3."<br />";
	   $list[4] = _MI_VP_RATERANK4."<br />";
	   $list[5] = _MI_VP_RATERANK5; 
       $radio1_ele->addOptionArray($list); 
	$rateform->addElement($radio1_ele);
	$button_tray = new XoopsFormElementTray('' ,'');
	$button_tray->addElement(new XoopsFormButton('', 'postrating', _MI_VP_SUBMITRATING, 'submit'));
		
	$button_cancel = new XoopsFormButton('', '', _MI_VP_CANCEL, 'button');
	$button_cancel->setExtra(' onclick=\'javascript: self.close()\'');
	$button_tray->addElement($button_cancel);
	
	$rateform->addElement($button_tray);

    $xoopsTpl->assign('rateform', $rateform->display());

$xoopsTpl->xoops_setCaching(0);
$xoopsTpl->display('db:ratevideopopup.html');

    break;
	
  case "postrating":

	$vid = $myts->addSlashes($_POST['vid']);
	$uid = $myts->addSlashes($_POST['uid']);
    $rating = $myts->addSlashes($_POST['raterank']);
	
	$ip = getenv("REMOTE_ADDR");
	$datetimeunix = time();
	
	//Check for submitter voting for own submission
    $result2 = mysql_query("SELECT * FROM ".$xoopsDB->prefix("vp_videos")." WHERE vid=$vid");
	$row2 = mysql_fetch_array($result2);
	$suid = $row2['uid'];
	if ($uid == $suid && $uid > 0) redirect_header("ratevideopopup.php?op=ratingformclose",3,_MI_VP_SUBMITTERNORATE);
		
	//Check for voting twice
    $result3 = mysql_query("SELECT * FROM ".$xoopsDB->prefix("vp_votedata")." WHERE rvid=$vid && (rhostname='$ip' || ruid=$uid)");
	//$row3 = mysql_fetch_array($result3);
	$numresult = mysql_num_rows($result3);
	if ($numresult > 0) redirect_header("ratevideopopup.php?op=ratingformclose",3,_MI_VP_NORATETWICE);
	
	//Check for anonymous user voting twice

    $sqlcommandline = "INSERT INTO ".$xoopsDB->prefix("vp_votedata")."(rvid, ruid, rating, rhostname, rtime) VALUES ($vid, $uid, $rating, '$ip', $datetimeunix)";
    $done = $xoopsDB->queryF($sqlcommandline);
	
	if($done) {
	  redirect_header("ratevideopopup.php?op=ratingformclose",3,_MI_VP_RATESUCCESS);
	} else {
	  redirect_header("ratevideopopup.php?op=ratingformclose",3,_MI_VP_RATEERROR);
	}

    $xoopsTpl->xoops_setCaching(0);
    $xoopsTpl->display('db:ratevideopopuppost.html');
	
    break;
  
  case "ratingformclose":

	echo "<script>self.close()</script>";

    break;

  default:
    echo "Error occurred";
	
	break;
}
	
?>