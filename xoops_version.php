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
//  Date:       10/07/2009                                                   //
//  Module:     Video Tube                                                   //
//  File:       xoops_version.php                                            //
//  Version:    1.86                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.0  Initial Release 04/14/2008                                  //
//  ***                                                                      //
//  Version 1.1  04/23/2008                                                  //
//  New: Update module version                                               //
//  New: Add auto approve registered user submission config parameter        //
//  Note: E-mail contact address change                                      //
//  ***                                                                      //
//  Version 1.3  05/09/2008                                                  //
//  Bug Fix: Corrected Video Display Format parameter default value          //
//  New: Update module version                                               //
//  ***                                                                      //
//  Version 1.4  05/21/2008                                                  //
//  New: Update module version                                               //
//  New: Add View By User submenu & template                                 //
//  ***                                                                      //
//  Version 1.5  05/26/2008                                                  //
//  New: Update module version                                               //
//  New: Add video submission template                                       //
//  ***                                                                      //
//  Version 1.6  08/01/2008                                                  //
//  New: Update module version                                               //
//  New: Add video categories                                                //
//  New: Add play video blocks                                               //
//  ***                                                                      //
//  Version 1.61  08/02/2008                                                 //
//  New: Eliminate What's New page                                           //
//  New: Change Submit A Video submenu item to Search/Submit                 //
//  New: Add config parameters to define video search preview overlay        //
//  ***                                                                      //
//  Version 1.62  08/09/2008                                                 //
//  New: Add display of number of videos in each category                    //
//  New: Add Video Tube to XOOPS site search                                 //
//  New: Add 1-column and 2-column video display format options              //
//  New: Add Display All Categories preference parameter                     //
//  ***                                                                      //
//  Version 1.63  08/23/2008                                                 //
//  New: Add page navigation option                                          //
//  New: Add global comments                                                 //
//  New: Add DailyMotion                                                     //
//  New: Add PHP version compare                                             //
//  ***                                                                      //
//  Version 1.64  09/06/2008                                                 //
//  New: Breakout search/submit functions by service from userfeatures.php   //
//  ***                                                                      //
//  Version 1.65  09/12/2008                                                 //
//  New: Add Manage My Videos submenu & associated preference parameter      //
//  ***                                                                      //
//  Version 1.7 RC1  09/14/2008                                              //
//  New: Add Featured blocks                                                 //
//  ***                                                                      //
//  Version 1.8  09/29/2008                                                  //
//  Bug Fix: Correct typo in userfeature.php submenu link                    //
//  ***                                                                      //
//  Version 1.81  01/06/2009                                                 //
//  Bug Fix: Clean up functions causing debug notices                        //
//  Rewrite category management admin interface                              //
//  ***                                                                      //
//  Version 1.82  01/10/2009                                                 //
//  Add preview offset preference parameters                                 //
//  Remove preview default psoition preference parameters                    //
//  ***                                                                      //
//  Version 1.83  01/12/2009                                                 //
//  Add number of inferred sub categories displayed                          //
//  ***                                                                      //
//  Version 1.86  10/07/2009                                                 //
//  Update YouTube JSON feed expected format                                 //
//  Add template assignments to support facebook sharer                      //
//  Add Preference parameter to control description copy to local db         //
//  ***                                                                      //

$VIDEOTUBE_DIRNAME   = basename( dirname( __FILE__ ) );

global $xoopsModuleConfig;

$modversion['name'] = "Video Tube";
$modversion['version'] = 1.86;
$modversion['description'] = "Video Tube";
$modversion['author'] = "tank";
$modversion['credits'] = "";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 1;
$modversion['image'] = "images/VideoTube.png";
$modversion['dirname'] = "videotube";
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Search
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "video_search";

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'vid';
$modversion['comments']['pageName'] = 'index.php';

// Menu/Sub Menu
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _MI_VP_SMNAME1;
$modversion['sub'][1]['url'] = "userfeatures.php?op=viewuserlist";
$modversion['sub'][2]['name'] = _MI_VP_SMNAME2;
$modversion['sub'][2]['url'] = "youtubesearch.php?op=submit";

