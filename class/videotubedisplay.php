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
//  Date:       09/29/2008                                                   //
//  Module:     Video Tube                                                   //
//  File:       class/videotubedisplay.php                                   //
//  Version:    1.8                                                          //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.7 RC1  Initial Release 09/14/2008                              //
//  ***                                                                      //
//  Version 1.8  09/29/2008                                                  //
//  New: Add Main menu option                                                //
//  ***                                                                      //

//
//  Class to facilitate display of video page menu
//  

class VideoTubeDisp
{

//  Private
//
    var $vsphp;
    var $dmenable;
    var $mcenable;
    var $btenable;
    var $msenable;
    var $mvenable;
    var $pmlabel0;
    var $pmlabel1;
    var $pmlabel2;
    var $pmlabel3;
    var $pmlabel4;
    var $pmlabel5;
    var $pmlabel6;
    var $pmlabel7;
    var $pmlabel8;
                             
//  Constructor
//  ToDo: Add details
//

	function VideoTubeDisp($vers_php,$dm_enable,$mc_enable,$bt_enable,$ms_enable,$mv_enable,$pm_label1,$pm_label2,$pm_label3,$pm_label4,$pm_label5,$pm_label6,$pm_label7,$pm_label8)
	{
      $this->vsphp = $vers_php;
      $this->dmenable = intval($dm_enable);
      $this->mcenable = intval($mc_enable);
      $this->btenable = intval($bt_enable);
      $this->msenable = intval($ms_enable);
      $this->mvenable = intval($mv_enable);
      $this->pmlabel0 = 'Main';
	  $this->pmlabel1 = $pm_label1;
      $this->pmlabel2 = $pm_label2;
      $this->pmlabel3 = $pm_label3;
      $this->pmlabel4 = $pm_label4;
      $this->pmlabel5 = $pm_label5;
      $this->pmlabel6 = $pm_label6;
      $this->pmlabel7 = $pm_label7;
      $this->pmlabel8 = $pm_label8;
    }

    function renderPageMenu()
    {
        $ret = '';

		$ret .= '<style>';
        $ret .= '.menunavleft{ background-color: #ffffff; border: 1px #000000 solid; font-size: 9px; color: #000000;}';
        $ret .= '.menunavleft a{color: #000000; text-decoration: none; font-weight: normal; background-color: transparent;}';
        $ret .= '.menunavleft a:hover{color: #0000ff;}';
        $ret .= '.menunav{ background-color: #ffffff; border-right: 1px #000000 solid; border-top: 1px #000000 solid; border-bottom: 1px #000000 solid; font-size: 9px; color: #000000;}';
        $ret .= '.menunav a{color: #000000; text-decoration: none; font-weight: normal; background-color: transparent;}';
        $ret .= '.menunav a:hover{color: #0000ff;}';
        $ret .= '</style>';
		$ret .= '<table align="center" cellpadding="0" cellspacing="0"><tr>';
		$ret .= '<td class="menunavleft" align="center" onmouseover="style.backgroundColor=\'#cccccc\';" onmouseout="style.backgroundColor=\'#ffffff\'">';
		$ret .= '<a href="index.php" alt="Main">';
		$ret .= $this->pmlabel0;
		$ret .= '</a>';
		$ret .= '</td>';
		$ret .= '<td class="menunav" align="center" onmouseover="style.backgroundColor=\'#cccccc\';" onmouseout="style.backgroundColor=\'#ffffff\'">';
		$ret .= '<a href="userfeatures.php?op=viewuserlist" alt="View By User">';
		$ret .= $this->pmlabel1;
		$ret .= '</a>';
		$ret .= '</td>';
		$ret .= '<td class="menunav" align="center" onmouseover="style.backgroundColor=\'#cccccc\';" onmouseout="style.backgroundColor=\'#ffffff\'">';
		$ret .= '<a href="youtubesearch.php?op=submit" alt="YouTube Search & Submit">';
		$ret .= $this->pmlabel2;
		$ret .= '</a>';
		$ret .= '</td>';
		if (version_compare($this->vsphp, '5.0.0', '>=')) {
		  if ($this->dmenable > 0) {
		    $ret .= '<td class="menunav" align="center" onmouseover="style.backgroundColor=\'#cccccc\';" onmouseout="style.backgroundColor=\'#ffffff\'">';
		    $ret .= '<a href="dailymotionsearch.php?op=searchdm">';
		    $ret .= $this->pmlabel4;
		    $ret .= '</a>';
		    $ret .= '</td>';
		  }
		  if ($this->mcenable > 0) {
		    $ret .= '<td class="menunav" align="center" onmouseover="style.backgroundColor=\'#cccccc\';" onmouseout="style.backgroundColor=\'#ffffff\'">';
		    $ret .= '<a href="metacafesearch.php?op=searchmc">';
		    $ret .= $this->pmlabel5;
		    $ret .= '</a>';
		    $ret .= '</td>';
		  }
		  if ($this->btenable > 0) {
		    $ret .= '<td class="menunav" align="center" onmouseover="style.backgroundColor=\'#cccccc\';" onmouseout="style.backgroundColor=\'#ffffff\'">';
		    $ret .= '<a href="bliptvsearch.php?op=searchbt">';
		    $ret .= $this->pmlabel6;
		    $ret .= '</a>';
		    $ret .= '</td>';
		  }
		}
		if ($this->msenable > 0) {
		  $ret .= '<td class="menunav" align="center" onmouseover="style.backgroundColor=\'#cccccc\';" onmouseout="style.backgroundColor=\'#ffffff\'">';
		  $ret .= '<a href="userfeatures.php?op=manualsubmit">';
		    $ret .= $this->pmlabel7;
		    $ret .= '</a>';
		  $ret .= '</td>';
		}
		if ($this->mvenable > 0) {
		  $ret .= '<td class="menunav" align="center" onmouseover="style.backgroundColor=\'#cccccc\';" onmouseout="style.backgroundColor=\'#ffffff\'">';
		  $ret .= '<a href="userfeatures.php?op=listmyvideos">';
		  $ret .= $this->pmlabel8;
		  $ret .= '</a>';
		  $ret .= '</td>';
		}
		$ret .= '<td class="menunav" align="center" onmouseover="style.backgroundColor=\'#cccccc\';" onmouseout="style.backgroundColor=\'#ffffff\'">';
		$ret .= '<a href="videohelp.php">';
		$ret .= $this->pmlabel3;
		$ret .= '</a>';
		$ret .= '</td></tr></table>';

		return $ret;   
    }
}

?>