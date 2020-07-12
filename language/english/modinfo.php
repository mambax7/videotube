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
//  Date:       01/12/2009                                                   //
//  Module:     Video Tube                                                   //
//  File:       languages/english/modinfo.php                                //
//  Version:    1.83                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.0  Initial Release 04/14/2008                                  //
//  ***                                                                      //
//  Version 1.1  04/23/2008                                                  //
//  Bug Fix: Remove text from Video Display Order description indicating     //
//           "Random" as an available option                                 //
//  New: Add auto approve registered user submission config parameter        //
//  Note: E-mail contact address change                                      //
//  ***                                                                      //
//  Version 1.4  05/21/2008                                                  //
//  New: Add new definitions as necessary to support current release         //
//  ***                                                                      //
//  Version 1.5  05/26/2008                                                  //
//  New: Add new definitions as necessary to support submission template     //
//  ***                                                                      //
//  Version 1.6  08/01/2008                                                  //
//  New: Add config parameters for enhanced search display                   //
//  New: Add config parameters for categories                                //
//  New: Add labels for additional YouTube data                              //
//  ***                                                                      //
//  Version 1.61  08/02/2008                                                 //
//  New: Eliminate What's New page                                           //
//  New: Change Submit A Video submenu item to Search/Submit                 //
//  New: Add config parameters to define video search preview overlay        //
//  ***                                                                      //
//  Version 1.62  08/09/2008                                                 //
//  New: Add display of number of videos in each category                    //
//  New: Add Display All Categories preference parameter                     //
//  ***                                                                      //
//  Version 1.63  08/23/2008                                                 //
//  New: Add new definitions as necessary to support current release         //
//  ***                                                                      //
//  Version 1.64  09/06/2008                                                 //
//  New: Update YouTube parameter descriptions                               //
//  New: Add MetaCafe support                                                //
//  New: Add blip.tv support                                                 //
//  New: Add Manual Submission support                                       //
//  New: Add text for expanded Video Help page                               //
//  ***                                                                      //
//  Version 1.65  09/12/2008                                                 //
//  New: Add Manage My Videos submenu                                        //
//  ***                                                                      //
//  Version 1.7 RC1  09/14/2008                                              //
//  New: Add Page Menu items                                                 //
//  New: Add Featured blocks                                                 //
//  ***                                                                      //
//  Version 1.82  01/10/2009                                                 //
//  Remove Preview default position parameters                               //
//  Add Preview position offset parameters                                   //
//  ***                                                                      //
//  Version 1.83  01/12/2009                                                 //
//  Add number of inferred sub categories displayed parameter                //
//  ***                                                                      //

// The name of this module
define("_VP_MOD_NAME","Video Tube");

// A brief description of this module
define("_VP_MOD_DESC","Video Display Interface");

// Name for Submenu Link
define("_MI_VP_SMNAME1","View By User");
define("_MI_VP_SMNAME2","Search YouTube");
define("_MI_VP_SMNAME3","Video Help");
define("_MI_VP_SMNAME4","Search DailyMotion");
define("_MI_VP_SMNAME5","Search MetaCafe");
define("_MI_VP_SMNAME6","Search blip.tv");
define("_MI_VP_SMNAME7","Manual Submission");
define("_MI_VP_SMNAME8","Manage My Videos");

// Name for Pagemenu Link
define("_MI_VP_PMNAME1","UserView");
define("_MI_VP_PMNAME2","YouTube");
define("_MI_VP_PMNAME3","Help");
define("_MI_VP_PMNAME4","DailyMotion");
define("_MI_VP_PMNAME5","MetaCafe");
define("_MI_VP_PMNAME6","blip.tv");
define("_MI_VP_PMNAME7","Manual Submit");
define("_MI_VP_PMNAME8","Manage");

