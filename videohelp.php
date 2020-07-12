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
//  File:       videohelp.php                                                //
//  Version:    1.84                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.0  Initial Release 04/14/2008                                  //
//  ***                                                                      //
//  Version 1.1  04/23/2008                                                  //
//  Bug Fix: Change certain includes to include_once                         //
//  New: Change include paths for 2.2.x compatibility                        //
//  New: Add caution regarding videos codes beginning with a dash            //
//  Note: E-mail contact address change                                      //
//  ***                                                                      //
//  Version 1.5  05/26/2008                                                  //
//  New: Change to reflect new search and submission features                //
//  ***                                                                      //
//  Version 1.6  08/01/2008                                                  //
//  New: Change to reflect new search features and addition of categories    //
//  ***                                                                      //
//  Version 1.61  08/02/2008                                                 //
//  New: Change text to variable defines in modinfo language file            //
//  New: Update screen shots to reflect appearance of new portable video     //
//       preview overlay                                                     //
//  ***                                                                      //
//  Version 1.64  09/06/2008                                                 //
//  New: Add instructions for Manual Submission                              //
//  ***                                                                      //
//  Version 1.65  09/12/2008                                                 //
//  New: Update screen shots to include addition of local description field  //
//  ***                                                                      //
//  Version 1.7 RC1  09/14/2008                                              //
//  New: Add page menu                                                       //
//  ***                                                                      //
//  Version 1.84  01/18/2009                                                 //
//  Add language support                                                     //
//  ***                                                                      //

include_once '../../mainfile.php';
include_once XOOPS_ROOT_PATH."/header.php";

if (file_exists('language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php')) {
  include_once 'language/'.$GLOBALS['xoopsConfig']['language'].'/modinfo.php';
}else{
  include_once 'language/english/modinfo.php';
}

global $xoopsOption, $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModuleConfig;

  include XOOPS_ROOT_PATH.'/modules/videotube/class/videotubedisplay.php';
  $videodisplay = new VideoTubeDisp(PHP_VERSION,$xoopsModuleConfig['dailymotionenable'],
                                    $xoopsModuleConfig['metacafeenable'],$xoopsModuleConfig['bliptvenable'],
                                    $xoopsModuleConfig['manualsubmitenable'],$xoopsModuleConfig['managevideosenable'],
                                    _MI_VP_PMNAME1,_MI_VP_PMNAME2,_MI_VP_PMNAME3,_MI_VP_PMNAME4,
                                    _MI_VP_PMNAME5,_MI_VP_PMNAME6,_MI_VP_PMNAME7,_MI_VP_PMNAME8);
  if ($xoopsModuleConfig['pagemenuenable']) {
    echo $videodisplay->renderPageMenu();
  }

?>
<table align="center" width="60%" cellpadding="5" cellspacing="5">
<tr><td align="center"><h3><?php echo _MI_VH_PAGETITLE; ?></h3></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH1; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo1.jpg" width="476" height="311" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH2; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo2.jpg" width="476" height="405" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH3; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo3.jpg" width="475" height="418" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH4; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo4.jpg" width="476" height="403" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH5; ?></td></tr>
<tr><td align="center">&nbsp;</td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH6; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo6.jpg" width="448" height="328" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH7; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo7.jpg" width="451" height="452" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH8; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo8.jpg" width="448" height="417" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH9; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo9.jpg" width="449" height="412" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH10; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo10.jpg" width="447" height="327" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH11; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo11.jpg" width="449" height="436" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH12; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo12.jpg" width="447" height="328" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH13; ?></td></tr>
<tr><td align="center"><img src="images/submitvideo13.jpg" width="448" height="330" style="border: 1px solid #000000"></td></tr>
<tr><td align="left"><?php echo _MI_VH_PARAGRAPH14; ?></td></tr>

</table>

<?php
include(XOOPS_ROOT_PATH."/footer.php");

?>