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
//  File:       admin/admin_header.php                                       //
//  Version:    1.82                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.0  Initial Release 04/14/2008                                  //
//  ***                                                                      //
//  Version 1.1  04/23/2008                                                  //
//  New: Change include paths for 2.2.x compatibility                        //
//  Note: E-mail contact address change                                      //
//  ***                                                                      //
//  Version 1.6  08/01/2008                                                  //
//  Bug Fix: Remove header include for 2.2.x compatibility                   //
//  ***                                                                      //
//  Version 1.81  01/06/2009                                                 //
//  Rewrite category management admin interface                              //
//  Add language support                                                     //
//  ***                                                                      //
//  Version 1.82  01/10/2009                                                 //
//  Update from XoopsTree to VideoTubeTree                                   //
//  ***                                                                      //

include_once '../../../mainfile.php';
include_once '../class/videotubetree.php';
include_once XOOPS_ROOT_PATH."/class/xoopsmodule.php";
include_once XOOPS_ROOT_PATH."/include/cp_functions.php";
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";


if ($xoopsUser) {
  $xoopsModule = XoopsModule::getByDirname("videotube");
  if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) { 
    redirect_header(XOOPS_URL."/",3,_NOPERM);;
    exit();
  }
} else {
  redirect_header(XOOPS_URL."/",3,_NOPERM);
  exit();
}

if (file_exists(XOOPS_ROOT_PATH.'/modules/videotube/language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php')) {
  include_once XOOPS_ROOT_PATH.'/modules/videotube/language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php';
}else{
  include_once XOOPS_ROOT_PATH.'/modules/videotube/language/english/modinfo.php';
}

if (file_exists(XOOPS_ROOT_PATH.'/modules/videotube/language/'.$GLOBALS['xoopsConfig']['language'].'/admin.php')) {
  include_once XOOPS_ROOT_PATH.'/modules/videotube/language/'.$GLOBALS['xoopsConfig']['language'].'/admin.php';
}else{
  include_once XOOPS_ROOT_PATH.'/modules/videotube/language/english/admin.php';
}

?>