// Block Names
define("_MI_VP_BNAME1","Top Videos H");
define("_MI_VP_BNAME2","Random Videos H");
define("_MI_VP_BNAME3","Latest Videos H");
define("_MI_VP_BNAME4","Top Videos V");
define("_MI_VP_BNAME5","Random Videos V");
define("_MI_VP_BNAME6","Latest Videos V");
define("_MI_VP_BNAME7","Latest Play Video");
define("_MI_VP_BNAME8","Most Viewed Play Video");
define("_MI_VP_BNAME9","Random Play Video");
define("_MI_VP_BNAME10","Latest Video Wall");
define("_MI_VP_BNAME11","Most Viewed Video Wall");
define("_MI_VP_BNAME12","Random Video Wall");
define("_MI_VP_BNAME13","Featured Videos H");
define("_MI_VP_BNAME14","Featured Videos V");
define("_MI_VP_BNAME15","Featured Play Video");

// Admin Menu
define("_MI_VP_ADMENU1","Home");
define("_MI_VP_ADMENU2","Video Submissions");
define("_MI_VP_ADMENU3","Published Videos");
define("_MI_VP_ADMENU4","Manage Categories");
define("_MI_VP_ADMENU5","Video Reports");

// Block Info
define("_MI_VP_NUMVIDEOS","Number of Videos in Block");
define("_MI_VP_NUMVIDEOSDSC","How many video thumbnails will be displayed in block.");

define("_MI_VP_EDITSUBMITADD","Submit Video Edits");
define("_MI_VP_EDITSUBMITCANCEL","Cancel");
define("_MI_VP_EDITSUCCESS","Video Edits Recorded Successfully");
define("_MI_VP_EDITERROR","Error Occurred While Attempting To Edit Video");
define("_MI_VP_MANAGE_HEADER","Manage My Videos");
define("_MI_VP_ID_CHEADING","ID");
define("_MI_VP_CODE_CHEADING","Code");
define("_MI_VP_TITLE_CHEADING","Title");
define("_MI_VP_ARTIST_CHEADING","Artist");
define("_MI_VP_CATEGORY_CHEADING","Category");
define("_MI_VP_EDIT_CHEADING","Edit");
define("_MI_VP_DELETE_CHEADING","Delete");
define("_MI_VP_EDITHEADER", "Edit Video Information");
define("_MI_VP_EDIT","Edit");
define("_MI_VP_DELETE","Delete");
define("_MI_VP_DELSUCCESS","Video Deleted Successfully");
define("_MI_VP_DELERROR","Error Occurred While Attempting To Delete Video");
define("_MI_VP_CANCEL","Cancel");
define("_MI_VP_DEL_REALLY","Do You Really Want To Delete This Video?");
define("_MI_VP_ID","Video ID");

