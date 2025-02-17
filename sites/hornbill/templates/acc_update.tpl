<div id="WinAccUpdate" class="centerbox" style="left:10px; top:10px;width:1100px; height:404px ; " >

<!--input style="position:absolute;  left:50px; top:30px; width:100px;" type="text" id="EntId" >
<button style="position:absolute;  left:150px; top:30px;width:20px;"  onclick="Call_Search('WinAccUpdate','12260','EntId', '0', '0', 'EntId');"  >?</button-->


<DIV id="DivAtch" class="fileSet" style="position:absolute; left:10px; top:60px;width:500px; height:300px; border:1px solid #f00;" >
  <button onClick="Call_Service('WinAccUpdate',30454,30452,null,null,'hdnPth', 'EntPth','SHW');"  style="bottom: 0px; top: auto; height: 24px; left: 0pt; right: 0pt; margin-right: auto; position: absolute; margin-left: auto; border: 1px solid; background: transparent;">Add Attachment</button>
  <input type='hidden' id='OL_INSVW' value='12261'>
  <input type='hidden' id='OL_DELVW' value='12262'>
 </div>


<!--DIV id="DivAtch" class="fileSet" style="position:absolute; left:550px; top:60px;width:500px; height:300px; border:1px solid #f00;" >
  <button onClick="Call_Service('WinAccUpdate',30454,30452,null,null,'hdnPth', 'EntPth','SHW');"  style="bottom: 0px; top: auto; height: 24px; left: 0pt; right: 0pt; margin-right: auto; position: absolute; margin-left: auto; border: 1px solid; background: transparent;">Add Attachment</button>
  <input type='hidden' id='OL_INSVW' value='12261'>
  <input type='hidden' id='OL_DELVW' value='12262'>
 </div-->



 
</div>

<script type = "text/javascript">
function fileAttach () {
     var cWin = Call_Service('WinAccUpdate',30454,30452 ,null,null,'hdnPth', 'EntPth','SHW');
  }

/*script type = "text/javascript">
function fileAttach () {
     var cWin = Call_Service('WinAccUpdate',30454,30452 ,null,null,'hdnPth', 'EntPth','SHW');
  }
*/

/*
  var reader;
  var progress = document.querySelector('.percent');

  function abortRead() {
    reader.abort();
  }

  function errorHandler(evt) {
    switch(evt.target.error.code) {
      case evt.target.error.NOT_FOUND_ERR:
        alert('File Not Found!');
        break;
      case evt.target.error.NOT_READABLE_ERR:
alert('File is not readable');
        break;
      case evt.target.error.ABORT_ERR:
        break; // noop
      default:
        alert('An error occurred reading this file.');
    };
  }

  function updateProgress(evt) {
    // evt is an ProgressEvent.
    if (evt.lengthComputable) {
      var percentLoaded = Math.round((evt.loaded / evt.total) * 100);
      // Increase the progress bar length.
 if (percentLoaded < 100) {
        progress.style.width = percentLoaded + '%';
        progress.textContent = percentLoaded + '%';
      }
    }
  }
  function handleFileSelect(evt) {
    // Reset progress indicator on new file selection.

    var pWin = document.getElementById('pWin');
/*    if (pWin.value == '' ) {
      evt.stopPropagation();
      return  false ;
    }*/
/*      progress.style.width = '0%';
    progress.textContent = '0%';

    var fN = evt.target.files; 
    reader = new FileReader();
    reader.onerror = errorHandler;
    reader.onprogress = updateProgress;

    var xhr = new XMLHttpRequest();
    var bndry = '------multipartformboundary' + (new Date).getTime();
    var dl = '--';
    var str = '' ;
    var cf     = '\r\n';
reader.onabort = function(e) {
      alert('File read cancelled');
    };
    reader.onloadstart = function(e) {
      var pBar = document.getElementById('pBar');
      pBar.className = 'loading';
      //$(msgW).show('slow');
    };
    reader.onload = function(e) {
      // Ensure that the progress bar displays 100% at the end.
      progress.style.width = '100%';
      progress.textContent = 'Loading Completed.';
//      setTimeout("document.getElementById('pBar').className='';", 2000);
      str += dl +  bndry + cf + 'Content-Disposition: form-data; name="fN"; filename="' + fN[0].name + '"' + cf ;
      str += 'Content-Type: application/octet-stream' + cf + cf + e.target.result + cf ;
      str += dl + bndry + cf ;
      str += dl + bndry + dl + cf ;
//alert(str);
      xhr.open("POST", "/ajx.php?srv=12178&cat=srv_" + pWin.value , true);
      xhr.setRequestHeader('content-type', 'multipart/form-data; boundary=' + bndry);
//alert("Sending ...") ;
      xhr.sendAsBinary(str);        
      xhr.onload = function(evt) { 
         if (xhr.responseText) {
            dt = eval(xhr.responseText) ;
            var entPth = document.getElementById('EntPth');
            entPth.value = dt[1] ;
       alert(entPth.value);
        };
      }
    };
    reader.readAsBinaryString(fN[0]);
  }
  document.getElementById('fN').addEventListener('change', handleFileSelect, false);*/
</script>

