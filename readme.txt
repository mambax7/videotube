****************************************************************************
 
  Video Tube v1.86 beta update release| Xoops Module by Tank | Oct 07, 2009
  Website: http://www.customvirtualdesigns.com
 
****************************************************************************

Video Tube is an XOOPS module that provides the ability to submit and view 
videos taking advantage of the video embedding feature provided by most video
services. 

Initial release 1.01
 - Video display formats are configurable
 - Interface provided for visitor submissions
 - Admin interface for reviewing and publishing submissions
 - Video thumbnails display Tool Tip submission info
 - Number of views recorded for each video
 - Random, Most Viewed and Latest blocks provided
 - Blocks available in both horizontal and vertical display formats
 - Video Help page provided to assist visitors in making submissions

Update release 1.1
 - Fixed bug so administrator edit functions properly
 - Fixed bug so Display Order preference description text is accurate
 - Fixed bug so start display pointer gets adjusted if display is on
   the last page and there is only 1 video remaining to be displayed
 - Fixed bug changing some include commands to include_once. Using the
   standard include command was causing some items to appear twice in 
   certain template designs
 - Fixed bug Admin Deny Submission status messages were missing
 - Fixed bug in video_display template replacing "step back a directory"
   level reference to xoops_url when building links for thumbnails 
 - Addressed all known XOOPS 2.2.x compatibility issues
 - Added parameter in Preferences for enabling Auto Approval of Video
   Submissions by registered users

Update release 1.2
 - Fixed bug modifying comparison for enabling/disabling Next Page link to
   ensure proper operation when last page appears
 - Fixed bug removing $HTTP_GET_VARS segemnt due to potential security risks
   NOTE: Special thanks to avtx30 for bringing the security issue to our
         attention

Update release 1.3
 - Fixed bug where Video Display Format configuration parameter was 
   initialized to an illegal value for a new installation which disabled
   videos from being displayed.

Update release 1.4
 - Fixed bug in video display template which was causing text fonts to 
   change in other blocks
 - Add View By User feature which provides a full list of all video
   submitters. All columns provide sort ascending and descending links.
   List also provides submitters' statistics.
 - Add Submitter column in the Admin section when viewing Video Submissions
   and Published Videos.
 - Provide improved page navigation using built-in Pagenav class.

Update release 1.5
 - Add YouTube search and video preview to video submission page
 - Add video submission form auto-fill
 - Update Video Help to reflect new video submission layout
 - Create submission template
 
Update release 1.6
 - Fixed bug so Id is passed correctly allowing video submissions to be
   approved by admin
 - Fixed compatibility issues with XOOPS 2.2.6
 - Add 'format=5' to search queries so videos, in which Embedding Is
   Disabled, will not appear in the search results
 - Add Categories
 - Add total search results and search pagination to video submission page
 - Make Search button default on video submission page so search on keywords
   can also be initiated by pressing the Enter key
 - Add Preferences parameter for defining number of search results to display
   per page
 - Add Preferences parameter for selection of search results format as
   Thumbnails Only or Thumbnails with Full Text Description
 - Add Video Play Blocks (Random, Latest, Most Viewed)
 - Add YouTube content to display window under embedded player
 - Add colored border option for embedded player

Update release 1.61
 - Fixed bug so video submissions work when categories are enabled or disabled
 - Eliminate What's New page because the info was more for webmasters instead of
   site visitors
 - Add a check for certain JSON feed fields to verify they exist to eliminate 
   Javascript errors when displaying Video Info below embedded player
 - Replace the original video search preview overlay with a new portable overlay
   and add several definition preference parameters to control overlay appearance
 - Convert Video Help page text to variable defines in modinfo language file
 - Update Video Help screen capture images to reflect the appearance of the new
   portable video preview overlay

Update release 1.62
 - Add display of number of videos contained in each category. This number appears
   in brackets to the right of category title in drop-down listbox. Displaying these
   values is enabled/disabled through a preferences config parameter.
 - Add 1-column and 2-column video display format options
 - Add 3 Video Wall blocks - Random, Most Viewed and Latest
 - Add Video Tube to XOOPS site search
 - Add preference parameter to Display All Categories to eliminate confusion
   regarding the 2 video minimum requirement. When set to YES all categories are 
   shown and when category is selected that does not contain at least two videos
   then an error message is displayed. When set to NO only categories containing 
   at least two videos are available for selection.