// Config Preferences Info
define("_MI_VP_VDISPLAYFORMAT","Video Display Format");
define("_MI_VP_VDISPLAYFORMATDSC","Determines how the video display page will appear.");
define("_MI_VP_VAUTOPLAY","Video AutoPlay");
define("_MI_VP_VAUTOPLAYDSC","Determines if the initial video will start playing automatically. This does not affect subsequent pages. When a video thumbnail is selected and the page refreshes, the video will always start playing automatically.");
define("_MI_VP_VDISPLAYORDER","Video Display Order");
define("_MI_VP_VDISPLAYORDERDSC","[<i>By ID</i>] will sort the videos by their order of submission in ascending order.<br />[<i>By views</i>] will sort the videos by number of views in descending order.");
define("_MI_VP_VDISPLAYNUMBER","Number of Videos Displayed");
define("_MI_VP_VDISPLAYNUMBERDSC","Determines how many videos are displayed per page. When set to 0 no limit is used. All videos appear on a single page.");
define("_MI_VP_VANONSUBMIT","Anonymous Users Submit Videos");
define("_MI_VP_VANONSUBMITDSC","Determines if anonymous users are permitted to submit videos.");
define("_MI_VP_VAUTOAPPROVESUBMIT","Auto Approve Registered Users' Submissions");
define("_MI_VP_VAUTOAPPROVESUBMITDSC","Determines if registered users' video submissions are approved (published) automatically.");
define("_MI_VP_VSEARCHRESULTSMAX","Maximum Number of Search Results Per Page");
define("_MI_VP_VSEARCHRESULTSMAXDSC","The maximum number of search results to display per page.");
define("_MI_VP_VSEARCHRESULTSFORMAT","YouTube Search Results Display Format");
define("_MI_VP_VSEARCHRESULTSFORMATDSC","Determines how the search results will appear when searching YouTube.");
define("_MI_VP_VBORDERENABLE","YouTube Display Border Enable");
define("_MI_VP_VBORDERENABLEDSC","Determines if border around embedded player is enabled.");
define("_MI_VP_VPRIMARYCOLOR","YouTube Border Primary Color");
define("_MI_VP_VPRIMARYCOLORDSC","Primary color used for embedded player border (in RRGGBB format).");
define("_MI_VP_VSECONDARYCOLOR","YouTube Border Secondary Color");
define("_MI_VP_VSECONDARYCOLORDSC","Secondary color used for embedded player border (in RRGGBB format).");
define("_MI_VP_VCATEGORIESENABLE","Video Categories Enable");
define("_MI_VP_VCATEGORIESENABLEDSC","Determines if categories are to be used.");
define("_MI_VP_VDEFAULTHEIGHT","YouTube Video Search Overlay Default Height");
define("_MI_VP_VDEFAULTHEIGHTDSC","Defines video overlay height (in pixels) when size = 100%.");
define("_MI_VP_VDEFAULTWIDTH","YouTube Video Search Overlay Default Width");
define("_MI_VP_VDEFAULTWIDTHDSC","Defines video overlay width (in pixels) when size = 100%.");
define("_MI_VP_VSEARCHOVERLAYSIZE","Video Search Overlay Size");
define("_MI_VP_VSEARCHOVERLAYSIZEDSC","Video overlay scaling size in percent.");
define("_MI_VP_VSOBKGRDCLR","Video Search Overlay Background Color");
define("_MI_VP_VSOBKGRDCLRDSC","Background color used for overlay window (in RRGGBB format).");
define("_MI_VP_VSOBRDRCLR","Video Search Overlay Border Color");
define("_MI_VP_VSOBRDRCLRDSC","Border color used for overlay window (in RRGGBB format).");
define("_MI_VP_VSOBRDRSIZE","Video Search Overlay Border Size");
define("_MI_VP_VSOBRDRSIZEDSC","Border size used for overlay window (in pixels). Set to 0 for no border.");
define("_MI_VP_VPAGENAV","Video Page Navigation Type Select");
define("_MI_VP_VPAGENAVDSC","Determines type of page navigation display used on video display page.");
define("_MI_VP_DAILYMOTIONENABLE","Daily Motion Enable");
define("_MI_VP_DAILYMOTIONENABLEDSC","Determines if Search DailyMotion should appear in Video Tube menu. NOTE: Requires PHP5 or higher");
define("_MI_VP_METACAFEENABLE","MetaCafe Enable");
define("_MI_VP_METACAFEENABLEDSC","Determines if Search MetaCafe should appear in Video Tube menu. NOTE: Requires PHP5 or higher");
define("_MI_VP_BLIPTVENABLE","blip.tv Enable");
define("_MI_VP_BLIPTVENABLEDSC","Determines if Search blip.tv should appear in Video Tube menu. NOTE: Requires PHP5 or higher");
define("_MI_VP_MANUALSUBMITENABLE","Manual Submission Enable");
define("_MI_VP_MANUALSUBMITENABLEDSC","Determines if Manual Submission should appear in Video Tube menu.");
define("_MI_VP_MANAGEVIDEOSENABLE","User Manage My Videos Enable");
define("_MI_VP_MANAGEVIDEOSENABLEDSC","Determines if registered users are allowed to edit/delete their own submissions.");
define("_MI_VP_PAGEMENUENABLE","Video Tube Page Menu Enable");
define("_MI_VP_PAGEMENUENABLEDSC","Determines if submenu items are displayed inline on a page menu bar.");
define("_MI_VP_DESCCOPYENABLE","YouTube Description Copy Enable");
define("_MI_VP_DESCCOPYENABLEDSC","Determines if the YouTube video description will be copied into the local database upon submission.");

