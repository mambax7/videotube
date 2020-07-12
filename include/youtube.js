<!--
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
//  File:       include/youtube.js                                           //
//  Version:    1.86                                                         //
//  ------------------------------------------------------------------------ //
//  Change Log                                                               //
//  ***                                                                      //
//  Version 1.86  10/07/2009                                                 //
//  Update YouTube JSON feed expected format                                 //
//  Add Preference parameter to control description copy to local db         //
//  ***                                                                      //

var timer;
var i =0;
var youtubediv = new Array();
var voheight;
var vowidth;
var voxoffset;
var voyoffset;
var closelabel;
var previewlabel;
var resultslabel;
var tolabel;
var aboutlabel;
var nextlabel;
var backlabel;
var titlelabel;
var desclabel;
var authorlabel;
var viewslabel;
var durationlabel;
var pubdatelabel;
var pubtimelabel;
var ytdesccopy;

function playVid(vidId,title,description) {
  if (vidPaneID.style.display=='block') {
    vidPaneID.style.display='none';
    vidPaneID.innerHTML=''; 
  } else {
    vidPaneID.style.display='block';
	vidPaneID.style.position='absolute';
    vidPaneID.innerHTML='<A HREF="javascript:playVid()">'+closelabel+'</A>';
    var vidstring ='<center><embed enableJavascript="false" allowScriptAccess="never"';
    vidstring+=' allownetworking="internal" type="application/x-shockwave-flash"';
    vidstring+='  src="http://www.youtube.com/v/'+vidId+'&autoplay=1" ';
    vidstring+=' wmode="transparent" height="'+voheight+'" width="'+vowidth+'"></center>';
    vidstring+= previewlabel+'<br>';
    vidstring+= title;
    vidPaneID.innerHTML+=vidstring;
    myform = window.document.submitvideoform;
    myform.code.value=vidId;
    myform.title.value=title;
	if (ytdesccopy == 1 ) {
	  myform.description.value=description;
	}
  }
}

function getMousePos(e){
  if (e == null) { e = window.event }
  vidPaneID.style.left = document.documentElement.scrollLeft + e.clientX + voxoffset+'px';
  vidPaneID.style.top = document.documentElement.scrollTop + e.clientY + voyoffset+'px';
}

function moveHandler(e){      
  if (e == null) { e = window.event }
  if (e.button<=1&&dragOK){         
    savedTarget.style.left=e.clientX-dragXoffset+'px';
    savedTarget.style.top=e.clientY-dragYoffset+'px';
    return false;
  }   
}   

function cleanup(e) {
  document.onmousemove=null;
  document.onmouseup=null;
  savedTarget.style.cursor=orgCursor;
  dragOK=false;
}   

function dragHandler(e){
  var htype='-moz-grabbing';
  if (e == null) { e = window.event; htype='move';}
  var target = e.target != null ? e.target : e.srcElement;
  orgCursor=target.style.cursor;
  if (target.className=="vidFrame") {
    savedTarget=target;
    target.style.cursor=htype;
    dragOK=true;
    dragXoffset=e.clientX-parseInt(vidPaneID.style.left);
    dragYoffset=e.clientY-parseInt(vidPaneID.style.top);
    document.onmousemove=moveHandler;
    document.onmouseup=cleanup;
    return false;
  }
}

function clearList(ul){
  var list = document.getElementById(ul);
  while (list.firstChild) {
    list.removeChild(list.firstChild);
  }		
}

function mousOverImage(name,id,nr){
  if(name)
    imname = name;
  imname.style.border = '1px solid #0000ff';
  imname.src = "http://img.youtube.com/vi/"+id+"/"+nr+".jpg";
  nr++;
  if(nr > 3)
    nr = 1;
  timer =  setTimeout("mousOverImage(false,'"+id+"',"+nr+");",1000);
}