$smenu = 3;
if (version_compare(PHP_VERSION, '5.0.0', '<')) {
  if ($xoopsModuleConfig['manualsubmitenable']) {
    $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME7;
    $modversion['sub'][$smenu]['url'] = "userfeatures.php?op=manualsubmit";
    $smenu++;
    if ($xoopsModuleConfig['managevideosenable']) {
      $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME8;
      $modversion['sub'][$smenu]['url'] = "userfeatures.php?op=listmyvideos";
      $smenu++;
    }
    $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME3;
    $modversion['sub'][$smenu]['url'] = "videohelp.php";
    $smenu++;
  }else{
    if ($xoopsModuleConfig['managevideosenable']) {
      $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME8;
      $modversion['sub'][$smenu]['url'] = "userfeatures.php?op=listmyvideos";
      $smenu++;
    }
    $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME3;
    $modversion['sub'][$smenu]['url'] = "videohelp.php";
  }
}else{
  if ($xoopsModuleConfig['dailymotionenable']) {
    $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME4;
    $modversion['sub'][$smenu]['url'] = "dailymotionsearch.php?op=searchdm";
    $smenu++;
  }
  if ($xoopsModuleConfig['metacafeenable']) {
    $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME5;
    $modversion['sub'][$smenu]['url'] = "metacafesearch.php?op=searchmc";
    $smenu++;
  }
  if ($xoopsModuleConfig['bliptvenable']) {
    $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME6;
    $modversion['sub'][$smenu]['url'] = "bliptvsearch.php?op=searchbt";
    $smenu++;
  }
  if ($xoopsModuleConfig['manualsubmitenable']) {
    $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME7;
    $modversion['sub'][$smenu]['url'] = "userfeatures.php?op=manualsubmit";
    $smenu++;
  }
  if ($xoopsModuleConfig['managevideosenable']) {
    $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME8;
    $modversion['sub'][$smenu]['url'] = "userfeatures.php?op=listmyvideos";
    $smenu++;
  }
  $modversion['sub'][$smenu]['name'] = _MI_VP_SMNAME3;
  $modversion['sub'][$smenu]['url'] = "videohelp.php";
}

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "vp_videos";
$modversion['tables'][1] = "vp_categories";

// Update to current version
$modversion['onUpdate'] = 'include/module.php';

// Templates
$modversion['templates'][1]['file'] = 'video_display.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'userindexlist.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'reportvideopopup.html';
$modversion['templates'][3]['description'] = '';
$modversion['templates'][4]['file'] = 'recommendvideopopup.html';
$modversion['templates'][4]['description'] = '';
$modversion['templates'][5]['file'] = 'ratevideopopup.html';
$modversion['templates'][5]['description'] = '';
$modversion['templates'][6]['file'] = 'reportvideopopuppost.html';
$modversion['templates'][6]['description'] = '';

// Blocks
$modversion['blocks'][1]['file'] = "vp_mostviewed_h.php";
$modversion['blocks'][1]['name'] = _MI_VP_BNAME1;
$modversion['blocks'][1]['description'] = _MI_VP_BNAME1DSC;
$modversion['blocks'][1]['show_func'] = "videotube_mostviewed_h";
$modversion['blocks'][1]['edit_func'] = "mostviewed_edit_h";
$modversion['blocks'][1]['options']	= "3";
$modversion['blocks'][1]['template'] = "viewed_block_h.html";

$modversion['blocks'][2]['file'] = "vp_random_h.php";
$modversion['blocks'][2]['name'] = _MI_VP_BNAME2;
$modversion['blocks'][2]['description'] = _MI_VP_BNAME2DSC;
$modversion['blocks'][2]['show_func'] = "videotube_random_h";
$modversion['blocks'][2]['edit_func'] = "random_edit_h";
$modversion['blocks'][2]['options']	= "3";
$modversion['blocks'][2]['template'] = "random_block_h.html";

$modversion['blocks'][3]['file'] = "vp_latest_h.php";
$modversion['blocks'][3]['name'] = _MI_VP_BNAME3;
$modversion['blocks'][3]['description'] = _MI_VP_BNAME3DSC;
$modversion['blocks'][3]['show_func'] = "videotube_latest_h";
$modversion['blocks'][3]['edit_func'] = "latest_edit_h";
$modversion['blocks'][3]['options']	= "3";
$modversion['blocks'][3]['template'] = "latest_block_h.html";