define("_MI_VP_CODE","Code");
define("_MI_VP_TITLE","Title");
define("_MI_VP_ARTIST","Artist");
define("_MI_VP_CATEGORY","Category");

define("_MI_VP_SUBMITHEADER","Video Submission");
define("_MI_VP_SUBMIT","Submit");
define("_MI_VP_RESET","Reset");

define("_MI_VP_AUTOSUBMITSUCCESS","Video submission successful and has been automatically approved");
define("_MI_VP_SUBMITSUCCESS","Video submission successful .... it will appear after approved by admin");
define("_MI_VP_SUBMITERROR","Error occurred during video submission. Please report this to an admin.");

define("_MI_VP_PREVPAGELABEL","<<Back");
define("_MI_VP_NEXTPAGELABEL","Next>>");

define("_MI_VP_NOTMINIMUM","Sorry but we can not display videos until a minimum of 2 videos are published");
define("_MI_VP_PLEASESUBMIT","Please submit your videos through to get started");
define("_MI_VP_CATNOTMINIMUM","Sorry but we can not display the selected category until a minimum of 2 videos are assigned to it");
define("_MI_VP_CATPLEASESUBMIT","Please select a different category or contact the site administrator");


define("_MI_VP_LISTINSTRUCTIONS","click arrows to re-sort list / click desired username to view user's video page");
define("_MI_VP_COLUMN1LABEL","Username");
define("_MI_VP_COLUMN2LABEL","Videos");
define("_MI_VP_COLUMN3LABEL","Views");
define("_MI_VP_COLUMN4LABEL","Last Update");

define("_MI_VP_MYPAGETITLE","My Video Tube Page");
define("_MI_VP_MAINPAGETITLE","Main Video View");
define("_MI_VP_CATPAGETITLE","Category Video View");
define("_MI_VP_LISTPAGETITLE","Video Tube Submitter Listing");

define("_MI_VP_VIDEOVIEWER","Video Viewer");
define("_MI_VP_YOUTUBESEARCH","YouTube Search");
define("_MI_VP_DMSEARCH","DailyMotion Search");
define("_MI_VP_MCSEARCH","MetaCafe Search");
define("_MI_VP_BTSEARCH","blip.tv Search");
define("_MI_VP_KEYWORDS","Keywords");

define("_MI_VP_NAVLABEL","Navigation");

define("_MI_VI_VIDEOTITLE","Video Title");
define("_MI_VI_DESC","Description");
define("_MI_VI_DISPLAYMORE","Display More Info");
define("_MI_VI_DISPLAYLESS","Display Less Info");
define("_MI_VI_LOCALVIEWS","Local Views");
define("_MI_VI_SUBMITTER","Submitter");
define("_MI_VI_DURATION","Duration");
define("_MI_VI_SUBMITDATE","Submit Date");
define("_MI_VI_VIEWS","Views");
define("_MI_VI_SUBMITTIME","Submit Time");
define("_MI_VI_FAVORITES","Favorites");
define("_MI_VI_AUTHOR","Author");
define("_MI_VI_RATERS","Number Raters");
define("_MI_VI_PUBDATE","Publish Date");
define("_MI_VI_AVGRATING","Avg Rating(1-5)");
define("_MI_VI_PUBTIME","Publish Time");

define("_MI_VP_SEARCHDMHEADER","DailyMotion Search");
define("_MI_VP_SEARCHMCHEADER","MetaCafe Search");
define("_MI_VP_SEARCHBTHEADER","blip.tv Search");
define("_MI_VP_SEARCH","Search");
define("_MI_VP_EMBEDCODE","Embed Code URL");
define("_MI_VP_FULLEMBEDCODE","Full Embed Code");
define("_MI_VP_DESC","Description");
define("_MI_VP_THUMB","Thumbnail URL");
define("_MI_VP_SERVICENAME","Service Name");

