<!--

var timer;
var i =0;
var voheight;
var vowidth;

function playVid(vidCode,vidId,title,thumb,voheight,vowidth) {
  if (vidPaneID.style.display=='block') {
    vidPaneID.style.display='none';
    vidPaneID.innerHTML=''; 
  } else {
    vidPaneID.style.display='block';
    vidPaneID.innerHTML='<center><A HREF="javascript:playVid()">CLOSE VIDEO</A></center>';
    var vidstring ='<center><embed src="'+vidId+'" width="'+vowidth+'" height="'+voheight+'" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" flashVars="playerVars=autoPlay=yes" type="application/x-shockwave-flash"> </embed>';;
    vidstring+='</center>';
    vidstring+='<center>** Portable Video Preview **<br>';
    vidstring+=title+'</center>';
    vidPaneID.innerHTML+=vidstring;
    myform = window.document.submitvideoform;
    myform.code.value=vidCode;
    myform.title.value=title;
    myform.embedcode.value=vidId;
    myform.thumb.value=thumb;
  }
}

function getMousePos(e,voxoffset,voyoffset){
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
  dragOK=true;
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


document.onmousedown=dragHandler;

//-->