$modversion['blocks'][4]['file'] = "vp_mostviewed_v.php";
$modversion['blocks'][4]['name'] = _MI_VP_BNAME4;
$modversion['blocks'][4]['description'] = _MI_VP_BNAME4DSC;
$modversion['blocks'][4]['show_func'] = "videotube_mostviewed_v";
$modversion['blocks'][4]['edit_func'] = "mostviewed_edit_v";
$modversion['blocks'][4]['options']	= "3";
$modversion['blocks'][4]['template'] = "viewed_block_v.html";

$modversion['blocks'][5]['file'] = "vp_random_v.php";
$modversion['blocks'][5]['name'] = _MI_VP_BNAME5;
$modversion['blocks'][5]['description'] = _MI_VP_BNAME5DSC;
$modversion['blocks'][5]['show_func'] = "videotube_random_v";
$modversion['blocks'][5]['edit_func'] = "random_edit_v";
$modversion['blocks'][5]['options']	= "3";
$modversion['blocks'][5]['template'] = "random_block_v.html";

$modversion['blocks'][6]['file'] = "vp_latest_v.php";
$modversion['blocks'][6]['name'] = _MI_VP_BNAME6;
$modversion['blocks'][6]['description'] = _MI_VP_BNAME6DSC;
$modversion['blocks'][6]['show_func'] = "videotube_latest_v";
$modversion['blocks'][6]['edit_func'] = "latest_edit_v";
$modversion['blocks'][6]['options']	= "3";
$modversion['blocks'][6]['template'] = "latest_block_v.html";

$modversion['blocks'][7]['file'] = "vp_latest_play.php";
$modversion['blocks'][7]['name'] = _MI_VP_BNAME7;
$modversion['blocks'][7]['description'] = _MI_VP_BNAME7DSC;
$modversion['blocks'][7]['show_func'] = "videotube_latest_play";
$modversion['blocks'][7]['edit_func'] = "latest_edit_play";
$modversion['blocks'][7]['options']	= "100";
$modversion['blocks'][7]['template'] = "latest_block_play.html";

$modversion['blocks'][8]['file'] = "vp_mostviewed_play.php";
$modversion['blocks'][8]['name'] = _MI_VP_BNAME8;
$modversion['blocks'][8]['description'] = _MI_VP_BNAME8DSC;
$modversion['blocks'][8]['show_func'] = "videotube_mostviewed_play";
$modversion['blocks'][8]['edit_func'] = "mostviewed_edit_play";
$modversion['blocks'][8]['options']	= "100";
$modversion['blocks'][8]['template'] = "viewed_block_play.html";

$modversion['blocks'][9]['file'] = "vp_random_play.php";
$modversion['blocks'][9]['name'] = _MI_VP_BNAME9;
$modversion['blocks'][9]['description'] = _MI_VP_BNAME9DSC;
$modversion['blocks'][9]['show_func'] = "videotube_random_play";
$modversion['blocks'][9]['edit_func'] = "random_edit_play";
$modversion['blocks'][9]['options']	= "100";
$modversion['blocks'][9]['template'] = "random_block_play.html";

$modversion['blocks'][10]['file'] = "vp_latest_wall.php";
$modversion['blocks'][10]['name'] = _MI_VP_BNAME10;
$modversion['blocks'][10]['description'] = _MI_VP_BNAME10DSC;
$modversion['blocks'][10]['show_func'] = "videotube_latest_wall";
$modversion['blocks'][10]['edit_func'] = "latest_edit_wall";
$modversion['blocks'][10]['options']	= "3";
$modversion['blocks'][10]['template'] = "latest_block_wall.html";

$modversion['blocks'][11]['file'] = "vp_mostviewed_wall.php";
$modversion['blocks'][11]['name'] = _MI_VP_BNAME11;
$modversion['blocks'][11]['description'] = _MI_VP_BNAME11DSC;
$modversion['blocks'][11]['show_func'] = "videotube_mostviewed_wall";
$modversion['blocks'][11]['edit_func'] = "mostviewed_edit_wall";
$modversion['blocks'][11]['options']	= "3";
$modversion['blocks'][11]['template'] = "viewed_block_wall.html";