// Added for v1.81
// ***************************************
define("_MI_VP_VSEARCHOVERLAYXOFFSET","Video Search Preview Position X Offset");
define("_MI_VP_VSEARCHOVERLAYXOFFSETDSC","Defines preview window position x-axis offset from current mouse position");
define("_MI_VP_VSEARCHOVERLAYYOFFSET","Video Search Preview Position Y Offset");
define("_MI_VP_VSEARCHOVERLAYYOFFSETDSC","Defines preview window position y-axis offset from current mouse position");

define("_MI_VP_MAIN","Home");
define("_MI_VP_MAIN_DISPLAY","Main Category Listing");

define("_MI_VP_SUBMITTED","submitted by");
define("_MI_VP_ON","on");
define("_MI_VP_AT","at");

define("_MI_VP_FORMDESC","Desc");
define("_MI_VP_FORMREL","Relative");
define("_MI_VP_FORMPUB","Published");

define("_MI_VP_CLOSEVIDEO","CLOSE VIDEO");
define("_MI_VP_PREVIEW","** Portable Video Preview **");
define("_MI_VP_RESULTS","video results");
define("_MI_VP_TO","to");
define("_MI_VP_ABOUT","of about");

define("_MI_VP_PAGESEL","Page Select");
define("_MI_VP_CATSEL","Category Select");
define("_MI_VP_CATLIST","Category Listing");
define("_MI_VP_CATEGORY_TITLE","Category Title");

define("_MI_VP_PLEASESELECT","Please select a category to view videos");
define("_MI_VP_NOVIDEOSFOUND","No Videos Found - Please Search and Submit Videos");

define("_MI_VP_SUBMITREPORT","Submit Report");
define("_MI_VP_REPORTHEADER","Report This Video To Administrator");
define("_MI_VP_REPORTFORMRADIO","Reason For Reporting");
define("_MI_VP_REPORTREASON1","Video No Longer Available");
define("_MI_VP_REPORTREASON2","Inappropriate Content");
define("_MI_VP_REPORTREASON3","Incorrect Category");
define("_MI_VP_REPORTREASON4","Poor Quality Video/Audio");
define("_MI_VP_REPORTREASON5","This Video Post Is SPAM");
define("_MI_VI_VIDEOCODE","Video Code");
define("_MI_VP_REPORTSUCCESS","Report was successfully posted<br />Thanks for reporting this video");
define("_MI_VP_REPORTERROR","An error was encountered when trying to post report");
define("_MI_VI_REPORTBUTTON","Report Video");
define("_MI_VI_RECOMMENDBUTTON","Recommend To A Friend");
define("_MI_VI_RATEBUTTON","Rate Video");
define("_MI_VI_MAILSUBJECT","View Video Recommendation From");
define("_MI_VI_MAILBODY","A friend has sent you a recommendation to watch this video");

define("_MI_VP_SUBMITRATING","Submit Rating");
define("_MI_VP_RATEHEADER","Rate This Video");
define("_MI_VP_RATEFORMRADIO","Rating Selection");
define("_MI_VP_RATERANK1","1 (Poor)");
define("_MI_VP_RATERANK2","2 (Sub-standard)");
define("_MI_VP_RATERANK3","3 (Average)");
define("_MI_VP_RATERANK4","4 (Great)");
define("_MI_VP_RATERANK5","5 (Excellent)");
define("_MI_VP_RATESUCCESS","Rating was successfully posted<br />Thanks for rating this video");
define("_MI_VP_RATEERROR","An error was encountered when trying to post rating");
define("_MI_VP_SUBMITTERNORATE","Sorry but you can not rate a video which you submitted.");
define("_MI_VP_NORATETWICE","Sorry but you already rated this video.");
define("_MI_VP_LOCALRATERS","Local Raters");
define("_MI_VP_LOCALRATING","Local Rating");

