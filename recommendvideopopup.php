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


$xoopsTpl->assign('lang_recommendvideotitle', 'Recommend Video To A Friend');

$xoopsTpl->assign('config', $xoopsModuleConfig);
	
$xoopsTpl->xoops_setCaching(0);
$xoopsTpl->display('db:recommendvideopopup.html');
?>