Update release 1.63
 - Bug fix in youtube.js passing overlay dimensions to portable video preview
   when Next or Back button is selected
 - Add page navigation display option parameter. Options are Numeric, Graphics
   and Drop-down List
 - Add global comments
 - Add Daily Motion video service support
   NOTE: Daily Motion feature requires PHP5. If an older version of PHP is detected
   then the Daily Motion feature will be disabled on your site.

Update release 1.64
 - Bug fix correct pagination links when View By User videos displayed
 - Add MetaCafe video service support
 - Add blip.tv video service support
   NOTE: MetaCafe and blip.tv features require PHP5. If an older version of PHP 
   is detected then the MetaCafe and blip.tv features will be disabled on your site.
 - Add manual video submission support for submitting videos from other services
   that support embedding
 - Add Preference parameters for enable/disable of Daily Motion, MetaCafe and blip.tv
   video search and submit submenus
 - Add Preference parameter for enable/disable of Manual Submission submenu
 - Clean up Preferences parameters that were originally provided for YouTube videos
   before other video services were added

Update release 1.65
 - Bug fix random and most viewed video play blocks - display more and display less
   buttons incorrectly linked
 - Bug fix service number comparison correction required to allow admin edits
   to Daily Motion videos to function properly
 - Bug fix admin view submission before approval not working for metacafe or blip.tv
 - Bug fix add video size parameter label text for video play blocks
 - Bug fix improperly escaped video title causing javascript error to occur when 
   video preview selected and video title contains quote characters
 - Bug fix addition of passing category through sanitizer for all video submission 
   forms (potential security risk)
 - Add 'Approve All' button on admin video submissions page
 - Add local description field to all submission forms. If a local description
   exists it will be used. Local description always takes precedence over
   description retrieved from video service.
 - Add ability for users to edit and/or delete their own video submissions.
   Also provided a preference parameter to enable/disable this feature.
 - Update Video Help to reflect the addition of local description field to
   automated search and submit features. Also add border around screen shots.

Update release 1.7 RC1
 - Bug fix correct some javascript function calls in Random Video Play block 
   template and Most Viewed Video Play block template related to 
   Display More Info/Display Less Info buttons.
 - Add Featured video blocks where user can define specific videos to display
   Horizontal and vertical blocks can display from 1 to 5 thumbnails.
 - Add optional page menu so submenu items are displayed on all video tube pages
   as a menu bar at the top of each page

Update release 1.8 Official
 - Bug fix correct typo Manual Submission link
 - Add 'Main' menu tab in page display menu

Update release 1.81
 - Bug fix clean up majority of functions causing debug notices
 - Update adding category selection in Manage My Videos edit feature
 - Update module logo using XOOPS 2.3.x graphic template
 - Add language support
 - Eliminate all hard-coded text and move to language files
 - Add module style directory and style css file
 - Add html class attributes to video tube blocks
 - Change database table video fields 'title', 'artist', 'embedcode' and 'thumb' from
   varchar to text to remove length restriction problems
 - Add support of sub-categories
 - Total rewrite of the categories management admin interface
 - Add Category display parameter with options for 'basic' which displays
   categories as drop-down list and 'advanced' which displays categories
   in a fieldset with breadcrumb header
 - Eliminate 'Display Number Of Videos In Each Category' preference parameter
 - Eliminate 'Display All Categories' preference parameter
 - When comment is posted the video title is automatically assigned to the
   comment reply title

Update release 1.82
 - Bug fix clean up more of the functions causing debug notices
 - Bug fix pass category preselected when initiating video edit and pass
   cid when posting video edit
 - Change module class XoopsTree to new label VideoTubeXoopsTree to eliminate
   interference with other modules & blocks still using XoopsTree class.
 - Change the video preview (video overlay) positioning on search result
   screens so it references the current mouse pointer position when video is
   selected. This causes the preview to always appear near the selected video
   thumbnail.
 - Eliminate the preference parameters Video Search Overlay Default X Position &
   Y Position since the positioning now uses the current mouse pointer coordinates
 - Add two preference parameters Video Preview X Axis Offset and Video Preview Y Axis Offset
   so user can customize where preview window will appear