define("_MI_VP_VCATTYPE","Category Display Type");
define("_MI_VP_VCATTYPEDSC","This selection determines how categories will be displayed.<br />'Basic' displays categories in a drop-down list<br />'Advanced' displays categories in a fieldset form with breadcrumb header");
define("_MI_VP_VCATTYPECOLUMNS","Category Display Columns");
define("_MI_VP_VCATTYPECOLUMNSDSC","When 'Advanced' Category Type is selected this parameter determines how many columns will be used when displaying categories.");
define("_MI_VP_VCATTYPEINFERRED","Category Display Inferred Subcategories");
define("_MI_VP_VCATTYPEINFERREDDSC","When 'Advanced' Category Type is selected this parameter determines how many inferred subcategories will be displayed under current categories.");

define("_MI_VP_VDISPFORMOPT1","1 Column");
define("_MI_VP_VDISPFORMOPT2","2 Column");
define("_MI_VP_VDISPFORMOPT3","3 Column");
define("_MI_VP_VDISPFORMOPT4","4 Column");
define("_MI_VP_VDISPFORMOPT5","5 Column");
define("_MI_VP_VDISPORDOPT1","By Views ASC");
define("_MI_VP_VDISPORDOPT2","By Submission DESC");
define("_MI_VP_VDISPORDOPT3","By Views DESC");
define("_MI_VP_VDISPORDOPT4","By Submission ASC");
define("_MI_VP_VCATTYPEOPT1","Basic");
define("_MI_VP_VCATTYPEOPT2","Advanced");
define("_MI_VP_VSEARCHRESULTSOPT1","Full Text");
define("_MI_VP_VSEARCHRESULTSOPT2","Thumbnail Only");
define("_MI_VP_VPAGENAVOPT1","Numbers");
define("_MI_VP_VPAGENAVOPT2","Graphic Images");
define("_MI_VP_VPAGENAVOPT3","Drop-Down List");

define("_MI_VP_BNAME1DSC","Displays most viewed video thumbnails in horizontal format");
define("_MI_VP_BNAME2DSC","Displays random video thumbnails in horizontal format");
define("_MI_VP_BNAME3DSC","Displays most recent submitted video thumbnails in horizontal format");
define("_MI_VP_BNAME4DSC","Displays most viewed video thumbnails in vertical format");
define("_MI_VP_BNAME5DSC","Displays random video thumbnails in vertical format");
define("_MI_VP_BNAME6DSC","Displays most recent submitted video thumbnails in vertical format");
define("_MI_VP_BNAME7DSC","Displays most recent submitted video in a play block");
define("_MI_VP_BNAME8DSC","Displays most viewed video in a play block");
define("_MI_VP_BNAME9DSC","Displays random video in a play block");
define("_MI_VP_BNAME10DSC","Displays most recent submitted videos in a video wall block");
define("_MI_VP_BNAME11DSC","Displays most viewed videos in a video wall block");
define("_MI_VP_BNAME12DSC","Displays random videos in a video wall block");
define("_MI_VP_BNAME13DSC","Displays featured video thumbnails in horizontal format");
define("_MI_VP_BNAME14DSC","Displays featured video thumbnails in vertical format");
define("_MI_VP_BNAME15DSC","Displays featured video in a play block");
// ***************************************

define("_MI_VH_PAGETITLE","Video Tube Help");

