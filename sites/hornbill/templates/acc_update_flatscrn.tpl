<div id="WinAccUpdateFil" class="centerbox" style=" width:440px; height:110px; padding:10px; z-index:99; overflow:hidden" >
 <input type='hidden' id="EntPth" name="EntPth" \>
 <input type='hidden' id="pWin" name="pWin" \>
 <div style="position:absolute; top:40px;left:10px ; " >
  Select File: <input id="fN" type="file" name="fN[]" multiple><button onclick="abortRead();">Cancel</button>
  <div id="pBar"><div class="percent">0%</div></div>
 </div>
</div>
 <script type = "text/javascript">
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
      progress.style.width = '0%';
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
      xhr.open("POST", "/ajx.php?srv=30452&cat=srv_" + pWin.value , true);
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
  document.getElementById('fN').addEventListener('change', handleFileSelect, false);
</script>
<style>
  #pBar {
    margin: 10px 0;
    padding: 3px;
    border: 1px solid #000;
    font-size: 14px;
    clear: both;
    text-align:center;
    opacity: 0;
    -moz-transition: opacity 1s linear;
    -o-transition: opacity 1s linear;
    -webkit-transition: opacity 1s linear;
  }
  #pBar.loading {
    opacity: 1.0;
  }
  #pBar .percent {
    background-color: #99ccff;
    height: auto;
    width: 0;
  }
</style>
