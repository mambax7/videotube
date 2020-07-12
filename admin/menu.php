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
//  File:       admin/menu.php                                               //
//  Version:    1.81                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.0  Initial Release 04/14/2008                                  //
//  ***                                                                      //
//  Version 1.1  04/23/2008                                                  //
//  Note: E-mail contact address change                                      //
//  ***                                                                      //
//  Version 1.6  08/01/2008                                                  //
//  New: Change 'Add A Video' link                                           //
//  New: Add 'Category Listing' selection                                    //
//  New: Add 'Add A Category' selection                                      //
//  ***                                                                      //
//  Version 1.63  08/23/2008                                                 //
//  New: Removed 'Add A Video' selection                                     //
//  ***                                                                      //
//  Version 1.81  01/06/2009                                                 //
//  Code cleanup and housekeeping                                            //
//  Rewrite category management admin interface                              //
//  ***                                                                      //

$adminmenu[1]['title'] = _MI_VP_ADMENU1;
$adminmenu[1]['link'] = "admin/index.php";
$adminmenu[2]['title'] = _MI_VP_ADMENU2;
$adminmenu[2]['link'] = "admin/index.php?op=submission";
$adminmenu[3]['title'] = _MI_VP_ADMENU3;
$adminmenu[3]['link'] = "admin/index.php?op=published";
$adminmenu[4]['title'] = _MI_VP_ADMENU4;
$adminmenu[4]['link'] = "admin/index.php?op=catmanage";
$adminmenu[5]['title'] = _MI_VP_ADMENU5;
$adminmenu[5]['link'] = "admin/index.php?op=videoreport";

?>