$modversion['blocks'][12]['file'] = "vp_random_wall.php";
$modversion['blocks'][12]['name'] = _MI_VP_BNAME12;
$modversion['blocks'][12]['description'] = _MI_VP_BNAME12DSC;
$modversion['blocks'][12]['show_func'] = "videotube_random_wall";
$modversion['blocks'][12]['edit_func'] = "random_edit_wall";
$modversion['blocks'][12]['options']	= "3";
$modversion['blocks'][12]['template'] = "random_block_wall.html";

$modversion['blocks'][13]['file'] = "vp_featured_h.php";
$modversion['blocks'][13]['name'] = _MI_VP_BNAME13;
$modversion['blocks'][13]['description'] = _MI_VP_BNAME13DSC;
$modversion['blocks'][13]['show_func'] = "videotube_featured_h";
$modversion['blocks'][13]['edit_func'] = "featured_edit_h";
$modversion['blocks'][13]['options'] = "0|0|0|0|0";
$modversion['blocks'][13]['template'] = "featured_block_h.html";

$modversion['blocks'][14]['file'] = "vp_featured_v.php";
$modversion['blocks'][14]['name'] = _MI_VP_BNAME14;
$modversion['blocks'][14]['description'] = _MI_VP_BNAME14DSC;
$modversion['blocks'][14]['show_func'] = "videotube_featured_v";
$modversion['blocks'][14]['edit_func'] = "featured_edit_v";
$modversion['blocks'][14]['options'] = "0|0|0|0|0";
$modversion['blocks'][14]['template'] = "featured_block_v.html";

$modversion['blocks'][15]['file'] = "vp_featured_play.php";
$modversion['blocks'][15]['name'] = _MI_VP_BNAME15;
$modversion['blocks'][15]['description'] = _MI_VP_BNAME15DSC;
$modversion['blocks'][15]['show_func'] = "videotube_featured_play";
$modversion['blocks'][15]['edit_func'] = "featured_edit_play";
$modversion['blocks'][15]['options'] = "100|0";
$modversion['blocks'][15]['template'] = "featured_block_play.html";

// Config Settings
$confassign = 1;

$modversion['config'][$confassign]['name'] = 'videodisplayformat';
$modversion['config'][$confassign]['title'] = '_MI_VP_VDISPLAYFORMAT';
$modversion['config'][$confassign]['description'] = '_MI_VP_VDISPLAYFORMATDSC';
$modversion['config'][$confassign]['formtype'] = 'select';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 3;
$modversion['config'][$confassign]['options'] = array('_MI_VP_VDISPFORMOPT1' => 1, '_MI_VP_VDISPFORMOPT2' => 2, '_MI_VP_VDISPFORMOPT3' => 3, '_MI_VP_VDISPFORMOPT4' => 4, '_MI_VP_VDISPFORMOPT5' => 5);

$confassign++;

$modversion['config'][$confassign]['name'] = 'videoautoplay';
$modversion['config'][$confassign]['title'] = '_MI_VP_VAUTOPLAY';
$modversion['config'][$confassign]['description'] = '_MI_VP_VAUTOPLAYDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 1;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videodisplayorder';
$modversion['config'][$confassign]['title'] = '_MI_VP_VDISPLAYORDER';
$modversion['config'][$confassign]['description'] = '_MI_VP_VDISPLAYORDERDSC';
$modversion['config'][$confassign]['formtype'] = 'select';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;
$modversion['config'][$confassign]['options'] = array('_MI_VP_VDISPORDOPT1' => 0, '_MI_VP_VDISPORDOPT2' => 1, '_MI_VP_VDISPORDOPT3' => 2, '_MI_VP_VDISPORDOPT4' => 3);

$confassign++;

$modversion['config'][$confassign]['name'] = 'videodisplaynumber';
$modversion['config'][$confassign]['title'] = '_MI_VP_VDISPLAYNUMBER';
$modversion['config'][$confassign]['description'] = '_MI_VP_VDISPLAYNUMBERDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;

$confassign++;

$modversion['config'][$confassign]['name'] = 'anonsubmitvideo';
$modversion['config'][$confassign]['title'] = '_MI_VP_VANONSUBMIT';
$modversion['config'][$confassign]['description'] = '_MI_VP_VANONSUBMITDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;

$confassign++;