Update release 1.83
 - Bug fix missed adding x offset and y offset parameters when selecting NEXT or BACK
   buttons on YouTube search results page
 - Bug fix removed extra escape line in javascript causing special characters
   to be shown as UTF codes instead of actual characters on YouTube search
 - Add preference parameter so you can define the number of inferred subcategories
   to be displayed under current categories when category display type is set to Advanced

Update release 1.84
 - Bug fix remove mouse position coordinates display at bottom of Video Preview window
 - Bug fix add language support to videohelp.php file
 - Bug fix typo in javascript code causing Back button not to work in Search YouTube
   display results screens
 - Remove the annoying "minimum of 2 videos required" message
 - Add ability to display a single video
 - Add "Please Select A Category" display message when no videos can be found in the current
   category
 - Add "No Videos Found" display message when no videos can be found and categories are
   not enabled
 - Change the category video count so the value displayed includes all videos in all
   associated subcategories when Category Display Type = Advanced
 - Eliminate "Main Video View" and "Category Video View" headings appearing near top of
   video display screen when Category Display Type = Basic
 - Add french language files
 - Add "Report Video" feature
 - Add "Recommend Video" feature

Update release 1.85
 - Bug fix add report and recommend template assignments to view by user
 - Add "Rate Video" feature
 - Add Back and Next buttons at bottom of YouTube search results pages 

Update release 1.86
 - Bug fix update YouTube JSON feed expected format
 - Add template assignments to support facebook sharer
 - Add Preference parameter to control description copy to local db
   
Tested on XOOPS 2.3.3

New Install - Instructions:
1) After downloading, unzip the package
2) Upload the directory labeled 'videotube' to your server into the 'modules' subdirectory
3) Select Administration Menu - System Admin - Modules
4) Scroll to bottom of screen and click the install icon associated with Video Tube module
5) After installation complete is indicated
   - return to Modules Administration
   - Enter the order number for where you want this item to appear in the Main menu
   - Modify the item label to be used if desired 
6) Be sure to enable/configure blocks and set group permissions as desired
7) Don't forget to modify Video Tube Preferences for desired features

Update from any previous version to v1.86 - Instructions:
1) After downloading, unzip the package
2) VERY IMPORTANT: Backup your XOOPS database before continuing with this procedure
3) If you are updating from v1.81 or higher and you have made changes to the module style css file
   then delete the /modules/videotube/style/videotube.css file from the new module
   package so your current css file on your server will not be overwritten in the next step.
4) Upload all files and subdirectories under the directory labeled 'videotube' to your server 
   into the 'modules/videotube/' subdirectory overwriting all existing files
   NOTE: If you experience problems after update is performed another option
   is to remove the videotube subdirectory from the modules directory
   then ftp the new videotube directory to the modules directory.
5) Select Administration Menu - System Admin - Modules
6) Scroll to Video Tube module and click the Update icon
7) After update indicates successful completion
   - return to Modules Administration
8) Be sure to enable/configure blocks and set group permissions as desired
9) Don't forget to modify Video Tube Preferences for desired features
10) It should also be noted that you can delete the following file
   /modules/videotube/class/xoopstree.php because it is no longer used.
   It has been replaced by /modules/videotube/class/videotubetree.php

Information about blocks:

This version of the module comes with 15 different blocks
1) Random Videos - Horizontal
2) Random Videos - Vertical
3) Random Video - Play
4) Random Video - Wall
5) Most Viewed Videos - Horizontal
6) Most Viewed Videos - Vertical
7) Most Viewed Video - Play
8) Most Viewed Video - Wall
9) Latest Videos - Horizontal
10) Latest Videos - Vertical
11) Latest Video - Play
12) Latest Video - Wall
13) Featured Videos - Horizontal
14) Featured Videos - Vertical
15) Featured Video - Play

Additional Notes:

You can now control the appearance by accessing the module style sheet
located at /modules/videotube/style/videotube.css