function mouseOutImage(name){
  if(name)
    imname = name;
  imname.style.border = 	'1px solid #ffffff';
  if(timer)
    clearTimeout(timer)
}

function getVideoId(url){
  var match = url.indexOf('=');
  if (match) {
    id = url.substring(match+1,match+12);
    return id;
  }
}

function getId(string){
  var match = string.lastIndexOf("'s Videos");
  if (match != -1) {
    id = string.substring(0,match);
    return id.toLowerCase();
  }
  var match = string.lastIndexOf("query");
  if (match != -1) {
    id = string.substring(match+7);
    return id.toLowerCase();
  }
}

function listVideos(json,divid) {
  divid.innerHTML = '';
  var totalResults = json.feed.openSearch$totalResults.$t;
  var searchStart = json.feed.openSearch$startIndex.$t;
  var searchItems = json.feed.openSearch$itemsPerPage.$t;
  var searchEnd = (parseInt(searchStart)+parseInt(searchItems)-1);
  var nextSearch = searchEnd+1;
  var prevSearch = (searchEnd - (parseInt(searchItems)*2))+1;
  var ul = document.createElement('ul');
  ul.setAttribute('id', 'youtubelist');
  var li = document.createElement('li');
  li.setAttribute('id', 'youtubebox');
  if (((parseInt(searchStart))>1) && (searchEnd < parseInt(totalResults))) {
    li.innerHTML = '<table cellspacing=0><tr><td class="odd" style="border-bottom: 3px double #000000" align=left valign=middle><b>"'+searchTerms+'"</b> '+resultslabel+'<b> '+searchStart+' '+tolabel+' '+searchEnd+' </b> '+aboutlabel+' <b>'+totalResults+'</b></td><td class="odd" style="border-bottom: 3px double #000000" align="right"><input style="font-size:80%" type="button" value="'+backlabel+'" onclick="insertVideos(\'youtubeDivSearch\',\'search\',\''+searchTerms+'\','+prevSearch+','+searchItems+','+inlineVideo+','+voheight+','+vowidth+','+voxoffset+','+voyoffset+',\''+closelabel+'\',\''+previewlabel+'\',\''+resultslabel+'\',\''+tolabel+'\',\''+aboutlabel+'\',\''+nextlabel+'\',\''+backlabel+'\',\''+titlelabel+'\',\''+desclabel+'\',\''+authorlabel+'\',\''+viewslabel+'\',\''+durationlabel+'\',\''+pubdatelabel+'\',\''+pubtimelabel+'\');"/><input style="font-size:80%" type="button" value="'+nextlabel+'" onclick="insertVideos(\'youtubeDivSearch\',\'search\',\''+searchTerms+'\','+nextSearch+','+searchItems+','+inlineVideo+','+voheight+','+vowidth+','+voxoffset+','+voyoffset+',\''+closelabel+'\',\''+previewlabel+'\',\''+resultslabel+'\',\''+tolabel+'\',\''+aboutlabel+'\',\''+nextlabel+'\',\''+backlabel+'\',\''+titlelabel+'\',\''+desclabel+'\',\''+authorlabel+'\',\''+viewslabel+'\',\''+durationlabel+'\',\''+pubdatelabel+'\',\''+pubtimelabel+'\');"/></td></tr></table>';       
  }else if (searchEnd >= (parseInt(totalResults))){
    li.innerHTML = '<table cellspacing=0><tr><td class="odd" style="border-bottom: 3px double #000000" align=left valign=middle><b>"'+searchTerms+'"</b> video results<b> '+searchStart+' to '+totalResults+' </b> of about <b>'+totalResults+'</b></td><td class="odd" style="border-bottom: 3px double #000000" align="right"><input style="font-size:80%" type="button" value="'+backlabel+'" onclick="insertVideos(\'youtubeDivSearch\',\'search\',\''+searchTerms+'\','+prevSearch+','+searchItems+','+inlineVideo+','+voheight+','+vowidth+','+voxoffset+','+voyoffset+',\''+closelabel+'\',\''+previewlabel+'\',\''+resultslabel+'\',\''+tolabel+'\',\''+aboutlabel+'\',\''+nextlabel+'\',\''+backlabel+'\',\''+titlelabel+'\',\''+desclabel+'\',\''+authorlabel+'\',\''+viewslabel+'\',\''+durationlabel+'\',\''+pubdatelabel+'\',\''+pubtimelabel+'\');"/></td></tr></table>';       
  }else{
    li.innerHTML = '<table cellspacing=0><tr><td class="odd" style="border-bottom: 3px double #000000" align=left valign=middle><b>"'+searchTerms+'"</b> video results<b> '+searchStart+' to '+searchEnd+' </b> of about <b>'+totalResults+'</b></td><td class="odd" style="border-bottom: 3px double #000000" align="right"><input style="font-size:80%" type="button" value="'+nextlabel+'" onclick="insertVideos(\'youtubeDivSearch\',\'search\',\''+searchTerms+'\','+nextSearch+','+searchItems+','+inlineVideo+','+voheight+','+vowidth+','+voxoffset+','+voyoffset+',\''+closelabel+'\',\''+previewlabel+'\',\''+resultslabel+'\',\''+tolabel+'\',\''+aboutlabel+'\',\''+nextlabel+'\',\''+backlabel+'\',\''+titlelabel+'\',\''+desclabel+'\',\''+authorlabel+'\',\''+viewslabel+'\',\''+durationlabel+'\',\''+pubdatelabel+'\',\''+pubtimelabel+'\');"/></td></tr></table>'; 
  }
  ul.appendChild(li);
    
  if(json.feed.entry){
    for (var i = 0; i < json.feed.entry.length; i++) {
      var entry = json.feed.entry[i];
      for (var k = 0; k < entry.link.length; k++) {
        if (entry.link[k].rel == 'alternate') {
          url = entry.link[k].href;
          break;
        }
      }
      var thumb = entry['media$group']['media$thumbnail'][1].url;
      var li = document.createElement('li');
      var videotitle = entry.title.$t;
      var vtitle = videotitle.split("\'").join("\\'"); 
      videotitle = videotitle.split("\'").join("&#39;");
      videotitle = videotitle.split("\"").join("&#34;");
	  var youtubedescription = entry['media$group']['media$description'].$t;
	  youtubedescription = youtubedescription.split("\'").join("\\'");
      youtubedescription = youtubedescription.split("\'").join("&#39;");
      youtubedescription = youtubedescription.split("\"").join("&#34;");
      var ytauthor = entry.author[0].name.$t;
      ytauthor = ytauthor.split("\'").join("&#39;");
      ytauthor = ytauthor.split("\"").join("&#34;");
      li.setAttribute('id', 'youtubebox');
      var vdesc = " ";
	  
      if (inlineVideo == 1) {
        // Thumbnail Only Search Results Display
        if (ytdesccopy == 1 ) {
		  li.innerHTML = '<a href="javascript:playVid("'+getVideoId(url)+'","'+vtitle+'","'+youtubedescription+'");"><img src="'+thumb+'" id="youtubethumb" alt="'+vtitle+'" title="'+vtitle+'"  onmouseout="mouseOutImage(this)" onmouseover="mousOverImage(this,"'+getVideoId(url)+'",2)"></a>';
		} else {
		  li.innerHTML = '<a href="javascript:playVid("'+getVideoId(url)+'","'+vtitle+'","'+vdesc+'");"><img src="'+thumb+'" id="youtubethumb" alt="'+vtitle+'" title="'+vtitle+'"  onmouseout="mouseOutImage(this)" onmouseover="mousOverImage(this,"'+getVideoId(url)+'",2)"></a>';
		}
	  } else {
        var youtubeviews = entry['yt$statistics'].viewCount;
        var youtubeduration = entry['media$group']['yt$duration'].seconds;
        var youtubepublished = entry.published.$t;
        youtubepublished = youtubepublished.split("T");
        var youtubepublisheddate = youtubepublished[0];
        var youtubepublished2 = youtubepublished[1];
        youtubepublished2 = youtubepublished2.split("-");
        var youtubepublishedtime = youtubepublished2[0];

        // Expanded Search Results Display
        li.innerHTML = '<table><tr><td class="even" width=125px align=center valign=middle style="border: 1px solid #ffffff"><a href="javascript:playVid(\''+getVideoId(url)+'\',\''+vtitle+'\',\''+youtubedescription+'\');" onclick="javascript:getMousePos(event);"><div id="'+videotitle+'"><img src="'+thumb+'" id="youtubethumb" alt="'+videotitle+'" title="'+videotitle+'"  onmouseout="mouseOutImage(this)" onmouseover="mousOverImage(this,\''+getVideoId(url)+'\',2)" ></div></a></td><td class="even" align=left valign=top style="border: 1px solid #ffffff"><b>'+titlelabel+':</b> '+videotitle+'<br><b>'+desclabel+':</b> '+youtubedescription+'<br><b>'+authorlabel+':</b> '+ytauthor+'<br><b>'+viewslabel+':</b> '+youtubeviews+'<br><b>'+durationlabel+':</b> '+youtubeduration+'<br><b>'+pubdatelabel+':</b> '+youtubepublisheddate+'<br><b>'+pubtimelabel+':</b> '+youtubepublishedtime+'</td></tr></table>';
      }
      ul.appendChild(li);
    }
  if (((parseInt(searchStart))>1) && (searchEnd < parseInt(totalResults))) {
    li.innerHTML = '<table cellspacing=0><tr><td class="odd" style="border-bottom: 3px double #000000" align=left valign=middle><b>"'+searchTerms+'"</b> '+resultslabel+'<b> '+searchStart+' '+tolabel+' '+searchEnd+' </b> '+aboutlabel+' <b>'+totalResults+'</b></td><td class="odd" style="border-bottom: 3px double #000000" align="right"><input style="font-size:80%" type="button" value="'+backlabel+'" onclick="insertVideos(\'youtubeDivSearch\',\'search\',\''+searchTerms+'\','+prevSearch+','+searchItems+','+inlineVideo+','+voheight+','+vowidth+','+voxoffset+','+voyoffset+',\''+closelabel+'\',\''+previewlabel+'\',\''+resultslabel+'\',\''+tolabel+'\',\''+aboutlabel+'\',\''+nextlabel+'\',\''+backlabel+'\',\''+titlelabel+'\',\''+desclabel+'\',\''+authorlabel+'\',\''+viewslabel+'\',\''+durationlabel+'\',\''+pubdatelabel+'\',\''+pubtimelabel+'\');"/><input style="font-size:80%" type="button" value="'+nextlabel+'" onclick="insertVideos(\'youtubeDivSearch\',\'search\',\''+searchTerms+'\','+nextSearch+','+searchItems+','+inlineVideo+','+voheight+','+vowidth+','+voxoffset+','+voyoffset+',\''+closelabel+'\',\''+previewlabel+'\',\''+resultslabel+'\',\''+tolabel+'\',\''+aboutlabel+'\',\''+nextlabel+'\',\''+backlabel+'\',\''+titlelabel+'\',\''+desclabel+'\',\''+authorlabel+'\',\''+viewslabel+'\',\''+durationlabel+'\',\''+pubdatelabel+'\',\''+pubtimelabel+'\');"/></td></tr></table>';       
  }else if (searchEnd >= (parseInt(totalResults))){
    li.innerHTML = '<table cellspacing=0><tr><td class="odd" style="border-bottom: 3px double #000000" align=left valign=middle><b>"'+searchTerms+'"</b> video results<b> '+searchStart+' to '+totalResults+' </b> of about <b>'+totalResults+'</b></td><td class="odd" style="border-bottom: 3px double #000000" align="right"><input style="font-size:80%" type="button" value="'+backlabel+'" onclick="insertVideos(\'youtubeDivSearch\',\'search\',\''+searchTerms+'\','+prevSearch+','+searchItems+','+inlineVideo+','+voheight+','+vowidth+','+voxoffset+','+voyoffset+',\''+closelabel+'\',\''+previewlabel+'\',\''+resultslabel+'\',\''+tolabel+'\',\''+aboutlabel+'\',\''+nextlabel+'\',\''+backlabel+'\',\''+titlelabel+'\',\''+desclabel+'\',\''+authorlabel+'\',\''+viewslabel+'\',\''+durationlabel+'\',\''+pubdatelabel+'\',\''+pubtimelabel+'\');"/></td></tr></table>';       
  }else{
    li.innerHTML = '<table cellspacing=0><tr><td class="odd" style="border-bottom: 3px double #000000" align=left valign=middle><b>"'+searchTerms+'"</b> video results<b> '+searchStart+' to '+searchEnd+' </b> of about <b>'+totalResults+'</b></td><td class="odd" style="border-bottom: 3px double #000000" align="right"><input style="font-size:80%" type="button" value="'+nextlabel+'" onclick="insertVideos(\'youtubeDivSearch\',\'search\',\''+searchTerms+'\','+nextSearch+','+searchItems+','+inlineVideo+','+voheight+','+vowidth+','+voxoffset+','+voyoffset+',\''+closelabel+'\',\''+previewlabel+'\',\''+resultslabel+'\',\''+tolabel+'\',\''+aboutlabel+'\',\''+nextlabel+'\',\''+backlabel+'\',\''+titlelabel+'\',\''+desclabel+'\',\''+authorlabel+'\',\''+viewslabel+'\',\''+durationlabel+'\',\''+pubdatelabel+'\',\''+pubtimelabel+'\');"/></td></tr></table>'; 
  }
  ul.appendChild(li);

}else{
  	divid.innerHTML = 'No Results Found';
  }
  document.getElementById(divid).appendChild(ul);
}