$modversion['config'][$confassign]['name'] = 'autoapprovesubmitvideo';
$modversion['config'][$confassign]['title'] = '_MI_VP_VAUTOAPPROVESUBMIT';
$modversion['config'][$confassign]['description'] = '_MI_VP_VAUTOAPPROVESUBMITDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 1;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videosearchresultsmax';
$modversion['config'][$confassign]['title'] = '_MI_VP_VSEARCHRESULTSMAX';
$modversion['config'][$confassign]['description'] = '_MI_VP_VSEARCHRESULTSMAXDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 25;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videosearchresultsformat';
$modversion['config'][$confassign]['title'] = '_MI_VP_VSEARCHRESULTSFORMAT';
$modversion['config'][$confassign]['description'] = '_MI_VP_VSEARCHRESULTSFORMATDSC';
$modversion['config'][$confassign]['formtype'] = 'select';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;
$modversion['config'][$confassign]['options'] = array('_MI_VP_VSEARCHRESULTSOPT1' => 0, '_MI_VP_VSEARCHRESULTSOPT2' => 1);

$confassign++;

$modversion['config'][$confassign]['name'] = 'videouseborder';
$modversion['config'][$confassign]['title'] = '_MI_VP_VBORDERENABLE';
$modversion['config'][$confassign]['description'] = '_MI_VP_VBORDERENABLEDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videoprimarycolor';
$modversion['config'][$confassign]['title'] = '_MI_VP_VPRIMARYCOLOR';
$modversion['config'][$confassign]['description'] = '_MI_VP_VPRIMARYCOLORDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'string';
$modversion['config'][$confassign]['default'] = '0000FF';

$confassign++;

$modversion['config'][$confassign]['name'] = 'videosecondarycolor';
$modversion['config'][$confassign]['title'] = '_MI_VP_VSECONDARYCOLOR';
$modversion['config'][$confassign]['description'] = '_MI_VP_VSECONDARYCOLORDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'string';
$modversion['config'][$confassign]['default'] = '800080';

$confassign++;

$modversion['config'][$confassign]['name'] = 'videousecats';
$modversion['config'][$confassign]['title'] = '_MI_VP_VCATEGORIESENABLE';
$modversion['config'][$confassign]['description'] = '_MI_VP_VCATEGORIESENABLEDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videocattype';
$modversion['config'][$confassign]['title'] = '_MI_VP_VCATTYPE';
$modversion['config'][$confassign]['description'] = '_MI_VP_VCATTYPEDSC';
$modversion['config'][$confassign]['formtype'] = 'select';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 1;
$modversion['config'][$confassign]['options'] = array('_MI_VP_VCATTYPEOPT1' => 0, '_MI_VP_VCATTYPEOPT2' => 1);

$confassign++;

$modversion['config'][$confassign]['name'] = 'videocattypecolumns';
$modversion['config'][$confassign]['title'] = '_MI_VP_VCATTYPECOLUMNS';
$modversion['config'][$confassign]['description'] = '_MI_VP_VCATTYPECOLUMNSDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 3;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videocattypeinferred';
$modversion['config'][$confassign]['title'] = '_MI_VP_VCATTYPEINFERRED';
$modversion['config'][$confassign]['description'] = '_MI_VP_VCATTYPEINFERREDDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 2;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videodefaultheight';
$modversion['config'][$confassign]['title'] = '_MI_VP_VDEFAULTHEIGHT';
$modversion['config'][$confassign]['description'] = '_MI_VP_VDEFAULTHEIGHTDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 344;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videodefaultwidth';
$modversion['config'][$confassign]['title'] = '_MI_VP_VDEFAULTWIDTH';
$modversion['config'][$confassign]['description'] = '_MI_VP_VDEFAULTWIDTHDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 425;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videosearchoverlaysize';
$modversion['config'][$confassign]['title'] = '_MI_VP_VSEARCHOVERLAYSIZE';
$modversion['config'][$confassign]['description'] = '_MI_VP_VSEARCHOVERLAYSIZEDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 50;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videosearchoverlayxoffset';
$modversion['config'][$confassign]['title'] = '_MI_VP_VSEARCHOVERLAYXOFFSET';
$modversion['config'][$confassign]['description'] = '_MI_VP_VSEARCHOVERLAYXOFFSETDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 50;

$confassign++;