define("_MI_VH_PARAGRAPH1","Video Tube is designed to display videos using the 
embedding feature provided by many of today's video services. Video submission is
automated for certain video services. You can search YouTube, Daily Motion,
MetaCafe and blip.tv, preview the video and submit the video without leaving 
this site. When you select any one of the Search selections from the Main Menu 
under Video Tube, here is what the submission screen looks 
like. NOTE: The example shown is for YouTube. The other search selection screens will
vary somewhat in appearance. Other services, besides YouTube, may or may not be available on this site.");

define("_MI_VH_PARAGRAPH2","To begin type the keywords to search on in the search box then press the Search 
button or press the *Enter* key. How the search results will appear depends on
configuration settings when Video Tube was installed. If the search results appear
as video thumbnails ONLY then hovering (moving mouse pointer) over the 
thumbnails will display their titles. Search results may also appear as thumbnails
with full text descriptions. Just above the search results you will see how many
total search results were found and which results you are viewing (how search results appear
will vary between the different video service search selections). Navigation buttons
*Back* and *Next* can be used to select which page of search results to view.");

define("_MI_VH_PARAGRAPH3","When you click on one of the thumbnails a preview of the video will appear in 
the Portable Video Viewer and the required fields will be filled in automatically in the submission 
form.");

define("_MI_VH_PARAGRAPH4","The title and artist are not separate in most video services. 
An optional Artist entry field is provided if you want to manually enter this info 
but it is not required. The description field is also optional. If you fill-in the description then it will
appear under the video when the video appears on the display screen. If this field is left empty then the
description retrieved from the video service will appear. If categories are enabled then the last step prior to submitting 
the video is to select the category in which you want your video to appear.");

define("_MI_VH_PARAGRAPH5","Now click the submit button and you are done. You should see a message on the screen
indicating whether or not the submission was successful. If this message indicates an error
occurred then please report this to one of the site administrators or webmasters.");

define("_MI_VH_PARAGRAPH6","Another option is available if you wish to submit a video from
a video service other than those already provided as Search features. To manually submit a video
select Manual Submission from the Main Menu under Video Tube, here is what the submission screen looks 
like.");

define("_MI_VH_PARAGRAPH7","Manual submission requires a little more work because no automated features
are available. You must visit the Video Service's website to acquire the full embedding code for the
desired video, the video title, the video description and the URL for the video thumbnail. As an
example let's say we want to post a video that appears on Live Leak. First we head on over to
http://www.liveleak.com and perform a search to locate the desired video.");

define("_MI_VH_PARAGRAPH8","The search results appear as thumbnails with text descriptions. When you
locate the desired video place your mouse pointer over the thumbnail image and right click the mouse.
A group of menu selections will appear on the screen. Select the item labeled Properties.");

define("_MI_VH_PARAGRAPH9","A properties window will appear on the screen. You want to copy the text
that appears to the the right of Address: (URL). This is easily accomplished by placing the mouse pointer
at the beginning of the test, depress the left mouse button and hold it down while dragging the mouse
pointer to the end of the text. Now release the left mouse button. The selected text should become
highlighted. To copy the selected text to your clipboard (this is a temporary buffer your computer
provides) press and hold the < Ctrl > key then press the < C > key. Now the text has been copied to
your clipboard.");

define("_MI_VH_PARAGRAPH10","Now we want to go back to the manual submission form on the site. Click
on the box to the right of Thumb URL. The cursor should now appear in this entry field. To paste the
text from your clipboard press and hold the < Ctrl > key then press the < V > key.");

define("_MI_VH_PARAGRAPH11","Next we need to go back to the Live Leak site to get our embedding code.
Let's select the video to play by clicking on the thumbnail image. The window should transition to the
video play screen. Now select the View Embed Code button directly under the playing video. When the
embed code text box appears click on the box. All of the text inside the box becomes highlighted.
Once again we press and hold the < Ctrl > key then press the < C > key to copy the text.");

define("_MI_VH_PARAGRAPH12","Heading back to the manual submission form on the site again, this time click
on the box to the right of Full Embed Code. The cursor should now appear in this entry field. To paste the
text from your clipboard press and hold the < Ctrl > key then press the < V > key.");

define("_MI_VH_PARAGRAPH13","You can repeat similar steps to copy the video title and video description
or you can make up your own title and description. That's up to you. There is also an optional field for
you to type the video service name if you want it to appear under the video thumbnail.");

define("_MI_VH_PARAGRAPH14","Now click the submit button and you are done. You should see a message on the screen
indicating whether or not the submission was successful. If this message indicates an error
occurred then please report this to one of the site administrators or webmasters.");

?>