function youtubeInit(root) {
  id = getId(root.feed.title.$t);
  listVideos(root, youtubediv[id]);
}

function insertVideos(div,typ,q,startindex,results,overlay,vsoheight,vsowidth,vsoxoffset,vsoyoffset,desccopyflag,label_01,label_02,label_03,label_04,label_05,label_06,label_07,label_08,label_09,label_10,label_11,label_12,label_13,label_14){
  closelabel = label_01;
  previewlabel = label_02;
  resultslabel = label_03;
  tolabel = label_04;
  aboutlabel = label_05;
  nextlabel = label_06;
  backlabel = label_07;
  titlelabel = label_08;
  desclabel = label_09;
  authorlabel = label_10;
  viewslabel = label_11;
  durationlabel = label_12;
  pubdatelabel = label_13;
  pubtimelabel = label_14;
  inlineVideo = overlay;
  searchTerms = q;
  voheight = vsoheight;
  vowidth = vsowidth;
  voxoffset = vsoxoffset;
  voyoffset = vsoyoffset;
  ytdesccopy = desccopyflag;
  youtubediv[q.toLowerCase()] = div;
  clearList(div);
  var script = document.createElement('script');
  if(typ == "search")
    script.setAttribute('src', 'http://gdata.youtube.com/feeds/videos?vq='+q+'&start-index='+startindex+'&max-results='+results+'&format=5&alt=json-in-script&callback=youtubeInit');
  script.setAttribute('id', 'jsonScript');
  script.setAttribute('type', 'text/javascript');
  document.documentElement.firstChild.appendChild(script);
}

document.onmousedown=dragHandler;

//-->