$modversion['config'][$confassign]['name'] = 'videosearchoverlayyoffset';
$modversion['config'][$confassign]['title'] = '_MI_VP_VSEARCHOVERLAYYOFFSET';
$modversion['config'][$confassign]['description'] = '_MI_VP_VSEARCHOVERLAYYOFFSETDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = -50;

$confassign++;

$modversion['config'][$confassign]['name'] = 'vsobackgroundcolor';
$modversion['config'][$confassign]['title'] = '_MI_VP_VSOBKGRDCLR';
$modversion['config'][$confassign]['description'] = '_MI_VP_VSOBKGRDCLRDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'string';
$modversion['config'][$confassign]['default'] = 'eeeeee';

$confassign++;

$modversion['config'][$confassign]['name'] = 'vsobordercolor';
$modversion['config'][$confassign]['title'] = '_MI_VP_VSOBRDRCLR';
$modversion['config'][$confassign]['description'] = '_MI_VP_VSOBRDRCLRDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'string';
$modversion['config'][$confassign]['default'] = '000000';

$confassign++;

$modversion['config'][$confassign]['name'] = 'vsobordersize';
$modversion['config'][$confassign]['title'] = '_MI_VP_VSOBRDRSIZE';
$modversion['config'][$confassign]['description'] = '_MI_VP_VSOBRDRSIZEDSC';
$modversion['config'][$confassign]['formtype'] = 'text';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = '2';

$confassign++;

$modversion['config'][$confassign]['name'] = 'videopagenav';
$modversion['config'][$confassign]['title'] = '_MI_VP_VPAGENAV';
$modversion['config'][$confassign]['description'] = '_MI_VP_VPAGENAVDSC';
$modversion['config'][$confassign]['formtype'] = 'select';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 1;
$modversion['config'][$confassign]['options'] = array('_MI_VP_VPAGENAVOPT1' => 0, '_MI_VP_VPAGENAVOPT2' => 1, '_MI_VP_VPAGENAVOPT3' => 2);

$confassign++;

$modversion['config'][$confassign]['name'] = 'dailymotionenable';
$modversion['config'][$confassign]['title'] = '_MI_VP_DAILYMOTIONENABLE';
$modversion['config'][$confassign]['description'] = '_MI_VP_DAILYMOTIONENABLEDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;

$confassign++;

$modversion['config'][$confassign]['name'] = 'metacafeenable';
$modversion['config'][$confassign]['title'] = '_MI_VP_METACAFEENABLE';
$modversion['config'][$confassign]['description'] = '_MI_VP_METACAFEENABLEDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;

$confassign++;

$modversion['config'][$confassign]['name'] = 'bliptvenable';
$modversion['config'][$confassign]['title'] = '_MI_VP_BLIPTVENABLE';
$modversion['config'][$confassign]['description'] = '_MI_VP_BLIPTVENABLEDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;

$confassign++;

$modversion['config'][$confassign]['name'] = 'manualsubmitenable';
$modversion['config'][$confassign]['title'] = '_MI_VP_MANUALSUBMITENABLE';
$modversion['config'][$confassign]['description'] = '_MI_VP_MANUALSUBMITENABLEDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 1;

$confassign++;

$modversion['config'][$confassign]['name'] = 'managevideosenable';
$modversion['config'][$confassign]['title'] = '_MI_VP_MANAGEVIDEOSENABLE';
$modversion['config'][$confassign]['description'] = '_MI_VP_MANAGEVIDEOSENABLEDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;

$confassign++;

$modversion['config'][$confassign]['name'] = 'pagemenuenable';
$modversion['config'][$confassign]['title'] = '_MI_VP_PAGEMENUENABLE';
$modversion['config'][$confassign]['description'] = '_MI_VP_PAGEMENUENABLEDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 1;

$confassign++;

$modversion['config'][$confassign]['name'] = 'descriptioncopyenable';
$modversion['config'][$confassign]['title'] = '_MI_VP_DESCCOPYENABLE';
$modversion['config'][$confassign]['description'] = '_MI_VP_DESCCOPYENABLEDSC';
$modversion['config'][$confassign]['formtype'] = 'yesno';
$modversion['config'][$confassign]['valuetype'] = 'int';
$modversion['config'][$confassign]['default'] = 0;


?>