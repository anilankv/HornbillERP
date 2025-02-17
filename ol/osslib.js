var xH ;
var prtW ;
var j_g =0 ;
var winA_g = new Array();  // Available Windows ;
var srvA_g = new Array();  // Available Services ;
var tmrId_g = 0;
var tmrPause_g = 0 ;
var tmrCnt_g= 0 ;
var tmrStr_g;
var dbgDv_g = null;
var alrtDv_g = null;
var w_g;       // Screen Div
var twSwitch_g;
var chatTrg_g;
var mnuTrg_g;
var fde_g;       // Screen Div
var whlFct = 8 ;
var pstDt_g = null ; // GtkObject *PreWin_g ;
var vS_g = null ;
var bdy_g, top_g, lft_g, rgt_g, bot_g ;
try {
  if (typeof XMLHttpRequest.prototype.sendAsBinary == 'undefined') {
    XMLHttpRequest.prototype.sendAsBinary = function(text){
      var data = new ArrayBuffer(text.length);
      var ui8a = new Uint8Array(data, 0);
      for (var i = 0; i < text.length; i++) ui8a[i] = (text.charCodeAt(i) & 0xff);
      this.send(ui8a);
    }
  }
} catch (e) {}
if(typeof addEvent !=  'function'){
   var addEvent = function(o, t, f, l){
      var d = 'addEventListener', n = 'on' + t, rO = o, rT = t, rF = f, rL = l ;
      if(o[d]&&!l)return o[d](t, f, false) ;
      if(!o._evts)o._evts = { } ;
      if(!o._evts[t]){
         o._evts[t] = o[n]?{ b:o[n] } :{ } ;
         o[n] = new Function('e','var r=true,o=this,a=o._evts["' + t + '"],i; for(i in a){o._f=a[i];r=o._f(e||window.event)!=false&&r;o._f=null} return r');
         if(t !=  'unload')addEvent(window, 'unload', function(){ removeEvent(rO, rT, rF, rL) })
      }
      if(!f._i)f._i = addEvent._i ++ ;
      o._evts[t][f._i] = f
   } ;
   addEvent._i = 1 ;
   var removeEvent = function(o, t, f, l){
      var d = 'removeEventListener' ;
      if(o[d]&&!l)return o[d](t, f, false) ;
      if(o._evts&&o._evts[t]&&f._i)delete o._evts[t][f._i]
   }
}
//var wEv = isEvtVld(bdy_g,'mousewheel') ? 'mousewheel' : 'wheel';
function getFncObj(str) {
    var s = window;
    var sA = str.split('.');
    for (i = 0; i < sA.length - 1; i++) {
        s = s[sA[i]];
        if (s == undefined) return;
    }
    return s[sA[sA.length - 1]];
}
function launchIntoFullscreen(o) {
   if(o.requestFullscreen) {
      o.requestFullscreen();
   } else if(o.mozRequestFullScreen) {
      o.mozRequestFullScreen();
   } else if(o.webkitRequestFullscreen) {
      o.webkitRequestFullscreen();
   } else if(o.msRequestFullscreen) {
      o.msRequestFullscreen();
   }
}
function exitFullscreen() {
   if(document.exitFullscreen) {
      document.exitFullscreen();
   } else if(document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
   } else if(document.webkitExitFullscreen) {
      document.webkitExitFullscreen();
   }
}
function FullScreen( o, ev) {
   launchIntoFullscreen(document.documentElement);
   rmCls(o.li,'open')
}
function _l(el, evname, fnc,p1,p2,p3,p4 ) {
   if(!el) return ;
   if (el.addEventListener) { // Gecko / W3C
      el.addEventListener(evname, function(e){ fnc(this,e,p1,p2,p3,p4) ; }, true);
   } else if (el.attachEvent) { // IE
      el.attachEvent("on" + evname, function(e){ fnc(this,e,p1,p2,p3,p4);});
   } else {
      el["on" + evname] = function(e){ fnc(this,e,p1,p2,p3,p4);};
   } 
}
function setHdNd(tp, fn, doc, m) {
   if (doc == null) doc = document ;
   var hd = doc.getElementsByTagName('head')[0];
   var nd = document.createElement((tp=='js') ? 'script':'link');
   var url =  tp + '/' + fn + '.' + tp ;
   if (tp=='js') {
      _s(null, url, nd, setHdNdCf) ;
      nd.setAttribute('language', 'javascript');
      nd.setAttribute('type', 'text/javascript');
//      nd.setAttribute('src', '/' + url );
   } else {
      nd.setAttribute('rel', 'stylesheet');
      nd.setAttribute('type', 'text/css');
      if(m) nd.setAttribute('media', m);
//      nd.setAttribute('title', 'dynamicLoadedSheet');
      nd.setAttribute('href', window.location.origin + '/' + url );
//if (nd.styleSheet){
//  nd.styleSheet.cssText = nd.innerHTML;
//} else {
//  nd.appendChild(document.createTextNode(nd.innerHTML));
//}
   }
//DebugInfo("type (" + tp + ") fn (" + fn + ") doc (" + doc + ") m (" + m + ")  => " + url    ) ;
   hd.appendChild(nd);
}
function setHdNdCf(nd) {
   this.innerHTML = this.xR.responseText ;
}
function nF(msg){ };
function strpos (str, ptn, ofs) {
   var pos = (str + '').indexOf(ptn, (ofs||0)) ;
   return (pos === -1) ? false : pos ;
}
if (!xH) {
   try {
      xH = new ActiveXObject("Msxml2.XMLHTTP")
   } catch (e) {
      try {
         xH = new ActiveXObject("Microsoft.XMLHTTP")
      } catch (e) {
         try {
            xH = new XMLHttpRequest();
         } catch (e) {
            alert ('Your browser does not support XMLHttpRequest');
            xH = false ;
         }
      }
   }
}
xH.onreadystatechange = nF ;
xH.onerror = nF ;
function process_key(e) {
   var e = (e) ? e : ((event) ? event : null);
   var node = (e.target) ? e.target : ((e.srcElement) ? e.srcElement : null);
   if ((e.keyCode == 13) && (node.type=="text"))  {return false;}
//   if([32, 37, 38, 39, 40].indexOf(e.keyCode) > -1) {
//      if(node.id !='gDiv') {
//         e.preventDefault(); 
//         e.stopPropagation();
//         return false ;
//      }
//   }
}
function isCls(o,c) {
  if(!o) return false ;
  if(!o.className) return false ;
  return !!o.className.match(new RegExp('(\\s|^)'+c+'(\\s|$)'));
}
function addCls(o,c) {
  if(!o) return false ;
  if (!isCls(o,c)) o.className += " "+c;
}
function rmCls(o,c) {
    if(!o) return false ;
    var r = new RegExp('(\\s|^)'+c+'(\\s|$)');
    o.className=o.className.replace(r,' ');
}
function setCls(o,c) {
  o.className = c;
}
function switchCls(o,c,n) {
    if(!o) return false ;
    var r = new RegExp('(\\s|^)'+c+'(\\s|$)');
    o.className=o.className.replace(r,n);
}
document.onkeypress = process_key ;
//document.onkeyup = process_key ;
function buildPOST(theFormName) {
   theForm = document.forms[theFormName];
   var qs = '' ;
   var e = 0;
   for (e=0;e<theForm.elements.length;e++) {
      if (theForm.elements[e].name!='') {
         var name = theForm.elements[e].name;
         qs+=(qs=='')?'':'&'
         qs+= name+'='+_e(theForm.elements[e].value);
      }
   }
   qs+="\n";
   return qs
}
function buildPostWithoutForm(win, isVld) {
   var dt = getRawPostWithoutForm(win, isVld) ;
   if( !(dt )){
      return false ;
   }
   return _e(dt);
}

function open_url( urlTo) {
   xH.open("POST", urlTo, false)
//      xH.setRequestHeader('Content-Type', data.length )
//      xH.send(data)
   if ( xH.status!=200) {
       DebugInfo("Url: "+urlTo+" not found");
      return null;
   }
}
function _as(data, urlTo,o, cf, p1, p2, p3, p4, ct=null) {
   return _s(data, urlTo,o, cf, p1, p2, p3, p4, 1, ct) ;
} ;
function _s(data, urlTo,o, cf, p1, p2, p3, p4, af=0, ct=null) {
//console.info ( '_s', af, ct, urlTo, data, o) ; 
//console.info(' _s d', (data) ? data.length : 'null', urlTo, 'o', o, 'cf', cf, 'p1', p1, 'p2',p2, 'p3',p3, 'p4',p4, 'af',af, 'ct',ct) ;
//console.info(' _s ', urlTo) ;
   var xR = (af) ? new XMLHttpRequest() : xH;
   xR.st = 0 ;
   xR.cf = cf ;
   xR.p = [ p1, p2, p3, p4] ;
   xR.onload = function() { 
      if(this.st == 1 ) return ;
      this.st = 1 ;
      if(o)o.xR = this ;
      if(this.cf) this.cf.apply(o ? o:this, this.p);
   };
   xR.onerror = function() {
      //DebugInfo('No response (' + xR + ')'  ) ;
      nF('') ;
   };
   xR.open((data)?"POST" :"GET", "/" + urlTo, (af == 1)?true:false);
   //xR.open((data)?"POST" :"GET", "/" + urlTo, true);
   if(data) {
      xR.setRequestHeader('Content-Type', (ct) ? ct : 'application/json' );
      //xR.setRequestHeader('Content-Type', (ct) ? ct : data.length );
      if(data != '' ) xR.send(data);
      else xR.send('#' ) ;
   } else {
      xR.send() ;
   }
   if (xR.readyState === 4 && xR.status === 200) {
      if(af == 0) return xR.responseText ;
      else xR.onload() ;
      //xR.onload() ;
   }
}
function getUrlParam( k ){
   k = k.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
   var r = "[\\?&]"+k+"=([^&#]*)";  
   var rx = new RegExp( r );  
   var rs = rx.exec( window.location.href ); 
   if( rs == null )    return "";  
   else    return rs[1];
}
function setInnerHtml(o, dt) {
//console.info(' setInnerHtml o ', o, '  dt.len ', dt.length ) ;  
   if (!dt || dt.length === 0 ){
      o.innerHTML = dt;
      return ;
   }
   var tO = createTag ( 'div', 'tO', 'fade' , bdy_g, 'none', null ) ;
   tO.innerHTML = dt;
   o.appendChild(tO.firstChild);
   tO.remove();
}
function initSrv (oW, srv, key, mod ) {
   if(oW) {
      var msg_g = this.getResponseHeader("msg_g" ) ;
      if (msg_g) {
         var grp_g = this.getResponseHeader("grp_g" ) ;
         var gnm_g = this.getResponseHeader("gnm_g" ) ;
         setRollInfo(grp_g, gnm_g) ;
         if(msg_g !='') DebugInfo("<P>" + msg_g + "</P>" ) ;
      }
      if(!(oW.iW[oW.act])){
	 setInnerHtml(oW, eval(xH.responseText)['bdy']) ;
         //var dt=eval(xH.responseText)['bdy'];
         //var tO = createTag ( 'div', 'tO', 'fade' , bdy_g, 'none', null ) ;
         //tO.innerHTML = dt;
//console.info(' initSrv 258 ', tO, '  bdy ', dt ) ;  
         //oW.appendChild(tO.firstChild);
         //tO.remove();
      }
//      oW.appendChild(this.responseText) ;
      srv = oW.act ;
      key = (oW.key) ? oW.key : oW.val ;
      mod = oW.mod ;
//console.info(' initSrv 264 ', 'srv',  srv, 'key', key, 'oWkey', oW.key, 'oWval', oW.val, 'mod', mod, 't0', tO ) ;  
//         oW.appendChild(tO.firstChild);
   }
   srv = (srv=='1001')? '997' : srv ;
   var bdyonly = (oW) ? '1':'0' ;
   var url = "index.php?f=3&srv=1139&id=srv:wid:wpm:trg:grp:agr:sact:grd:ws:js:css:fnc&ssrv=" + srv + "&bdyonly=" + bdyonly ;
//   var url = "index.php?f=3&srv=1139&id=srv:wid:wpm:trg:grp:agr:sact:grd:ws:js:css:fnc&ssrv=" + srv  ;
// _s(data, urlTo,o, cf, p1, p2, p3, p4, af=0, ct=null) 
   _s(null, url,false, setStruct,srv, key, mod, oW,1);
}
function tempFn (b, e ) {
   var tO = e.target ;
   e.preventDefault();
   e.stopPropagation();
   return false ;
} 
window.onbeforeunload = confirmExit ;
function confirmExit() {
   if(w_g) {
      if (( w_g.mod_g != 'SHW') && (w_g.mod_g != 'NRM') ) {
         return "Your have attempted to leave this page.  If you have made any changes to the fields without clicking the Save/Confirm button, your changes will be lost.  Are you sure you want to exit this page?";
      }
   }
}
function setStruct (srv, key,mod, oW) {
//console.info( 'setStruct L312 oW ' + oW + ' w_g ' + w_g + ' srv ' + srv + ' key ' + key + ' mod ' + mod  ) ;
   var wid ;
   var dt = eval( this.responseText ) ;
   if (!(dt)) return ;
   if (!(dt['srv'])) {
      return ;
   }
   w_g = getElmntById ( dt['srv'][srv][2], (oW) ? oW : bdy_g ) ;
   if (! w_g ){
      if ( dt['srv'][srv][9] == 't' ) return ;
      alertBox("Module not Configured (" + dt['srv'][srv][0] + " | " + dt['srv'][srv][2] + ") 9( " + dt['srv'][srv][9]  + ")<br>" ) ;
      return ;
   }
   if (oW) {
      if (!oW.wA[w_g.id]) {
         oW.wA[w_g.id] = w_g ;
      } else {
      }
      w_g = oW.wA[w_g.id] ;
//      oW.style.display = 'block' ;
//      w_g.style.display = 'block' ;
//      setDivFade(oW.parentNode, true ) ;
   }
   if(!(w_g.tlD)) {
      for ( var d in dt['css'] ) {
         setHdNd('css', d ) ;
      }
      for ( var d in dt['js'] ) {
         setHdNd('js', d ) ;
      }
      for ( var d in dt['fnc'] ) {
         eval(d) ;
      }
      if(!(dt['srv'][srv])) return ;
      //fde_g = createTag ( 'div', 'fde_g', 'fade' , bdy_g.parentNode, 'none', null ) ;
   }
   w_g.w = w_g ;
   w_g.i_f = 1 ;
   w_g.srv_g = srv ;
   w_g.olDt = null;
   winA_g[w_g.id] = w_g ;
   if(!(w_g.tlD)) {
      srvA_g['s'+srv] = w_g.id ;
      w_g.grp = new Array() ;
      w_g.vR = new Array() ;
      w_g.btn = new Array() ;
      w_g.parm = new Array() ;
      w_g.eA = new Array() ;
      w_g.mA = new Array() ;
      w_g.wA = new Array() ;
      w_g.dbP = new Array() ; // For temp storage of wid param
      w_g.ws = new Array() ; // For storage of wid status
      w_g.fde_g = createTag ( 'div', 'fde_g', 'fade' , w_g, 'none', null ) ;
      w_g.exDt = new Array();
      w_g.nam_g = dt['srv'][srv][1] ;
      w_g.mod_g = dt['srv'][srv][3] ;
      w_g.ttl_g = dt['srv'][srv][4] ;
      w_g.stl_g = dt['srv'][srv][5] ;
      w_g.typ_g = dt['srv'][srv][6] ;
      w_g.tmf_g = dt['srv'][srv][8] ;
      w_g.hfl_g = dt['srv'][srv][10] ;
      w_g.rfl_g = dt['srv'][srv][11] ;
      w_g.cfl_g = dt['srv'][srv][12] ;
      if( dt['srv'][srv][7] != '' ) w_g.kw_g = getElmntById( dt['srv'][srv][7], w_g ) ;
      w_g.wA[w_g.id] = w_g ;
      for ( var mw in dt['ws'] ) {
         if ((mw == "itemValidation") || (mw=="isArray") ) continue ;
         var m = mw.split("||")[0] ;
         var w = mw.split("||")[1] ;
         if (w_g.ws[w] == undefined ){
            w_g.ws[w] = new Array() ; 
         }
         if (w_g.ws[w][m] == undefined ){
            w_g.ws[w][m] = new Object() ; 
            w_g.ws[w][m].v = dt['ws'][mw][1] ;
            w_g.ws[w][m].s = dt['ws'][mw][2] ;
         }
      }
      setWidgets (w_g, dt['wid'],dt['wpm']);
//console.info("setStruct 368 w_g", w_g, ' winA_g[w_g.id] ', winA_g[w_g.id] ) ;
      w_g.hB = createTag ( 'div', 'srvHlpIcn', '' , w_g, 'none', null ) ;
      w_g.hB.onclick = function(){getHelp(this.parentNode)} ;
      if(w_g.kw_g != undefined ){
         w_g.kw_g.gK = new Array() ;
         setKeyWidEvent( w_g, w_g.kw_g) ;
      }
      for ( var m in dt['sact'] ){
         if ((m == "itemValidation") || (m=="isArray") ) continue ;
         w_g.mA[m] = new Object() ; 
         w_g.mA[m].nam = dt['sact'][m][1] ;
         w_g.mA[m].ord = dt['sact'][m][2] ;
         w_g.mA[m].lbl = dt['sact'][m][3] ;
         w_g.mA[m].icn = dt['sact'][m][4] ;
         w_g.mA[m].typ = dt['sact'][m][5] ;
         w_g.mA[m].hfl = dt['sact'][m][6] ;
         w_g.mA[m].rfl = dt['sact'][m][7] ;
         w_g.mA[m].cfl = dt['sact'][m][8] ;
         w_g.mA[m].ag = new Array() ; 
      }
      for( g in dt['grp']) {
         if ((g == "itemValidation") || (g=="isArray") ) continue ;
         w_g.grp[g] = new Object() ; 
         if ( dt['grp'][g][2].length ) {
            w_g.grp[g].kw = w_g.wA[dt['grp'][g][2]] ;
            if (w_g.grp[g].kw) {
               if (w_g.grp[g].kw.gK == undefined ) {
                  w_g.grp[g].kw.gK = new Array() ;
               }
               w_g.grp[g].kw.gK[g] = g ;
               setKeyWidEvent( w_g, w_g.grp[g].kw) ;
            }
         } else w_g.grp[g].kw = null ;
         w_g.grp[g].v = new Array() ;
         for ( var v in dt['gv'][g] ) {
            if ((v == "itemValidation") || (v=="isArray") ) continue ;
            w_g.grp[g].v[v] = v ;
         }
         w_g.grp[g].w = new Array() ;
         for ( var w in dt['gw'][g] ) {
            if ((w == "itemValidation") || (w=="isArray") ) continue ;
            w_g.grp[g].w[w] = (w_g.wA[w]) ? w_g.wA[w] : w_g.dbP[w]['gw'] ; 
            if ( wid = w_g.wA[w] ) {
               wid.g = (wid.g) ? wid.g : new Array  ;
               wid.g[g] = new Object ;
               wid.g[g].v = dt['gw'][g][w][2] ;
               wid.g[g].c = dt['gw'][g][w][3] ;
            } else {
               w_g.dbP[w]['gw'].g = (w_g.dbP[w]['gw'].g) ? w_g.dbP[w]['gw'].g : new Array ;
               w_g.dbP[w]['gw'].g[g] = new Object ;
               w_g.dbP[w]['gw'].g[g].v = dt['gw'][g][w][2] ;
               w_g.dbP[w]['gw'].g[g].c = dt['gw'][g][w][3] ;
            }
         }
         w_g.grp[g].s = new Array() ;
         for ( var s in dt['sg'][g] ) {
            if ((s == "itemValidation") || (s=="isArray") ) continue ;
            w_g.grp[g].s[s] = s ;
         }
         for ( var m in dt['agr'][g] ) {
            if ((m == "itemValidation") || (m=="isArray") ) continue ;
            w_g.mA[m].ag[g] = g ;
         }
         w_g.grp[g].actFlg = 0 ;
      }
      popScrWithDfl(w_g) ;
      setGridDtl (w_g, dt['grd']);
      for ( var d in dt['trg'] ) {
         var ds = dt['trg'][d] ;
//console.info(' trg ', d, ds) ;   
         if ((ds == "itemValidation") || (ds=="isArray") ) continue ;
         var Wid = w_g.wA[ds[1]] ;
         if(!Wid) {
           if(w_g.dbP[ds[1]]){
              w_g.dbP[ds[1]]['trg'] = ds ;
              continue ;
           } else Wid=w_g ;
         }
         if (!( Wid.eA )) Wid.eA = new Array() ;
         Wid.parm = (!Wid.parm) ? new Array() : Wid.parm ;
         if ( ds[2] == 'A' ) Wid.parm["af" + ds[3]] = ds[4] ;
         if ( ds[2] == 'B' ) Wid.parm["bf" + ds[3]] = ds[4] ;
         if ( ds[2] == 'E' ) {
//console.info(Wid.id,  ' Wid ', Wid , ' ds ' , ds) ;
            if(( ds[3] == 'input') || (ds[3] == 'change') ) { // To be applied for all type ds[2]='E'
               _l(Wid, ds[3], getFncObj(ds[4]), Wid);
               Wid.eA[ds[3]]= getFncObj(ds[4]) ; 
            } else Wid.eA[ds[3]] = ds[4] ;
         } else if ( ds[2] == 'D' ) {
            if(( Wid.av) && (Wid.tagName !='SELECT') ) Wid.eA[ds[3]] = ds[4] ;
            else if (Wid.t == 'grid' ) Wid.eA[ds[3]] = ds[4] ;
            //if(( Wid.av) && (Wid.tagName !='SELECT') ) Wid.eA[ds[3]] = function() { ds[4]} ;
            //else if (Wid.t == 'grid' ) Wid.eA[ds[3]] = function() {ds[4] }  ;
            else {
               ds[4] = ds[4].replace(/\\"/g, '"')
                        .replace(/([\{|:|,])(?:[\s]*)(")/g, "$1'")
                        .replace(/(?:[\s]*)(?:")([\}|,|:])/g, "'$1")
                        .replace(/([^\{|:|,])(?:')([^\}|,|:])/g, "$1\\'$2");
 
               Wid.setAttribute(ds[3], ds[4]) ;
               if ( ds[3].toUpperCase() == 'ONCHANGE' ) Wid.onchange = ds[4] ;
               if ( ds[3].toUpperCase() == 'ONCLICK' ) Wid.onclick = ds[4] ;
               //if ( ds[3].toUpperCase() == 'ONCHANGE' ) Wid.onchange = function() {ds[4]} ;
               //if ( ds[3].toUpperCase() == 'ONCLICK' ) Wid.onclick = function() {ds[4]} ;
            }
         }
//console.info ('L 466 w_g ', w_g, 'wA', w_g.wA, 'Wid',  Wid, 'eA', Wid.eA, ' ds ', ds )
      }
      setCurSrvDet( w_g ) ;
   }
   //setDrag(w_g) ;
   place_lbl(w_g) ;
//   setEvents (w_g);
   if(!key) key = '' ;
   if( key != '' ) setKey (key, w_g) ;
//   if (!eA) timerStart() ;
   w_g.tabIndex = 0 ;
   w_g.focus() ;
   var w1 = getUrlParam('w1') ;
   var w2 = getUrlParam('w2') ;
   var v1 = getUrlParam('v1') ;
   var v2 = getUrlParam('v2') ;
   if (w_g.wA[w1]) setWidVal( w_g, w_g.wA[w1], v1 ) ;  
   if (w_g.wA[w2]) setWidVal( w_g, w_g.wA[w2], v2 ) ;  
   WFalert() ; 
   if (w_g.tmf_g == 't') timerStart() ;
   setScreenMode( w_g, w_g.mod_g, true )
   for ( var m in w_g.mA ){
      if ((m == "itemValidation") || (m=="isArray") ) continue ;
      if( m == w_g.mod_g ){
         onBtnPress(0,0, m, w_g ) ;
         break ;
      }
   }
   place_lbl(w_g) ;
   w_g.i_f = 0 ;
//console.info ('oW', oW, w_g, 'w_g.srv_g', w_g.srv_g )
   if(oW){
      oW.iW[w_g.srv_g] = w_g ;
      w_g.oW = oW ;
//console.info ('Calls fillCW oW', oW, w_g, 'w_g.srv_g', w_g.srv_g, 'oW.iW[w_g.srv_g]',  oW.iW[w_g.srv_g] )
      fillCW(oW.iW[w_g.srv_g]) ;
   } else {
      if(top_g)top_g.parentNode.style.display = 'block' ;
      twSwitch_g = getElmntById('tw-switch', top_g ) ;
      _l(twSwitch_g, "change", tglMnuBar);
      chatTrg_g = getElmntById('chat-trigger', top_g ) ;
      if(chatTrg_g)chatTrg_g.sts = 0 ;
      _l(chatTrg_g, "click", tglChatBar);
      mnuTrg_g = getElmntById('menu-trigger', top_g ) ;
      if(mnuTrg_g)mnuTrg_g.sts = 0 ;
      _l(mnuTrg_g, "click", tglMnuBar);
   }
   winA_g[w_g.id] = w_g ;
}
function setWidgets (win, dt, dp){
   var Wid  ;
   if( dt ) {
      for ( w in dt ) {
         if ((w == "itemValidation") || (w=="isArray") ) continue ;
         if (win.wA[w]) continue ;
         if (win.wA[w] = getElmntById( w, win )) {
            Wid = win.wA[w] ;
            Wid.w = win ;
            Wid.f = dt[w][1] ;
            Wid.title = dt[w][2] ;
            Wid.d = dt[w][3] ;
            Wid.i = dt[w][4] ;
            Wid.n = dt[w][5] ;
            Wid.t = dt[w][6] ;
            if(dt[w][7].length) Wid.tabIndex = dt[w][7] ;
//           $sql = "select wnm,stp, av, ac, acg, ss, lf, bf, agf, pf, pl from sw.widget where snm = '$snm' " ;
            if ( dp[w] ) {
               Wid.stp = dp[w][1] ;
               Wid.av = (dp[w][2] == '') ? false : dp[w][2] ;
               Wid.ac = dp[w][3] ;
               Wid.aGrp = dp[w][4] ;
               Wid.ss = dp[w][5] ;
               Wid.lF = dp[w][6] ;
               Wid.bF = dp[w][7] ;
               Wid.agF = dp[w][8] ;
               Wid.prF = dp[w][9] ;
               Wid.pL = dp[w][10] ;
               Wid.srF = dp[w][11] ;
               Wid.ssrv = dp[w][12] ;
               if (Wid.tagName=='SELECT'){
                  if (Wid.av) {
                     Wid.cv = Wid.av ;
                     Wid.av = null ;
                  } else Wid.cv = ( vW = getElmntById(Wid.id + ":vno", win) ) ? vW.value : -1 ;
               } else if (Wid.av && (Wid.t != 'mtag')) {
                  Wid.rV = (Wid.rV) ? Wid.rV : '' ;
                  _l(Wid, "keyup", autoComplete, Wid);
                  _l(Wid, "click", autoComplete, Wid);
                  _l(Wid, "focus", autoComplete, Wid);
               }
            }
            if ((Wid.tagName=='SELECT')||(Wid.tagName=='INPUT')||(Wid.tagName=='TEXTAREA')||(Wid.t=='mtag')||(Wid.t=='Gallery')){
               if( dt[w][2].length ){
                  Wid.lbl = createTag ( 'label', 'popLbl', 'lbl' , Wid.parentNode, Wid.style.display,  dt[w][2]) ;
                  Wid.lbl.o = Wid ;
//                  Wid.lbl.style.top= parseInt(Wid.getBoundingClientRect().top) - parseInt(Wid.lbl.getBoundingClientRect().height) - parseInt(Wid.parentNode.getBoundingClientRect().top) + 'px' ;
                  Wid.lbl.setAttribute('for', Wid.id ) ;
               }
               if (Wid.tagName=='SELECT'){
                  Wid.bg = createTag ( 'div', 'cmbBg', 'cmbBg' , Wid.parentNode, 'block',null,null,'f'  ) ;
                  Wid.bg.o = Wid ;
               }
            }
            _l(Wid, "keyup", setWidEvt, Wid);
            _l(Wid, "focus", setWidEvt, Wid);
            _l(Wid, "blur", setWidEvt, Wid);
            if (Wid.st == undefined ) Wid.st = new Array() ; 
            //if(Wid.f)if (( Wid.f.charAt(0) == 'D') && (Wid.type == 'text' ) ) setDateWid ( Wid, win ) ; 
            if(Wid.f)if ( Wid.f.charAt(0) == 'D') Wid.type='date' ; 
            if(Wid.dtM){
               addCls( Wid.dtD, 'dw' ) ;
               _l(Wid.dtM, "keyup", setWidEvt, Wid);
               _l(Wid.dtM, "focus", setWidEvt, Wid);
               _l(Wid.dtM, "blur", setWidEvt, Wid);
               _l(Wid.dtY, "keyup", setWidEvt, Wid);
               _l(Wid.dtY, "focus", setWidEvt, Wid);
               _l(Wid.dtY, "blur", setWidEvt, Wid);
            }else addCls( Wid, 'dw' ) ;
            if ( Wid.t == 'tinyMCE' ) {
               Wid.edtr = getElmntById(Wid.id + "_parent", Wid.parentNode );
               Wid.style.display = 'none' ;
            }
            if ( Wid.t == 'Gallery' ) {
               if (Wid.pic == undefined ) Wid.pic = new Array() ;
            }
            Wid.eA = new Array() ;
            if ( Wid.t == 'alrt' ) setWidVal(win, wid, 0) ;
         } else {
            win.dbP[w] = dt[w] ; 
            win.dbP[w]['gw'] = new Object ; 
         }
      }
   } 
   for ( w in win.dbP ) {
      if ((w == "itemValidation") || (w=="isArray") ) continue ;
      if (win.wA[w] = getElmntById( w, win )) {
         Wid = win.wA[w] ;
         Wid.w = win ;
         Wid.f = win.dbP[w][1] ;
         Wid.title = win.dbP[w][2] ;
         Wid.d = win.dbP[w][3] ;
         Wid.i = win.dbP[w][4] ;
         Wid.n = win.dbP[w][5] ;
         if(win.dbP[w][7].length) Wid.tabIndex = win.dbP[w][7] ;
         Wid.ssrv= win.dbP[w][12] ;
//         if (Wid.st == undefined ) Wid.st = new Array() ; 
         //if(Wid.f)if (( Wid.f.charAt(0) == 'D') && (Wid.type == 'text' ) ) setDateWid ( Wid, win ) ; 
         if(Wid.f)if ( Wid.f.charAt(0) == 'D') Wid.type='date' ; 
         if(win.dbP[w]['gw'] ) Wid.g = win.dbP[w]['gw'].g ;
         Wid.eA = new Array() ;
//console.info(' win.dbP set w', w, 'dbp',  win.dbP[w]['trg']) ;
         if(win.dbP[w]['trg']) {
            var ds = win.dbP[w]['trg'] ;
            if ( ds[2] == 'E' ) {
//console.info(Wid.id,  ' Wid ', Wid , ' ds ' , ds) ;
               if( ds[3] == 'input' ) {
                  _l(Wid, ds[3], ds[4], Wid);
               } else Wid.eA[ds[3]] = ds[4] ;
            } else if ( ds[2] == 'D' ) {
               Wid.eA[ds[3]] = ds[4] ;
               Wid.setAttribute(ds[3], ds[4] ) ;
//DebugInfo( Wid.id +  ' 2. eAlenth=>.' + Wid.eA[ds[3]] + ' |0 ' + ds[0] + ' |1| ' + ds[1] + ' |2| ' + ds[2] + ' |3| ' + ds[3] + ' |4| ' + ds[4] ) ;
            }
         }
         if ( Wid.t == 'alrt' ) setWidVal(win, wid, 0) ;
      }
   }
}
function place_lbl(win) {
   var l ;
   for (w in win.wA) {
      place_widlbl(win.wA[w]);
   }
}
function place_widlbl(wid) {
   if(!(wid)) return ;
   if(!(l = wid.lbl)) return ;
   var p = (l.dtD) ? l.dtD : l.parentNode ;
   // l.style.top= parseInt(l.o.getBoundingClientRect().top) - parseInt(p.getBoundingClientRect().top) -15 + 'px' ;
   // l.style.left= parseInt(l.o.getBoundingClientRect().left) - parseInt(p.getBoundingClientRect().left) + 'px' ;
   if(wid.value) (wid.value.length ) ? addCls(l,'up') : rmCls(l, 'up');
}
function setWidEvt (b,e,o){
   if((o.av)&&(e.type=='focus')&&(o.tagName == 'INPUT' )) autoComplete(b,e,o) ;
   var val = getRawVal( o.w, o) ;
   if (o.lbl){
      if(val !== "") addCls(o.lbl,'up');
      else if (o.rCnt > 0) addCls(o.lbl,'up');
      else rmCls(o.lbl,'up');
      if(e.type=='focus') {
         addCls(o.lbl,'up');
         addCls(o.lbl,'on');
      }
      if(e.type=='blur') rmCls(o.lbl,'on');
   }
   if (((o.tagName=='INPUT') && (!o.av))||(o.tagName=='TEXTAREA')){
      if(e.type=='blur')  isValidData(o.w, o) ;
   }
   if(e.type=='focus') {
      if( isCls(o.parentNode, 'fg-line' )) addCls( o.parentNode, 'fg-toggled' ) ;
      if(o.dtM) addCls( o.dtD, 'on' ) ;
      else addCls( o, 'on' ) ;
   }
   if(e.type=='blur') {
      if( isCls(o.parentNode, 'fg-line' )) rmCls( o.parentNode, 'fg-toggled' ) ;
      if(o.dtM) rmCls( o.dtD, 'on' ) ;
      else rmCls( o, 'on' ) ;
   }
}
function setGridDtl (win, dt, rs){
   var Wid  ;
   if( dt ) {
      for ( wc in dt ) {
         if ((wc == "itemValidation") || (wc=="isArray") ) continue ;
         var w = wc.split(":")[0] ;
         var c = parseInt(wc.split(":")[1]) ;
         if (win.wA[w]) {
            Wid = win.wA[w] ;
            if (Wid.fP == undefined ) Wid.fP = new Array() ;
            if (Wid.fP[c] == undefined ) Wid.fP[c] = new Object() ;
            Wid.fP[c].vf = dt[wc][1] ;
            Wid.fP[c].sf = dt[wc][2] ;
            Wid.fP[c].af = dt[wc][3] ;
            Wid.fP[c].tp = dt[wc][4] ;
            Wid.fP[c].ag = dt[wc][5] ;
            Wid.fP[c].tl = dt[wc][6] ;
            Wid.fP[c].wd = dt[wc][7] ;
            Wid.fP[c].cv = dt[wc][8] ;
            Wid.fP[c].sv = dt[wc][9] ;
            Wid.fP[c].cf = dt[wc][10] ;
            Wid.fP[c].aw = dt[wc][11] ;
            Wid.fP[c].ac = dt[wc][12] ;
            Wid.fP[c].fn = Wid.fP[c].tl ;
            Wid.fP[c].cw = Wid.fP[c].wd ;
         }
      }  
   } else if (rs && rs[1]) {
      for ( c=0; c < rs[1].length ; c++ ){
         var fP = rs[1][c].split(":") ;
         if (win.fP == undefined ) win.fP = new Array() ;
         if (win.fP[c] == undefined ) win.fP[c] = new Object() ;
         if( !(fP[1])) fP[1] = (fP[0] == '_s' ) ? 'hhhi' : "vhst" ;
         if( fP[1].length != 4) fP[1] = "vhst"  ;
         if( !(fP[2])) fP[2] = 100 ;
         win.fP[c].vf = fP[1].charAt(0) ;
         win.fP[c].sf = fP[1].charAt(1) ;
         win.fP[c].af = fP[1].charAt(2) ;
         win.fP[c].tp = fP[1].charAt(3) ;
         win.fP[c].ag = 'f' ;
         win.fP[c].tl = fP[0] ;
         win.fP[c].wd = fP[2] ;
         win.fP[c].cv = (fP[3]) ? fP[3] : '-1' ;
         win.fP[c].sv = '' ;
         win.fP[c].cf = '' ;
         win.fP[c].aw = '' ;
         win.fP[c].fn = rs[1][c] ;
         win.fP[c].cw = win.fP[c].wd ;
      }
   }
}
function setDateWid(w, win) {
   if ( w.dtM ) return ;
   if(!w.n) w.n = 'NULL' ;
   var d = createTag ( 'div', null, 'DDMMYYYY' , null, w.style.display, null ) ;
   d.style.top = w.style.top ;
   d.style.left = w.style.left ;
   d.style.float = w.style.float ;
   d.style.position = w.style.position ;
   w.style.position = null ;
   w.dtD = d ; // 12-02-2012
   w.parentNode.insertBefore(d, w);
   d.appendChild(w) ;
   addCls(w, 'd') ;
   w.dtM = createTag ( 'input', w.id + '.m', 'm' , d, w.style.display, null ) ;
   w.dtY = createTag ( 'input', w.id + '.y', 'y' , d, w.style.display, null ) ;
   w.dtB = createTag ( 'button', w.id + '.b', 'b glyphicon glyphicon-calendar' , d, w.style.display, null ) ;
   w.dtM.onblur = w.onblur ;
   w.dtY.onblur = w.onblur ;
   OssCal.setup( w, w.dtB,win );
}
function getRawPostWithoutForm(win, isVld, grid) {
   var pA = new Array();
   var qs = '', i, j, rtn ;
   if(!win) return pA ;
   if ( win.kw_g) {
      pA['k'] = getWidVal( win, win.kw_g ) ;
      pA['k']= (pA['k'] == undefined) ? 'NULL' : pA['k'] ;
      pA['k']= (pA['k'].length) ? pA['k'] : 'NULL' ;
      if(win.kw_g.av) pA['k__'] = pA['k'] + '||' + win.kw_g.value ;
      pA[win.kw_g.id] = pA['k'] ;
   }
   pA['m'] = getUrlParam("m") ;
   if( win.wA == undefined) return pA ;
   for ( var w in win.wA) {
      if ((w == "itemValidation") || (w=="isArray") ) continue ;
      var e =  win.wA[w] ;
      if(!e) continue ;
      if ( e.t == 'grid' ) {
         if( e.sR ) {
            for ( var c=0 ; c < e.fP.length ; c++ ) {
              pA[c + ":" +  e.id] = getWidVal(win, e.gN.col[c] ) ;
              if(e.gN.col[c].av) pA[c + ":" +  e.id + '__' ] = pA[c + ":" +  e.id] + '||' + e.gN.col[c].value ;
            }
         } 
         if( e.f == 'M' ) {
           pA[ "mV:" +  e.id] = e.mV ;
         }
         if ( grid ) {
            if(e.id == grid.id){ 
               rtn = getGridPost(win, isVld, grid, pA) ;
               if ( rtn == null ) return null ;
            }
         } else {
            if(isVld){
               if( ( e.i) && ( e.i =='t') ){
                  if( e.rCnt < 1){
                     alert( 'There should be atleast one record in grid !!!') ;
                     e.focus() ;
                     return null ;
                  }
               }
            }
            rtn = getGridPost(win, false, e, pA) ;
//            if ( rtn == null ) return null ;
         }
      } else if ((e.t == 'fileSet') || (e.t == 'Photo' ) ) {
         pA[e.id] = (e.rV) ? e.rV : null ;
      } else  if( ( e.t == 'mtag' ) && (e.f == 'M') ) {
           pA[ "mV:" +  e.id] = e.mV ;
      } else {
         var arr = w.split(':') ;
         if( arr.length == 2 ) continue ;
         if( e == win ) continue ;
         if(isVld){
            var val = isValidData(win, e) ; 
            if( val == '_N' ){
               return null ;
//               if(!(grid) ) return null ; 
//               else if (win.typ_g == 'LoV' ) return null ;
            }
            pA[w] = val ;
         } else {
            pA[w] = getWidVal(win,e) ; 
         }
         if(e.av) pA[w+'__'] = pA[w] + '||' + e.value ;
      }
   }
   return pA ;
}
function getGridPost(win, isVld, e, pA) {
   if( !(e.gN)) return pA ;
   for ( var c=0 ; c < e.fP.length ; c++ ) {
      if(isVld){
         v = isValidData(win, e.gN.col[c]) ; 
         if( v == '_N' ) return null ; 
      } else v = getWidVal(win,e.gN.col[c]) ; 
      pA[c + ":" +  e.id] = v ;
      if(e.gN.col[c])if(e.gN.col[c].av) pA[c + ":" +  e.id+'__'] = v + '||' + e.gN.col[c].value ;
   }
   return pA ;
}
function setScreen( win, isPopul ){
//console.info( 'setScreen win', win.id, ' win.mod_g ', win.mod_g, ' win.pmod', win.pmod, 'isPopul', isPopul ) ;
//   if(win.i_f > 1 ) return ;
//   if(win.i_f == 1 ) win.i_f = 2 ;
   if ( win.kw_g ) {
      win.kw_g.chgFlg = 0 ;
   }
   if (win.mA[win.mod_g]==undefined) win.mod_g = 'SHW' ;
   win.bS.setAttribute('scrmod',win.mod_g);
//DebugInfo( '821  pmod ' + win.pmod + ' mod_g ' + win.mod_g + ' mA ' + win.mA ) ;
   if((win.pmod) && ( win.mod_g != win.pmod)) {
      for ( var ag in win.mA[win.pmod].ag ) {
         if ((ag == "itemValidation") || (ag=="isArray") ) continue ;
         //setGroup(win, ag, false ) ;
      }
   }
   for ( var ag in win.mA[win.mod_g].ag ) {
      if ((ag == "itemValidation") || (ag=="isArray") ) continue ;
      setGroup(win, ag, isPopul ) ;
      fillGroup ( win, ag ) 
   }
   if ( win.kw_g ) {
      if (win.kw_g.gK == undefined ) {
         win.kw_g.gK = new Array() ;
      }
      win.kw_g.gK[ag] = ag ;
      win.kw_g.chgFlg = 1 ;
   }
   for ( var i in win.wA) {
      var w = win.wA[i] ;
      if (!w) continue ;
      if (w==win) continue ;
      if ((win.ws[w.id] != undefined ) && (win.ws[w.id][win.mod_g] != undefined)) {
         tglWidShw ( w, (win.ws[w.id][win.mod_g].v == 't' ) ? true : false ) ;
         tglWidEbl ( w, (win.ws[w.id][win.mod_g].s == 't' ) ? true : false ) ;
      } else {
         tglWidShw ( w, true ) ;
         if ( win.typ_g != 'LoV' ) tglWidEbl ( w, false ) ;
      }
   }
   if ( win.eA['onset'] ) {
      eval(win.eA['onset']) ;
   }
}
function popScrWithDfl(win) {
//console.info("popScrWithDfl 871 win  ", win.id, ' gA ', win.grp) ;
   setWidDfl ( win, win.kw_g) ;
   if ((win.mod_g == 'NRM') && (win.mA[Mod] == undefined)) win.mod_g = 'SHW' ;
   var Mod = win.mod_g ;
   for ( var g in win.grp ) {
      //if ((g == "itemValidation") || (g!="isArray") ) continue ;
//console.info("popScrWithDfl 877  ", win.id, ' g ', g) ;
      popGrpDfl ( win.id, g ) 
   }
}
function setKeyWidEvent( win, wid){
   if ( wid.tagName != 'DIV' ) {
      //wid.setAttribute("onchange", "onKeyWidChng('" + win.id + "', '" + wid.id + "')") ;
      _l(wid, "change",onKeyWidChng);
   }
   wid.chgFlg = 1 ;
} 
function onKeyWidChng( wid ){
   if (!( wid.w)) return;
   if (wid == undefined ) return ;
   if (wid.gK == undefined ) return ;
   if (wid.chgFlg == undefined ) wid.chgFlg = 1 ;
//   if(wid.w.i_f > 1 ) return ;
//   if(wid.w.i_f == 1) wid.w.i_f = 2 ;
   if (wid.chgFlg == 1 ) { // if (wid.getAttribute("chgFlg") == 1 )){
      wid.chgFlg = 0 ;
      if( wid.gK.length == 0 ) {
         setScreen( wid.w ) ;
      } else {
         for ( var g in wid.gK ) {
           if ((g == "itemValidation") || (g=="isArray") ) continue ;
           //setGroup ( wid.w, g, 1 ) ;
           fillGroup ( wid.w, g ) ;
         }
      }
      wid.chgFlg = 1 ; 
   }
}
function setChgFlg( winid, widid, flg, grp ){
   win = winA_g[winid] ;
   wid = win.wA[widid] ;
   if ( flg ) {
      wid.chgFlg = 0 ;
      if( grp == -1 ) {
         setScreen( win ) ;
      } else {
         //setGroup ( win, grp ) ;
         fillGroup ( win, grp ) ;
      }
      wid.chgFlg = 1 ;
   }
   wid.chgFlg = 1 ;
}
function callNotes (b,e,win,s ) {
   Call_Service( win.id, s, win.kw_g.id,null,null,null,null) ;
}
function setCurSrvDet( win ){
   var o_tag ;
   win.tlD = createTag ( 'div', 'wtl', 'divTtl', win, 'block', win.nam_g, null, null,'f' ) ; 
   win.cB = createTag ( 'div', 'cB', 'cB glyphicon glyphicon-remove-sign', win.tlD, 'block', null ) ;
   _l(win.cB, "click",closeDiv, win, true,null);
   if( win.cfl_g == 't' ) {
      win.pntB = createTag ( 'button', 'pntB', 'pntB', win.tlD, 'none', win.mA['PNT'].lbl, 't' ) ;
      win.slpB = createTag ( 'button', 'slpB', 'slpB ', win.tlD, 'none', win.mA['SLP'].lbl, 't' ) ;
      win.rmkB = createTag ( 'button', 'rmkB', 'rmkB ', win.tlD, 'none', win.mA['RMK'].lbl, 't' ) ;
      win.pntB.title = win.mA['PNT'].lbl ;
      win.slpB.title = win.mA['SLP'].lbl ;
      win.rmkB.title = win.mA['RMK'].lbl ;
      _l(win.pntB, "click",callNotes, win, 14071);
      _l(win.slpB, "click",callNotes, win, 14072);
      _l(win.rmkB, "click",callNotes, win, 14070);
        //   var newwindow=window.open('index.php?f=5&srv=14518&mid='+mid,'_blank');
   } else {
      tglWidShw(win.rmkB, false ) ;
   }
   if ( win.foot == undefined ) {
      win.foot = createTag ('div', 'pgFoot', null, win, 'block' ) ; 
      win.bS = getElmntById("OSLBtnDiv" , win ) ;
      win.bS = (win.bS) ? win.bS : win.foot ;
      addCls(win.bS, 'btnSet');
      for ( var a in win.mA ){
         if ((a == "itemValidation") || (a=="isArray") ) continue ;
         if (win.mA[a].typ == '0') continue ;
         if ( win.typ_g == 'LoV' ) continue ;
         Wid = createTag ('button', a + 'Btn', null, win.bS, 'block', win.mA[a].lbl.toUpperCase()  ) ; 
         _l(Wid, "click",onBtnPress, a, win);
         Wid.title = win.mA[a].nam ;
         win.btn[a] = Wid ; 
         Wid.style.display = 'none' ;
         if ( win.typ_g != 'LoV' ) {
            if(( win.mA[a].hfl != 't') && (!win.btn['SAV']) ) {
               Wid = createTag ( 'button', 'savBtn', null, win.bS, 'none', win.mA['SAV'].lbl.toUpperCase() ) ; 
               _l(Wid, "click",onBtnPress, 'SAV', win);
               Wid.title = win.mA['SAV'].lbl ;
               win.btn['SAV'] = Wid ; 
            } else if (!win.btn['HLD']) {
               Wid = createTag ( 'button', 'hldBtn', null, win.bS, 'none', win.mA['HLD'].lbl.toUpperCase() ) ; 
               _l(Wid, "click",onBtnPress, 'HLD', win);
               Wid.title = win.mA['HLD'].lbl ;
               win.btn['HLD'] = Wid ; 
               Wid = createTag ( 'button', 'pcdBtn', null, win.bS, 'none', win.mA['PCD'].lbl.toUpperCase() ) ; 
               _l(Wid, "click",onBtnPress, 'PCD', win);
               Wid.title = win.mA['PCD'].lbl ;
               win.btn['PCD'] = Wid ; 
            }
            if(( win.mA[a].rfl == 't') && (!win.btn['RTN']) ) {
               Wid = createTag ( 'button', 'rtnBtn', null, win.bS, 'none', win.mA['RTN'].lbl.toUpperCase() ) ; 
               _l(Wid, "click",onBtnPress, 'RTN', win);
               win.btn['RTN'] = Wid ; 
               Wid.title = win.mA['RTN'].lbl ;
            }
            if( win.mA[a].cfl == 't' ) setRmkDiv(win) ;
         }
      }
      if ( win.typ_g != 'LoV' ) {
         Wid = createTag ( 'button', 'canBtn', null, win.bS, 'none', win.mA['CAN'].lbl.toUpperCase() ) ; 
         _l(Wid, "click",onBtnPress, 'CAN', win);
         win.btn['CAN'] = Wid ; 
         Wid.title = win.mA['CAN'].lbl ;
      }
   }
}
function setRmkDiv(win) {
   if (win.rD) return ;
   win.rD = createTag ( 'div', 'rD', 'rD', win.bS, 'none', null, null ) ; 
   win.rNoteW = createTag ( 'textarea', 'rNoteW', 'rNoteW', win.rD, 'block', null ) ; 
   win.rNoteW.lbl = createTag ( 'label', 'popLbl', 'lbl' , win.rD, 'block', 'Remarks') ;
   win.rNoteW.lbl.style.top= parseInt(win.rNoteW.getBoundingClientRect().top) - parseInt(win.rD.getBoundingClientRect().top) -15 +'px';
   win.rNoteW.style.left= parseInt(win.rNoteW.getBoundingClientRect().left) - parseInt(win.rD.getBoundingClientRect().left) + 'px' ;
   (win.rNoteW.innerHTML.length ) ? addCls(win.rNoteW.lbl,'up') : rmCls(win.rNoteW.lbl, 'up');
//   win.rNoteW.lbl.setAttribute('for', win.rNoteW.id ) ;
   _l(win.rNoteW, "blur", setWidEvt, win.rNoteW);
   _l(win.rNoteW, "focus", setWidEvt, win.rNoteW);
   _l(win.rNoteW, "keyup", setWidEvt, win.rNoteW);
   win.rSlipW = createTag ( 'textarea', 'rSlipW', 'rSlipW', win.rD, 'block', null ) ; 
   win.rSlipW.lbl = createTag ( 'label', 'popLbl', 'lbl' , win.rD, 'block', 'Slip') ;
   win.rSlipW.lbl.style.top= parseInt(win.rSlipW.getBoundingClientRect().top) - parseInt(win.rD.getBoundingClientRect().top) -15 +'px';
   win.rSlipW.style.left= parseInt(win.rSlipW.getBoundingClientRect().left) - parseInt(win.rD.getBoundingClientRect().left) + 'px' ;
   (win.rSlipW.innerHTML.length ) ? addCls(win.rSlipW.lbl,'up') : rmCls(win.rSlipW.lbl, 'up');
//   win.rSlipW.lbl.setAttribute('for', win.rSlipW.id ) ;
   _l(win.rSlipW, "blur", setWidEvt, win.rSlipW);
   _l(win.rSlipW, "focus", setWidEvt, win.rSlipW);
   _l(win.rSlipW, "keyup", setWidEvt, win.rSlipW);
   win.rPNoteW = createTag ( 'textarea', 'rPNoteW', 'rPNoteW', win.rD, 'block', null ) ; 
   win.rPNoteW.lbl = createTag ( 'label', 'popLbl', 'lbl' , win.rD, 'block', 'Private note') ;
   win.rPNoteW.lbl.style.top= parseInt(win.rPNoteW.getBoundingClientRect().top)-parseInt(win.rD.getBoundingClientRect().top) -15 +'px';
   win.rPNoteW.style.left= parseInt(win.rPNoteW.getBoundingClientRect().left) - parseInt(win.rD.getBoundingClientRect().left) + 'px' ;
   (win.rPNoteW.innerHTML.length ) ? addCls(win.rPNoteW.lbl,'up') : rmCls(win.rPNoteW.lbl, 'up');
//   win.rPNoteW.lbl.setAttribute('for', win.rPNoteW.id ) ;
   _l(win.rPNoteW, "blur", setWidEvt, win.rPNoteW);
   _l(win.rPNoteW, "focus", setWidEvt, win.rPNoteW);
   _l(win.rPNoteW, "keyup", setWidEvt, win.rPNoteW);
}
function Create_PDF( win, act, o, dt ){
//   if( pstDt_g == null ){
//      pstDt_g =  buildPostWithoutForm(winA_g[winid], false) ;
//   }
//   var url = "index.php?f=4&srv=" + act + "&sNm=" + winid + "&sEv=" + winA_g[winid].mod_g + "&vno=" + vno + "&ssrv=" + ssrv  ;
   var wnm = (o) ? "&sct=m&wNm=" + o.id : '' ;
   var url = "index.php?f=4&srv=" + act + "&sNm=" + win.id + "&sEv=" + win.mod_g + "&ssrv=" + win.srv_g + wnm ;
//   _s(pstDt_g, url,winA_g[winid], View_PDF) ;
   _s(_e(dt), url,false, View_PDF, win ) ;
//   window.open( url )  ;
}  
function Create_CSV( win, act, o, dt ){
   var wnm = (o) ? "&sct=m&wNm=" + o.id : '' ;
   var url = "index.php?f=3&srv=" + act + "&sNm=" + win.id + "&sEv=" + win.mod_g + "&ssrv=" + win.srv_g + wnm ;
   _s(_e(dt), url,false, View_PDF, win ) ;
}  
function View_PDF (o ) {
//   var f = new Blob([this.responseText], {type: 'application/pdf'});
//   var u = URL.createObjectURL(f);
//   var rs = eval(this.responseText.replace(/&amp;/g, "\&"));
   var rs = eval(this.responseText);
   if(rs['fN']) window.open( "index.php?f=3&srv=1136&ssrv=" + o.srv_g + "&fno=" + rs['fN'])  ;
//   alert(print_r(this)) ;
//   win=window.open('"data:application/pdf;base64, " + base64EncodedPDF')
//   win=window.open('data:' + rs['mime'] + ':base64, ' + responseText)
//   win.document.open()
//   win.document.write(this.responseText)
//   win.document.close()
}
function Execute_Service( winid, act, vSrc, cSrc, cDst, rSrc, rDst, mod, gp ){
   var val = (getElmntById(vSrc, winA_g[winid])) ? getWidVal(winA_g[winid], winA_g[winid].wA[vSrc]) : vSrc ;
   if( pstDt_g == null ){
      pstDt_g =  buildPostWithoutForm(winA_g[winid], false) ;
   }
   gp = (!gp) ? '' : gp ;
   var url = "index.php?f=2&srv=" + act + "&sNm=" + winid + "&sEv=" + winA_g[winid].mod_g + "&k=" + val + gp  ;
   _s(pstDt_g, url,winA_g[winid], alertBox, " <br>" + xH.responseText + "<br>") ;
}
function Call_Search( winid, vno, cSrc, cDst, rSrc, rDst, cbF = null  ) {
   return Call_Win(winid , "sW", vno, cSrc, cDst, rSrc, rDst, null, null, cbF) ;
}
function Call_Service( winid, act, vSrc, cSrc, cDst, rDst, rSrc, mod, gp, cbF = null ){
   if(act=='NULL'){
      alertBox("<P>Please contact system administrator to configure this facility !!!</P>" ) ;
      return ; 
   }
   return Call_Win(winid , "cW", act, cSrc, cDst, rSrc, rDst, vSrc, mod, gp, cbF)
}
function Call_Win(wnm, typ, aov, cSrc, cDst, rSrc, rDst,  vSrc, mod, gp, cbF) {
   var id = wnm + ":" + typ ;
   var oW = winA_g[id];
//console.info( 'Call_Win(wnm', wnm, 'typ', typ, 'aov', aov, 'cSrc', cSrc, 'cDst', cDst, 'rSrc', rSrc, 'rDst', rDst,  'vSrc', vSrc, 'mod', mod, 'gp', gp, 'cbF', cbF, 'oW', oW);
   if( !(oW) ) {
      oW = createTag ( 'div', id, 'cwin',  bdy_g.parentNode, 'block', null ) ; 
      setDivFade(oW.parentNode, true ) ;
      oW.w =  winA_g[wnm] ;
      oW.vR = new Array() ;
      oW.wA = new Array() ;
      oW.cSWa = new Array() ;
      oW.cDWa = new Array() ;
      oW.rSWa = new Array() ;
      oW.rDWa = new Array() ;
      winA_g[id] = oW ;
   }  else {
      oW.style.display = 'block' ;
   }
/*--------------        
   if( !(oW) ) {
      oW = createTag ( 'div', id, 'cwin',  bdy_g.parentNode, 'none', null ) ; 
      winA_g[id] = oW ;
      setDivFade(oW.parentNode, false) ;
      oW.w =  winA_g[wnm] ;
   }
   else oW.style.display = 'block' ;
-------------------  */
   oW.cSa = (cSrc)? cSrc.split("#") : new Array() ;
   oW.cDa = (cDst)? cDst.split("#") : new Array() ;
   oW.rSa = (rSrc)? rSrc.split("#") : new Array() ;
   oW.rDa = (rDst)? rDst.split("#") : new Array() ;
   oW.cbF = cbF ;
   oW.tp = typ ;
   if(!(oW.iW)) oW.iW = new Array() ;
   if (typ == "sW") {
      oW.tlD = createTag ( 'div', 'wtl', 'divTtl sHd', oW, 'block', "Search :: " + oW.w.nam_g,null, null, 'f' ) ; 
      oW.cB = createTag ( 'div', 'cB', 'cB glyphicon glyphicon-remove-sign', oW.tlD, 'block', null ) ; 
      _l(oW.cB, "click",closeDiv, oW, true,null);
      oW.iW['v'+aov] = createTag ( 'div', wnm + '_sW', 'Grid',  oW, 'block', null ) ; 
      oW.iW['v'+aov].puF = 1 ; //Pop-up flag
      oW.iW['v'+aov].v = aov ;
      oW.iW['v'+aov].agF= '1' ;
      oW.iW['v'+aov].eA = new Array() ;
      oW.wA[id] = oW.iW['v'+aov] ;
      oW.iW['v'+aov].t='grid' ;
      oW.mod_g='NRM' ;
      oW.tabIndex = 1 ;
      oW.iW['v'+aov].w = oW.w ;
      oW.iW['v'+aov].oW = oW ;
      getVw ( oW.w, oW.iW['v'+aov].v,fillCW, oW.iW['v'+aov], 'cf' ) ;
   } else {
//console.log( 'Call_Win 1113 oW - ', oW, 'wid', oW.w, oW.w.wA[vSrc], 'vSrc', vSrc ) ;
      oW.val = (oW.w.wA[vSrc]) ? getWidVal(oW.w, oW.w.wA[vSrc]) : vSrc ;
      oW.mod = mod ;
      oW.act = aov ;
      if(srvA_g['s'+aov]) {
         w_g = winA_g[srvA_g['s'+aov]] ; // getElmntById(srvA_g[aov], oW) ; //winA_g[srvA_g[aov]] ;
//         w_g.pmod = 'NRM' ;
         w_g.i_f = 0 ;
         w_g.oW = oW ;
//console.info( 'Call_Win 1122  srvA_g ', srvA_g, 'w_g', w_g, 'w_g', w_g, 'winA', winA_g, 'retBtn' , oW.retBtn, 'mod', mod ) ;
         tglWidShw(w_g, true) ;
         if(!oW.val) oW.val = '' ;
         //setScreenMode( w_g, 'CAN', true )
         //onBtnPress(0,0, 'CAN', w_g ) ;
         //setWidVal(w_g,w_g.kw_g, oW.val) ;
         //setScreenMode( w_g, mod, true )
         if( oW.val != '' ) setKey (oW.val, w_g) ;
         onBtnPress(0,0, mod, w_g ) ;
         oW.retBtn.w = w_g ;
         place_lbl(w_g) ;
         w_g.i_f = 1 ;
      } else {
         // oW.setAttribute("style","width:max-content;height:min-content;position:fixed;border:1px solid #000;");
         oW.setAttribute("style","position:fixed;border:1px solid #000;");
         //oW.setAttribute("style","width:1500px;height:750px;position:fixed;border:1px solid #000;");
         gp = (!gp) ? '' : gp ;
         var url= "index.php?f=3&srv=" + aov + "&sNm=" + oW.w.id + "&sEv=" + oW.w.mod_g + "&k=" + oW.val + gp  ;
//console.info( 'Call_Win 1140  srvA_g ', srvA_g, 'w_g', w_g, 'w_g', w_g, 'winA', winA_g, 'retBtn' , oW.retBtn, 'mod', mod, 'gp', gp, 'oW.val', oW.val, url );
         _s (null,url, false, initSrv, oW, aov, oW.val, mod ) ;
      }
   }
   setTimeout(function () { addCls(oW, 'vsbl') }, 50);
}
function fillCW(cW, rs) {
   var oW = cW.oW ;
   tglWidShw(cW, true) ;
   if (oW.tp == "sW") {
//      var rs = eval(this.responseText.replace(/&amp;/g, "\&"));
      setGridDtl (cW, null, rs) ;
      cW.srF = '1' ;
//      initGrid(cW, cW.v) ;
      callGrid( cW, cW.v, rs );
      pstDt_g = null ;
      for (var c in cW.fP ){
         oW.wA[c] = cW.gN.col[c] ;
      }
      cW.style.maxWidth= parseInt(cW.gDiv.getBoundingClientRect().right) - parseInt(oW.getBoundingClientRect().left) + parseInt(cW.xS.getBoundingClientRect().width) + 25 + 'px' ;
      oW.style.maxWidth= cW.style.maxWidth ;
      cW.style.maxHeight= cW.gH.getBoundingClientRect().height * (cW.pL + 7 ) + 'px' ;
      oW.style.maxHeight= parseInt(cW.getBoundingClientRect().height) + parseInt(oW.tlD.getBoundingClientRect().height) * 2  + 'px' ;
   } else {
//      cW.w = oW.w ;
      cW.style.position = "relative" ;
   }
   cW.mod_g = oW.mod ;
   for ( var j=0 ; j < oW.cSa.length ; j++ ){
      if ( oW.cDa[j] ) {
         var a = oW.cSa[j].split('||') ;
         oW.cSWa[j] = (oW.w.wA[a[0]]) ? oW.w.wA[a[0]] : getElmntById(a[0], oW.w)  ;
         if(a[1]) oW.cSWa[j].rvW =  (oW.w.wA[a[1]]) ? oW.w.wA[a[1]] : getElmntById(a[1], oW.w)  ;
         if (oW.tp == "sW") {
            if (!( cW.gS.col[oW.cDa[j]])) {
               if (!( cW.gN.col[oW.cDa[j]])) {
                  oW.cDWa[j] = (oW.wA[oW.cDa[j]]) ? oW.wA[oW.cDa[j]] : getElmntById(oW.cDa[j], cW)  ;
               } else {
                  oW.cDWa[j] = cW.gN.col[oW.cDa[j]] ;
               }
            } else {
               oW.cDWa[j] = cW.gS.col[oW.cDa[j]] ;
            }
         } else {
            oW.cDWa[j] = (oW.wA[oW.cDa[j]]) ? oW.wA[oW.cDa[j]] : getElmntById(oW.cDa[j], cW)  ;
         }
         if (!(oW.cDWa[j])) oW.cDWa[j] = oW.wA[oW.cDa[j]] = createTag ( 'input', oW.cDa[j], '', cW, 'none', '' ) ;
         if ( oW.cSWa[j] && oW.cDWa[j]){
            var rtn  = getWidVal(oW.w,oW.cSWa[j]) ;
            if(oW.cSWa[j].rvW) rtn += '||' + getWidVal(oW.w,oW.cSWa[j].rvW ) 
            else if (oW.cSWa[j].av ) rtn += '||' + oW.cSWa[j].value ;
//console.info('fillCW 1193', ' calls setWidVal', oW, oW.cDWa[j], rtn, 'j', j);
            setWidVal(oW, oW.cDWa[j], rtn) ;
            if (( oW.cSWa[j].t == 'grid') && ( oW.cDWa[j].t == 'grid') && ( oW.cSWa[j].f == 'M') && ( oW.cDWa[j].f == 'M') ) {
               setGridMV ( oW.cDWa[j], oW.cSWa[j].mV )
            }
         }
      }
   }
   if (oW.tp == "sW") {
      updateGrid (0,0,cW, true, null)  ;
      cW.style.width = cW.gDiv.clientWidth + 40 + "px" ; 
      cW.style.height = cW.gDiv.clientHeight + 10 + "px" ;
      grdRfrsh (cW, null, cW );
   }
   for ( var j=0 ; j < oW.rSa.length ; j++ ){
      if ( oW.rDa[j] ) {
         oW.rDWa[j] = (oW.w.wA[oW.rDa[j]]) ? oW.w.wA[oW.rDa[j]] : getElmntById(oW.rDa[j], oW.w)  ;
         var a = oW.rSa[j].split('||') ;
         oW.rSWa[j] = (oW.wA[a[0]]) ? oW.wA[a[0]] : getElmntById(a[0], oW)  ;
         if(a[1]) oW.rSWa[j].rvW =  (oW.wA[a[1]]) ? oW.wA[a[1]] : getElmntById(a[1], oW)  ;
      }
   }
   if( oW.retBtn ) oW.retBtn.w = cW ;
   if (!(oW.retBtn) || (oW.tp == 'sW')) {
      oW.retBtn = createTag ( 'button', 'retBtn', null, oW, 'block', oW.w.mA['RET'].lbl ) ; 
      oW.retBtn.title = oW.w.mA['RET'].lbl ;
      oW.retBtn.w = cW ;
      oW.retBtn.onclick = function() {
         var oW = this.w.oW ;
         for ( var j=0 ; j < oW.rSa.length ; j++ ){
            if ( oW.rSWa[j] && oW.rDWa[j]){
               var rtn  = getWidVal(oW.w,oW.rSWa[j]) ;
               if(oW.rSWa[j].rvW) rtn += '||' + getWidVal(oW.w,oW.rSWa[j].rvW ) 
               else if (oW.rSWa[j].av ) rtn += '||' + oW.rSWa[j].value ;
               setWidVal(oW.w, oW.rDWa[j], rtn) ;
               if (( oW.rSWa[j].t == 'grid') && ( oW.rDWa[j].t == 'grid') && ( oW.rSWa[j].f == 'M') && ( oW.rDWa[j].f == 'M') ) {
                  setGridMV ( oW.rDWa[j], oW.rSWa[j].mV )
               }else if (( oW.rSWa[j].t == 'grid') && ( oW.rSWa[j].f == 'M') ){
                  setWidVal ( oW.w, oW.rDWa[j], oW.rSWa[j].mV )
               }
            }
         }
         if(oW.cbF) oW.cbF.apply(oW);
         rmCls(oW, 'vsbl')
         oW.addEventListener('transitionend', function(e) {
//console.info( 'Call_Win 1122  srvA_g ', srvA_g, 'w_g', w_g, 'transitionend' ) ;
            this.style.display = 'none' ;
            if(this.tp == 'sW') this.innerHTML = '' ;
            setDivFade(this.parentNode, false ) ;
            tglWidShw(this.retBtn.w, false) ;
         }, {
            capture: false,
            once: true,
            passive: false
         });
         w_g = oW.w ;
         pstDt_g = null ;
         var fnc = this.w.eA['onClose'] ;
//console.info( 'Call_Win close  srvA_g ', srvA_g, ' this.w', this.w, 'eA', this.w.eA, ' fnc ', fnc ) ;
	 var vA = new Array();
	 vA[0]=(this.w.kw_g) ? this.w.kw_g.value: '' ;
         //eval(fnc).apply(this.w,vA) ; 
         eval(fnc);
         //if(fnc) eval(fnc + '(' + (this.w.kw_g) ? this.w.kw_g.value: '' +')');
      }
   }
   place_lbl(cW);
   return oW ;
}
function setKey (key, win){
   win.kv_g = key ;
//console.info('setKey win', win, 'kw', win.kw_g, 'key', key, 'kv_g', win.kv_g);
   if ((key != '') && win.kw_g) {
      if (win.kw_g) {
//         if(win.i_f > 1 ) return ;
//         if(win.i_f == 1 ) win.i_f = 2 ;
         setWidVal(win,w_g.kw_g, key) ;
      }
      for ( var w in win.wA) {
          if ((win.wA[w]) &&( win.wA[w].t == 'grid' ) ) {
                setGrdSR(win.wA[w], key ) ;
          }
      }
   }
}
function popGrpDfl ( winid, g ) {
//console.info("popGrpDfl 1271 win ", winid, ' g ', g) ;
   var win = winA_g[winid] ;
   for ( var w in win.grp[g].w ) {
//console.info("popGrpDfl 1272 wid ", w.id, ' g ', g) ;
      //if ((w == "itemValidation") || (w!="isArray") ) continue ;
      setWidDfl ( win, win.wA[w] ) ;
   }
   pstDt_g =  null ;
   for ( var s in win.grp[g].s ) {
      //if ((s == "itemValidation") || (s!="isArray") ) continue ;
      popGrpDfl ( win.id, s ) ;
   }
}
function setWidDfl ( win, wid ){
   if (!wid) return ;
   if (wid.tagName != 'DIV' ) {
   }
   var val = getDflVal(win,wid) ;
//console.info("setWidDfl w_g", w_g.id, ' wid ', wid.id, ' dflt ', val ) ;
   if (wid) { 
      if ( wid.t == 'grid' ) {
         if(wid.d == 'P') return ;
         if(!(wid.gDiv)) return ; 
         var fP = wid.fP ;
         wid.sR = null ;
         wid.mod_g = 'NRM' ;
         wid.aQp = Array() ; 
         wid.aQp['c'] = Array() ; 
         wid.aQp['f'] = Array() ; 
         wid.aQp['a'] = Array() ; 
         wid.aQp['s'] = Array() ; 
         wid.aQp['o'] = 0 ; 
         wid.rCnt = 0 ; 
         wid.sort = '' ; 
         wid.sCls = '' ;
         for ( var r=0 ; r < wid.row.length ; r++ ){
            for ( var c=0 ; c < fP.length ; c++ ){
               wid.row[r].col[c].innerHTML = '' ;
               wid.row[r].col[c].style.display = 'none' ;
            }
         }
         for ( var c=0 ; c < fP.length ; c++ ){
            setWidVal(win, wid.gAg.col[c], '' ) ;
         }
      } else if ( wid.t == 'mtag' ) {
         if(wid.d == 'P') return ;
         if(!(wid.gD)) return ; 
         for ( var r=0 ; r < wid.row.length ; r++ ){
            for ( var c=0 ; c < fP.length ; c++ ){
               wid.row[r].innerHTML = '' ;
               wid.row[r].style.display = 'none' ;
            }
         }
      } else if ( wid.t == 'tree' ) {
      } else if ( wid.t == 'm2m' ) {
      } else if ( wid.t == 'fileSet' ) {
         if(!(wid.fS)) return ;
         for (var i = wid.fS.length ; i > 0 ; i-- ) {
            wid.removeChild(wid.fS[i-1]);
            wid.fS.splice(i-1, 1) ;
         }
         wid.rV = null ;
      } else {
//DebugInfo (win.id + ' wid ' + wid.id + ' v ' + val );
         setRawVal( win, wid, val ) ;
      }
   }
}
function cpValTo ( winid, fwid, twid ) {
   var win = winA_g[winid] ;
   setWidVal(win, win.wA[twid], getWidVal(win, win.wA[fwid])) ;
}
function populateGroup ( winid, g, widid, kV ) {
   var win = winA_g[winid] ;
   if(!win) return ;
   if (win.grp[g] == undefined ) return ;
   if (win.grp[g].actFlg == 1)  return ;
   if(win.i_f > 1 ) return ;
   win.grp[g].actFlg = 1 ;
   if(win.i_f == 1 ) win.i_f = 2 ;
   var wid = win.wA[widid] ;
   fillGroup ( win, g, kV ) ;
   win.grp[g].actFlg = 0 ;
}
function fillGroup ( win, g, kV ) {
//console.info(' fiilgroup w', win.id, 'g', g, 'kV', kV) ;
   if (g == undefined ) return ;
   if (win.grp == undefined ) return ;
   if (win.grp[g] == undefined ) return ;
   pstDt_g =  buildPostWithoutForm(win, false ) ;
   var Mod = win.mod_g ;
   for ( var v in win.grp[g].v ) {
      if ((v == "itemValidation") || (v=="isArray") ) continue ;
      win.vR[v] = null ;
   }
   for ( var w in win.grp[g].w ) {
      if ((w == "itemValidation") || (w=="isArray") ) continue ;
      if(!(win.wA[w])) continue ;
      var Wid = win.wA[w] ;
      if (Wid) { 
         Wid.v = Wid.g[g].v ;
         Wid.c = Wid.g[g].c ;
         if (Wid.tagName=='DIV'){
            Wid.snf_g = true ;
         }
         if (Wid.t == 'grid'){
            callGrid( Wid, Wid.g[g].v, win.vR[Wid.g[g].v] );
         } else if (Wid.t == 'Gantt'){
            Wid.tv = Wid.g[g].v;
            Wid.rsv = Wid.g[g].c;
            Wid.rlv = Wid.av;
            callGantt( Wid);
         } else if (Wid.t == 'mtag'){
            callMTag( Wid, Wid.g[g].v, win.vR[Wid.g[g].v], Wid.g[g].c );
         } else if (Wid.t == 'tree'){
            callTree( win, Wid, Wid.g[g].v, Wid.g[g].c );
         } else if (Wid.t == 'm2m'){
            callM2M( win, Wid, Wid.g[g].v, Wid.g[g].c, Wid.f );
         } else if (Wid.t == 'Gallery'){
//console.info(' fillGroup 2 wid', Wid, 'Wid.g[g].v', Wid.g[g].v, 'Wid.g[g].c', Wid.g[g].c, 'Wid.f', Wid.f , 'win', win.id) ;
            callGallery( win, Wid, Wid.g[g].v, Wid.g[g].c, Wid.f );
         } else if (Wid.t == 'fileSet'){
            Wid.v = Wid.g[g].v ;
            Wid.c = Wid.g[g].c ;
            callFS(Wid,kV) ;
         } else if (Wid.t == 'Photo'){
            Wid.v = Wid.g[g].v ;
            Wid.c = Wid.g[g].c ;
            callPhoto(Wid,kV) ;
         } else if (Wid.tagName == 'IMG'){
            Wid.v = Wid.g[g].v ;
            Wid.c = Wid.g[g].c ;
            if ( !(win.vR[Wid.v])) {
//console.info(' fillGroup 1 o', Wid, 'vR', win.vR) ;
               getVw (Wid.w, Wid.v, setPhoto, Wid, 'cfp',null,null,null,null,null,null,kV ) ; 
            } else {
//console.info(' fillGroup 2 o', Wid, 'vR', win.vR) ;
               setPhoto(Wid) ;
            }
         } else if (Wid.t == 'preview'){
            if(!(Wid.ebd)){
               Wid.ebd = createTag( "embed", null, null, Wid, 'block', null ) ;
               Wid.ebd.setAttribute('style', 'overflow: auto; width: 100%; height: 100%;');
               Wid.ebd.setAttribute('type', 'application/pdf');
            }
            Wid.v = Wid.g[g].v ;
            Wid.c = Wid.g[g].c ;
            if ( !(win.vR[Wid.v])) {
               getVw (Wid.w, Wid.v, setEmbed, Wid, 'cfp',null,null,null,null,null,null,kV ) ; 
            } else {
               setEmbed(Wid) ;
            }
         } else {
            if(Wid.v > 0) {
//console.info(' fillGroup #2 o', Wid.v) ;
               if ( !(win.vR[Wid.v])) {
//console.info(' fillGroup #3 o', Wid.v, ' win.vR[Wid.v] ', win.vR[Wid.v]) ;
                  Wid.gno = g
                  getVw (win, Wid.v, setOthVw, Wid, 'cfm',null,null,null,null,null,null,kV ) ; 
               } else {
                  if(win.vR[Wid.v][2]){
                     if(win.vR[Wid.v][2][0]){
                        if(win.vR[Wid.v][2][0][Wid.c]) {
                           setWidVal( win, Wid, win.vR[Wid.v][2][0][Wid.c] ) ;
                        } else {
                           setWidVal( win, Wid, '' ) ;
                        }
                     }
                  }
               }
            }
         }
      }
   }
   pstDt_g =  null ;
   for ( var s in win.grp[g].s ) {
      if ((s == "itemValidation") || (s=="isArray") ) continue ;
      fillGroup ( win, s ) ;
   }
}
function callFS(o,kV) {
   if(!o.fN) {
      o.aBtn = createTag( "div", "aBtn" , '', o, 'block', null ) ;
      o.aBtn.wid = o ;
      o.aBtn.w = o.w ;
      o.aBtn.l = createTag( "div", "l" , 'fileclip glyphicon glyphicon-paperclip', o.aBtn, 'block', null ) ;
      o.rmkW = createTag( "INPUT", "rmkW", 'rmkW', o, 'none', null, null )
      o.rmkW.title = "Add remarks before file selection "
      o.fN = createTag( "INPUT", "fN", null, o.aBtn, 'block', null, "fN[]", 'file' )
      o.fN.setAttribute("multiple", true) ;
      o.fN.wid = o ;
      o.fN.w = o.w ;
//  document.getElementById('fN').addEventListener('change', handleFileSelect, false);
      _l(o.fN, 'change', hndlFS, o) ;
      o.cBtn = createTag( "div", "cBtn" , 'fileclose', o, 'block', 'X' ) ;
      o.cBtn.wid = o ;
      o.cBtn.w = o.w ;
      o.pBar = createTag( "DIV", "pBar", null, o, 'block', ''  )
      o.pc = createTag( "DIV", "p", null, o.pBar, 'block', ''  )
      o.eW = createTag( "label", "eW", null, o, 'block', null )
      o.eS = createTag( "DIV", "eS", 'eS', o, 'block', null )
      o.parm = (!o.parm) ? new Array() : o.parm ;
      o.fS = new Array() ;
   }
   if ( !(o.w.vR[o.v])) {
      getVw (o.w, o.v, setFS, o, 'cfp',null,null,null,null,null, null,kV ) ; 
   }
}
function noStream() {
   DebugInfo('No Access to camera!');
}
function gtStream(s) {
   vS_g = s;
   var o = bdy_g.video ;
   o.vd.onerror = function () {
      DebugInfo('video.onerror');
      if (o.vd) stop();
   };
   s.onended = noStream;
   if (window.webkitURL) o.vd.src = window.webkitURL.createObjectURL(s);
   else if (o.vd.mozSrcObject !== undefined) {
      o.vd.mozSrcObject = s;
      o.vd.play();
   } else if (navigator.mozGetUserMedia) {
      o.vd.src = s;
      o.vd.play();
   } else if (window.URL) o.vd.src = window.URL.createObjectURL(s);
   else o.vd.src = s;
   o.pSt = true ;
}

function callPhoto(o,kV) {
   if(!o.cnvs) {
      bdy_g.video = o ;
      addCls(o, 'imgCptr');
//      o.uM = (navigator.getUserMedia || navigator.oGetUserMedia || navigator.mozGetUserMedia || avigator.webkitGetUserMedia || navigator.msGetUserMedia )
      o.pSt = false ;
      o.vd = createTag( "video", "v" , '', o, 'none', '' ) ;
      o.vd.autoplay = 'true' ;
      o.vd.setAttribute('autoplay', 'true')
      o.cnvs = createTag( "canvas", "c" , '', o, 'none', '' ) ;
      o.cnvs.wid = o ;
      o.cnvs.w = o.w ;
      o.img = createTag( "img", "photo" , '', o, 'none', '' ) ;
      o.img.wid = o ;
      o.img.w = o.w ;
      o.aBtn = createTag( "div", "aBtn" , '', o, 'none', 'Capture' ) ;
      o.aBtn.wid = o ;
      o.aBtn.w = o.w ;
      o.uBtn = createTag( "div", "uBtn" , '', o, 'none', 'Upload' ) ;
      o.uBtn.wid = o ;
      o.uBtn.w = o.w ;
      _l(o.aBtn, 'click', takePhoto, o) ;
      _l(o.uBtn, 'click', ulPhoto, o) ;
      o.rmkW = createTag( "INPUT", "rmkW", 'rmkW', o, 'none', null, null )
      o.rmkW.title = "Add remarks before file selection "
      o.cBtn = createTag( "div", "cBtn" , '', o, 'none', 'X' ) ;
      o.cBtn.wid = o ;
      o.cBtn.w = o.w ;
      o.pBar = createTag( "DIV", "pBar", null, o, 'none', ''  )
      o.pc = createTag( "DIV", "p", null, o.pBar, 'none', ''  )
      o.eW = createTag( "label", "eW", null, o, 'none', null )
      o.parm = (!o.parm) ? new Array() : o.parm ;
      o.fS = new Array() ;
      if (navigator.getUserMedia) navigator.getUserMedia({video:true}, gtStream, noStream);
      else if (navigator.oGetUserMedia) navigator.oGetUserMedia({video:true}, gtStream, noStream);
      else if (navigator.mozGetUserMedia) navigator.mozGetUserMedia({video:true}, gtStream, noStream);
      else if (navigator.webkitGetUserMedia) navigator.webkitGetUserMedia({video:true}, gtStream, noStream);
      else if (navigator.msGetUserMedia) navigator.msGetUserMedia({video:true, audio:false}, gtStream, noStream);
   }
   if ( !(o.w.vR[o.v])) {
//console.info(' callPhoto 1 o', o, 'vR', o.w.vR) ;
      getVw (o.w, o.v, setPhoto, o, 'cfp',null,null,null,null,null,null,kV ) ; 
   } else {
//console.info(' callPhoto 2 o', o, 'vR', o.w.vR) ;
      setPhoto(o) ;
   }
}
function setPhoto(o) {
   var vR = o.w.vR[o.v][2] ;
//console.info(' setPhoto o', o, 'win', o.w, 'vR', vR) ;
   if (( vR ) ){
      if (( vR[0] ) ){
         var a = o.c.split('#') ;
         o.rV = vR[0][a[0]] ;
//         o.img.src = vR[0][a[1]] ;
         var urlTo = "index.php?f=3&srv=1136&ssrv=" + o.w.srv_g + "&dmd=inline&fno=" + o.rV  ;
         _s(null, urlTo, o, setImgSrc,o) ;
      }
   }
}
function takePhoto (b,e,o){
   if (o.pSt){
      o.cnvs.width = o.vd.videoWidth;
      o.cnvs.height = o.vd.videoHeight;
      o.cnvs.getContext('2d').drawImage(o.vd, 0, 0);
      var dt = o.cnvs.toDataURL('image/jpeg');
      o.img.setAttribute('src', dt)
      o.img.style.display = 'block' ;
      o.uBtn.style.display = 'block' ;
//      var urlTo = "index.php?f=3&srv=1137&ftp=P&ssrv=" + o.w.srv_g + '&wnm=' +o.id + '&rmk=' + getWidVal(o.w,o.rmkW);
//      _s(dt, urlTo,o, null, o, 0, 0, 0, null, 'application/upload') ;
   }
}

function ulPhoto(b,e,o) {
   var bndry = '------multipartformboundary' + (new Date).getTime();
   var dl = '--';
   var str = '' ;
   var cf     = '\r\n';
   var bEf = o.parm['bfADD'] ;
   if( bEf ) eval( bEf );
   str += dl +  bndry + cf + 'Content-Disposition: form-data; name="fN"; filename="Photo.png"' + cf ;
   str += 'Content-Type: application/octet-stream' + cf + cf + o.img.src + cf ;
   str += dl + bndry + cf ;
   str += dl + bndry + dl + cf ;
   var rmk = getWidVal(o.w,o.rmkW) ;
   xH.open("POST", "index.php?f=3&srv=1137&ssrv=" + o.w.srv_g + '&ftp=P&wnm=' +o.id + '&rmk=' + rmk, true);
   xH.setRequestHeader('content-type', 'multipart/form-data; boundary=' + bndry);
   xH.sendAsBinary(str);        
   xH.onload = function(e) { 
      if (xH.responseText) {
         dt = eval(xH.responseText) ;
         o.rV = dt[0] ;
      };
      setWidVal(o.w,o.rmkW, '' ) ;
   };
   o.uBtn.style.display = 'none' ;

}
function setImg(o, val) {
//console.info( "setImg o", o, ' w ', o.w, ' wid ', wid ) ;
   if(!o) return ; 
   if(o.tagName != 'IMG' ) return ;
   if(!val) val = -1 ;
   if(o.wid) o.wid.rV = val ;
   var urlTo = "index.php?f=3&srv=1136&ssrv=" + o.w.srv_g + "&dmd=inline&fno=" + val ;
   _s(null, urlTo, o, setImgSrc,o) ;
}
function setImgSrc (o) {
   o.src = this.xR.responseText ;
}
function setEmbed(o) {
   var sN = 0 ;
   if(!o.w.vR[o.v]) return ;
//   if(!sN) sN=0 ;
   sN = 0 ;
   var vR = o.w.vR[o.v][2] ;
   if (( vR ) ){
      if (( vR[sN] ) ){
         o.rV = vR[sN][o.c] ;
         var urlTo = "index.php?f=3&srv=1136&ssrv=" + o.w.srv_g + "&dmd=pdf&fno=" + o.rV  ;
         o.ebd.src = urlTo ;
      }
   }
}
function setOthVw(Wid) {
   var win = Wid.w ;
   var g = Wid.gno ;
//   win.vR[Wid.v] = eval( this.responseText.replace(/&amp;/g, "\&"));
   var val = '' ;
   if ( (win.vR[Wid.v] ) && (Wid.c > -1) ){
      if ( win.vR[Wid.v][2] != undefined ) {
         if ( win.vR[Wid.v][2].length > 0) {
            if (win.vR[Wid.v][2][0][Wid.c] ) {
               val = win.vR[Wid.v][2][0][Wid.c] ;
            }
         }
      }
   }
   if (Wid.tagName=='SELECT') {
      setWidVal( win, Wid, val ) ;
   } else {
      setWidVal( win, Wid, val ) ;
      if (Wid.av && Wid.tagName=='INPUT'){
         if (Wid.av > 0) {
              Wid.odV = '' ;
            _l(Wid, "keyup", autoComplete, Wid);
            _l(Wid, "focus", autoComplete, Wid);
         }
      }
   }
}
function setFS(o) {
//   o.w.vR[o.v] = eval( this.responseText.replace(/&amp;/g, "\&"));
   var vR = o.w.vR[o.v][2] ;
   for (var i = o.fS.length ; i > 0 ; i-- ) {
      o.eS.removeChild(o.fS[i-1]);
      o.fS.splice(i-1, 1) ;
   }
   if (( vR ) ){
      for ( var i = 0 ; i < vR.length ; i++ ) {
         var a = o.c.split('#') ;
         setFSentry(o,i, vR[i][a[0]], vR[i][a[1]], vR[i][a[2]]) ;
      }
   }
}
function setFSentry(o,n, val, str, rmk) {
   if(!(o.fS[n])){
      if(str == '') str = 'File #' + n ;
      if (!rmk) rmk = '' ;
      str = (rmk == '')? str : str + ' ( ' + rmk + ' ) ' ;
      o.fS[n] = createTag( "DIV", "fl" + n, 'ele', o.eS, 'block', null ) ;
      o.fS[n].t = createTag( "SPAN", "flt" + n, 'nam', o.fS[n], 'block', str ) ;
      o.fS[n].t.w = o.w ;
      o.fS[n].b = createTag( "SPAN", "flb" + n, 'btn', o.fS[n], 'block', 'X' ) ;
      o.fS[n].b.wid = o ;
      o.fS[n].b.w = o.w ;
      o.fS[n].b.c = n ;
      o.fS[n].b.rw = o.fS[n] ;
      o.fS[n].t.onclick = function() {
         window.open( "index.php?f=3&srv=1136&ssrv=" + this.w.srv_g + "&fno=" + this.parentNode.rV)  ;
      } ;
      o.fS[n].b.onclick = function() {
         var url = "index.php?f=3&srv=1135&ssrv=" + this.w.srv_g + "&fno=" + this.parentNode.rV  ;
         this.wid.sR = this.rw ;
         _s(false, url, this.wid, setExe,this.wid,'DEL',this.parentNode.rV) ;
         this.rw.style.display = 'none' ;  
         this.wid.sR = undefined ;
      } ;
   } else {
      o.fS[n].t.innerHTML = str ;
   }
   o.fS[n].rV = val ;
   o.fS[n].title = rmk ;
   o.fS[n].qI = undefined ;
   o.rV = o.fS[0].rV ;
}
function errorHandler(evt) {
}
function hndlFS(t,e,o) {
//    e.stopPropagation();
//    progress.style.width = '0%';
//    progress.textContent = '0%';
   var rid_l = -1 ;
   var fN = e.target.files; 
   o.fd = new FileReader();
   o.fd.onerror = function(e) {
      switch(e.target.error.code) {
        case e.target.error.NOT_FOUND_ERR:
          alert('File Not Found!');
          break;
        case e.target.error.NOT_READABLE_ERR:
          alert('File is not readable');
          break;
        case e.target.error.ABORT_ERR:
          break; // noop
        default:
          alert('An error occurred reading this file.');
     };
   }
//console.info ('fN', fN) ;
   o.fN = fN ;
   o.fd.onprogress = function(e){
      if (e.lengthComputable) {
        o.pc.style.width = Math.round(e.loaded / e.total) + '%' ;
      }
   };
   var bndry = '------multipartformboundary' + (new Date).getTime();
   var dl = '--';
   var str = '' ;
   var cf     = '\r\n';
   o.fd.onabort = function(e) {
      alert('File read cancelled');
   };
   o.fd.onloadstart = function(e) {
      addCls(o.pBar, 'loading');
      o.pc.style.width = '0 %';
      //$(msgW).show('slow');
   };
   function rdFN(idx) {
      if( idx >= fN.length ) return;
      o.fd.onload = function(e) {
         var bEf = o.parm['bfADD'] ;
         if( bEf ) eval( bEf );
         o.pc.style.width = 100 * (idx+1)/fN.length + '%';
         //o.pc.textContent = 'Loading Completed.';
         setTimeout("document.getElementById('pBar').className='';", 2000);
         str += dl +  bndry + cf + 'Content-Disposition: form-data; name="fN"; filename="' + fN[idx].name + '"' + cf ;
         str += 'Content-Type: application/octet-stream' + cf + cf + e.target.result + cf ;
         str += dl + bndry + cf ;
         str += dl + bndry + dl + cf ;
         var rmk = getWidVal(o.w,o.rmkW) ;
         pstDt_g =  buildPostWithoutForm(o.w, false) ;
         //nA = new Array() ;
         //nA['k'] = getWidVal(o.w, o.w.kw_g) ;
         var url =  "index.php?f=3&srv=1137&ssrv=" + o.w.srv_g + '&wnm=' +o.id + '&rid=' + rid_l + '&rmk=' + rmk ;
         xH.open("POST", url, true);
         //xH.setRequestHeader('pst-dt', pstDt_g);
         //xH.setRequestHeader('pst-dt', _e(nA));
         xH.setRequestHeader('content-type', 'multipart/form-data; boundary=' + bndry);
         xH.sendAsBinary(str);        
         xH.onload = function(e) { 
            if (xH.responseText) {
               dt = eval(xH.responseText) ;
               setFSentry(o, o.fS.length, dt[0], dt[2], dt[3]) ;
               setExe(o,'ADD', dt[0]) ;
               rid_l = dt[9] ;
               o.rV=rid_l ;
               o.fS[o.fS.length-1].qI = o.w.exDt.length - 1 ;
               if( (idx == fN.length - 1) && o.parm['afonComplete'] ) {
                  o.pc.textContent = 'Loading Completed !';
                  eval(o.parm['afonComplete']) ;
               }
               if( (idx == fN.length - 1) && o.eA['onupload'] ) {
		  var vA = o.eA['onupload'].split('|') 
		  vA[3] = dt[0] ; 
		  vA[4] = idx ; 
//console.info('Upload complete o', o, 'vA', vA, 'dt', dt );
                  eval(vA[0]).apply(o,vA) ; 
               }
            };
            rdFN(idx+1);
         };
         o.fN[idx].src = e.target.result ;
      };
      o.fd.readAsBinaryString(fN[idx]);
   }
   rdFN(0);
   setWidVal(o.w,o.rmkW, '' ) ;
}
function getDflVal( win, wid ) {
   if(! wid) return ;
   if ( win.wA ){
      if ( win.wA[wid.id] ){
         if ( wid.d ){
            ary = wid.d.split('#') ;
            switch ( ary[0] ){
               case 'V' : 
                    return (ary[1]) ? ary[1] : '' ;
                  break ;
               case 'Q' : 
                  if ( !(win.vR[ary[1]]) || ( win.i_f == 0 )) {
                     getVw ( win, ary[1], nF, null, "cfm", null, 0, null, null, null, null ) ;
                  }
                  return (win.vR[ary[1]]) ? win.vR[ary[1]][2][0][ary[2]]: '';
                  break ;
               case 'P' :
                    var v= getWidVal(win, wid);
                    if ((wid.av) && (wid.tagName=='INPUT')){
                       if(wid.D == undefined) acInit(wid)
                       if (wid.rV) v = wid.rV + '||' + wid.value ;
                    } ;
                    return v ;
                  break ;
               case 'E' :
                    if (ary[1]) { 
                       switch ( ary[1] ){
                          case 'date' : 
                             return formatDate(new Date(),'dd-MM-yyyy')
                          break ;
                          case 'time' : 
                             return formatDate(new Date(),'hh-mm-ss')
                          break ;
                          case 'now' : 
                             return formatDate(new Date(),'dd-MM-yyyy hh-mm-ss')
                          break ;
                       }
                    }
                  break ;
            } 
         }
      }
   }
   return '' ;
}
function trimSelOpt( winid, widid ) {
   var win = getElmntById(winid) ;
   var wid = getElmntById(widid, win) ;
   var dup = getElmntById(widid + ':dup', win) ;
//DebugInfo (wid.text) ;
   for ( var k = 0 ; k < dup.options.length ; k++ ) {
//         dup.option[k] = new Option( Wid.options[k].text, Wid.options[k].value, 0, 0 ) ;
   }
}
function setGroup ( win, g, dtFlg ) {
console.info('setGroup win', win.id, 'g', g, 'dtFlg', dtFlg) ;
   if (g == undefined ) return ;
   if (win.grp == undefined ) return ;
   if (win.grp[g] == undefined ) return ;
   var Mod = win.mod_g ;
   for ( var w in win.grp[g].w ) {
      if ((w == "itemValidation") || (w=="isArray") ) continue ;
      var Wid = win.wA[w] ;
      if (!Wid) continue ;
      if (Wid.tagName=='SELECT') {
         pstDt_g = null ;
//         Wid.multiple = ( Wid.f == 'M' ) ? 'true' : 'false' ;
         if( Wid.f == 'M' ) Wid.multiple = 'true'  ;
         fillCombo(Wid,  getSelectVal( Wid ) ) ;
         //if(dtFlg) fillCombo(Wid, null ) ;
      }
   }
   for ( var s in win.grp[g].s ) {
      if ((s == "itemValidation") || (s=="isArray") ) continue ;
      setGroup( win, s, dtFlg ) ;
   }
}
function tglWidShw ( w, st ) {
   if (!w) return ;
   w.style.display = (st == true ) ? 'block' : 'none';
   if (w.dtM){
      w.dtD.style.display = w.style.display  ;
      w.dtM.style.display = w.style.display  ;
      w.dtY.style.display = w.style.display  ;
      w.dtB.style.display = w.style.display  ;
   }
   if ( w.lbl ) {
      if(st == true) {
         // place_widlbl(w);
         rmCls(w.lbl, 'hide')
         var p = (w.dtD) ? w.dtD.parentNode : w.parentNode ;
         // w.lbl.style.top= parseInt(w.getBoundingClientRect().top) - parseInt(p.getBoundingClientRect().top) -15 + 'px' ;
         // w.lbl.style.left= parseInt(w.getBoundingClientRect().left) - parseInt(p.getBoundingClientRect().left) + 'px' ;
         (w.value && w.value.length ) ? addCls(w.lbl,'up') : rmCls(w.lbl, 'up');
      } else {
         addCls(w.lbl, 'hide') ;
      }
   }
}
function tglWidEbl ( w, st ) {
   if (!w) return ;
   if (w.w==w) return ;
   w.disabled = (st == true ) ? false : true ;
   if (w.dtM){
      w.dtM.disabled = w.disabled ;
      w.dtY.disabled = w.disabled ;
      w.dtB.disabled = w.disabled ;
   } else if (w.tagName=='DIV'){
      w.snf_g = (w.disabled) ? false : true ;
      if(w.snf_g)rmCls(w, 'dsbl' ) ;
      else addCls(w, 'dsbl' ) ;
      setDivEbl(w, (w.disabled) ? false : true ) ;
   } else if ( w.t == 'tinyMCE' ) {
      setDivFade(w.edtr, (w.disabled) ? true : false ) ;
   } 
}
function tglGrpShw ( win, g, st ) {
   if (win.grp == undefined ) return ;
   if (win.grp[g] == undefined ) return ;
   for ( var w in win.grp[g].w ) {
      var w = win.wA[i] ;
      tglWidShw ( w, st ) ;
   }
   for ( var s in win.grp[g].s ) {
      tglGrpShw ( win, s, st ) ;
   }
}
function tglGrpEbl ( win, g, st ) {
   if (win.grp == undefined ) return ;
   if (win.grp[g] == undefined ) return ;
   for ( var w in win.grp[g].w ) {
      var w = win.wA[i] ;
      tglWidEbl ( w, st ) ;
   }
   for ( var s in win.grp[g].s ) {
      tglGrpEbl ( win, s, st ) ;
   }
}
function setDivEbl( div, flg ){
   if (!(div.id)) return ;
   if (div.t == 'grid') {
      if(div.lF == '0') {
         div.setAttribute("md", flg ? '1': '0' ) ;
      }
      return ;
   }
   try {
      div.disabled = flg ? false : true;
   } catch(E){ }
   if (div.childNodes && div.childNodes.length > 0) {
      for (var x = 0; x < div.childNodes.length; x++) {
         if (div.childNodes[x].tagName=='DIV') setDivEbl(div.childNodes[x],flg);
         else if (div.childNodes[x].tagName) { 
            tglWidEbl ( div.childNodes[x], flg) ;
         }
      }
   }
}
function setDivFade( div, flg ){
   if (div.fde_g == undefined ) {
      var cls = (div.stp == 'inline') ? 'glassfade' : 'fade' ;
      div.fde_g = createTag ( 'div', 'fde_g', cls , div, 'none', null ) ;
   }
   div.fde_g.style.display = (flg) ? "block" : 'none' ;
}
function getVwAs ( win, vno, cf,o, m, pL, p, cnd, oby, ofs, flt, kV ) {
   getViewRes ( win, vno, m, pL, p, cnd, oby, ofs, flt, kV,cf,o,1 ) ;
}
function getVw ( win, vno, cf,o, m, pL, p, cnd, oby, ofs, flt, kV ) {
   getViewRes ( win, vno, m, pL, p, cnd, oby, ofs,flt, kV,cf,o ) ;
}
function getViewRes ( win, vno, m, pL, p, cnd, oby, ofs, flt, kV,cf,o,af ) {
//DebugInfo ( "getViewRes v " + vno ) ;
   if (!(vno)) return null ;
   if ( vno == '' ) return null ;
   if ( vno == '-1' ) return null ;
//DebugInfo ( "getViewRes 1 v " + vno ) ;
   var vtp = (isNaN(parseInt(vno))) ? 'v' : 't' ;
   if( !m ) m = 'c' ;
   if( !p ) p = 0 ;
   if( !pL ) pL = 10 ;
   if( !cnd ) cnd = '' ;
   if( !flt ) flt = '*' ;
   if( !oby ) oby = '' ;
   if( !ofs ) ofs = '' ;
   if( !kV ) kV = '' ;
   //pstDt_g =  buildPostWithoutForm(win, false) ;
   pstDt = getRawPostWithoutForm(win, false) ;
   var s = (win.mod_g) ? '&sEv=' + win.mod_g : '' ;
   var a = (win.dvs) ? win.dvs : 1127 ;
   //var url = "index.php?f=3&srv=" + a + "&v=" + vno + "&c=1&dmd=d&sct=" + m + "&pL=" + pL + "&Cnd=" + cnd + "&oBy=" + oby + "&ofs=" + ofs + "&flt=" + flt + "&p=" + p + "&sNm=" + win.id + "&sEv=" + win.mod_g + "&k=" + kV ;
   var url = "index.php?f=3&srv=" + a + "&v=" + vno + "&c=1&dmd=d&sct=" + m + "&pL=" + pL + "&p=" + p + "&sNm=" + win.id + s + "&k=" + kV ;
//   if(cf) {
   pstDt['k_g']= kV ;
   pstDt['qP_g']= cnd ;
//   if(af) _as(_e(pstDt), url,false,regVwDt,win,cf,o,vno, vtp) ;
//   else _s(_e(pstDt), url,false,regVwDt,win,cf,o,vno, vtp) ;
// _s(data, urlTo,o, cf, p1, p2, p3, p4, af=0, ct=null) 
   if(af) _as(_e(pstDt), url,false,regVwDt,win,cf,o,vno) ;
   else _s(_e(pstDt), url,false,regVwDt,win,cf,o,vno,0) ;
//   } else {
//      if(af) _as(pstDt_g, url) ;
//      else {
//         _s(pstDt_g, url) ;
//         win.vR[v] = eval( xH.responseText.replace(/&amp;/g, "\&"));
//      }
//      return rs ;
//   }
}
function regVwDt(win,cf,o,v) { 
   win.vR[v] = eval( this.responseText.replace(/&amp;/g, "\&")) ;
   if(cf) cf(o, win.vR[v]) ;
}
function getDt ( wid, grp, typ, lvl, pL, p, cnd, oby, ofs,flt, kV, cf ) {
   if( !p ) p = 0 ;
   if( !pL ) pL = 10 ;
   if( !cnd ) cnd = '' ;
   if( !flt ) flt = '*' ;
   if( !oby ) oby = '' ;
   if( !ofs ) ofs = '' ;
   if( !kV ) kV = '' ;
   if( pstDt_g == null ){
      pstDt_g =  buildPostWithoutForm(win, false) ;
   }
   var s = (win.mod_g) ? '&sEv=' + win.mod_g : '' ;
   var a = (win.dvs) ? win.dvs : 1127 ;
   var url = "index.php?f=3&srv="+a+"&v="+vno+"&c=1&dmd=d&sct="+m+"&pL="+pL+"&Cnd="+cnd+"&oBy="+oby+"&ofs="+ofs+"&flt="+flt+"&p="+p+"&sNm="+win.id+s+win.mod_g+"&k="+kV ;
   _s(pstDt_g, url,false, cf,null,null,null,null,1) ;
}
function fillCombo(o, val ) {
   var l ;
   if ((o.stp == 's' ) && (o.pstat)) return ;
   o.svl = val
   if ( o.w.i_f == 1) {
      if ( !(o.w.vR[o.cv]) ) {
         //getVwAs ( o.w, o.cv, fillCmbDt, o, 'a' ) ;
         getVw ( o.w, o.cv, fillCmbDt, o, 'a' ) ;
      } else {
         fillCmbDt (o) ;
      }
   } else {
      //getVwAs ( o.w, o.cv, fillCmbDt, o, 'a' ) ;
      getVw ( o.w, o.cv, fillCmbDt, o, 'a' ) ;
   }
}
function fillCmbDt(o) {
   var rs = o.w.vR[o.cv] ;
console.info( 'fillCmbDt o', o.id,  'rs',rs);
   if(!rs) return ;
   o.options[0] = new Option( '','' , 0, 0);
   o.pstat = 1 ;
   if( rs[2] != undefined ) {
      for ( l=0 ; l < rs[2].length ; l++ ){
         o.options[l+1] = new Option( rs[2][l][1], rs[2][l][0] , 0, 0);
      }
      for (l= o.options.length ; l > rs[2].length ; l--) {
         o.remove(l) ;
      }
   }
   if (o.svl) setSelectVal( o, o.svl ) ;
}
function setSelectVal( wid, val ) {
   if (val) {
      arr = val.split(":") ;
      if(arr){
         wid.value=arr[0] ;
         for ( var i=0 ; i < arr.length ; i++ ){
            for ( var j=0 ; j < wid.options.length ; j++ ){
               if ( arr[i] == wid.options[j] ){
                  wid.options[j].selected = true ;
                  break ;
               }
            }
         }
      }
   }
}
function getSelectVal( wid ) {
   var ret = '' ;
   for ( var j=0 ; j < wid.options.length ; j++ ){
      if ( wid.options[j].selected ){
         wid.options[j].selected = true ;
         if(wid.f=='M'){
            if (ret != '' ) ret += ',' ;
            ret += "'" +  wid.options[j].value + "'" ;
         } else {
            ret = wid.options[j].value ;
            break ;
         }
      }
   }
   return ret ;
}
function setWidVal ( win, wid, val ) {
   if (!(win)) return ;
   if (!(wid)) return ;
//console.info(' setWidVal ', win.id, wid.id, val.substring(0,40) ) ;
   if(wid.chgFlg != undefined ) if( wid.chgFlg != 1) return ;  //if( wid.getAttribute("chgFlg") != 1) return 
   wid.chgFlg = 0 ;
   setRawVal ( win, wid, val ) ;
   if(wid.lbl){
     (wid.value && wid.value.length) ? addCls(wid.lbl,'up') : rmCls(wid.lbl, 'up');
   }
   if ( wid.gK != undefined ){
//console.info(' setWidVal ', win, wid, val ) ;
      pstDt_g =  buildPostWithoutForm(win, false) ;
      for ( var g in wid.gK ) {
         if ((g == "itemValidation") || (g=="isArray") ) continue ;
         if((win.i_f == 1) && !(val.length)) continue ;
         //setGroup(win, g, true ) ;
         fillGroup(win, g ) ;
      }
   }
//   if(wid.onchange)wid.fireEvent('change');
   if(wid.eA && wid.eA['input']){
//console.info(' setWidVal w', win.id, 'wid', wid.id, 'val', val, 'onchange', wid.onchange, 'win.i_f', win.i_f) ;
      //if( win.i_f != 1 ) {
         //pstDt_g = null ;
         wid.eA['input'].apply(wid,[wid]) ;
      //}
   }
   if(wid.onchange){
      if( win.i_f != 1 ) {
         pstDt_g = null ;
         wid.onchange() ;
      }
   }
   if (wid.D ) {
      for ( var h in wid.eA ) {
         if(h.toUpperCase() == 'ONCHANGE' ) eval(wid.eA[h]) ;
      }
   }
   wid.chgFlg = 1 ;
}
function setRawVal ( win, wid, val ) {
   if (!(wid)) return ;
   if(!val)   val = '' ; 
   if(wid.n == val )  val = '' ;
   switch ( wid.tagName ) {
      case 'INPUT' : 
         if ( wid.type.toUpperCase() == 'TEXT' || wid.type.toUpperCase() == 'PASSWORD' || wid.type.toUpperCase() == 'DATE' ) {
//            if ( win.wA ){
//               if (( win.wA[wid.id] )  && ( wid.f) && ( wid.f.charAt(0) == 'D') ){
               if (( wid.f) && ( wid.f.charAt(0) == 'D') ){
                  //var dte = new Date(val) ;
                  if(val == 'NULL' ) val = '' ;
                  if(val == '') return ;
                  var dte = new Date() ;
//console.info ('2090 dte', dte, 'val', val ) ;
                  var ary = val.split('-') ;
                  dte.setFullYear(parseInt(ary[2]),parseInt(ary[1])-1,parseInt(ary[0])) ;
//console.info ('2092 dte', dte,'val', val, ary, parseInt(ary[2]),parseInt(ary[1])-1,parseInt(ary[0]) ) ;
                  dte.setDate(parseInt(ary[0])) ;
//console.info ('2093 dte', dte ) ;
                  //var dte = new Date(2021,5,1) ;
                  //if ( ary[0] ) wid.value = ary[0] ;
                  //if ( ary[1] ) wid.dtM.value =  ary[1] ;
                  //if ( ary[2] ) wid.dtY.value =  ary[2] ;
//console.info ('2097', wid.id , 'ary', ary, ' v ' , val , 'dte', dte,  ' setv ' , dte.toISOString().split('T')[0] , ' setd ' , formatDate(dte,'dd-MM-yyyy') , ' a ' , parseInt(ary[2]),'-',parseInt(ary[1]),'-',parseInt(ary[0]) );

                  wid.value = dte.toISOString().split('T')[0] ;
               } else {
                  if(val == 'NULL' )  val = '' ;
                  wid.value = val ;
               }
//            } else {
//               if(val == 'NULL' )  val = '' ;
//               wid.value = val ;
//            }
            if ((wid.av) && (wid.tagName == 'INPUT')){
               if(wid.D == undefined) acInit(wid)
               if(val.split) {
                  var a = val.split("||") ;
                  wid.rV = a[0] ;
                  wid.value = (a[1] == undefined) ? a[0] : a[1] ;
//                  wid.odV = val ;
               }
            }
         } else if ( wid.type.toUpperCase() == 'HIDDEN' ) {
            wid.value = val ;
         } else if ( wid.type.toUpperCase() == 'CHECKBOX' ) {
            wid.checked = (val == 't') ? true : false ;
         } else if ( wid.type.toUpperCase() == 'RADIO' ) {
            wid.checked = (val == 't') ? true : false ;
         } else if ( wid.type.toUpperCase() == 'BUTTON' ) {
            wid.innerHTML = val ;
         } 
         break ;
      case 'SELECT' : setSelectVal( wid, val ) ;
         break ;
      case 'DIV' :
         if ( wid.t == 'Photo'  ) {
            setImg(wid.img, val) ;
         } else if ( wid.t == 'preview'  ) {
            setEmbed(wid) ;
         } else if ( wid.t == 'Gallery'  ) {
//console.info ('setRawVal wid', wid, ' fno', val) ;
            addToGallery(wid, val) ;
         } else if ( (wid.t == 'alrt') || (wid.t ==  'fileSet') ) {
            wid.rV = val ;
            wid.setAttribute("rV", val ) ;
         } else if ( wid.t == 'notify'  ) {
            wid.innerHTML = (val == '0')? '' : val ;
         } else if ( wid.cbn ) {
            wid.cbn.checked = (val == 't' ) ? 'true' : 'false' ;
         } else if ( wid.val ) {
            wid.val = val ;
         } else if ( wid.childNodes.length <= 1  ) {
	    setInnerHtml(wid, val) ;
            //wid.innerHTML = $('<div/>').html(val).text() ;
            //wid.innerHTML = val ;
         }
         break ;
      case 'SPAN' : wid.innerHTML = val ; break ;
      case 'IMG' : setImg(wid, val) ; break ;
      case 'TEXTAREA' : 
         if ( wid.t == 'tinyMCE' ) {
            //tinyMCE.get(wid.id).setContent(  $("<div />").html(val).text() );
            tinyMCE.get(wid.id).setContent(  val );
         } else {
            //$(wid).val(val) ;
            wid.value =val ;
         }
      default :
         if ( wid.t == 'alrt' ) {
            wid.rV = val ;
            wid.setAttribute("rV", val ) ;
         } else if ( wid.value ) {
            wid.value = val ;
         } else if ( wid.innerHTML  ) {
            //if(val.length)wid.innerHTML = $('<div/>').html(val).text() ;
            if(val.length) setInnerHtml(wid, val);
         }
   }
   if (wid.lbl){
      if(val !== "") addCls(wid.lbl,'up');
      else rmCls(wid.lbl,'up');
   }
}
function getWidVal (win, wid ) {
   var rtn ;
   if(!wid) return rtn ;
//   var Wid = win.wA[wid.id];
   Wid = wid;
//DebugInfo (wid.id + ' getWidVal  '  + rtn ) ;
   rtn = getRawVal ( win, wid ) ;
//DebugInfo (wid.id + ' 1getWidVal  '  + rtn ) ;
   if ( rtn == '' ){
      if ( Wid != undefined ){
         rtn = (Wid.n != undefined) ? Wid.n : rtn ;
         if ( Wid.f != undefined )  {
            if ( Wid.f.charAt(0) == 'D' ) rtn = 'NULL' ;
            else if ( Wid.f.charAt(0) == 'T' ) rtn = 'NULL' ;
            else if ( Wid.f.charAt(0) == 'I' ) rtn = 'NULL' ;
            else if ( Wid.f.charAt(0) == 'N' ) rtn = 'NULL' ;
         }
      } else {
        rtn = 'NULL' ;
      }
   }
   if (( rtn == '--' )&&( wid.f.charAt(0) == 'D' )) rtn = 'NULL' ;
   return rtn ;
}
function getRawVal ( win, wid ) {
   var rtn = '' ;
   if(!(wid)) return rtn ;
   if ( !(wid.tagName)) return rtn ;
//DebugInfo (wid.id + ' getRawVal  '  +  wid.tagName.toUpperCase() + ' typ ' + wid.t ) ;
   switch ( wid.tagName.toUpperCase() ) {
      case 'INPUT' : 
         if (wid.type.toUpperCase() == 'DATE' ) {
            if(wid.value ==""){ 
               rtn='NULL' ;
            } else {
               var a=wid.value.split('-') ;
               if(!a[1]) {
                  rtn='NULL' ;
               } else {
                  rtn=a[2] + '-' + a[1] + '-' + a[0] ;
               }
            }
         //} else if (( wid.dtM)){
         //   rtn =  wid.value + '-' + wid.dtM.value + '-' + wid.dtY.value ;
         //   rtn = (rtn.length < 8 ) ? '' : rtn ;
         } else if ( wid.type.toUpperCase() == 'HIDDEN' ) {
            rtn = (wid.rV) ? wid.rV : wid.value  ;
         } else if ( wid.type.toUpperCase() == 'CHECKBOX' ) {
            rtn =  (wid.checked) ? 't' : 'f'  ;
         } else if ( wid.type.toUpperCase() == 'RADIO' ) {
            rtn =  (wid.checked) ? 't' : 'f'  ;
         } else if ( wid.type.toUpperCase() == 'BUTTON' ) {
            rtn =  wid.innerHTML  ;
         } else {
            rtn = (wid.rV ) ? wid.rV : wid.value  ;
         } 
         break ;
      case 'SELECT' : rtn =  getSelectVal( wid ) ; 
         break ;
      case 'TEXTAREA' : 
         if ( wid.t == 'tinyMCE' ) {
            rtn = tinyMCE.get(wid.id).getContent();
         }else { 
            //rtn = $(wid).val() ;
            rtn = wid.value ;
         }
         break ;
      case 'SPAN' : rtn =  wid.innerHTML ; 
         break ;
      case 'IMG' : rtn =  wid.src ; 
         break ;
      case 'DIV' :
         if (wid.t == 'grid') {
            if ( wid.gN ) rtn = getWidVal(win, wid.gN.col[0]) ;
            if (wid.f == 'M' ) {
               wid.mV = '' ;
               for ( i=0 ; i < wid.rCnt ; i++ ){
                  if ( wid.row[i].sF == 1) {
                     if (wid.mV != '' ) wid.mV += ',' ;
                     wid.mV += "'" + wid.row[i].col[0].rV + "'" ;
                  }
               }
            }
            else rtn = '' ;
         } else if ( wid.t == 'mtag' ) {
//DebugInfo(" getRawVal mTag " + wid.id + ' :: ' + wid.rCnt + ' mV ' + wid.mV + ' rV ' + wid.row[0].rV ) ;
            wid.mV = '' ;
            for ( i=0 ; i < wid.rCnt ; i++ ){
//DebugInfo(" getRawVal mTag " + wid.id + ' :: ' + wid.rCnt + ' mV ' + wid.mV + ' rV ' + wid.row[i].rV ) ;
               if (wid.mV != '' ) wid.mV += ',' ;
               wid.mV += "'" + wid.row[i].rV + "'" ;
            }
         } else if ( wid.t == 'Gallery' ) {
            if ((wid.pic) && (wid.pic[0])) rtn = wid.pic[0].id ;
            else rtn = 'NULL' ;
         } else if ( wid.t == 'm2m' ) {
            rtn = (wid.rV) ? wid.rV : '' ;
         } else if  ( wid.t == 'tree' ) {
            rtn = (wid.rV) ? wid.rV : '' ;
         } else if ( wid.cbn ) {
            rtn = (wid.cbn.checked) ? 't' : 'f' ;
         } else if ( wid.val ) {
            rtn = wid.val ;
         } else if ( wid.childNodes.length <= 1 ) {
            rtn =  wid.innerHTML ;
         } else rtn =  '' ; 
         break ;
      case 'BUTTON' : rtn =  wid.innerHTML ; 
         break ;
      case 'CHECKBOX' : rtn =  wid.checked  ; 
         break ;
      case 'RADIO' : rtn =  wid.checked  ;
         break ;
      default : rtn =  '' ;
   }
   if (wid.rV)  return wid.rV  ;
   return rtn ;
}
function setGridMV (o, mV) {
   if (o.t != 'grid') return ;
   if (o.f != 'M') return ;
   var a = mV.split(",") ;
   for ( var v in a ) {
      for ( i=0 ; i < o.rCnt ; i++ ){
         if (v == o.row[i].col[0].rV ) {
            o.row[i].sF = 1 ; 
            addCls(o.row[i], "mSel") ;
            o.row[i].cbn.checked = true ; 
         }
      }
   }
}
function initGrid (o,vno) {
   if ( o.gDiv ) {
      //o.oFs = 0 ;
      o.aQp['o'] = 0 ;
      return ;
   }
   o.cRw = 0 ; // Number of changed row
//DebugInfo("0 initGrid " + o.id + ' ::' + vno ) ;
   o.setAttribute("agF", o.agF ) ;
   o.setAttribute("srF", o.srF ) ;
   if(o.f == 'M' ) o.setAttribute("F", o.f ) ;
   o.setAttribute("md", 0 ) ;
   if(o.tabIndex < 0) o.tabIndex = 0 ;
   var o_tag ;
   if(!(o.pL) || (o.pL < 1)) o.pL = 10 ;
   o.cPag =1 ;
   o.parm = (!o.parm) ? new Array() : o.parm ;
//   o.btn = new Array() ;
   var cls = (o.stp == 'inline') ? 'glassfade' : 'fade' ;
   o.fde_g = createTag ( 'div', 'fde_g', cls , o, 'none', null ) ;
   o.gDvc = createTag ( 'div', 'gDvc', 'gDvc', o, 'none', null ) ;
   //o.lR = createTag ( 'div', 'lR', 'lR', o.gDvc, 'block', null ) ;
   o.gDiv = createTag ( 'div', 'gDiv', 'gDiv', o, 'table', null ) ;
   o.gS = createTag ( 'div', 'gS', null, o.gDiv, 'none', null ) ; 
   o.sB  = createTag ( 'div', 'sB', 'sB', o.gS, 'none', null ) ;
   o.gN = createTag ( 'div', 'gN', o.stp, o.gDiv, 'none', null ) ; 
   o.aB  = createTag ( 'div', 'aB', 'aB', o.gN, 'none', null ) ;
   o.gH = createTag ( 'div', 'gH', 'gHRow', o.gDiv, 'table-row', null ) ; 
   o.lH = createTag ( 'div', 'lH', 'lH', o.gH, (o.f == 'M' ) ? 'table-cell' : 'none', null ) ;
   o.gDiv.tabIndex = o.tabIndex ;
   //o.rR = createTag ( 'div', 'rR', 'rR', o, 'block', null ) ;
   o.gAg = createTag('div', 'gAg', 'gAgr', o.gDiv, (o.agF == 1 ) ? 'none' : 'table-row', null ) ; 
   o.agLh = createTag ( 'div', 'lH', 'lH', o.gAg, 'table-cell', null ) ;
   o.nLh = createTag ( 'div', 'lH', 'lH', o.gN, 'table-cell', null ) ;
   o.sLh = createTag ( 'div', 'lH', 'lH', o.gS, 'table-cell', null ) ;
//   o.sAg  = createTag ( 'div', 'sAg', 'sAg', o, (o.agF == 1 ) ? 'none' : 'table-cell', null ) ;
//   o.sH = createTag('div', 'sH', 'sH', o, 'none', null ) ; 
//   o.sV = createTag('div', 'sV', 'sV', o, 'none', null ) ; 
//   o.pH = createTag('div', 'pH', 'pH', o.sH, 'block', null ) ; 
//   o.pV = createTag('div', 'pV', 'pV', o.sV, 'block', null ) ; 
   o.aBtn  = createTag ( 'div', 'aBtn', 'aBtn', o.aB, 'none', null ) ;
   o.sBtn  = createTag ( 'div', 'sBtn', 'sBtn glyphicon glyphicon-search', o.sB, 'none', null ) ;
   o.pop = createTag ( 'div', 'pop', 'pop', o, 'none', null ); 
   o.pB  = createTag ( 'div', 'pB', 'pB', o.pop, 'table-cell', '' ) ;
   o.srB  = createTag ( 'div', 'srB', 'srB', o.pop, 'table-cell', '' ) ;
   o.pdfB  = createTag ( 'div', 'pdfB', 'pdfB', o.pop, 'table-cell', '' ) ;
   o.csvB  = createTag ( 'div', 'csvB', 'csvB', o.pop, 'table-cell', '' ) ;
   o.mB = createTag ( 'div', 'mB', 'mB', o.pop, 'table-cell', '' ) ;
   o.rfB  = createTag ( 'div', 'rfB', 'rfB', o.pop, 'table-cell', '' ) ;
   createTag ( 'span', 'lB', 'lB', o.pop,  'block' , 'Offset') ;
   o.oS  = createTag ( 'input', 'oS', 'oS', o.pop,  'table-cell' , '0' ) ;
   o.aQp = Array() ; 
   o.aQp['c'] = Array() ; 
   o.aQp['f'] = Array() ; 
   o.aQp['a'] = Array() ; 
   o.aQp['s'] = Array() ; 
   o.aQp['o'] = 0 ; 
   //o.oFs = 0 ;
//   o.onkeydown = function(ev) {
//      if([32, 37, 38, 39, 40].indexOf(ev.keyCode) > -1) {
//         ev.preventDefault();
//         return ;
//      }
//   }
   o.gDvc.onkeyup = function(ev) {
//alert (ev.target.id) ;
      var o = this.parentNode ;
      if(ev.target.parentNode == o.gN ) return ;
      switch (ev.keyCode) {
         case 13 :
            if(o.snf_g ==  false ) return ;
            if(!(o.mod_g) || (o.mod_g == 'NRM')) {
               if (o.sR) dcGridRow(b,ev, o.sR.key, o );
//               else addToGrid(o ) ; 
            } else {
               if(o.gN.aC == o.gN.lvC.cN ) addToGrid(o ) ;
            }
            break ;
         case 27 :
            if(o.sR) {
               if(o.mod_g != 'NRM') {
                  for ( var c=0 ; c < o.fP.length ; c++ ){
                     if(o.gN.col[c].D != undefined) {
                        if(o.gN.col[c].D.style.display == 'block'){
                           o.gN.col[c].D.style.display = 'none' ;
                           return true;
                        } ;
                     }
                  }
                  edtInGrid( o.w.id , o.id ) ;
               } else {
                  rmCls(o.sR, "gSel");
                  o.sR = null ;
                  if(o.stp=='inline'){
                     o.gN.style.top= '' ;
                  }
               }
            }
            break ;
         case 40 :
            if(!(o.mod_g) || (o.mod_g == 'NRM')) {
               if(!(o.sR)) {
                  setGrdSR(o,0 ) ;
               } else {
                  var k = (o.sR.k >= (o.rCnt-1) ) ? 0 : parseInt(o.sR.k) + 1 ; 
                  setGrdSR(o,k ) ;
               }
            }
            GridScrollToSel (o,o.sR,'v');
            return  ;
         case 38 :
            if(!(o.mod_g) || (o.mod_g == 'NRM')) {
               if(!(o.sR)) {
                  setGrdSR(o,o.rCnt-1 ) ;
               } else {
                  var k = (o.sR.k <= 0) ? o.rCnt-1 : o.sR.k - 1 ; 
                  setGrdSR(o,k ) ;
               }
            }
            GridScrollToSel (o,o.sR,'v');
            return  ;
         case 32 :
            if(!(o.mod_g) || (o.mod_g == 'NRM')) {
               if(!(o.sR) && !(o.osR)) {
                  setGrdSR(o,0 ) ;
               } else if(o.sR) {
                  o.osR = o.sR ;
                  setGrdSR(o,o.sR.k ) ;
               } else {
                  setGrdSR(o,o.osR.k ) ;
                  o.osR = null ;
               }
            }
            GridScrollToSel (o,o.sR,'v');
            return  ;
      }
   } 
   o.gH.col = new Array() ;
   o.gAg.col = new Array() ;
   o.gS.col = new Array() ;
   o.gN.col = new Array() ;
   //o.lR.row = new Array() ;
   //o.rR.row = new Array() ;
   o.row = new Array() ;
   o.fA = new Array() ;
   if (o.fP == undefined) o.fP = new Array();
   o.oldVal = new Array() ;
   o.allFld = 1 ;
   o.v = vno ;
   o.sR = null ;
   o.mod_g = 'NRM' ;
   o.rCnt = 0 ; 
   o.trCnt = 0 ; 
   o.sort = '' ; 
   o.sCls = '' ;
   _l(o.sBtn, "click",grdSrch,o, true,null);
   var pWid = null ;
   if (o.f == 'M' ) {
      o.mV = '' ;
      o.lH.cbn = createTag('input', 'b', null, o.lH, 'block', 0 ) ;
      o.lH.cbn.type = 'checkbox' ;
      o.lH.cbn.checked = false ;
      o.lH.val = 'f' ;
      o.lH.cbn.p = o ;
      o.lH.cbn.onclick = function() {
         var o = this.p ;
         o.mV = '' ;
         for ( i=0 ; i < o.rCnt ; i++ ){
            o.row[i].sF = (this.checked == true) ? 1 : 0 ;
            if ( o.row[i].sF == 1) {
//               o.row[i].oCls = (o.row[i].className == "mSel") ? o.row[i].oCls : o.row[i].className ;
               addCls(o.row[i], "mSel") ;
               o.row[i].cbn.checked = true ;
               if (o.mV != '' ) o.mV += ',' ;
               o.mV += "'" + o.row[i].col[0].rV + "'" ;
            } else  {
               rmCls(o.row[i], "mSel") ;
               o.row[i].cbn.checked = false ;
            }
         }
      }
   }
   for (var c in o.fP ){
      var vF = ( o.fP[c].vf != 'h' ) ? 'table-cell' : 'none' ;
      var aF = ( o.fP[c].af != 'h' ) ? 'table-cell' : 'none' ;
      var sF = ( o.fP[c].sf != 'h' ) ? 'table-cell' : 'none' ;
      var lZ = (o.f == 'M' ) ? 25 : 0 ;
      if ( o.fP[c].vf != 'h' ){
         o.fP[c].cw = o.fP[c].wd ;
         o.fP[c].lp = (c==0) ? lZ : o.fP[c-1].lp + parseInt(o.fP[c-1].cw) + parseInt(0);
      } else {
         o.fP[c].cw = 0 ;
         o.fP[c].lp = (c==0) ? lZ : o.fP[c-1].lp + parseInt(o.fP[c-1].cw) ;
      }
      td = createTag ( 'div', 'gH' + c, 'gHCell', o.gH, vF, null ) ; 
      //td.style.position = 'absolute' ;
      o.gH.col[c] = td ;
      cb = createTag ( 'input',  "gH_" + c , '', td, 'block',  't' ) ; 
      o.gH.col[c].cb = cb ;
      cb.type='CHECKBOX' ;
      var ttl = o.fP[c].tl ;
      if (( o.fP[c].tp == 'E') || ( o.fP[c].tp == 'X') || ( o.fP[c].tp == 'F') || ( o.fP[c].tp == 'J') ) ttl = ttl.split('|')[1] ; 
      nobr = createTag ( 'nobr', "" , '', td, 'block',  ttl ) ; 
      _l(nobr, "click",gridSort,c, o);
      //td.style.width= o.fP[c].cw + "px" ;
      //td.style.left= o.fP[c].lp + "px" ;
      //td.style.float= "left" ;

      td = createTag('div', c + ':a:' + o.id, 'gCell' + o.fP[c].tp, o.gAg, vF, null ) ; 
      //td.style.left= o.fP[c].lp + "px" ;
      //td.style.float= "left" ;
      //td.style.position = 'absolute' ;
      if ( o.agF == '-1' ) { 
         if (o.fP[c].ag=='t') {
            if ((o.fP[c].tp == 'n')||(o.fP[c].tp == 'i')) {
               setWidVal(o.w, td, '0.00') ;
               o.w.wA[td.id] = td ;
            }
         }
      }
      o.gAg.col[c] = td ;
//      o.gDiv.style.width= o.fP[c].lp + o.fP[c].cw  + "px" ;
      if ( o.fP[c].aw != '' ) {
         o.gN.col[c] = getElmntById(o.fP[c].aw, o.w ) ;
         o.w.wA[c + ':' + o.id] = o.gN.col[c] ;
      } else {
         switch ( o.fP[c].tp  ){
            case 'n' :
            case 'i' :
                  o.gN.col[c] = createTag('input', c + ':' + o.id,o.fP[c].tp,o.gN,aF,'',c+':'+o.id);
                  if ( aF == 'block' ) o.gN.col[c].f = 'I' ;
                  o.gS.col[c] = createTag('input', c + ':e:' + o.id, o.fP[c].tp +'_e', o.gS, 'table-cell', null ) ; 
                  o.gS.col[c].gt = createTag('input', c + ':g:' + o.id, o.fP[c].tp + '_g', o.gS, 'block', null ) ; 
                  o.gS.col[c].lt = createTag('input', c + ':l:' + o.id, o.fP[c].tp + '_l', o.gS, 'block', null ) ; 
                  o.gS.col[c].gt.title = 'Low limit' ;
                  o.gS.col[c].lt.title = 'High limit' ;
               break ;
            case 'd' :
                 o.gN.col[c] = createTag('input', c + ':' + o.id,o.fP[c].tp,o.gN,aF,'',c+':'+o.id,'date');
                 if ( aF == 'block' ) {
                    o.gN.col[c].f = 'D' ;
                         // createTag( t, id, cls, p, v, val, nam, typ,pos )
                    //setDateWid(o.gN.col[c], o.w) ;
                 }
                 o.gS.col[c] = createTag('input', c + ':e:' + o.id, o.fP[c].tp , o.gS, 'table-cell','', null, 'date' ) ; 
                 o.gS.col[c].gt = createTag('input', c + ':g:' + o.id, o.fP[c].tp , o.gS, 'block', '', null, 'date' ) ; 
                 o.gS.col[c].lt = createTag('input', c + ':l:' + o.id, o.fP[c].tp , o.gS, 'block', '', null, 'date' ) ; 
                 o.gS.col[c].f = 'D' ;
                 //setDateWid(o.gS.col[c], o.w) ;
                 o.gS.col[c].lt.f = 'D' ;
                 //setDateWid(o.gS.col[c].lt, o.w) ;
                 o.gS.col[c].gt.f = 'D' ;
                 //setDateWid(o.gS.col[c].gt, o.w) ;
                 //o.gS.col[c].dtD.title = 'Matching Date' ;
                 //o.gS.col[c].gt.dtD.title = 'Low limit' ;
                 //o.gS.col[c].lt.dtD.title = 'High limit' ;
                 o.gS.col[c].title = 'Matching Date' ;
                 o.gS.col[c].gt.title = 'Low limit' ;
                 o.gS.col[c].lt.title = 'High limit' ;
                 addCls(o.gS.col[c].dtD, 'd_e' ) ;
                 addCls(o.gS.col[c].lt.dtD, 'd_l' ) ;
                 addCls(o.gS.col[c].gt.dtD, 'd_g' ) ;

              break ;
           case 'f' :
                o.gN.col[c] = createTag('div', c + ':' + o.id,'fileSet',o.gN,aF,'',c+':'+o.id);
                o.gN.col[c].v = o.fP[c].cv ;
                o.gN.col[c].c = o.fP[c].ac ;
                o.gN.col[c].w = o.w ;
                o.gN.col[c].t = 'fileSet' ;
                callFS(o.gN.col[c]) ;
                o.gS.col[c] = createTag('input', c + ':e:' + o.id, o.fP[c].tp, o.gS, 'table-cell', null ) ; 
              break ;
           case 't' :
           case 'a' :
                 o.gN.col[c] = createTag('input', c + ':' + o.id,o.fP[c].tp,o.gN,aF,'',c+':'+o.id);
                 if (o.fP[c].tp == 'a') {
                    o.gN.col[c].av = o.fP[c].cv ;
                    o.gN.col[c].ac = o.fP[c].ac ;
                    if (o.gN.col[c].av > 0) {
                       o.gN.col[c].w = o.w ;
//DebugInfo(o.id + ' ' + o.gN.col[c].id + ' 2 acInit ' + o.gN.col[c].av ) ;
                       o.gN.col[c].rV = '' ;
                       _l(o.gN.col[c], "keyup", autoComplete, o.gN.col[c]);
                       _l(o.gN.col[c], "click", autoComplete, o.gN.col[c]);
                       _l(o.gN.col[c], "focus", autoComplete, o.gN.col[c]);
                    }
                 }
                 o.gS.col[c] = createTag('input', c + ':e:' + o.id, o.fP[c].tp, o.gS, 'table-cell', null ) ; 
//                 if (o.fP[c].tp == 'a') {
//                    o.gS.col[c].av = o.fP[c].cv ;
//                    o.gS.col[c].ac = o.fP[c].ac ;
//                    if (o.gS.col[c].av > 0) {
//                       o.gS.col[c].w = o.w ;
////DebugInfo(o.id + ' ' + o.gS.col[c].id + ' 3 acInit ' + o.gS.col[c].av ) ;
//                       o.gS.col[c].rV = '' ;
//                       _l(o.gS.col[c], "keyup", autoComplete, o.gS.col[c]);
//                       _l(o.gS.col[c], "click", autoComplete, o.gS.col[c]);
//                       _l(o.gN.col[c], "focus", autoComplete, o.gN.col[c]);
//                    }
//                 }
              break ;
           case 'T' :
           case 'H' :
                 o.gN.col[c] = createTag('textarea',c+':'+o.id,o.fP[c].tp,o.gN,aF,'',c+':'+o.id ) ; 
                 _l(o.gN.col[c], "focus", setGrdTextWidIndex, o);
                 _l(o.gN.col[c], "blur", rstGrdTextWidIndex, o);
                 o.gS.col[c] = createTag('input', c + ':e:' + o.id, o.fP[c].tp, o.gS, 'table-cell', null ) ; 
              break ;
           case 'c' :
                 o.gN.col[c] = createTag('select',c+':'+o.id,o.fP[c].tp,o.gN,aF,'',c+':' + o.id) ; 
                 o.gN.col[c].cv = o.fP[c].cv ;
                 o.gN.col[c].w = o.w ;
                 fillCombo(o.gN.col[c], null ) ;
                 o.gS.col[c] = createTag('select', c + ':e:' + o.id, o.fP[c].tp, o.gS, 'table-cell', null ) ; 
                 o.gS.col[c].multiple = 'multiple' ;
                 o.gS.col[c].cv = o.fP[c].cv ;
                 o.gS.col[c].w = o.w ;
                 fillCombo(o.gS.col[c], null ) ;
              break ;
           case 'b' :
                 o.gN.col[c] = createTag('input',c+':'+o.id, o.fP[c].tp,o.gN,aF,'',c+':'+ o.id) ; 
                 o.gN.col[c].type='checkbox' ;
                 o.gS.col[c] = createTag('input', c + ':e:' + o.id, o.fP[c].tp, o.gS, 'table-cell', null ) ; 
                 o.gS.col[c].type='checkbox' ;
           case 'E' :
           case 'X' :
           case 'F' :
           case 'J' :
                 o.gN.col[c] = createTag('div',c+':'+o.id, o.fP[c].tp,o.gN,aF,'',c+':'+ o.id) ; 
                 o.gS.col[c] = createTag('div', c + ':e:' + o.id, o.fP[c].tp, o.gS, sF, null ) ; 
              break ;
         }
         if (aF == 'table-cell' ) { 
            if (pWid != null ) {
               pWid.nxtWid = o.gN.col[c] ;
               pWid.onblur = function() {
//                  this.nxtWid.focus() ;
               }
            }
            pWid = o.gN.col[c] ;
         }
         var wd = o.fP[c].wd ;
         if ( o.fP[c].af == 'S' ){
            var a = o.fP[c].sv.split("#") ;
            var cSrc = (a[1]) ?  c + ":" + o.id + "#" +  a[1] :  c + ":" + o.id ;
            var cDst = (a[2]) ?  '0#' + a[2] :  "0"  ;
            var rSrc = (a[3]) ?  '0#' + a[3] :  "0"  ;
            var rDst = (a[4]) ?   c + ":" + o.id + "#" +  a[4] : c + ":" + o.id  ;
            td = createTag('div', '', 'cellSrch', o.gN, aF,"" ) ; 
            //_l(td, "click",Call_Service,o.w.id, a[0],val, cSrc, cDst, rSrc, rDst);
            td.setAttribute("onclick", "Call_Search('" + o.w.id + "','" + a[0] + "','" + cSrc + "','" + cDst +"','" + rSrc +"','" + rDst + "')") ;
            td.style.left = parseInt(o.fP[c].lp) + parseInt(o.fP[c].wd) - 23 + 'px' ; 
            o.gN.col[c].sI = td ;
            cSrc = (a[1]) ?  c + ":e:" + o.id + "#" +  a[1] :  c + ":e:" + o.id ;
            rDst = (a[4]) ?   c + ":e:" + o.id + "#" +  a[4] : c + ":e:" + o.id  ;
            td = createTag('div', '', '', o.gS, sF, '?' ) ; 
            td.setAttribute("onclick", "Call_Search('" + o.w.id + "','" + a[0] + "','" + cSrc + "','" + cDst +"','" + rSrc +"','" + rDst + "')") ;
            td.style.left = o.fP[c].lp + o.fP[c].wd - parseInt(td.getBoundingClientRect().width ) + 'px' ; 
         }
         if ( o.fP[c].af == 'A' ){
            var a = o.fP[c].csf.split("#") ;
            var cSrc = (a[1]) ?  c + ":" + o.id + "#" +  a[1] :  c + ":" + o.id ;
            var cDst = (a[2]) ?  '0#' + a[2] :  "0"  ;
            var rSrc = (a[3]) ?  '0#' + a[3] :  "0"  ;
            var rDst = (a[4]) ?   c + ":" + o.id + "#" +  a[4] : c + ":" + o.id  ;
            td = createTag('div', '', '', o.gN, aF, '+' ) ; 
            var val = getWidVal( o.w, o.gN.col[c] ) ;
            td.setAttribute("onclick", "Call_Service('" + o.w.id + "','" + a[0] + "','" + val +  "','" + cSrc + "','" + cDst +"','" + rSrc +"','" + rDst + "')") ;
            td.style.left = o.fP[c].lp + o.fP[c].wd - parseInt(td.getBoundingClientRect().width ) + 'px' ; 
           // _l(td, "click",Call_Service,o.w.id, a[0],val, cSrc, cDst, rSrc, rDst);
            cSrc = (a[1]) ?  c + ":e:" + o.id + "#" +  a[1] :  c + ":e:" + o.id ;
            rDst = (a[4]) ?   c + ":e:" + o.id + "#" +  a[4] : c + ":e:" + o.id  ;
            td = createTag('div', '', '', o.gS, sF, '+' ) ; 
            val = getWidVal( o.w, o.gS.col[c] ) ;
            td.setAttribute("onclick", "Call_Service('" + o.w.id + "','" + a[0] + "','" + val +  "','" + cSrc + "','" + cDst +"','" + rSrc +"','" + rDst + "')") ;
            td.style.left = o.fP[c].lp + o.fP[c].wd - parseInt(td.getBoundingClientRect().width ) + 'px' ; 
            o.gN.col[c].cI = td ;
           // _l(td, "click",Call_Service,o.w.id, a[0],val, cSrc, cDst, rSrc, rDst);
         }
         if( o.fP[c].tp != 'd'){
            //o.gN.col[c].setAttribute("style", "left:" + o.fP[c].lp + "px; width:" + o.fP[c].wd + "px; ") ;               
            //o.gS.col[c].setAttribute("style", "left:" + o.fP[c].lp + "px; width:" + o.fP[c].wd + "px; ") ;               
            o.gN.col[c].setAttribute("style", "width:" + o.fP[c].wd + "px; ") ;               
            o.gS.col[c].setAttribute("style", "width:" + o.fP[c].wd + "px; ") ;               
            //if(o.gS.col[c].gt) o.gS.col[c].gt.setAttribute("style", "left:" + o.fP[c].lp + "px; width:" + o.fP[c].wd + "px; ") ;
            //if(o.gS.col[c].lt) o.gS.col[c].lt.setAttribute("style", "left:" + o.fP[c].lp + "px; width:" + o.fP[c].wd + "px; ") ;
            if(o.gS.col[c].gt) o.gS.col[c].gt.setAttribute("style", "width:" + o.fP[c].wd + "px; ") ;
            if(o.gS.col[c].lt) o.gS.col[c].lt.setAttribute("style", "width:" + o.fP[c].wd + "px; ") ;
         } else {
            //if(o.gN.col[c].dtD) o.gN.col[c].dtD.setAttribute("style", "left:" + o.fP[c].lp + "px; ") ;               
            if(o.gS.col[c].dtD) { 
               //o.gS.col[c].dtD.setAttribute("style", "left:" + o.fP[c].lp + "px; width:" + o.fP[c].wd + "px; ") ;               
               o.gS.col[c].dtD.setAttribute("style", "width:" + o.fP[c].wd + "px; ") ;               
               //if(o.gS.col[c].gt) o.gS.col[c].gt.dtD.setAttribute("style", "left:" + o.fP[c].lp + "px; width:" + o.fP[c].wd + "px; ") ;
               //if(o.gS.col[c].lt) o.gS.col[c].lt.dtD.setAttribute("style", "left:" + o.fP[c].lp + "px; width:" + o.fP[c].wd + "px; ") ;
               if(o.gS.col[c].gt) o.gS.col[c].gt.dtD.setAttribute("style", "width:" + o.fP[c].wd + "px; ") ;
               if(o.gS.col[c].lt) o.gS.col[c].lt.dtD.setAttribute("style", "width:" + o.fP[c].wd + "px; ") ;
            }
         }
         o.gS.col[c].title = "Matching Value" ;
         o.gN.col[c].cN = c ;
         o.w.wA[o.gN.col[c].id] = o.gN.col[c] ;
         if(!(o.w.ws[o.gN.col[c].id]) ) o.w.ws[o.gN.col[c].id] = new Array();
         for ( var m in o.w.mA ) {
            if(!(o.w.ws[o.gN.col[c].id][m])) {
               o.w.ws[o.gN.col[c].id][m] = new Object() ; 
               if ( o.fP[c].af != 'h' ) {
                  o.w.ws[o.gN.col[c].id][m].v = 't' ;
                  o.w.ws[o.gN.col[c].id][m].s = 't' ;
               }
            }
            if ( o.fP[c].af == 'h' ) o.w.ws[o.gN.col[c].id][m].v = 'f' ;
         }
         if ( o.fP[c].af != 'h' ){
            o.gN.col[c].tabIndex = c + 1 ;
            if(!(o.gN.lvC)) o.gN.fvC = o.gN.col[c] ;
            o.gN.lvC = o.gN.col[c] ;
            o.gN.col[c].onfocus = function() {
               this.parentNode.aC = this.cN ;
            }
            o.gN.col[c].onblur = function() {
            }
         }
         o.gN.col[c].tabIndex = o.tabIndex + c ;
         o.gS.col[c].tabIndex = o.gN.col[c].tabIndex ;
      }
      if((o.gN.col[c].f) && (o.gN.col[c].f.charAt(0) == 'D') && ( o.fP[c].af != 'h' )) {
         //o.gN.col[c].dtB.onkeyup = function(ev) {
         //   if (ev.keyCode == 9) {
         //      if ((this.f) && (this.f.charAt(0) == 'D')) return ;
         //      else {
         //         var i = parseInt(this.cN + 1) ;
         //         while ( (o.gN.col[i]) && (o.gN.col[i].vf != 'h') ) i++ ; 
         //         if(o.gN.col[i])o.gN.col[i].focus() ;
         //      }
         //   }
         //} ;
      } ;
      if (pWid != null ) {
         pWid.onkeyup = function(ev) {
            if (ev.keyCode == 9) {
               if ((this.f) && (this.f.charAt(0) == 'D')) return ;
               else {
                  var i = parseInt(this.cN) + 1 ;
                  while ( (o.gN.col[i]) && (o.gN.col[i].vf != 'h') ) i++ ; 
                  if(o.gN.col[i])o.gN.col[i].focus() ;
               }
            }
         }
      }
      _l(o.gN.col[c], "focus", setWidEvt, o.gN.col[c]);
      _l(o.gN.col[c], "blur", setWidEvt, o.gN.col[c]);
      _l(o.gN.col[c], "focus", syncGridScroll, o, o.gN);
      if ( o.gN.col[c].f == 'D') addCls(o.gN.col[c].dtD, 'dw') ;
      else addCls(o.gN.col[c], 'dw') ;
      _l(o.gS.col[c], "focus", setWidEvt, o.gS.col[c]);
      _l(o.gS.col[c], "blur", setWidEvt, o.gS.col[c]);
//      _l(o.gS.col[c], "focus", syncGridScroll, o, o.gDvc);
      _l(o.gS.col[c], "focus", syncGridScroll, o, o.gS);
      if( o.gS.col[c] ) if ( o.gS.col[c].f == 'D') addCls(o.gS.col[c].dtD, 'dw') ;
      else addCls(o.gS.col[c], 'dw') ;
      o.gN.col[c].w = o.w ;
      if( o.gS.col[c] ) o.gS.col[c].w = o.w ;
   }
   //o.xS = createTag ( 'div', 'xDiv', 'xDiv glyphicon glyphicon-list', o, 'block', null ) ; 
   o.xS = createTag ( 'div', 'xDiv', 'xDiv glyphicon glyphicon-list', o.gH, 'block', null ) ; 
//   if( o.fP[c] ) {
//      o.gDiv.style.width= parseInt(o.fP[c].lp) + parseInt(o.fP[c].cw)  + "px" ;
//   } else {
//   }
//   o.gDiv.style.width= o.gAg.style.width ;
//   o.gN.style.width= o.gAg.style.width ;
//   o.gS.style.width= o.gAg.style.width ;
   o.cnfB =  createTag('div', 'cnfB', 'cnfB', o.gN, null, 'Confirm' ) ;
   o.canB =  createTag('div', 'canB', 'canB', o.gN, null, 'Cancel' ) ;
   _l(o.cnfB, "click", onGrdDelCnf,  o);
   _l(o.canB, "click", onGrdDelCan,  o);
   _l(o.xS, 'click', tglGrdPop, o) ;
   _l(o.srB, "click", grdSrchShow, o);
   _l(o.mB, "click", grdMore, o);
   _l(o.rfB, "click", grdRfrsh, o);
   _l(o.pB, "click", grdPrt, o);
   _l(o.pdfB, "click", grdPdf, o);
   _l(o.csvB, "click", grdCsv, o);
   _l(o.oS, "keyup", sGrdOfs,  o);
   if(o.gN.lvC) o.gN.lvC.onkeyup = function(ev) {
     if((ev.keyCode == '13') && (o.stp == 'inline' )){
        o.aBtn.focus() ;
     }
   }
//    o.gDvc.scrollWidth = o.gH.scrollWidth ;
   _l(o.aBtn, "click",onGrdBtn, o.mod_g, o);
   o.gDiv.setAttribute("draggable", "true" ) ;
   _l(o, "mouseover", gridFocus, o);
   _l(o, "mouseout", gridBlur, o);
   _l(o.gDvc, "mousedown", gridScroll, 1, o);
   _l(o.gDvc, "mousemove", gridScroll,2, o);
   _l(o.gDvc, "mouseup", gridScroll,3, o);
   _l(o.gDvc, "scroll", grdLoadChk,o);
   setWidgets (o.w,null, null);
   for (var c in o.fP ){
      setWidDfl ( o.w, o.gN.col[c] ) ;
   }
//   setEvents (w_g, o);
   setGridScroll(o) ;
}
function grdLoadChk(gd,e,o ) {
   if( o.inAct == '1' ) return ;
   if (o.pL >= o.trCnt ) return ;
   var rO = gd.getBoundingClientRect() ;
   var h = Math.round(rO.bottom - rO.top) ;
   if ( gd.scrollTop > (gd.scrollHeight - h)) { 
      if (o.v > 0) setActing (o, true) ;
      grdMore( gd,e,o ) ;
   }
}
function setGrdTextWidIndex(c, ev, o) {
   o.gDvc.style.zIndex = -1 ;
   c.style.height = '80px' ;
}
function rstGrdTextWidIndex(c, ev, o) {
   o.gDvc.style.zIndex = 98 ;
   c.style.height = '24px' ;
}
function dragInfo(b,e,o ) {
//DebugInfo ("o " +  o.id + ' trg ' + e.target.id + ' typ ' + e.type + ' this ' + b.id ) ;  
}
function insGridRow( o, i) {
   if( o.row[i] ) {
      o.row[i].className = "gR_" +  i % 2  ;
      o.row[i].style.display = 'table-row' ;
//      o.row[i].delbtn.style.display = 'block';
//      o.row[i].edtbtn.style.display = 'block';
      if(o.f=='M')o.row[i].cbn.style.display = 'table-cell'; ;
      for ( c=0, k=0 ; c < o.fP.length ; c++ ){
         o.row[i].col[c].style.display = ( o.fP[c].vf != 'h' ) ? 'table-cell' : 'none' ;
      }
      return o.row[i] ;
   } ;
   var tP = (i == 0) ? 0 : parseInt(o.row[i-1].getBoundingClientRect().bottom ) - parseInt(o.gDiv.getBoundingClientRect().top)   ;
//DebugInfo( o.id + ' | ' + i + ' tp ' + tP ) ;
   tr = createTag ( 'div', 'gR_' + i, "gR_" +  i % 2, o.gDiv, 'table-row', null ) ; 
   tr.o=o ;
//   tr.style.position = 'absolute' ;
//if ( i > 0 ) DebugInfo(o.id + "| i " + i + " |tP  " + tP + " | t  " + o.row[i-1].style.top + " |h  " + o.row[i-1].offsetHeight) ;
//else DebugInfo(o.id + "| i " + i + " |tP  " + tP + " | t  " + tr.style.top + " |h  " + tr.style.offsetHeight) ;
//   tr.style.top = tP + 'px' ;
//   tr.style.width = o.gH.scrollWidth + 'px' ; ;
//   tr.style.width= parseInt(o.fP[c-1].lp) + parseInt(o.fP[c-1].cw)  + "px" ;
   o.row[i] = tr ;
   tr.col = new Array() ;
//   _l(tr, "click", selectGridRow, i, o);
//   _l(tr, "dblclick", dcGridRow, i, o);
   _l(tr, "click", grdRowClick, i, o);
   _l(tr, "dblclick", grdRowClick, i, o);
   setGridScroll(o) ; 
//DebugInfo ( o.id + ' sH ' + o.gDiv.scrollHeight + ' sW ' + o.gDiv.scrollWidth + ' cH ' + o.gDiv.clientHeight + ' cW ' + o.gDiv.clientWidth + " rH) " + parseInt( o.gDiv.getBoundingClientRect().height ) + ' rW ' + parseInt(o.gDiv.getBoundingClientRect().width ) ) ;   
//   o.gDiv.style.width= o.gH.col[c].getBoundingClientRect().left - o.gDiv.getBoundingClientRect().left + o.fP[c].cw + 'px' ;
   if (o.f == 'M' ) {
      td = createTag('div', 'gC:' + i + ':S', 'lH', tr, 'none', null ) ;
      o.row[i].cbn = createTag('input', 'b', null, td, 'block', 0 ) ;
      o.row[i].cbn.type = 'checkbox' ;
      o.row[i].cbn.td = td ;
//      o.row[i].cbn.td.style.top = tP + 'px' ;
      o.row[i].cbn.checked = false ;
      o.row[i].val = 'f' ;
      o.row[i].cbn.r = o.row[i] ;
      o.row[i].cbn.onclick = function() {
         this.r.sF = (this.r.sF != 1) ? 1 : 0 ;
         if (this.r.sF == 1) {
//            this.r.oCls = (this.r.className == "mSel") ? this.r.oCls : this.r.className ;
//            this.r.oCls = this.r.className ;
            addCls(this.r,"mSel") ;
         } else {
            rmCls(this.r,"mSel") ;
//            this.r.className = this.r.oCls ;
         }
         this.r.o.mV = '' ;
         for ( i=0 ; i < this.r.o.rCnt ; i++ ){
            if ( this.r.o.row[i].sF == 1) {
               if (this.r.o.mV != '' ) this.r.o.mV += ',' ;
               this.r.o.mV += "'" + this.r.o.row[i].col[0].rV + "'" ;
            } 
         } 
      }
   }
   for ( c=0, k=0 ; c < o.fP.length ; c++ ){
      td = createTag('div', 'gC:' + i + ':' + c, 'gCell' + o.fP[c].tp, tr, 'table-cell', null ) ;
      addCls(td,"gC" + c) ;
      //td.style.left= o.fP[c].lp + "px" ;
      //td.style.float= "left" ;
      //td.style.width= o.fP[c].cw + "px" ;
      td.style.display = ( o.fP[c].vf != 'h' ) ? 'table-cell' : 'none' ;
      o.row[i].col[c] = td ;
      //o.row[i].col[c].rV = '' ;
      td.rV = 'Test' ;
      if (o.fP[c].tp  == 'b' ) {
         td.cbn = createTag('input', 'b', null, td, 'block', 0 ) ;
         td.cbn.type = 'checkbox' ;
         td.cbn.disabled = 'true' ;
         td.cbn.checked = false ;
         td.val = 'f' ;
      } else if ((o.fP[c].tp  == 'E')  || (o.fP[c].tp  == 'X' ) || (o.fP[c].tp  == 'F' ) || (o.fP[c].tp  == 'J' ) ) {
         td.innerHTML = o.fP[c].tl ;
         td.o = o ;
         td.tr = tr ;
         //td.btn = createTag('button', 'b', null, td, 'block', o.fP[c].tl ) ;
         addCls(td, o.fP[c].cf) ;
         if(o.fP[c].tp  == 'E') _l(td, "click", grid_action_callwin, o.fP[c].cv, o.fP[c].sv,i);
         if(o.fP[c].tp  == 'X') _l(td, "click", grid_action_callservice, o.fP[c].cv, o.fP[c].sv,i,o.fP[c].ac);
         if(o.fP[c].tp  == 'F') _l(td, "click", grid_action_file_get, o.fP[c].cv, o.fP[c].sv,i,o.fP[c].ac);
         if(o.fP[c].tp  == 'J') _l(td, "click", o.fP[c].cv, o.fP[c].sv,i,o.fP[c].ac);
      } else {
         td.innerHTML = '' ;
         if (o.fP[c].tp  == 'f' ) {
            td.ac = o.fP[c].ac ;
            td.w=o.w ;
            _l(td, "dblclick", getFile, td);
            td.fN = null ;
            td.innerHTML = '' ;
         }
         td.innerHTML = '' ;
      }
      if(o.fP[c].tp  == 'H'){
         td.o = o ;
         td.tr = tr ;
         _l(td, "click", grid_action_callwin, o.fP[c].cv, o.fP[c].sv,i);
      }
      k++ ;               
   }
//   o.row[i].style.width = o.gH.style.width ;
//   o.row[i].delbtn = createTag('div', 'gC:' + i + ':edtB', 'rB', tr, null, 'Ⓧ' ) ;
//   o.row[i].delbtn.r = o.row[i] ;
////   o.row[i].delbtn.style.position = 'absolute' ;
////   o.row[i].delbtn.style.top = tP + 'px' ;
//   _l(o.row[i].delbtn, "click", onGrdDel, i, o);
//   o.row[i].edtbtn = createTag('div', 'gC:' + i + ':delB', 'rB', tr, null, '✎' ) ;
//   o.row[i].edtbtn.r = o.row[i] ;
//   _l(o.row[i].edtbtn, "click", dcGridRow, i, o);
   return tr ;
}
function grid_action_callwin( b, e, act, mod, rNo ){
   var cWid = b.id.split(":")[2]+':'+b.o.id ;
   var kA = [b.o.row[rNo].col[0].rV] ;
   if(cWid) {
      if(b.o.w.dbP[cWid]){
         if(b.o.w.dbP[cWid]['trg']){
            var flg = getFncObj(b.o.w.dbP[cWid]['trg'][4]).apply(b.o.w,kA) ;
//console.info( 'grid_action_callwin kA', kA, 'trg', b.o.w.dbP[cWid]['trg'],  'b', b, 'e', ) ;
            if(!flg) return ;
         }           
      }           
   }
   Call_Service( b.o.w.id, act, kA[0], null, null, null, null, mod );
}
function grid_action_callservice( b, e, act, mod, rNo,f ){
   var cWid = b.id.split(":")[2]+':'+b.o.id ;
   var kA = [b.o.row[rNo].col[0].rV] ;
   if(b.o.w.dbP[cWid]){
      var flg = getFncObj(b.o.w.dbP[cWid]['trg'][4]).apply(b.o.w,kA) ;
      if(!flg) return ;
   }           
   var gp= '&kv=' + kA[0] + "&f=" + f ;
   Execute_Service( b.o.w.id, act, b.o.row[rNo].col[0].rV, null, null, null, null, mod, gp )
}
function grid_action_file_get( b, e, act, mod, rNo,f ){
   var cWid = b.id.split(":")[2]+':'+b.o.id ;
   var kA = [b.o.row[rNo].col[0].rV] ;
   if(b.o.w.dbP[cWid]){
      var flg = getFncObj(b.o.w.dbP[cWid]['trg'][4]).apply(b.o.w,kA) ;
      if(!flg) return ;
   }           
   var gp= '&kv=' + kA[0] + "&f=" + f ;
   var url = "index.php?srv=" + act + "&sNm=" + b.o.w.id + "&sEv=" + winA_g[b.o.w.id].mod_g + "&k=" + b.o.row[rNo].col[0].rV + gp  ;
   _s(pstDt_g, url,winA_g[b.o.w.id], getBlob, winA_g[b.o.w.id] ) ;
}
function getBlob(o) {
   var blob = new Blob([o.xR.responseText], {type: 'text/rtf'});
   let a = document.createElement("a");
   a.style = "display: none";
   document.body.appendChild(a);
   let url = window.URL.createObjectURL(blob);
   a.href = url;
   a.download = 'myFile.rtf';
   a.click();
   window.URL.revokeObjectURL(url);
}
function  tglGrdPop (b,e,o) {
   if(o.popSt == '1' ) {
      o.pop.style.display = 'none' ;
      o.popSt = '0' ;
      //o.xS.innerHTML = '?' ;
      o.gS.style.display = 'none' ;
      o.sB.style.display = 'none' ;
      o.fde_g.style.display = 'none' ;
   } else {
      o.pop.style.display = 'block' ;
      o.popSt = '1' ;
   }
}
function  onGrdDel (b,e,i,o) {
   if(o.getAttribute("md")=='0') return ;
   addCls(o.canB, 'act') ;
   addCls(o.cnfB, 'act') ;
   o.setAttribute("md", 3 ) ;
//   o.mod_g = 'DEL' ;
   o.gN.style.paddingTop = o.row[i].getBoundingClientRect().top -  o.gN.getBoundingClientRect().top - 3 + 'px' ;
   o.aB.style.paddingTop = o.gN.style.paddingTop ;
   if(o.sR != o.row[i]) setGrdSR(o,i );
}
function  onGrdDelCnf (b,e,o) {
   rmCls(o.canB, 'act') ;
   rmCls(o.cnfB, 'act') ;
//   o.canB.className = 'canB' ;
//   o.cnfB.className = 'cnfB' ;
   o.gN.style.paddingTop = '0' ;
   o.aB.style.paddingTop = o.gN.style.paddingTop ;
   var pm = o.mod_g ;
   o.mod_g = 'DEL' ;
   onGrdBtn(b,e, 'CNF', o ) ;
   o.mod_g = pm ;
}
function  onGrdDelCan (b,e,o) {
   rmCls(o.canB, 'act') ;
   rmCls(o.cnfB, 'act') ;
//   o.canB.className = 'canB' ;
//   o.cnfB.className = 'cnfB';
   o.gN.style.paddingTop = '0' ;
   o.aB.style.paddingTop = o.gN.style.paddingTop ;
//   setGridMode( o, 'NRM' ) ;
   o.setAttribute("md", 1 ) ;
}
function callGrid( o, v, rsA  ) {
   initGrid(o, v) ;
   o.v = v ;
   updateGrid (0,0, o, false, rsA) ;
}
function updateGrid (b,e,o, isSrch, rsAry) {
   var fP = o.fP ;
   if(o.cRw > 0) return ; // Number of changed row
   if (o.v > 0) setActing (o, true) ;
   o.cPag = 1 ;
   rmGrdRow ( o, 0, o.rCnt );
   o.rCnt = 0 ;
//   var p= o.pgSel.value ;
   o.aQp['c'] = Array() ;
   var l = 0 ;
   for ( c=0 ; c < fP.length ; c++ ){
      if( isSrch ){
         if ( (fP[c].sf !== 'h') ){
            var t = fP[c].tp ;
            if ( (t == 'd') || (t=='n')||(t=='i') ){
               var v = getWidVal(o.w,o.gS.col[c]) ;
               if ( (v != 'NULL') && (v != '') ){ 
                  //if (o.sCnd.length)  o.sCnd += " AND " ; 
                  //o.sCnd += 'q."' + fP[c].fn + '"=' + "'" + v + "' " ; 
                  o.aQp['c'][l] = Array() ;
                  if (l > 0 ) o.aQp['c'][l]['J'] = 'AND' ;
                  else o.aQp['c'][l]['J'] = '' ;
                  o.aQp['c'][l]['T'] = 'F' ;
                  o.aQp['c'][l]['C'] = '=' ;
                  o.aQp['c'][l]['L'] = fP[c].fn ;
                  o.aQp['c'][l]['R'] = v ;
                  l++ ;
               }
               v = getWidVal(o.w,o.gS.col[c].lt);
               if ( (v != 'NULL') && (v != '') ){ 
                  //if (o.sCnd.length)  o.sCnd += " AND " ; 
                  //o.sCnd += 'q."' + fP[c].fn + '"<' + "'" + v + "' " ; 
                  o.aQp['c'][l] = Array() ;
                  if (l > 0 ) o.aQp['c'][l]['J'] = 'AND' ;
                  else o.aQp['c'][l]['J'] = '' ;
                  o.aQp['c'][l]['T'] = 'F' ;
                  o.aQp['c'][l]['C'] = '<' ;
                  o.aQp['c'][l]['L'] = fP[c].fn ;
                  o.aQp['c'][l]['R'] = v ;
                  l++ ;
               }
               v = getWidVal(o.w,o.gS.col[c].gt) ;
               if ( (v != 'NULL') && (v != '') ){ 
                  //if (o.sCnd.length)  o.sCnd += " AND " ; 
                  //o.sCnd += 'q."' + fP[c].fn + '">' + "'" + v + "' " ; 
                  o.aQp['c'][l] = Array() ;
                  if (l > 0 ) o.aQp['c'][l]['J'] = 'AND' ;
                  else o.aQp['c'][l]['J'] = '' ;
                  o.aQp['c'][l]['T'] = 'F' ;
                  o.aQp['c'][l]['C'] = '>' ;
                  o.aQp['c'][l]['L'] = fP[c].fn ;
                  o.aQp['c'][l]['R'] = v ;
                  l++ ;
               }
            } else if ( t == 'c' ){
               var Wid = o.gS.col[c] ;
               var selCsv ='';
               for (var k=0; k<Wid.options.length; k++) {
                  if (Wid.options[k].selected ){
                     if (selCsv.length)  selCsv += ", " ; 
                     selCsv += "'" + Wid.options[k].text + "'" ;
                  }
               }
               if (selCsv.length){ 
                  //if (o.sCnd.length)  o.sCnd += " AND " ; 
                  //o.sCnd += 'q."' + fP[c].fn + '" IN (' + selCsv + ") " ; 
                  o.aQp['c'][l] = Array() ;
                  if (l > 0 ) o.aQp['c'][l]['J'] = 'AND' ;
                  else o.aQp['c'][l]['J'] = '' ;
                  o.aQp['c'][l]['T'] = 'F' ;
                  o.aQp['c'][l]['C'] = 'IN' ;
                  o.aQp['c'][l]['L'] = fP[c].fn ;
                  o.aQp['c'][l]['R'] = selCsv ;
                  l++ ;
               }
            } else if ( t == 'b' ){
            } else if ( (t == 't') || (t == 'a') ){
               var Wid = o.gS.col[c] ;
               if(Wid) if (Wid.value.length){ 
                  var v = getWidVal(o.w,Wid) ;
                  if( v != undefined ) {
                     //if (o.sCnd.length)  o.sCnd += " AND " ; 
                     //o.sCnd += 'q."' + fP[c].fn  + '"::text ilike ' + "'|" + v + "|' " ; 
                     o.aQp['c'][l] = Array() ;
                     if (l > 0 ) o.aQp['c'][l]['J'] = 'AND' ;
                     else o.aQp['c'][l]['J'] = '' ;
                     o.aQp['c'][l]['T'] = 'F' ;
                     o.aQp['c'][l]['C'] = 'ILIKE' ;
                     o.aQp['c'][l]['L'] = fP[c].fn ;
                     o.aQp['c'][l]['R'] = v ;
                     l++ ;
                  }
               }
            }
         }
      }
   }
   if (isSrch) gotoGridPage(0,0, 'f', o) ;
   else {
      gotoGridPage(0,0, 'f',  o) ;
//      o.sCnd = '' ;
   }
}
function sGrdOfs (b, e, o ) {
//DebugInfo ( ' keycode ' + e.keyCode + ' typ ' + e.type ) ;
   if (e.type=='keyup') {
      if(e.keyCode  == 13) {
      //o.oFs = parseInt(o.oS.value) ;
      o.aQp['o'] = parseInt(o.oS.value) ;
      rmGrdRow ( o, 0, o.rCnt );
      o.rCnt = 0 ;
//   if ( !(o.w.vR[o.v]) ) {
      getVw ( o.w, o.v, sGrdDt, o, "cfm", o.pL, 0, o.aQp, o.oStr, o.oFs, o.fStr ) ;
//   }
      }
   }
}
function gotoGridPage(b,e, mod, o) {
   var fP= o.fP ;
   var k = 0 ;
   if(o.cRw > 0) { // Number of changed row
      setActing (o, false) ;
      return ; 
   }
   //o.fStr = '' ;
   o.aQp['f'] = Array() ;
   for ( c=0 ; c < fP.length ; c++ ){
      var fW = o.gH.col[c].cb ;
      if ( fW && fW.checked ) {
         o.aQp['f'][c] = fP[c].fn ;
         o.fA[c] = 1 ;
         k++ ;
      } else o.fA[c] = 0 ;
   }
   if ( k == 0 ){
      o.allFld = 1 ;
      for ( c=0 ; c < fP.length ; c++ ){
         o.fA[c] = 1 ;
      }
   } else {
      o.allFld = 0 ;
   }
   if ( o.sort.length ){
      var ary = o.sort.split(":") ;
      o.aQp['s']['f'] = fP[ary[0]].fn ;
      //o.oStr = ' order by q."' +  fP[ary[0]].fn + '" ' ;
      if ( ary[1] == '1' ) o.aQp['s']['o'] = 'DESC' ;
      //if ( ary[1] == '1' ) o.oStr += ' DESC ' ;
   }
//   if ( !(o.w.vR[o.v]) ) {
      getVw ( o.w, o.v, sGrdDt, o, "cfm", o.pL, 0, o.aQp, o.oStr, o.oFs, o.fStr ) ;
//   }
}
function refreshGrid(o) {
   o.aQp['f'] = Array() ;
   for ( var c=0 ; c < o.fP.length ; c++ ){
      var fW = o.gH.col[c].cb ;
      if ( fW && fW.checked ) {
         o.aQp['f'][c] = fP[c].fn ;
      }
   }
   getVw ( o.w, o.v, sGrdDt, o, "cfm", o.pL, 0, o.aQp, o.oStr, o.oFs, o.fStr ) ;
}
function sGrdDt(o,rs ){
//   var rs = o.w.vR[o.v] ;
   if(o.cRw > 0) { // Number of changed row
      setActing (o, false) ;
      return ; 
   }
   if(rs[0]) o.trCnt = parseInt (rs[0][0] ) ;
   o.trAdd = 0 ;
   o.trEdt = 0 ;
   o.trDel = 0 ;
   if (o.trCnt > 1 ) o.setAttribute("rec_cnt", o.trCnt + " rows" ) ;
   else o.setAttribute("rec_cnt", "" );
   o.pC = Math.round((o.trCnt / o.pL) + 0.499) ;
   var tr, td, i, j, k, nobr ;
   var fP = o.fP ;
   var fA = o.fA ;
   if( o.sR) setGrdSR(o, o.sR.k );
   var gDiv = o.gDiv ;
   var gH = o.gH ;
   var gAg = o.gAg ;
   var lZ = (o.f == 'M' ) ? 25 : 0 ;
   //o.aStr = '' ;
   o.aQp['f'] = Array() ;
   if (rs[1]) {
      for ( c=0, k = 0 ; c < fP.length ; c++ ){
         if( fA[c] == 1)  {
            fP[c].fn = rs[1][k] ;
            k++ ;
         }
//         fA[c] = 1 ;
      }
   }
   for ( c=0 ; c < fP.length ; c++ ){
      if ( (fP[c].vf != 'h') && (fA[c] == 1) ){
         fP[c].cw = fP[c].wd ;
         fP[c].lp = (c==0) ? lZ : fP[c-1].lp + parseInt(fP[c-1].cw) + parseInt(0);
      } else {
         fP[c].cw = 0 ;
         fP[c].lp = (c==0) ? lZ : fP[c-1].lp + parseInt(fP[c-1].cw) ;
      }
      if ( o.agF == '-1' ) { 
         if (o.fP[c].ag=='t') {
            if ((fP[c].tp == 'n')||(fP[c].tp == 'i')) {
               setWidVal(o.w, td, '0.00') ;
            }
         }
      }
      //o.aStr = ( o.aStr != '' ) ? o.aStr + ', ' : o.aStr  ;
      //if ((o.fP[c].ag == 't')&&(( o.fP[c].tp == 'n' ) || ( o.fP[c].tp == 'i' ))) o.aStr += 'sum(q."' + o.fP[c].fn + '") as s' + c ;
      //else o.aStr += 'sum(1) as s' + c ;
      o.aQp['f'][c] = o.fP[c].fn ;
      if ((o.fP[c].ag == 't')&&(( o.fP[c].tp == 'n' ) || ( o.fP[c].tp == 'i' ))) o.aQp['a'][c] = o.fP[c].fn  ;
      else o.aQp['a'][c] = ''  ;
      //else o.aQp['f'][c] = '1' ;
   }
   var rC = rs[2].length ;
   var rCnt = o.rCnt ;
   for ( i=rCnt ; i < rCnt + rC ; i++ ){
      insGridRow( o, i)
      o.row[i].qI = undefined ;
      setGridRow (o, o.row[i], rs[2][i-rCnt], 'r' ) ;
      o.row[i].sF = 0 ;
      if (o.f == 'M' ) {
         o.row[i].cbn.checked = false ;
         rmCls(o.row[i], "mSel" ) ;
         o.row[i].cbn.td.style.display = 'block';
      }
      if(o.ss){
         addCls(o.row[i], rs[2][i-rCnt][o.ss]) ;
      }
      o.rCnt++  ;
   }
   //o.oFs += rC ;
   o.aQp['o'] += rC ;
   if (o.f == 'M' ) {
      o.lH.cbn.checked = false;
   }
   for ( c=0; c < fP.length ; c++ ){
      var fW = o.gH.col[c].cb ;
      if (!fW) break ;
      if ( !(fW.checked) && !(o.allFld) ) {
         if ( fP[c].vf != 'h' ) {
            o.gH.col[c].style.display = 'none' ;
            o.gAg.col[c].style.display = 'none' ;
         }
      }
      if ( o.allFld ) {
         if ( fP[c].vf != 'h' ) {
            o.gH.col[c].style.display = 'table-cell' ;
            o.gAg.col[c].style.display = 'table-cell' ;
         }
      }
      //o.gH.col[c].style.width= fP[c].cw + "px" ;
//      o.gH.col[c].style.left = fP[c].lp  + "px" ;
//      o.gAg.col[c].style.width= fP[c].cw + "px" ;
//      o.gAg.col[c].style.left = fP[c].lp  + "px" ;
   }
//   o.gH.style.width =  o.gH.col[c-1].getBoundingClientRect().right - o.gH.getBoundingClientRect().left + 'px';
   if ( o.eA['psf'] ) eval( o.eA['psf']) ;
   if (o.agF == '-1') {
      getVwAs ( o.w, o.v,setGridAgrCf,o, "s", o.pL, 0, o.aQp, '', '', o.aStr ) ;
   }
   setActing (o, false) ;
//   if( o.psf ) eval( o.psf.value );
   setGridScroll(o) ;
}
function setActing (o,actng ) {
   o.inAct = actng ? '1' : '0' ;
/**
   setDivFade(o,actng) ;
   if(actng) addCls(o, 'acting' ) ;
   else rmCls(o, 'acting' ) ;
**/
}
function setGridRow (o, tr, dt, tp ) {
   var j = 0;
   for ( c=0 ; c < o.fP.length ; c++ ){
      if ( o.fA[c] != 1 ){
         continue ;
      }
      j++ ;
   }
   for ( c=0, k=0 ; c < o.fP.length ; c++ ){
      var td = tr.col[c] ;
      if ( o.fA[c] != 1 ){
         td.style.display = 'none' ;
         continue ;
      }
      if ( tp == 'r') {
         td.rV = dt[k] ;
      } else {
         td.rV = getWidVal(o.w, dt[c]) ;
      }
      switch ( o.fP[c].tp ) {
         case 'i' :
         case 'n' :
            if ( tp == 'w' ) {
               var wVal = getWidVal(o.w,dt[c]) ;
               var agVal = getWidVal(o.w,o.gAg.col[c]) ;
               agVal = (agVal == 'NULL') ? 0 : agVal ;
               agVal = (agVal == '') ? '0' : agVal ;
               wVal = (wVal == 'NULL') ? '0' : wVal ;
               wVal = (wVal == '') ? '0' : wVal ;
               var oVal= (o.oldVal[c]) ? o.oldVal[c] : 0 ;
               if((o.agF == '-1')&&(o.fP[c].ag=='t')) setWidVal(o.w, o.gAg.col[c],  parseFloat(agVal) +  parseFloat(wVal) - parseFloat(oVal)) ;
            }
         case 't' :
         case 'T' :
         case 'H' :
         case 'd' :
         case 'f' :
            if ( tp == 'w' ) {
               if(o.fP[c].tp=='f'){
                  if (dt[c].fS[dt[c].fS.length-1] ) {
                     if (dt[c]) td.innerHTML = dt[c].fS[dt[c].fS.length-1].t.innerHTML ;
                     if (dt[c]) td.fN = dt[c].fS[dt[c].fS.length-1].rV;
                  } ;
               } else {
                  var val = getWidVal(o.w, dt[c]) ; 
//DebugInfo ('setGridRow ' + o.id + ' k ' + k + ' c ' + c + ' v ' + val  + ' rCnt ' + o.rCnt + ' dt ' + dt[c].id + ' n ' + dt[c].n ) ;
                  if( dt[c].n == val ) val = '' ;
                  if (dt[c] ) td.innerHTML = val ;
               }
            } else {
               if(o.fP[c].tp=='f'){
                  td.fN = dt[td.ac] ;
                  td.innerHTML = dt[k] ;
               } else {
                  if(o.fP[c].tl == '#') {
                     td.innerHTML = dt[j] ;
                  } else {
                     td.innerHTML = dt[k] ;
                  } 
               }
            }
           break ;
         case 'b' :
         case 'B' :
//DebugInfo( " 1 " + o.id + " " + o.fP[c].tp + " tp " + tp + " dt " + dt[c] ) ;
            if ( tp == 'r') {
               if ( (dt[k] == '1') || (dt[k] == 't' ) || (dt[k] == 'true' )) td.cbn.checked = true ;
               else td.cbn.checked = false ;
            } else if (dt[c] ) {
               td.cbn.checked = dt[c].checked ;
               td.val = (td.cbn.checked) ? 't' : 'f' ;
            }
           break ;
         case 'c' :
            if ( tp == 'w' ) {
               if (dt[c] ) if(dt[c].options[dt[c].selectedIndex]) td.innerHTML = dt[c].options[dt[c].selectedIndex].text;
            } else {
               td.innerHTML = dt[k] ;
            }
            break ;
         case 'a' :
            if ( tp == 'r') {
               var a = dt[k].split("||") ;
               td.rV = a[0] ;
               td.innerHTML = (a[1] == undefined) ? a[0] : a[1] ;
            } else if (dt[c] ) {
               td.innerHTML = dt[c].value ;
               td.rV = dt[c].rV ;
            }
            break ;
         case 'g' :
            if ( tp == 'r') {
               td.innerHTML = dt[k] ;
            } else if (dt[c] ) {
               td.innerHTML = dt[c].innerHTML ;
            }
         case 'E' :
         case 'X' :
         case 'F' :
         case 'J' :
            td.rV = ' Test Rov ' ;  dt[0].rV ;
            td.innerHTML = o.fP[c].tl.split('|')[0];
            break ;
      }
      td.title =  td.innerHTML;
      k++ ;               
   }
}
function gridSort(b,e, cNo, o ) {
   //o.oFs = 0 ;
   o.aQp['o'] = 0 ;
   if ( o.sort.length ){
      var ary = o.sort.split(":") ;
      if ( ary[0] == cNo ) {
         if ( ary[1] == '0' ) o.sort = cNo + ":1" ;
         else o.sort = cNo + ":0" ;
      } else {
         o.sort = cNo + ":0" ;
      }
   } else {
      o.sort = cNo + ":0" ;
   }
   if ( o.aQp['s']['o'] == 'DESC') o.aQp['s']['o'] = '' ;
   else o.aQp['s']['o'] = 'DESC' ;
   updateGrid (0,0, o, false, null) ;
}
function grdPdf (b,e, o ) {
   grdPrt (b,e, o, 'PDF' );
} 
function grdCsv (b,e, o ) {
   grdPrt (b,e, o, 'CSV' );
} 
function grdPrt (b,e, o, pdf=false ) {
   var fP = o.fP ;
   var aQp  = o.aQp  ;
   var k = 0 ; 
   o.aQp['f'] = Array() ;
   for ( c=0 ; c < fP.length ; c++ ){
      var fW = o.gH.col[c].cb ;
      if ( fW && fW.checked ) {
         o.aQp['f'][c] = fP[c].fn ;
         o.fA[c] = 1 ;
         k++ ;
      } else o.fA[c] = 0 ;
   }
   if ( k == 0 ){
      o.allFld = 1 ;
      for ( c=0 ; c < fP.length ; c++ ){
         o.fA[c] = 1 ;
      }
   } else {
      o.allFld = 0 ;
   }
   var dt = getRawPostWithoutForm(o.w, false) ;
   dt['qP_g'] = o.aQp ;
   dt['_v'] = o.v ;
   dt['_s'] = o.w.id ;
   var url = "index.php?f=3&srv=1125&ssrv=" + o.w.srv_g + "&wNm=" + o.id + '&pW=' + 1 + "&sNm=" + o.w.id + "&sEv=SHW&sct=m" ;
   var cf = gridPrintCF ;
   if (pdf =='PDF') {
      Create_PDF( o.w, 1125, o, dt );
//      url = "index.php?f=4&srv=1125&ssrv=" + o.w.srv_g + "&wNm=" + o.id + '&pW=' + 1 + "&pdf=1"  + "&sNm=" + o.w.id + "&sEv=SHW" ;
//      cf = gridPdfCF ;
   } else if (pdf =='CSV'){
      Create_CSV( o.w, 1134, o, dt );
   } else {
      _as(_e(dt), url,false, cf, o) ;
   }
   o.pop.style.display = 'none' ;
   o.popSt = '1' ;
}

function gridPdfCF( o, rs) {
   alertBox,( " <br>" + this.responseText + "<br>") ;
}
function gridPrintCF( o, rs) {
   var dt = eval( this.responseText.replace(/&amp;/g, "\&"));
   prtW = window.open('', '_blank' );
   prtW.document.firstChild.setAttribute("moznomarginboxes", 0 ) ;
   prtW.document.firstChild.setAttribute("mozdisallowselectionprint", 0 ) ;
   var pO = createTag ( 'div', 'Grid', 'Grid', prtW.document.body, 'table', null ) ;
   setHdNd('css', 'print', prtW.document, "all") ;
   pO.hdr = createTag ( 'div', 'gHdr', 'Hdr', pO, 'block', dt['_H'] ) ;
   pO.gH = createTag ( 'div', 'gH', 'gHRow', pO, 'table-row', null ) ;
   pO.gDiv = createTag ( 'div', 'gDiv', 'gDiv', pO, 'table', null ) ;
   pO.gAg = createTag('div', 'gAg', 'gAgr', pO, (o.agF == 1 ) ? 'none' : 'table-row', null ) ;
   pO.gH.col = new Array() ;
   pO.row = new Array() ;
   pO.agr = new Array() ;
   for ( c=0; c < o.fP.length ; c++ ){
      if ( o.fA[c] != 1 ) continue ;
      if ( o.fP[c].vf == 'h' ) continue ;
      pO.gH.col[c] = createTag ( 'div', 'gH' + c, 'gHCell', pO.gH, 'table-row', o.fP[c].tl ) ;
      // pO.gH.col[c].style.width= o.fP[c].cw + "px" ;
      if (o.fP[c].ag == 't' ) {
         if ((o.fP[c].tp == 'n')||(o.fP[c].tp == 'i')) {
            pO.agr[c] = 0 ;
         }
      }
   }
   for ( i=0 ; i < dt[2].length ; i++ ){
      var j = dt[2][i].length - 1;
      pO.row[i] = createTag ( 'div', 'gR_' + i, "gR_" +  i % 2, pO.gDiv, 'table-row', null ) ;
      pO.row[i].col = new Array() ;
      for ( c=0; c < o.fP.length ; c++ ){
         if ( o.fA[c] != 1 ) continue ;
         if ( o.fP[c].vf == 'h' ) continue ;
         if(o.fP[c].tl == '#') dt[2][i][c] = dt[2][i][j] ;
         pO.row[i].col[c] = createTag('div', 'gC:' + i + ':' + c, 'gCell' + o.fP[c].tp, pO.row[i], 'table-cell', dt[2][i][c] ) ;
         // pO.row[i].col[c].style.width= o.fP[c].cw + "px" ;
         if (o.fP[c].ag == 't' ) {
            if ((o.fP[c].tp == 'n')||(o.fP[c].tp == 'i')) {
               pO.agr[c] += parseInt(pO.agr[c]) ;
            }
         }
      }
   }
   for ( c=0 ; c < o.fP.length ; c++ ){
      if ( o.fP[c].vf == 'h' ) continue ;
      if ( o.fA[c] != 1 ) continue ;
      if ( o.agF == '-1' ) {
         if (o.fP[c].ag=='t') {
            if ((o.fP[c].tp == 'n')||(o.fP[c].tp == 'i')) {
               pO.gAg.col[c] = createTag ( 'div', 'gAg' + c, 'gHCell', pO.gAg, 'table-cell', pO.agr[c] ) ;
               //pO.gAg.col[c].style.width= o.fP[c].cw + "px" ;
            }
         }
      }
   }
   setTimeout(grdToPrinter, 1000);
}
function grdToPrinter() {
   prtW.print();
//   prtW.close();
   prtW = null ;
}
function gridScroll(gd,e,st, o ) {
   if (st == 1) {
      gd.drgF = 1 ;
      gd.mX = e.pageX||e.clientX + document.documentElement.scrollLeft ;
      gd.mY = e.pageY||e.clientY + document.documentElement.scrollTop ;
   }
   if (st == 2) {
      if(gd.drgF == 1 ) {
         var mX = e.pageX||e.clientX + document.documentElement.scrollLeft ;
         var mY = e.pageY||e.clientY + document.documentElement.scrollTop ;
         var dX = mX-gd.mX ;
         var dY = mY-gd.mY ;
         gd.scrollLeft += dX ;
         gd.scrollTop += dY ;
         gd.mX = mX ;
         gd.mY = mY ;
         syncGridScroll (gd,e,o,gd )
      }
   }
   if (st == 3) {
      gd.drgF = 0 ;
   }
   if (st == 0) {
      var dY = whlFct * (e.detail ? e.detail * 2 : -e.wheelDelta / 20 );
      gd.scrollTop += dY ;
      syncGridScroll (gd,e,o,gd )
      e.preventDefault();
      e.stopPropagation();
   }
   if (st == 5) {
   }
   if (st == 6) {
   }
   if (st == 7) {
   }
   if (st == 8) {
   }
//DebugInfo(" st " + st + " sT " +  gd.scrollTop + ' clientY ' + e.clientY + " pageY " + e.pageY + ' DocTop  ' + document.documentElement.scrollTop + " sL " +  gd.scrollLeft  ) ;
}
function grdRfrsh (gd,e,o ){
//DebugInfo('grdRfrsh ' + o.id ) ;
   //o.oFs = 0 ;
   o.aQp['o'] = 0 ;
   updateGrid (0,0, o, false, null) ;
   o.pop.style.display = 'none' ;
   o.popSt = '1' ;
}
function grdMore (gd,e,o ){
//DebugInfo('grdMore ' + o.id ) ;
   o.pop.style.display = 'none' ;
   o.popSt = '1' ;
   gotoGridPage(0,0, 'g', o) ;
//   getVw ( o.w, o.v, sGrdDt, o, "cfm", o.pL, 0, o.aQp, o.oStr, o.ofs , o.fStr ) ;
}
function gridFocus (gd,e,o ){
//   if(e.target.id != gd.id ) return ;
//DebugInfo("in " +  e.target.id) ;
//   document.body.style.overflowY = 'hidden';
//   document.body.style.position = 'fixed';
//   o.sH.style.zIndex = 12 ;
//   o.sV.style.zIndex = 12 ;
//   gridScroll(o.gDvc,e,0, o ) ;
   syncGridScroll(0,0,o,o.gDvc) ;
}
function gridBlur (gd,e,o ){
//   if(e.target.id != gd.id ) return false ;
//DebugInfo("out " +  e.target.id) ;
//   gd.drgF = 0 ;
//   document.body.style.overflowY = 'auto';
//   document.body.style.position = 'relative';
//   o.sH.style.zIndex = 0 ;
//   o.sV.style.zIndex = 0 ;
//   gridScroll(o.gDvc,e,0, o ) ;
   syncGridScroll(0,0,o,o.gDvc) ;
}
function GridScrollToSel (o,c,m){
   if (!c) return ;
   var rO = o.gDvc.getBoundingClientRect() ;
   var rC = c.getBoundingClientRect() ;
   var dT = rO.top - rC.top;  
   var dB = rC.bottom - rO.bottom;  
   var dL = rO.left - rC.left;  
   var dR = rC.right - rO.right;  
   if ( m=='v') {
      if( dT > 0 ) o.gDvc.scrollTop = o.gDvc.scrollTop - dT ;  
      else if ( dB > 0 ) o.gDvc.scrollTop = o.gDvc.scrollTop + dB ;
   }
   if ( m=='h') {
      if( dR > 0 ) o.gDvc.scrollLeft = o.gDvc.scrollLeft - dL ;  
      else if ( dL > 0 ) o.gDvc.scrollLeft = o.gDvc.scrollLeft + dL ;
   }
   //setGridScroll(o) ;
   syncGridScroll(0,0,o,o.gDvc) ;
}
function syncGridScroll (t,e,o,io ){
   var rG = io.getBoundingClientRect() ;
   var rO = o.getBoundingClientRect() ;
//   o.gDvc.scrollLeft = io.scrollLeft ;
   o.gH.scrollLeft = io.scrollLeft ;
   o.gN.scrollLeft = io.scrollLeft ;
   o.gS.scrollLeft = io.scrollLeft ;
   o.gAg.scrollLeft = io.scrollLeft ;
//   o.rR.scrollTop = o.gDvc.scrollTop ;
//   o.lR.scrollTop = o.gDvc.scrollTop ;
}
function setGridScroll(o) {
//  var rG = o.gDiv.getBoundingClientRect() ;
//  var rH = o.gH.getBoundingClientRect() ;
//  var rO = o.getBoundingClientRect() ;
//  var sH = parseInt( o.gDiv.scrollHeight ) ; 
//  var sT = parseInt( o.gDiv.scrollTop ) ; 
//  var sL = parseInt( o.gDiv.scrollLeft ) ; 
//  var sW = parseInt( o.gDiv.scrollWidth ) ; 
//  o.pH.style.width = parseInt(rG.width * rG.width/o.gDiv.scrollWidth ) + 'px' ;
//  o.pV.style.height = parseInt(rG.height * rG.height/o.gDiv.scrollHeight ) + 'px' ;
//  o.sV.style.right = parseInt(rO.right) - parseInt(rG.right)+ 'px' ;
//  o.sH.style.bottom = parseInt(rO.bottom) - parseInt(rG.bottom)+ 'px' ;
//  o.sV.style.top = parseInt(rG.top) - parseInt(rO.top)+ 'px' ; 
//  o.sH.style.left = parseInt(rG.left) - parseInt(rO.left)+ 'px' ;
//  o.sH.style.right = o.sV.style.right ;
//  o.sV.style.bottom = o.sH.style.bottom ;
//  o.sV.style.display = ( (parseInt( o.gDiv.scrollHeight) - parseInt(rG.height) ) > 3 ) ? 'block' : 'none' ;
//  o.sH.style.display = ( (parseInt( o.gDiv.scrollWidth) - parseInt(rG.width )) > 3 ) ? 'block' : 'none' ;
}
function appendGridAdd(o) {
   var fP = o.fP ;
   var tr = insGridRow( o, o.rCnt) ;
   setGridRow (o, tr, o.gN.col, 'w' ) ;
   o.rCnt++  ;
   return tr ;
}
function setGridEdt(o) {
   if(!o.sR) {
      alertBox('No row selected to edit ') ;
      return ;
   }
   setGridRow (o, o.sR, o.gN.col, 'w' ) ;
   o.gN.style.paddingTop = '0' ;
   o.aB.style.paddingTop = o.gN.style.paddingTop ;
   o.mod_g = 'NRM' ;
}
function deleteGridRow (o) {
   for ( c=0 ; c < o.fP.length ; c++ ){
      if ( (o.fP[c].tp == 'i' ) || (o.fP[c].tp == 'n' )) {
         var agVal = getWidVal(o.w,o.gAg.col[c]) ;
         agVal = (agVal == 'NULL') ? '0' : agVal ;
         agVal = (agVal == '') ? '0' : agVal ;
         if((o.agF == '-1') && (o.fP[c].ag=='t')) setWidVal(o.w, o.gAg.col[c],  parseFloat(agVal) - parseFloat(o.oldVal[c])) ;
         o.oldVal[c] = 0 ;
      }
   }
   rmGrdRow ( o, o.sR.k, o.sR.k )  ; 
   o.sR = null ;
}
function rmGrdRow ( o, frm, to ) {
   for ( i=frm ; i <= to ; i++ ){
      if (!(o.row[i])) continue ;
      o.row[i].qI = undefined ;
      o.row[i].sF = 0 ;
      o.row[i].style.display = 'none' ;
//      o.row[i].delbtn.style.display = 'none';
//      o.row[i].edtbtn.style.display = 'none';
      if(o.f=='M')o.row[i].cbn.td.style.display = 'none'; ;
   }
   setGridScroll(o) ;
}
function grdRowClick(b,e, key, o ){
   if ( o.rCnt <= key) return ;
   if (! o.row){ return ; }
   if (! o.row[key]){ return ; }
   if(e.type == 'dblclick' ){
      dcGridRow(b,e, key, o ) ;
   } else {
      if(!(o.row[key].clk)) o.row[key].clk= 1;
      else o.row[key].clk += 1;
      o.row[key].tmr = setTimeout(selectGridRow, 250,b,e,key,o) ;
   }
}
function selectGridRow(b,e, key, o ){
//   if (o.snf_g == '0' ) return ;
//   if (o.f == 'M' ) return ;
//   if ( o.rCnt <= key) return ;
//   if (! o.row){ return ; }
//   if (! o.row[key]){ return ; }
   if(o.row[key].clk == 0) return ;
   if(o.row[key].clk > 1) {
      dcGridRow(b,e, key, o ) ;
   } else {
      o.row[key].clk = 0;
      setGrdSR(o,key) ;
   }
}
function dcGridRow(b,e, key, o ){
   o.row[key].clk = 0;
//   clearTimeout(o.row[key].tmr) ;
   if ( o.row[key] != o.sR ) setGrdSR(o, key ) ; 
   if(o.getAttribute("md")=='0') return ;
   o.setAttribute("md", 3 ) ;
   o.mod_g = 'EDT' ;
   if ( o.w.typ_g == 'LoV' ) {
        o.w.mod_g = 'EDT' ;
        setGridMode( o, 'EDT' ) ;
     }
   o.gN.style.paddingTop = o.row[key].getBoundingClientRect().top -  o.gN.getBoundingClientRect().top - 3 + 'px' ;
   o.aB.style.paddingTop = o.gN.style.paddingTop ;
   o.gN.focus() ;
}
function setGrdRowDfl(o) {
   if(!(o.gN)) return ;
   for ( var w in o.gN.col ) {
      if ((w == "itemValidation") || (w=="isArray") ) continue ;
      setWidDfl ( o.w, o.gN.col[w] ) ;
   }
} ;
function setGrdSR(o, key) {
   if(!(o.row)) return ;
   var fP = o.fP ;
   if ( o.sR != null ){
//      if(o.btn['EDT'])o.btn['EDT'].disabled = true ;
//      if(o.btn['DEL'])o.btn['DEL'].disabled = true ;
      for ( c=0 ; c < fP.length ; c++ ){
         switch ( fP[c].tp  ){
            case 'd' :
               var dte = new Date() ;
               if (o.oldVal[c] != 'NULL' ) o.oldVal[c] = dte.print("%d-%m-%Y") ;
               setWidVal(o.w,o.gN.col[c],o.oldVal[c]) ;
               break ;
            case 'i' :
            case 'n' :
            case 'a' :
            case 't' :
            case 'f' :
            case 'T' :
            case 'H' :
               setWidVal(o.w,o.gN.col[c],'') ;
               o.oldVal[c] = 'NULL' ;
               break ;
            case 'c' :
               for ( k = 0 ; k < o.gN.col[c].options.length ; k++ ){
                  o.gN.col[c].options[k].selected = false ;
               }
               o.oldVal[c] = '-1' ;
               break ;
            case 'b' :
               o.gN.col[c].checked = false ;
               o.oldVal[c] = false ;
               break ;
         }
      }
      rmCls(o.sR, "gSel") ;
   }
//DebugInfo ( '4 |' + o.eA + ' || '  + '| row select |' + key + '||' + o.id) ;
   if ( (o.row[key]) && (o.row[key] != o.sR )) {
//      o.sCls = o.row[key].className ;
      o.sR = o.row[key] ;
      o.sR.k = key ;
      addCls(o.sR,"gSel") ;
//      if(o.btn['EDT'])o.btn['EDT'].disabled = false ;
//      if(o.btn['DEL'])o.btn['DEL'].disabled = false ;
      for ( c=0 ; c < fP.length ; c++ ){
         switch ( fP[c].tp  ){
            case 'd' :
            case 'i' :
            case 'n' :
            case 't' :
            case 'T' :
            case 'H' :
               o.oldVal[c] = o.sR.col[c].innerHTML ;
               if(o.oldVal[c] == '' ) o.oldVal[c] = 'NULL' ; 
               setWidVal(o.w,o.gN.col[c],o.oldVal[c]) ;
               break ;
            case 'f' :
               o.oldVal[c] = o.sR.col[c].rV ;
               if(o.oldVal[c] == '' ) o.oldVal[c] = 'NULL' ; 
               setWidVal(o.w,o.gN.col[c],o.oldVal[c]) ;
               break ;
            case 'a' :
               o.oldVal[c] = o.sR.col[c].rV ;
               setWidVal(o.w,o.gN.col[c],o.oldVal[c] + '||' + o.sR.col[c].innerHTML) ;
               if(o.oldVal[c] == '' ) o.oldVal[c] = 'NULL' ; 
               break ;
            case 'c' :
               for ( k = 0 ; k < o.gN.col[c].options.length ; k++ ){
                  if ( o.gN.col[c].options[k].text == o.sR.col[c].innerHTML ){
                     o.oldVal[c] = o.gN.col[c].options[k].value ;
                     o.gN.col[c].options[k].selected = true ;
                  } else o.gN.col[c].options[k].selected = false ;
               }
               if(o.oldVal[c] == '' ) o.oldVal[c] = '-1' ; 
               break ;
            case 'b' :
               o.gN.col[c].checked = o.sR.col[c].cbn.checked ;
               o.oldVal[c] = o.gN.col[c].checked ;
               break ;
         }
      }
   } else {
      setGrdRowDfl(o);
      o.sR = null ;
   }
   for ( var h in o.eA ) {
      if(h == 'onselect' ) eval(o.eA[h]) ;
   }
}
function grdSrch(b,e,o, isSrch, rsAry){
   if(o.cRw > 0) return ; // Number of changed row
   o.oS.value  = 0;
   //o.oFs  = 0;
   o.aQp['o'] = 0 ;
   rmGrdRow ( o, 0, o.rCnt );
   updateGrid (b,e,o, isSrch, rsAry);
   o.gS.style.display = 'none' ;
   o.sB.style.display = 'none' ;
   o.fde_g.style.display = 'none' ;
}
function grdSrchShow(b,e,o){
   if(o.cRw > 0) return ; // Number of changed row
   o.gS.style.display = 'table-row' ;
   o.sB.style.display = 'block' ;
   //o.xS.innerHTML = '?' ;
   o.fde_g.style.display = 'block' ;
   o.pop.style.display = 'none' ;
   o.popSt = '0' ;
}
function setGridAgr(b,e,o ){
   var fP = o.fP ;
   var gAg = o.gAg ;
   var fA = o.fA ;
   for (var c=0 ; c < fP.length ; c++ ){
      if ((fP[c].vf == 'h') || ( !(fA[c]) )) gAg.col[c].style.display = 'none' ;
   }
   getVwAs ( o.w, o.v, setGridAgrCf,o,"s", o.pL, o.cPag, o.aQp, o.oStr,'', o.aStr ) 
}
function setGridAgrCf(o,rs){
   if(rs)if(rs[2]) for ( var c=0 ; c < o.fP.length ; c++ ){
      if (o.fP[c].ag == 't') {
         if ((o.fP[c].tp == 'n')||(o.fP[c].tp == 'i')) {
            setWidVal(o.w, o.gAg.col[c], rs[2][0][c]) ;
         }
      }
   }
}
function onGrdBtn(b,e, act, o ) {
   if( o.snf_g ==  false ) return ;
   o.mod_g = (o.mod_g == 'NRM') ? 'ADD' : o.mod_g ;
   savToGrid( o ) ;
   o.setAttribute("md", 1 ) ;
}
function addToGrid(o) {
   onGrdBtn(null, null, 'ADD', o) ;
}
function savToGrid(o) {
   o.gDvc.scrollTop = o.gDvc.clientHeight ;
//DebugInfo ( "savToGrid " + o.id  + " act " + o.mod_g ) ;
   if(!(setExe (o,o.mod_g))) return  ;
   o.cRw++ ;
   if ( o.w.typ_g == 'LoV' ) {
      var bEf = o.parm['bf' + o.mod_g] ;
      if( bEf ) eval( bEf );
      var dt=_e(o.w.exDt);
      var url = "index.php?f=2&srv=1128&sNm=" + o.w.id + "&sEv=" + o.mod_g +"&aEv=" + o.mod_g ;
      if ( o.mod_g == 'EDT' ) {
         setGridEdt(o) ;
      }
      //o.oFs = 0
      o.aQp['o'] = 0 ;
      o.cPag = 1 ;
      rmGrdRow ( o, 0, o.rCnt );
      o.rCnt = 0 ;
      o.cRw = 0 ; // Number of changed row
      _s( dt, url, o, exCf, o);   // ToAsync
   } else {
      if ( o.mod_g == 'ADD' ) {
         var tr = appendGridAdd(o) ;
         o.trAdd +=1 ;
         tr.qI = o.w.exDt.length - 1 ;
      } else if ( o.mod_g == 'DEL')  {
         o.trDel +=1 ;
         deleteGridRow(o,o.w) ;
      } else {
         o.trEdt +=1 ;
         setGridEdt(o) ;
      }
   }
   var recnt = '' ;
   if ( o.trAdd > 0 ) {
      recnt += o.trAdd + ' Add +'
   }
   if ( o.trEdt > 0 ) {
      recnt += o.trEdt + ' Edit +'
   }
   if ( o.trDel > 0 ) {
      recnt += o.trDel + ' Delete +'
   }
   if ( o.trCnt > 1 ) {
      recnt += o.trCnt + ' rows '
   }
   o.setAttribute("rec_cnt", recnt ) ;
   setGrdRowDfl(o);
//   setGridMode( o, 'NRM' ) ;
//   o.focus() ;
}
function canInGrid( o ) {
   setGridMode( o, 'NRM' ) ;
}
function setGridMode( o, mod ) {
   switch ( mod ) {
      case 'ADD' :
         for ( var w in o.gN.col ) {
            if ((w == "itemValidation") || (w=="isArray") ) continue ;
            setWidDfl ( o.w, o.gN.col[w] ) ;
         }
         if ( o.stp = 'inline' )o.gDiv.style.top = parseInt(o.gDiv.style.top) + 20 + "px" ;
      case 'EDT' :
      case 'VRF' :
      case 'ATH' :
      case 'APV' :
      case 'DCD' :
      case 'DEL' :
//           o.fde_g.style.display = 'block' ;
//           o.gN.style.display = 'block' ;
           for ( var b in o.btn ) {
              if (( b != 'SAV') && (b != 'CAN') && ( b != 'HLD') && (b != 'PCD') && ( b != 'RTN')) o.btn[b].style.display =  'none' ;
              else o.btn[b].style.display =  'block' ;
           }
         break ;
      default :
           for ( var b in o.btn ) {
              if (( b != 'SAV') && (b != 'CAN') && ( b != 'HLD') && (b != 'PCD') && ( b != 'RTN')) o.btn[b].style.display =  'none' ;
              else o.btn[b].style.display =  'none' ;
           }
//           o.fde_g.style.display = 'none' ;
//           o.gN.style.display = 'none' ;
           o.focus() ;
           if(o.mod_g == 'ADD' )if ( o.stp = 'inline' )o.gDiv.style.top = parseInt(o.gDiv.style.top) - 20 + "px" ;
         break ;
   }
   o.mod_g = mod ;
   for ( var c in o.gN.col ) {
      var w = o.gN.col[c] ;
      var v = (o.fP[c].af == 'h' ) ? false : true ;
      var s = (o.fP[c].af == 'h' ) ? false : true ;
      if ( (o.w.ws[w.id] !== undefined) && (o.w.ws[w.id][o.w.mod_g] != undefined) ){
         s &= (o.w.ws[w.id][o.w.mod_g].s == 't') ? true : false ;
      }
      if ( (o.w.ws[w.id] !== undefined) && (o.w.ws[w.id][o.w.mod_g] != undefined) ){
         v &= (o.w.ws[w.id][o.w.mod_g].v == 't') ? true : false ;
      }
      tglWidShw ( w, v) ;
      tglWidEbl ( w, s) ;
   }
}
function onBtnPress(b, e, mod, win, nEv, sEv ) {
   var pM = (sEv == undefined) ? win.mod_g : sEv ;
//console.info('onBtnPress win ', win.id, 'mod',  mod ) ; 
   pM = (!pM) ? win.pmod : pM ;
   var qWid, bEf, aEf;
   var key = (win.kw_g) ? win.kw_g.value : '' ;
   //if(mod==0) mod = 'SHW';
   if(( mod == 'SHW' ) || ( mod == 'NRM' )) {
//      popScrWithDfl(win) ;
   }
   if(( win.mA[mod].typ == '2' ) || ( mod == 'ADD' )) {
      popScrWithDfl(win) ;
   } else if ((mod != 'SAV')&&(mod != 'CNF')&&(mod != 'CAN')&&(mod != 'HLD')&& (b != 'PCD') &&(mod != 'RTN') ) {
        win.olDt = getRawPostWithoutForm(win, false ) ;
   }
   bEf = win.parm["bf" + mod ] ;
   if( bEf ) { eval( bEf ); }
   if (( mod == 'SAV') || ( mod == 'CNF' ) || ( mod == 'HLD' ) || ( mod == 'PCD' ) || (mod == 'RTN')||(mod == 'DRT')) {
      var qWid;
      if (win.btn['SAV']) win.btn['SAV'].disabled = true; 
      if (win.btn['CAN']) win.btn['CAN'].disabled = true; 
      if (win.btn['RTN']) win.btn['RTN'].disabled = true; 
      if (win.btn['HLD']) win.btn['HLD'].disabled = true; 
      if (win.btn['PCD']) win.btn['PCD'].disabled = true; 
      bEf = win.parm["bf" + win.mod_g ] ;
      if( bEf ) { eval( bEf ); }
      if(win.err_g == 1 ) {
         win.err_g=0 ;
         if (win.btn['SAV']) win.btn['SAV'].disabled = false; 
         if (win.btn['CAN']) win.btn['CAN'].disabled = false; 
         if (win.btn['RTN']) win.btn['RTN'].disabled = false; 
         if (win.btn['HLD']) win.btn['HLD'].disabled = false; 
         if (win.btn['PCD']) win.btn['PCD'].disabled = false; 
         return 0 ;
      }
      if(!(setExe (win,(mod=='DRT')?mod:win.mod_g))) {
         if (win.btn['SAV']) win.btn['SAV'].disabled = false; 
         if (win.btn['CAN']) win.btn['CAN'].disabled = false; 
         if (win.btn['RTN']) win.btn['RTN'].disabled = false; 
         if (win.btn['HLD']) win.btn['HLD'].disabled = false; 
         if (win.btn['PCD']) win.btn['PCD'].disabled = false; 
         return 0 ;
      }
      if(win.rD) {
         win.exDt['ext'] = new Array() ;
         win.exDt['ext']['N'] = getWidVal(win, win.rNoteW) ; 
         win.exDt['ext']['S'] = getWidVal(win, win.rSlipW) ; 
         win.exDt['ext']['P'] = getWidVal(win, win.rPNoteW) ; 
      }
      var dt=_e(win.exDt);
      var n = (nEv) ? '&nEv=' + nEv : ''
      var url = "index.php?f=2&srv=1128&sNm=" + win.id + "&sEv="+pM+"&aEv=" + win.mod_g + "&k=" + key + n + '&st='+mod ;
      _s( dt, url, win, exCf, win,mod);   // ToAsync
   } else {
      if ((mod == 'CAN')||( mod == 'RTN') ) mod =  (win.mA['NRM'])? 'NRM' : 'SHW' ;
      if((win.mod_g != mod) &&(mod!='DRT')) setScreenMode( win, mod, (mod == 'ADD' ) ? false :true  ) ;
      // setScreenMode( win, mod, (mod == 'ADD' ) ? false :true  ) ;
   }
   return 1 ;
}
function setScreenMode( win, mod, isPopul ) {
//console.info( 'setScreenMode win', win.id, ' win.mod_g ', win.mod_g, 'mod', mod, ' win.pmod', win.pmod, 'isPopul', isPopul ) ;
   var savBtn = win.btn['SAV'] ;
   var canBtn = win.btn['CAN'] ;
   var hldBtn = win.btn['HLD'] ;
   var pcdBtn = win.btn['PCD'] ;
   var rtnBtn = win.btn['RTN'] ;
   for ( var a in win.mA ){
      if ((a == "itemValidation") || (a=="isArray") ) continue ;
      switch ( mod ) {
         case 'SHW' :
         case 'NRM' :
         case 'SAV' :
         case 'CNF' :
         case 'CAN' :
         case 'PRT' :
         case 'SRH' :
               if(win.btn[a]){ win.btn[a].style.display = 'block' ; }
            break ;
         default :
               if(win.btn[a]){ win.btn[a].style.display = 'none' ; }
      }
   }
   switch ( mod ) {
      case 'NRM' :
      case 'SHW' :
      case 'SAV' :
      case 'CNF' :
      case 'HLD' :
      case 'PCD' :
      case 'RTN' :
      case 'CAN' :
            for ( var w in win.wA ){
                if ((w == "itemValidation") || (w=="isArray") ) continue ;
                if (!win.wA[w]) continue ;
                if( win.wA[w].t != 'grid' ) continue ;
                if( win.wA[w].mod_g ) {
                   win.wA[w].cRw = 0 ;
                   setGridMode( win.wA[w], 'NRM' ) ;
                } ;
                // else setGridMode( win.wA[w], 'NRM' ) ;
            }
      case 'PRT' :
      case 'SRH' :
            if(win.btn['DRT'])win.btn['DRT'].style.display = 'none' ;
            if(savBtn)savBtn.style.display = 'none' ;
            if(canBtn)canBtn.style.display = 'none' ;
            if(hldBtn)hldBtn.style.display = 'none' ;
            if(pcdBtn)pcdBtn.style.display = 'none' ;
            if(rtnBtn)rtnBtn.style.display = 'none' ;
            if(win.rD) rmCls(win.rD, 'on' ) ;
         break ;
      default :
            if(win.btn['DRT'])win.btn['DRT'].style.display = 'block' ;
            if(canBtn)canBtn.style.display = 'block' ;
            if(rtnBtn && (win.mA[mod].rfl == 't'))rtnBtn.style.display = 'block' ;
            if(savBtn && (win.mA[mod].hfl != 't')){
               savBtn.style.display = 'block' ;
               if ( (mod == 'ADD') || (mod == 'EDT' )){
                  savBtn.title = win.mA['SAV'].lbl ;
                  savBtn.innerHTML = win.mA['SAV'].lbl ;
               } else { 
                  savBtn.title = win.mA['CNF'].lbl ;
                  savBtn.innerHTML = win.mA['CNF'].lbl ;
               }
            }
            if(hldBtn && (win.mA[mod].hfl == 't')) hldBtn.style.display = 'block' ;
            if(pcdBtn && (win.mA[mod].hfl == 't')) pcdBtn.style.display = 'block' ;
            if(win.rD) addCls(win.rD, 'on' ) ;
   }
//DebugInfo( '4035  mod_g ' + win.mod_g + ' mod ' + mod + ' pmod ' + win.pmod ) ;
   win.pmod = (win.mod_g == 0) ? 'SHW' : win.mod_g ;
   win.mod_g = mod ;
   switch ( mod ) {
      case 'NRM' :
      case 'SHW' :
      case 'SAV' : 
      case 'CNF' :
      case 'HLD' :
      case 'PCD' :
      case 'RTN' :
      case 'CAN' :
           setScreen(win, isPopul) ;
         break ;
      case 'DRT' :
         break ;
      default :
           setScreen(win, false) ;
         break ;
   }
}
function callGallery( win, wid, vno, col, fmt ) {
//console.info('callGallery', win, wid, vno, col, fmt )
   if (wid.pic == undefined ) wid.pic = new Array() ;
   wid.tmr = 0 ;
   wid.tcnt = 0 ;
   wid.icnt = 0 ;
   wid.curimg = -1 ;
   wid.tpause = 0 ;
   wid.tmtfunc = "slideGal" ;
   wid.v = vno ;
   wid.innerHTML = '' ;
   stopWidTimer(win.id, wid.id) ;
   if ( !(win.vR[vno]) ){
      //getVwAs ( win, vno,setGal, wid, 'a' ) ;
      getVw ( win, vno,setGal, wid, 'a' ) ;
   } else {
      setGal(wid,win.vR[vno]) ;
   }
}
function setGal (wid,rs) {
   wid.rV = -1 ;
//console.info('setGal', wid, rs )
   if( rs[2] != undefined ) {
      wid.icnt = rs[2].length ;
      for (var l=0 ; l < rs[2].length ; l++ ){
         wid.pic[l] = createTag( "div", wid.id + '_o' + l, 'galElm', wid, 'block', null ) ;
         wid.pic[l].b = createTag( "div", wid.id + '_b' + l , 'delBtn', wid.pic[l], 'block', 'X' ) ;
         wid.pic[l].ebd = createTag( "img", wid.id + '_p' + l , 'galImg', wid.pic[l], 'block', null ) ;
         wid.pic[l].lbl = createTag( "span", wid.id + '_l' + l , 'galLbl', wid.pic[l], 'block', rs[2][l][1] ) ;
         wid.pic[l].ebd.setAttribute('style', 'overflow: auto; width: 100%; height: 100%;');
         //wid.pic[l].ebd.setAttribute('type', 'application/pdf');
         //getVw (wid.w, rs[2][l][0], setEmbed, wid.pic[l], 'cfp',null,null,null,null,null,null,kV ) ; 
         _l(wid.pic[l].ebd, "click", setGalSelection, wid,  l);
         wid.pic[l].w = wid.w ;
         wid.pic[l].v = wid.v ;
         wid.pic[l].c = 0;
         wid.pic[l].ebd.w = wid.w;
         wid.pic[l].ebd.wid = wid;
         wid.pic[l].b.w = wid.w;
         wid.pic[l].b.wid = wid;
         //setEmbed(wid.pic[l], l);
         var vR = wid.w.vR[wid.v][2] ;
         if (( vR ) ){
            if (( vR[l] ) ){
               setImg(wid.pic[l].ebd, vR[l][0]) ;
               wid.pic[l].rV = vR[l][0] ;
               //var urlTo = "/index.php?f=3&srv=1136&ssrv=" + wid.w.srv_g + "&dmd=pdf&thumb=1&fno=" + vR[l][0]  ;
               //wid.pic[l].ebd.style.background = 'url(' + urlTo + ') no-repeat ';
               //wid.pic[l].ebd.src = urlTo ;
               wid.pic[l].b.onclick = function() {
                  var url = "index.php?f=3&srv=1135&ssrv=" + this.w.srv_g + "&fno=" + this.parentNode.rV  ;
                  _s(false, url, this.wid, setExe,this.wid,'DEL',this.parentNode.rV) ;
                  this.parentNode.style.display = 'none' ;  
               } ;
            }
         }
      }
      wid.tmr  = setTimeout("trigWidTimer('" + wid.w.id + "','" + wid.id + "')", 1000);
   }
}
function setGalSelection (o, e, wid, l) {
   //if (wid.rV != l ) {
   //   wid.rV =l ;
      //if(wid.selCf) wid.selCf( wid ) ;
      //wid.selCf( wid ) ;
   //}
   window.open( "index.php?f=3&srv=1136&ssrv=" + wid.w.srv_g + "&fno=" + wid.pic[l].rV)  ;
}
function insertToGal(fncn, widid, p2, val, idx) {
console.info ('insertToGal widid', widid, ' w ', w_g.id, 'p2', p2, ' fno', val, 'this', this.id, 'idx', idx) ;
//   addToGallery(w_g.wA[widid], fno)
   wid = w_g.wA[widid] ;
   if (wid.pic == undefined ){
      wid.pic = new Array() ;
      wid.icnt = 0 ;
   }
   if (!val) return ;
   if (val =='') return ;
   var l = wid.ict+idx ;
   wid.pic[l] = createTag( "div", wid.id + '_o' + l, 'galElm', wid, 'block', null ) ;
   wid.pic[l].b = createTag( "div", wid.id + '_b' + l , 'delBtn', wid.pic[l], 'block', 'X' ) ;
   //wid.pic[l].ebd = createTag( "div", wid.id + '_p' + l , 'galImg', wid.pic[l], 'block', null ) ;
   wid.pic[l].ebd = createTag( "img", wid.id + '_p' + l , 'galImg', wid.pic[l], 'block', null ) ;
   wid.pic[l].lbl = createTag( "span", wid.id + '_l' + l , 'galLbl', wid.pic[l], 'block', "" ) ;
   //wid.pic[l].ebd.setAttribute('style', 'overflow: auto; width: 100%; height: 100%;');
   //wid.pic[l].ebd.setAttribute('type', 'application/pdf');
   //getVw (wid.w, rs[2][l][0], setEmbed, wid.pic[l], 'cfp',null,null,null,null,null,null,kV ) ; 
   _l(wid.pic[l].ebd, "click", setGalSelection, wid,  l);
   wid.pic[l].w = wid.w;
   wid.pic[l].ebd.w = wid.w;
   wid.pic[l].ebd.wid = wid;
   wid.pic[l].b.w = wid.w;
   wid.pic[l].b.wid = wid;
   wid.pic[l].rV= val ;
   //var urlTo = "/index.php?f=3&srv=1136&dmd=pdf&thumb=1&fno=" + wid.rV + '&ssrv=' + wid.w.srv_g ;
   //wid.pic[l].ebd.style.background = 'url(' + urlTo + ') no-repeat ';
   //var fUrl = window.URL.createObjectURL(this.fN[idx]) ;
   //wid.pic[l].ebd.src = 'url(' + fUrl + ') no-repeat ';
   //wid.pic[l].ebd.src = this.fd.readAsDataURL(this.fN[idx]) ;
   //wid.pic[l].ebd.src = urlTo ;
   //setImg(wid.pic[l].ebd, vA[i]);
}
function addToGallery(wid, val) {
//console.info ('addToGallery wid', wid, ' fno', val) ;
   if (wid.pic == undefined ){
      wid.pic = new Array() ;
      wid.icnt = 0 ;
   }
   if (!val) return ;
   if (val =='') return ;
   var vA = val.split(",") ;
   for (var i=0 ; i < vA.length ; i++ ){
      var l = i + wid.icnt ;
      wid.pic[l] = createTag( "div", wid.id + '_o' + l, 'galElm', wid, 'block', null ) ;
      wid.pic[l].b = createTag( "div", wid.id + '_b' + l , 'delBtn', wid.pic[l], 'block', 'X' ) ;
      //wid.pic[l].ebd = createTag( "div", wid.id + '_p' + l , 'galImg', wid.pic[l], 'block', null ) ;
      wid.pic[l].ebd = createTag( "div", wid.id + '_p' + l , 'galImg', wid.pic[l], 'block', null ) ;
      wid.pic[l].lbl = createTag( "span", wid.id + '_l' + l , 'galLbl', wid.pic[l], 'block', "" ) ;
      //wid.pic[l].ebd.setAttribute('style', 'overflow: auto; width: 100%; height: 100%;');
      //wid.pic[l].ebd.setAttribute('type', 'application/pdf');
      //getVw (wid.w, rs[2][l][0], setEmbed, wid.pic[l], 'cfp',null,null,null,null,null,null,kV ) ; 
      _l(wid.pic[l].ebd, "click", setGalSelection, wid,  l);
      wid.pic[l].w = wid.w;
      wid.pic[l].ebd.w = wid.w;
      wid.pic[l].ebd.wid = wid;
      wid.pic[l].b.w = wid.w;
      wid.pic[l].b.wid = wid;
      if (( vA[i] ) ){
         wid.pic[l].rV= vA[i].replace(/'/g,'') ;
         var urlTo = "/index.php?f=3&srv=1136&dmd=pdf&thumb=1&fno=" + wid.rV + '&ssrv=' + wid.w.srv_g ;
         wid.pic[l].ebd.style.background = 'url(' + urlTo + ') no-repeat ';
         //wid.pic[l].ebd.src = urlTo ;
         //setImg(wid.pic[l].ebd, vA[i]);
      }
   }
}
function slideGal (winid, widid) {
   var win = winA_g[winid] ;
   var wid = win.wA[widid] ;
   //wid.curimg += 1 ;
   //if (wid.curimg >= wid.icnt ) { 
   //   wid.curimg = 0 ;
   //}
   //wid.style.background = 'url(' + wid.pic[wid.curimg].pth + ') no-repeat ';
   //if (!(wid.icnt ) ) { 
   //   wid.lbl.innerHTML = '' ;
   //} else {
   //   wid.lbl.innerHTML = parseInt(wid.curimg + 1) + "/" + wid.icnt ;
   //}
}
function trigWidTimer(winid, widid) {
   var win = winA_g[winid] ;
   var wid = win.wA[widid] ;
   if(wid.tmr) clearTimeout(wid.tmr);
   win.tcnt +=  1 ;
   eval( wid.tmtfunc + "('" + win.id + "','" + wid.id + "')" ) ;
   wid.tmr  = setTimeout("trigWidTimer('" + win.id + "','" + wid.id + "')", 3000);
}
function resetWidTimer(winid, widid) {
   var win = winA_g[winid] ;
   var wid = win.wA[widid] ;
   wid.tcnt = 0 ;
   wid.tpause = 0 ;
}
function startWidTimer(winid, widid) {
   var win = winA_g[winid] ;
   var wid = win.wA[widid] ;
   resetWidTimer(winid, widid)
   wid.tmr  = setTimeout("trigWidTimer('" + win.id + "','" + wid.id + "')", 3000);
}

function pauseWidTimer(winid, widid) {
   var win = winA_g[winid] ;
   var wid = win.wA[widid] ;
   if(!( wid.tpause)) {
      clearTimeout(wid.tmr);
      wid.tpause = 1 ;
   }
}
function restartWidTimer(winid, widid) {
   var win = winA_g[winid] ;
   var wid = win.wA[widid] ;
   if( wid.tpause) {
      wid.tpause = 1 ;
      wid.tmr  = setTimeout("trigWidTimer('" + win.id + "','" + wid.id + "')", 3000);
   }
}
function stopWidTimer(winid, widid) {
   var win = winA_g[winid] ;
   var wid = win.wA[widid] ;
   if(wid.tmr) {
      clearTimeout(wid.tmr);
      wid.tcnt= 0 ;
      wid.tpause = 0 ;
      wid.tmr  = 0;
   } 
}
function callTree( win, wid, vno1, vno2 ) {
   var w ;
   if (!(wid.k)) {
      wid.k = '0' ;
   }
   wid.val = wid.k ;
   if (! (wid.aGrp) ){
      return ;
   }
   var mA =  new Array();
   var mL = 0 ;
   wid.mA = mA ;
   wid.v = vno1 ;
   wid.v1 = vno2 ;
   if(wid.cb) wid.removeChild(wid.cb);
   if(wid.ol) wid.removeChild(wid.ol);
   wid.cb = undefined ;
   wid.ol = undefined ;
   mA[mL] = new ossTMenu(wid, wid, 0, wid.title, '0') ;
   if ( !(win.vR[vno1]) ) {
      getVwAs ( win, vno1,setTMenu, wid, 'a' ) ;
   }
}
function setTMenu(wid,rs) {
   if( rs[2] != undefined ) {
      for (var l=0 ; l < rs[2].length ; l++ ){
         mL = rs[2][l][0] ;
         pM =  wid.mA[mL] ;
         if(pM) {
            wid.mA[rs[2][l][1]] = new ossTMenu(pM.root, pM.div, rs[2][l][1], rs[2][l][2], rs[2][l][1] );
            pM.items[ pM.items.length] = wid.mA[rs[2][l][1]]
         }
      }
   }
   wid.mA[0].div.value = 1 ;
   if(wid.mA[0].div.cb) wid.mA[0].div.cb.checked = true ;
}
function ossTMenu(o, pDiv, val, txt, idx) {
   this.w = o.w ;
   this.root = o ;
   this.items   = new Array();
   this.addItem = ossTMAddItem;
   if( pDiv.ol == undefined ) {
      if( pDiv.t != 'tree' ) {
         addCls(pDiv, 'node') ;
         pDiv.cb = createTag ( 'input', 'i_' + pDiv.id, null , pDiv, null, null, null, 'checkbox' ) ;
      }
      pDiv.ol = createTag ( 'ol', 'n_' + pDiv.id, null , pDiv, null, null ) ;
   }
   this.div = createTag ( 'li', idx, 'itm' , pDiv.ol, null, null ) ;
   this.div.l = createTag ( 'label', 'l_' + idx, null , this.div, null, txt ) ;
   if(val) this.div.rV = val ;
   //this.div.l.setAttribute("onclick", "actTree('" + o.w.id + "','" + o.id + "', '" + val + "')") ;
   _l(this.div.l, "click", actTree, o, val);
}
function actTree(b,e,o, val ){
   if( !(val) || (val == 'null' ) || (val == '')){
      val = o.rV ;
   } else {
      o.rV = val ;
   }
   fillGroup( o.w, o.aGrp, val );
}
function ossTMAddItem(val, txt) {
   var lastItemIndex = this.items.length;
   var mnu = new ossTMenu(this.root, this.div, val, txt, this.div.id + ":" + lastItemIndex );
   this.items[lastItemIndex] = mnu ;
   getVwAs ( mnu.this.div, this.root.v, TMAddItem, mnu, 'a', null, null, null, null, null, null, val ) ;
}

function TMAddItem(mnu,rs) {
//   var rs = this.responseText ;
   if( rs[2] != undefined ) {
      for ( var l=0 ; l < rs[2].length ; l++ ){
         mnu.addItem(rs[2][l][0], rs[2][l][1]) ;
      }
   }
}
function callScrFrmMsg(winid, divid, key,flg) {
   var win = winA_g[winid] ;
   var div = win.wA[divid] ;
   var val = div.sR.col[4].innerHTML ;
   var act = div.sR.col[9].innerHTML ;
   var v1 = div.sR.col[10].innerHTML ;
   var v2 = div.sR.col[11].innerHTML ;
   var w1 = div.sR.col[13].innerHTML ;
   var w2 = div.sR.col[14].innerHTML ;
   var dt= new Array();
   dt[0]= new Array();
   dt[0]['__s'] = w_g.id ;
   dt[0]['__w'] = div.id ;
   dt[0]['__e'] = 'VRF' ;
   dt[0]['z'] = div.sR.col[0].innerHTML ;
   _s(_e(dt), "index.php?f=2&srv=1128&sNm=" + winid + "&sEv=VRF"+"&aEv=VRF", false,null) ;
   var mod = 'SHW' ;
   //------------edited to retain the message screen that they already choosed-------------- 
   if(flg) window.open ("index.php?srv=" + act + "&smd=" + mod + "&k=" + val + "&v1=" + v1 + "&v2=" + v2 + '&w1=' + w1 + '&w2=' + w2);
   else window.location.href = "index.php?srv=" + act + "&smd=" + mod + "&k=" + val + "&v1=" + v1 + "&v2=" + v2 + '&w1=' + w1 + '&w2=' + w2 ;
}
function cmbKeyIn( e ) {
   var oL = this.listbox.options.length;
   if (!e) var e = window.event;
   var cC = (e.keyCode) ? e.keyCode : (e.which) ? e.which : '' ;
   var eTrgt = (e.target) ? e.target : (e.srcElement) ? e.srcElement : null ;
   if ( eTrgt.nodeName.toUpperCase() == 'INPUT' && cC >= 32 && cC <= 126 ) {
      var eStr = this.value + String.fromCharCode(cC);
      var eL = eStr.length;
      for (var i = 0; i < oL; i++) {
         if (eStr == this.listbox.options[i].value.substring(0, eL)) {
            this.listbox.options[i].selected = true;
            break;
         }
      }
   }
}
function toggleDivShow(winid, divid, sts) {
   var win = winA_g[winid];
   var div = win.wA[divid] ;
   div.style.display = (div.style.display == 'none' ) ? "block" : "none" ;
}
function cmbTextChg() {
   var eL = this.value.length;
   var oL = this.listbox.options.length;
   for (var i = 0; i < oL; i++) {
      if (this.value == this.listbox.options[i].value.substring(0, eL)) {
         this.listbox.options[i].selected = true;
         break;
      }
   }
   this.value = this.listbox.options[this.listbox.selectedIndex].value;
}
function cmbChg() {
   this.textbox.value = this.options[this.selectedIndex].value;
}
   function getChildById( id, tag  ) {
      if ( tag == null ) tag = bdy_g ;
      for (var i = 0; i < tag.childNodes.length; i++) {
         if ( tag.childNodes[i].id != undefined ) {
            if ( tag.childNodes[i].id == id) {
               return tag.childNodes[i] ;
            }
         }
      }
      return null ;
   }

   function getElmntById( id, tag ) {
      if ( tag == null ) tag = bdy_g.parentNode ;
      if ( tag == false ) tag = bdy_g ;
      if ( tag == undefined ) tag = bdy_g ;
      if ( tag == undefined ) return null ;
      var els = tag.getElementsByTagName('*');
      var elsLen = els.length;
      for (i = 0 ; i < elsLen; i++) {
         if ( els[i].id  == id ) {
            return els[i] ;
         }
      }
      return null ;
   }
   function getElmntByName( id, tag ) {
      if ( tag == null ) tag = bdy_g ;
      var els = tag.getElementsByTagName('*');
      var elsLen = els.length;
      for (i = 0 ; i < elsLen; i++) {
         if ( els[i].name  == id ) {
            return els[i] ;
         }
      }
      return null ;
   }

   function getChildByName( name, tag ) {
      if ( tag == null ) tag = bdy_g ;
      for (var i = 0; i < tag.childNodes.length; i++) {
         if ( tag.childNodes[i].name == name) {
            return tag.childNodes[i] ;
         }
      }
      return null ;
   }
   function getChildByTag( tNam, tag ) {
      if ( tag == null ) tag = bdy_g ;
      var l = 0 ;
      var ret = new Array() ;
      for (var i = 0; i < tag.childNodes.length; i++) {
         if ( tag.childNodes[i].tagName == tNam) {
            ret[l] = tag.childNodes[i] ;
            l++ ;
         }
      }
      return ret ;
   }

   function getElmntsById( id, tag ) {
      if ( tag == null ) tag = bdy_g ;
      var l = 0 ;
      var els = tag.getElementsByTagName('*');
      var elsLen = els.length;
      var ret = new Array() ;
      for (var i = 0; i < els.length; i++) {
         if ( els[i].id == id) {
            ret[l] = els[i] ;
            l++ ;
         }
      }
      return ret ;
   }
  
var n_v = '0123456789';
var a_v = 'abcdefghijklmnopqrstuvwxyz';
var A_v = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
var s_v = "][?<~#!@$%^&*()+=}|:;,>{-_" ;
function isValidData(win, wid) {
   if ( !(wid)) return true ;
   if ( !(wid.w)) return true ;
   if ( !(wid.w.wA[wid.id]) ) return true ;
   var val = getWidVal(wid.w,wid) ; 
   if( ( wid.i) && ( wid.i =='t') ){
      if( (val == '') || ( val == 'NULL' ) ) {
         addCls(wid, 'err') ;
         alertBox('The entry for <b>' + wid.title + '</b> should not be blank !' ) ;
         return '_N'
      } 
   } else {
      if(( val == '' ) || ( val == 'NULL' )) {
         rmCls(wid, 'err') ;
         wid.style.background = '' ;
         return val ;
      } 
   }
   if( !(validate_wid( wid.w, wid, val, wid.f ))){
      return '_N'
   } 
   wid.style.background = '' ;
   return val ;
}
function validate_wid( win, wid, val, fmt ){
   var rtn ;
//DebugInfo(" validate_wid ::|::" + win.id + "::|::" + wid.id + "::|::" + val + "::|::" + fmt ) ;
   if (!(fmt)) return true ;
   if (wid.w != win) return true ;
   if (fmt.length < 1 ) return true ;
   arr = fmt.split("#") ;
   switch ( fmt.charAt(0)){
      case 'X' : rtn = v_an( win, val, arr ) ; break ;
      case 'A' : rtn = v_ca( win, val, arr ) ; break ;
      case 'a' : rtn = v_sm( win, val, arr ) ; break ;
      case 'C' : rtn = v_ap( win, val, arr ) ; break ;
      case 'I' : rtn = v_nm( win, val, arr ) ; break ;
      case 'i' : rtn = v_iv( win, val, arr ) ; break ;
      case 'E' : rtn = v_em( win, val, arr ) ; break ;
      case 'D' : rtn = v_dt( win, val, arr ) ; break ;
      case 'T' : rtn = v_an( win, val, arr ) ; break ;
      case 'Z' : rtn = v_ay( win, val, arr ) ; break ;
      case 'N' : rtn = v_nm( win, val, arr ) ; break ;
      default : rtn = false ;
   }
   if ( rtn == false ){
      wid.style.background = '#e88' ;
      alertBox('Please provide correct data for <b><i>"' + wid.title + '"</i></b> ! Format : ' + fmt + ' val ' + val ) ;
   }
   return rtn ;
}
function v_an(win, val, fa){
   var an = a_v + A_v + n_v ;
   var C_v = a_v + A_v ;
   if ( fa[1] ) {  
      if ( fa[1].length > 0 ) {
         if (val.length != fa[1].length) {
            return false ;
         }
         for ( var i = 0 ; i < val.length ; i++ ) {
            var fc = fa[1].charAt(i);
            var ch = val.charAt(i);
            switch(fc){
               case 'X' : 
                  if ( an.indexOf(ch) == -1 ) return false ;
                  break ;
               case 'A' :
                  if ( A_v.indexOf(ch) == -1 ) return false ;
                  break ;
               case 'a' :
                  if ( a_v.indexOf(ch) == -1 ) return false ;
                  break ;
               case 'I' :
                  if ( n_v.indexOf(ch) == -1 ) return false ;
                  break ;
               case 'C' :
                  if ( C_v.indexOf(ch) == -1 ) return false ;
                  break ;
               case 'Z' :
                  break ;
               default :
                  if ( ch != fc ) return false ;
                  break ;
            } 
         }
      }
   }
   for ( var i = 0 ; i < val.length ; i++ ) {
      var ch = val.charAt(i);
      if ( an.indexOf(ch) == -1 ){
         return false ;
      }
   }
   return true ;
}
function v_ay(win, val, fa){
   if ( fa[1] ) {  
      if ( fa[1].length > 0 ) {
         if (val.length != fa[1].length) {
            return false ;
         }
         for ( var i = 0 ; i < val.length ; i++ ) {
            var fc = fa[1].charAt(i);
            var ch = val.charAt(i);
            switch(fc){
               case 'Z' : 
                  break ;
               default :
                  if ( ch != fc ) return false ;
                  break ;
            } 
         }
      }
   }
   return true ;
}
function v_ap(win, val, fa){
   var C_v = a_v + A_v ;
   if ( fa[1] ) {  
      if ( fa[1].length > 0 ) {
         if (val.length != fa[1].length) {
            return false ;
         }
         for ( var i = 0 ; i < val.length ; i++ ) {
            var fc = fa[1].charAt(i);
            var ch = val.charAt(i);
            switch(fc){
               case 'A' :
                  if ( A_v.indexOf(ch) == -1 ) return false ;
                  break ;
               case 'a' :
                  if ( a_v.indexOf(ch) == -1 ) return false ;
                  break ;
               default :
                  if ( ch != fc ) return false ;
                  break ;
            } 
         }
      }
   }
   for ( var i = 0 ; i < val.length ; i++ ) {
      var ch = val.charAt(i);
      if ( C_v.indexOf(ch) == -1 ){
         return false ;
      }
   }
   return true ;
}
function v_ca(win, val, fa){
   if ( fa[1] ) {  
      if ( fa[1].length > 0 ) {
         if (val.length != fa[1].length) {
            return false ;
         }
         for ( var i = 0 ; i < val.length ; i++ ) {
            var fc = fa[1].charAt(i);
            var ch = val.charAt(i);
            switch(fc){
               case 'A' :
                  if ( A_v.indexOf(ch) == -1 ) return false ;
                  break ;
               default :
                  if ( ch != fc ) return false ;
                  break ;
            } 
         }
      }
   }
   for ( var i = 0 ; i < val.length ; i++ ) {
      var ch = val.charAt(i);
      if ( A_v.indexOf(ch) == -1 ){
         return false ;
      }
   }
   return true ;
}
function v_sm(win, val, fa){
   if ( fa[1] ) {  
      if ( fa[1].length > 0 ) {
         if (val.length != fa[1].length) {
            return false ;
         }
         for ( var i = 0 ; i < val.length ; i++ ) {
            var fc = fa[1].charAt(i);
            var ch = val.charAt(i);
            switch(fc){
               case 'A' :
                  if ( a_v.indexOf(ch) == -1 ) return false ;
                  break ;
               default :
                  if ( ch != fc ) return false ;
                  break ;
            } 
         }
      }
   }
   for ( var i = 0 ; i < val.length ; i++ ) {
      var ch = val.charAt(i);
      if ( a_v.indexOf(ch) == -1 ){
         return false ;
      }
   }
   return true ;
}
function v_em(win, val){
   if(  val.length < 5 ) return false;
   var str = a_v + A_v + n_v + "_-@.";
   for (var i = 0; i < val.length; i++){
      var ch = val.charAt(i);
      if ( str.indexOf(ch) == -1 ) return false ;
   }
   var ary = val.split('@') ;
   if( ary.length != 2) return false ;
   if ( ary[1].indexOf(".") == -1 ) return false;
   return true;
}
function v_nm(win, val, fa){
   var str = n_v + ".+-";
   if ( fa[1] ) {  
      if ( fa[1].length > 0 ) {
         if (val.length != fa[1].length) {
            return false ;
         }
         for ( var i = 0 ; i < val.length ; i++ ) {
            var fc = fa[1].charAt(i);
            var ch = val.charAt(i);
            switch(fc){
               case '.' :
               case '+' :
               case '-' :
                  if ( ch != fc ) return false ;
                  break ;
               default : 
                  var ch = val.charAt(i);
                  if ( str.indexOf(ch) == -1 ){
                     return false ;
                  }
            } 
         }
         return true ;
      }
   }
   for ( var i = 0 ; i < val.length ; i++ ) {
      var ch = val.charAt(i);
      if ( str.indexOf(ch) == -1 ){
         return false ;
      }
   }
   var ary = val.split('.') ;
   if( ary.length > 2) return false ;
   if ( fa[2] ) {  
      if ( fa[2].length > 0 ) {
         if ( parseInt(val) < parseInt(fa[2])) return false ;
      }
   }
   if ( fa[3] ) {  
      if ( fa[3].length > 0 ) {
         if ( parseInt(val) > parseInt(fa[3])) return false ;
      }
   }
   var wid ;
   if ( fa[4] ) {  
      if ( fa[4].length > 0 ) {
         if(( wid = win.wA[fa[4]])){
            if ( parseInt(val) < getWidVal(win, wid )) return false ;
         }
      }
   }
   if ( fa[5] ) {  
      if ( fa[5].length > 0 ) {
         if(( wid = win.wA[fa[5]])){
            if ( parseInt(val) > getWidVal(win, wid )) return false ;
         }
      }
   }
   return true ;
}
function v_pw(win, np, cp){
   var pw = a_v + A_v + n_v + s_v ;
   for ( var i = 0 ; i < np.length ; i++ ) {
      var ch = np.charAt(i);
      if ( pw.indexOf(ch) == -1 ){
         return 'n' ;
      }
   }
   for ( var j = 0 ; j < cp.length ; j++ ) {
      var ch = np.charAt(j);
      if ( pw.indexOf(ch) == -1 ){
         return 'c' ;
      }
   }
   if ( np != cp ) return 'm'  ;
   return true ;
}
function v_iv (win, val, fa) {
   var rg = /^(\d{3}|\d{2}|\d):([0-5][0-9]|[0-5][0-9]:[0-5][0-9])$/;
   if(val != '' && !val.match(rg)) return false;
   return true ;
}
function gDate(x) {
   var a = x.split('-');
   return new Date (a[2], a[1] - 1,a[0]);
}
function v_dt(win, val, fa){
   var tod = new Date();
   tod.setHours(0,0,0,0,0) ;
   var ary = val.split("-") ;
   if( ary.length != 3) return false ;
   var dy = (ary[0] * 10) / 10 ;
   var mn = (ary[1] * 10) / 10 ;
   var yr = (ary[2] * 10) / 10 ;
   if( dy == 0 ) return false ;
   if( mn == 0 ) return false ;
   if( mn > 12 ) return false ;
   if( yr == 0 ) return false ;
   if (mn == 2) {
      if (dy > (((yr % 4 == 0) && ( (!(yr % 100 == 0)) || (yr % 400 == 0))) ? 29 : 28 )) return false ;
   } else if (mn == 4 || mn == 6 || mn == 9 || mn == 11) {
      if (dy > 30) return false;
   }
   if (dy > 31) return false;
   var vdt = gDate(val) ;
   if ( fa[2] ) {  
      if ( fa[2].length > 0 ) {
         if( fa[2] == 'C' ){
             if ( vdt < tod ) return false ;
         } else {
            if ( vdt < gDate(fa[2])) return false ;
         }
      }
   }
   if ( fa[3] ) {  
      if ( fa[3].length > 0 ) {
         if( fa[2] == 'C' ){
             if ( vdt > tod ) return false ;
         } else {
            if ( vdt > gDate(fa[2])) return false ;
         }
      }
   }
   var wid ;
   if ( fa[4] ) {  
      if ( fa[4].length > 0 ) {
         if(( wid = win.wA[fa[4]])){
            if ( vdt < parseDate(wid.value)) return false ;
         }
      }
   }
   if ( fa[5] ) {  
      if ( fa[5].length > 0 ) {
         if(( wid = win.wA[fa[5]])){
            if ( vdt > parseDate(wid.value)) return false ;
         }
      }
   }
   return true ;
}
function v_tm(win,val, fa){
   var tm ;
   var ary = val.split(' ') ;
   if( ary.length > 2) return false ;
   if( ary.length == 2){
      if (!(v_dt(ary[1], fa))) return false ;
      tm = ary[1] ;
   } else { 
      tm = ary[0] ;
   } ;
   var ary = tm.split(':') ;
   var hh = parseInt(ary[0]) ;
   var mm = parseInt(ary[1]) ;
   var ss = parseInt(ary[2]) ;
   if( hh > 23 ) return false ;
   if( hh < 0 ) return false ;
   if( mm > 59 ) return false ;
   if( mm < 0 ) return false ;
   if( ss > 60 ) return false ;
   if( ss < 0 ) return false ;
   return true ;
}
var MONTH_NAMES=new Array('January','February','March','April','May','June','July','August','September','October','November','December','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
var DAY_NAMES=new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sun','Mon','Tue','Wed','Thu','Fri','Sat');
function LZ(x) {return(x<0||x>9?"":"0")+x}
function isDate(val,format) {
   var date=getDateFromFormat(val,format);
   if (date==0) { return false; }
   return true;
}
function compareDates(date1,dateformat1,date2,dateformat2) {
   var d1=getDateFromFormat(date1,dateformat1);
   var d2=getDateFromFormat(date2,dateformat2);
   if (d1==0 || d2==0) {
      return -1;
   } else if (d1 > d2) {
      return 1;
   }
   return 0;
}
function formatDate(date,format) {
   format=format+"";
   var result="";
   var iFmt=0;
   var c="";
   var token="";
   var y=date.getYear()+"";
   var M=date.getMonth()+1;
   var d=date.getDate();
   var E=date.getDay();
   var H=date.getHours();
   var m=date.getMinutes();
   var s=date.getSeconds();
   var yyyy,yy,MMM,MM,dd,hh,h,mm,ss,ampm,HH,H,KK,K,kk,k;
   var v=new Object();
   if (y.length < 4) {y=""+(y-0+1900);}
   v["y"]=""+y;
   v["yyyy"]=y;
   v["yy"]=y.substring(2,4);
   v["M"]=M;
   v["MM"]=LZ(M);
   v["MMM"]=MONTH_NAMES[M-1];
   v["NNN"]=MONTH_NAMES[M+11];
   v["d"]=d;
   v["dd"]=LZ(d);
   v["E"]=DAY_NAMES[E+7];
   v["EE"]=DAY_NAMES[E];
   v["H"]=H;
   v["HH"]=LZ(H);
   if (H==0){v["h"]=12;}
   else if (H>12){v["h"]=H-12;}
   else {v["h"]=H;}
   v["hh"]=LZ(v["h"]);
   if (H>11){v["K"]=H-12;} else {v["K"]=H;}
   v["k"]=H+1;
   v["KK"]=LZ(v["K"]);
   v["kk"]=LZ(v["k"]);
   if (H > 11) { v["a"]="PM"; }
   else { v["a"]="AM"; }
   v["m"]=m;
   v["mm"]=LZ(m);
   v["s"]=s;
   v["ss"]=LZ(s);
   while (iFmt < format.length) {
      c=format.charAt(iFmt);
      token="";
      while ((format.charAt(iFmt)==c) && (iFmt < format.length)) {
         token += format.charAt(iFmt++);
      }
      if (v[token] != null) { result=result + v[token]; }
      else { result=result + token; }
   }
   return result;
}
function _isInteger(val) {
   var digits="1234567890";
   for (var i=0; i < val.length; i++) {
      if (digits.indexOf(val.charAt(i))==-1) { return false; }
   }
   return true;
}
function _getInt(str,i,minlength,maxlength) {
   for (var x=maxlength; x>=minlength; x--) {
      var token=str.substring(i,i+x);
      if (token.length < minlength) { return null; }
      if (_isInteger(token)) { return token; }
   }
   return null;
}
function getDateFromFormat(val,format) {
   val=val+"";
   format=format+"";
   var i_val=0;
   var iFmt=0;
   var c="";
   var token="";
   var token2="";
   var x,y;
   var now=new Date();
   var year=now.getYear();
   var month=now.getMonth()+1;
   var date=1;
   var hh=now.getHours();
   var mm=now.getMinutes();
   var ss=now.getSeconds();
   var ampm="";
   
   while (iFmt < format.length) {
      // Get next token from format string
      c=format.charAt(iFmt);
      token="";
      while ((format.charAt(iFmt)==c) && (iFmt < format.length)) {
         token += format.charAt(iFmt++);
      }
      // Extract contents of value based on format token
      if (token=="yyyy" || token=="yy" || token=="y") {
         if (token=="yyyy") { x=4;y=4; }
         if (token=="yy")   { x=2;y=2; }
         if (token=="y")    { x=2;y=4; }
         year=_getInt(val,i_val,x,y);
         if (year==null) { return 0; }
         i_val += year.length;
         if (year.length==2) {
            if (year > 70) { year=1900+(year-0); }
            else { year=2000+(year-0); }
         }
      } else if (token=="MMM"||token=="NNN"){
         month=0;
         for (var i=0; i<MONTH_NAMES.length; i++) {
            var month_name=MONTH_NAMES[i];
            if (val.substring(i_val,i_val+month_name.length).toLowerCase()==month_name.toLowerCase()) {
               if (token=="MMM"||(token=="NNN"&&i>11)) {
                  month=i+1;
                  if (month>12) { month -= 12; }
                  i_val += month_name.length;
                  break;
               }
            }
         }
         if ((month < 1)||(month>12)){return 0;}
      } else if (token=="EE"||token=="E"){
         for (var i=0; i<DAY_NAMES.length; i++) {
            var day_name=DAY_NAMES[i];
            if (val.substring(i_val,i_val+day_name.length).toLowerCase()==day_name.toLowerCase()) {
               i_val += day_name.length;
               break;
            }
         }
      } else if (token=="MM"||token=="M") {
         month=_getInt(val,i_val,token.length,2);
         if(month==null||(month<1)||(month>12)){return 0;}
         i_val+=month.length;
      } else if (token=="dd"||token=="d") {
         date=_getInt(val,i_val,token.length,2);
         if(date==null||(date<1)||(date>31)){return 0;}
         i_val+=date.length;
      } else if (token=="hh"||token=="h") {
         hh=_getInt(val,i_val,token.length,2);
         if(hh==null||(hh<1)||(hh>12)){return 0;}
         i_val+=hh.length;
      } else if (token=="HH"||token=="H") {
         hh=_getInt(val,i_val,token.length,2);
         if(hh==null||(hh<0)||(hh>23)){return 0;}
         i_val+=hh.length;
      } else if (token=="KK"||token=="K") {
         hh=_getInt(val,i_val,token.length,2);
         if(hh==null||(hh<0)||(hh>11)){return 0;}
         i_val+=hh.length;
      } else if (token=="kk"||token=="k") {
         hh=_getInt(val,i_val,token.length,2);
         if(hh==null||(hh<1)||(hh>24)){return 0;}
         i_val+=hh.length;hh--;
      } else if (token=="mm"||token=="m") {
         mm=_getInt(val,i_val,token.length,2);
         if(mm==null||(mm<0)||(mm>59)){return 0;}
         i_val+=mm.length;
      } else if (token=="ss"||token=="s") {
         ss=_getInt(val,i_val,token.length,2);
         if(ss==null||(ss<0)||(ss>59)){return 0;}
         i_val+=ss.length;
      } else if (token=="a") {
         if (val.substring(i_val,i_val+2).toLowerCase()=="am") {ampm="AM";}
         else if (val.substring(i_val,i_val+2).toLowerCase()=="pm") {ampm="PM";}
         else {return 0;}
         i_val+=2;
      } else {
         if (val.substring(i_val,i_val+token.length)!=token) {return 0;}
         else {i_val+=token.length;}
      }
   }
   // If there are any trailing characters left in the value, it doesn't match
   if (i_val != val.length) { return 0; }
   // Is date valid for month?
   if (month==2) {
      // Check for leap year
      if ( ( (year%4==0)&&(year%100 != 0) ) || (year%400==0) ) { // leap year
         if (date > 29){ return 0; }
      } else { if (date > 28) { return 0; } }
   }
   if ((month==4)||(month==6)||(month==9)||(month==11)) {
      if (date > 30) { return 0; }
   }
   // Correct hours value
   if (hh<12 && ampm=="PM") { hh=hh-0+12; }
   else if (hh>11 && ampm=="AM") { hh-=12; }
   var newdate=new Date(year,month-1,date,hh,mm,ss);
   return newdate.getTime();
}

function parseDate(val) {
   var preferEuro=(arguments.length==2)?arguments[1]:false;
   generalFormats=new Array('y-M-d','MMM d, y','MMM d,y','y-MMM-d','d-MMM-y','MMM d');
   monthFirst=new Array('M/d/y','M-d-y','M.d.y','MMM-d','M/d','M-d');
   dateFirst =new Array('d/M/y','d-M-y','d.M.y','d-MMM','d/M','d-M');
   var checkList=new Array('generalFormats',preferEuro?'dateFirst':'monthFirst',preferEuro?'monthFirst':'dateFirst');
   var d=null;
   for (var i=0; i<checkList.length; i++) {
      var l=window[checkList[i]];
      for (var j=0; j<l.length; j++) {
         d=getDateFromFormat(val,l[j]);
         if (d!=0) { return new Date(d); }
      }
   }
   return null;
}
function DebugInfo ( msg, l ){
   if(!(bdy_g)) return ;
   var oid = (l) ? 'alrtDv_g' : 'dbgDv_g' ;
   var o = (l) ? alrtDv_g : dbgDv_g ;
   if ( o == null){
      var o = createTag ( 'div', oid, 'dbgBox', bdy_g.parentNode, null, "" ) ; 
      createTag ( 'div', 'PagTtl', 'PagTtl', o, null, "Alert..." ) ; 
      o.l = l ;
      o.m = createTag ( 'div', 'm', null, o, null, "" ) ; 
      o.cbtn = createTag ( 'button', 'clsBtn', null, o, null, "OK" ) ; 
      o.cbtn.onclick = function() {
         this.parentNode.style.display = 'none' ;
         if(o.l) fde_g.style.display = 'none' ;
          o.m.innerHTML = '' ;
      }
      if (l) alrtDv_g = o ;
      else dbgDv_g = o ;
   } 
   o.style.display = 'block' ;
   o.m.innerHTML += "<span>" + msg + "</span><br\>" ;
   if(o.l) fde_g.style.display = 'block' ;
//   o.cbtn.focus() ;
}
function alertBox ( msg ){
   DebugInfo ( msg, 1)
} 
function WFalert() {
//DebugInfo("WFalert " + bdy_g.login  + " cS " + bdy_g.curSrv ) ;
   if (!( bdy_g.login )) return ;
   if ( bdy_g.curSrv == 1001 ) return ;
//   if ( bdy_g.curSrv == 1005 ){
//   } else {
//      if ( !(o = getChildById( "msgalert", bdy_g)) ){
//         var o = document.createElement('div') ;
//         o.setAttribute("valign", "middle") ;
//         o.setAttribute("align", "center") ;
//         o.setAttribute("id", "msgalert") ;
//         bdy_g.appendChild(o) ;
//      }
      _as(null, "index.php?f=3&srv=1124&sNm=" + w_g.id + "&sEv=" + w_g.mod_g,false, setWFalert, w_g) ;
//   }
}
function setWFalert(o) {
   var un = getElmntById( 'nMsgCnt_g', top_g ) ;
   if(un) {
     var dt = eval( this.responseText ) ;
     un.innerHTML= (dt['c'] == '0') ? '' : dt['c'] ;
   } ;
//   var ohm = o.innerHTML;
//   o.innerHTML = "";
//   o.innerHTML += "<div class = 'msg_alrt'> New Messages for you. Click <a href='index.php?srv=1005' >here</a></div>";
//   if((ohm == "")&&(this.responseText != "0:0:0"))
//   o.innerHTML += "<div style='position:absolute ; border:1px solid; right:0px ; top:0px ; width:18px; height:18px;z-index:9999' onClick='closeMsgAlert();'>X</div>" ;
}
function getFile(o, ev) {
   if(o.fN) window.open( "index.php?f=3&srv=1136&ssrv=" + o.w.srv_g + "&fno=" + o.fN)  ;
}
function autoComplete(o, ev) {
   if(!( o.D )){
      acInit(o) ;
   }
   o.D.style.zIndex=999 ;
   o.D.style.display = 'block' ;
   var v = o.value ;
   if (ev.type=='focus') {
      o.select() ;
   }
//   if ((((ev.type=='keyup') || (ev.type=='focus')) && (o.value != o.odV)) ) {
   if (!(o.value.length) ) {
      o.rV = '' ;
   }
   if (!(o.value.length) || (o.value != o.odV) ) {
//DebugInfo (' auto Complete (' +  o.value.length + ')(' + o.value + ')(' + o.odV + ')'  ) ;
//      o.rV = '' ;
      o.odV = v ;
      getVwAs (o.w, o.av, ppltAc, o, 'p',11,o.acP,null,null,null,null,o.value ) ; 
   } ;
//DebugInfo( o.id + " 1 top  " + o.getBoundingClientRect().position  + ' | ' + parseInt(o.w.getBoundingClientRect())  ) ;
//DebugInfo( o.id + " 1 left  " + parseInt(o.getBoundingClientRect().left)  + ' | ' + o.w.getBoundingClientRect().position ) ;
   o.D.style.width= o.clientWidth + 'px' ;
//   if (o.w.style.position == 'absolute' ) {
//DebugInfo  (  o.D.style.left + ' 1 =' + o.getBoundingClientRect().left + ' - ' + o.w.getBoundingClientRect().left + 'px') ;
     o.D.style.top=  o.getBoundingClientRect().top -  o.w.getBoundingClientRect().top + parseInt(o.offsetHeight) + 'px' ;
     o.D.style.left= o.getBoundingClientRect().left -  o.w.getBoundingClientRect().left + 'px' ;
//DebugInfo  (  o.D.style.left + ' 2 =' + o.getBoundingClientRect().left + ' - ' + o.w.getBoundingClientRect().left + 'px') ;
//   } else {
//     o.D.style.top=  o.getBoundingClientRect().top + parseInt(o.offsetHeight) + 'px' ;
//     o.D.style.left= o.getBoundingClientRect().left +  'px' ;
//   }
//DebugInfo( o.id + " 0  " + ev.type + " cod " + ev.keyCode + ' rCnt ' + o.D.rCnt)  ;
   if (ev.type=='keyup') {
      switch (ev.keyCode) {
         case 13 :
         case 9 :
            if((o.D.rCnt > 0) && !(o.D.sR)){
               o.D.sR = o.D.row[0] ; 
//               o.D.sCls = o.D.sR.className ;
               addCls(o.D.sR, "gSel") ;
            }
            if(o.D.sR.rN >= 0){ 
               o.value = o.D.sR.dV
               o.rV = o.D.sR.rV
               o.D.style.display = 'none' ;
               for ( var h in o.eA ) {
                  if(h.toUpperCase() == 'ONCHANGE' ) eval(this.w.eA[h]) ;
               }
               if(this.w.lbl){
                  (this.w.value.length ) ? addCls(this.w.lbl,'up') : rmCls(this.w.lbl, 'up');
               }
               if (ev.keyCode = 13) return ;
            } 
            break ;
         case 27 :
            if(o.D.sR) {
               rmCls(o.D.sR,  "gSel") ;
               o.D.sR  = null ;
            }
            o.D.style.display = 'none' ;
            return true;
         case 40 :
            if(o.D.rCnt > 0) {
               if (!(o.D.sR)) {
                  o.D.sR = o.D.row[0] ; 
               } else if (o.D.sR.rN >= (o.D.rCnt -1)) {
//                  o.D.sR.className = o.D.sCls ;
                  rmCls(o.D.sR,  "gSel") ;
                  o.D.sR = o.D.row[0] ;
               } else {
                  rmCls(o.D.sR,  "gSel") ;
//                  o.D.sR.className = o.D.sCls ;
                  o.D.sR = o.D.row[parseInt(o.D.sR.rN + 1)]
               }
            } 
//            o.D.sCls = o.D.sR.className ;
            addCls(o.D.sR, "gSel") ;
            return ;
         case 38 :
            if(o.D.rCnt > 0) {
               if (!(o.D.sR)) {
                  o.D.sR = o.D.row[o.D.rCnt - 1] ;
               } else if (o.D.sR.rN == 0) {
                  rmCls(o.D.sR,  "gSel") ;
//                  o.D.sR.className = o.D.sCls ;
                  o.D.sR = o.D.row[o.D.rCnt - 1] ;
               } else {
                  rmCls(o.D.sR,  "gSel") ;
//                  o.D.sR.className = o.D.sCls ;
                  o.D.sR = o.D.row[o.D.sR.rN - 1]
               }
            } 
//            o.D.sCls = o.D.sR.className ;
            addCls(o.D.sR, "gSel") ;
            return ;
      }
   } 
};
function acInit(o) {
   if(!( o.D )){
      o.odV = (o.odV) ? o.odV : '' ;
      o.rV = (o.rV) ? o.rV : '' ;
      o.D = createTag ( 'div', o.id + ':cW', 'acW', o.w, 'none', null ) ; 
      o.D.rCnt = 0 ; 
//DebugInfo( o.id + " 2 top  " + o.getBoundingClientRect().position  + ' | ' + parseInt(o.w.getBoundingClientRect())  ) ;
//DebugInfo( o.id + " 2 left  " + parseInt(o.getBoundingClientRect().left)  + ' | ' + o.w.getBoundingClientRect().position ) ;
      o.D.style.width= o.clientWidth + 'px' ;
//      if (o.w.style.position == 'absolute' ) {
      o.D.style.top=  o.getBoundingClientRect().top -  o.w.getBoundingClientRect().top + parseInt(o.offsetHeight) + 'px' ;
      o.D.style.left= o.getBoundingClientRect().left -  o.w.getBoundingClientRect().left + 'px' ;
//        o.D.style.top=  o.getBoundingClientRect().top -  o.w.getBoundingClientRect().top + parseInt(o.offsetHeight) + 'px' ;
//        o.D.style.left= o.getBoundingClientRect().left -  o.w.getBoundingClientRect().left + 'px' ;
//      } else {
//        o.D.style.top=  o.getBoundingClientRect().top + parseInt(o.offsetHeight) + 'px' ;
//        o.D.style.left= o.getBoundingClientRect().left +  'px' ;
//      }
      o.D.gDiv = createTag ( 'div', 'gDiv', 'gDiv', o.D, 'table', null ) ;
      o.D.w = o ;
      o.acP = 0 ;
      var a = o.ac.split("#") ;
      o.aRc = ( a[0] ) ? a[0] : 0 ; 
      o.aDc = ( a[1] ) ? a[1] : o.aRc ; 
      o.aLc = ( a[2] ) ? a[2] : o.aDc ; 
      o.D.pL = 15 ;
      o.D.row = new Array() ;
      for ( i=0 ; i < o.D.pL ; i++ ){
         var tr = createTag ( 'div', 'gR_' + i, "gR_" +  i % 2, o.D.gDiv, 'table-row', null ) ; 
         o.D.row[i] = tr ;
         tr.rN = i ;
         tr.col = new Array() ;
         tr.w = o ;
//         tr.style.top = 22 * i + "px" ;
         for (var c=0 ; c < 1 ; c++ ){
            var td = createTag('div', 'gC:' + i + ':' + c, 'gCellt', tr, 'table-cell', null ) ;
//           td.style.left= '0' ;
//           td.style.right= '0' ;
           tr.col[c] = td  ;
           td.tr = tr ;
           td.w = o ;
         }
         tr.onclick = function() {
//            this.w.D.sCls = this.className ;
            this.w.D.sR = this ;
            addCls(this, "gSel") ;
            this.w.value = this.dV
            //this.w.odV = this.w.value ;
            this.w.rV = this.rV
            for ( var h in this.w.eA ) {
               if(h.toUpperCase() == 'ONCHANGE' ) eval(this.w.eA[h]) ;
            }
            if(this.w.lbl){
               (this.w.value.length ) ? addCls(this.w.lbl,'up') : rmCls(this.w.lbl, 'up');
            }
            this.w.D.style.display = 'none' ;
            this.w.D.s = 0 ;
            this.w.focus() ;
            if (typeof(this.w.onchange) == 'function') this.w.onchange() ;
         } ;
      }
      o.D.onblur = function () {
         this.style.display = 'none' ;
//DebugInfo (' on blur ' + this.value ) ;
         if (this.value == ''){
            this.rV = '';
         }
         this.s = 0 ;
      }
      o.D.onfocus = function () {
         this.style.display = 'none' ;
         this.s = 1 ;
      }
      o.onfocus = function () {
         this.select() ;
      }
      o.onclick = function () {
         this.select() ;
      }
      o.onblur = function () {
         setTimeout(acHide, 200, this);
      }
      if ( !(o.w.vR[o.av]) && ( o.w.i_f == 1)) {
         getVwAs (o.w, o.av, ppltAc, o, 'p',11,o.acP,null,null,null, null,o.value ) ; 
      }
   }
}
function ppltAc (o,rs ) {
   var hg = 1 ;
 //  o.acR = eval(this.responseText.replace(/&amp;/g, "\&"));
   o.acR = rs;
   if((o.acR)&&(o.acR[2])) {
      if(o.D.sR){
         if (o.D.rCnt <= o.D.sR.rN ){
            o.D.sR.className = o.D.sCls ;
            o.D.sR = null ;
         }
      }
      o.D.rCnt = o.acR[2].length ; 
      for ( var r=0 ; r < o.D.pL ; r++ ){
         if (o.D.rCnt > r ){
            o.D.row[r].style.display = 'block' ;
            o.D.row[r].col[0].innerHTML = o.acR[2][r][o.aLc] ;
            o.D.row[r].rV = o.acR[2][r][o.aRc] ;
            o.D.row[r].dV = o.acR[2][r][o.aDc] ;
            o.D.row[r].title = o.acR[2][r][o.aLc] ;
            if(o.D.row[r].rV == o.rV) {
//               o.D.sCls = o.D.row[r].className ;
               o.D.sR = o.D.row[r] ;
               addCls(o.D.row[r], "gSel" ) ;
//               o.D.row[r].className = "gSel" ;
         //      o.value = o.D.row[r].dV
            }
            hg += 30 ;
         } else {
            o.D.row[r].style.display = 'none' ;
            o.D.row[r].col[0].innerHTML = '' ;
            o.D.row[r].rV = '' ;
            o.D.row[r].dV = '' ;
         }
      }
      o.D.style.height = hg + 'px' ;
   }
};
function acHide(o) {
  if( o.D.s != 1 ) o.D.style.display = 'none' ;
}
function closeMsgAlert (){
   var div = document.getElementById('msgalert') ;
   bdy_g.removeChild(div) ;
}
function closeDiv (b,e,o){
//console.info(o,  "L5308 o.retBtn ", o.retBtn ) ;
   var cfc = 0 ;
   if(o.retBtn) o.retBtn.click() ;
   else cfc=1 ;
   if(o.oW){ 
      if(o.oW.retBtn) o.oW.retBtn.click() ;
      else cfc=1 ;
   }
   else o.parentNode.removeChild(o) ;
   if ( cfc==1 ){
      var fnc = o.eA['onClose'] ;
      if(fnc) eval(fnc + '(' + (o.kw_g) ? o.kw_g.value: '' +')');
   }
}
function createTag( t, id, cls, p, v, val, nam, typ,pos ) {
   var o = document.createElement(t);
   if (v == 'visible' ) o.style.display = 'block' ;
   else if (v == 'hidden' ) o.style.display = 'none' ;
   else if (v == 'disabled' ) o.disabled = true ;
   else o.style.display = v ;
//   else o.disabled = false ;
   if (id) o.id = id ;
   if (nam) o.name = nam ;
   if(typ) o.type = typ ;
   if (cls){ 
      addCls(o, cls) ;
      if ( (cls == 'hidden') && (t == 'input' ) ) o.type = cls ; 
   }
   if ((val) && ( o.value != undefined )) o.value = val ;
   if ((val) && ( o.innerHTML != undefined )) o.innerHTML = val ;
   if (p){
     if(pos=='f') p.insertBefore(o,p.firstChild);
     else if(pos=='bl') p.insertBefore(o,p.lastChild);
     else p.appendChild(o) ;
   }
   return o ;
}
///////////////////
   function _e(v) {
      //return v ;
      switch(typeof v) {
         case 'object':
            if ( v === null )  return 'N;';
            var c = v.constructor;
            if (c != null ) {
               if ( c == Array ) {
                  return _eA(v);
               } else {
                  var match = c.toString().match( /\s*function (.*)\(/ );
                  if ( match == null ) {
                     return _eO(v,'OSS_Object');
                  }
                  var cname = match[1].replace(/\s/,'');
                  var match = cname.match(/Error/);
                  if ( match == null ) {
                     return _eO(v,'OSS_Object');
                  } else {
                     return _eE(v,'OSS_Error');
                  }
               }
            } else {
               return 'N;';
            }
         break;
         case 'string':
            return _eS(v);
         break;
         case 'number':
            if (Math.round(v) == v) return 'i:'+v+';' ;
            else return 'd:'+v+';';
         break;
         case 'boolean':
            if (v == true) return 'b:1;';
            else return 'b:0;';
         break;
         default:
            return 'N;';
         break;
      }
   }
   
   function _eS(v) {
      var s = ''
      for(var n=0; n<v.length; n++) {
         var c=v.charCodeAt(n);
         // Ignore everything but ASCII
//         if (c<128) { s += String.fromCharCode(c); }
         s += String.fromCharCode(c); 
      }
      return 's:'+s.length+':"'+s+'";';
//      return 's:'+v.length+':"'+encodeURIComponent(v)+'";' ;
   }
    
   function _eA(v) {
      var indexed = new Array();
      var count = v.length;
      var s = '';
      for (var i=0; i<v.length; i++) {
         indexed[i] = true;
         s += 'i:'+i+';'+ _e(v[i]);
      };
      for ( var prop in v ) {
         if ( indexed[prop] ) continue;
         s += _e(prop) + _e(v[prop]);
         count++;
      };
      return 'a:'+count+':{'+s+'}' ;
   }
    
   function _eO(v, cname) {
      var s='';
      var count=0;
      for (var prop in v) {
         s += 's:'+prop.length+':"'+prop+'";';
         if (v[prop]!=null) s += _e(v[prop]);
         else s +='N;';
         count++;
      };
      return 'O:'+cname.length+':"'+cname.toLowerCase()+'":'+count+':{'+s+'}';   
   }
   function _eE(v, cname) {
      var e = new Object();
      if ( !v.name ) {
         e.name = cname;
         e.message = v.description;
      } else {
         e.name = v.name;
         e.message = v.message;
      };
      return this._eO(e,cname);
   }
///////////////////
function triggerTimer() {
   if(tmrId_g) clearTimeout(tmrId_g);
   tmrCnt_g+=  1 ;
//DebugInfo("triggerTimer " + tmrCnt_g) ;
   var secCnt = tmrCnt_g%  60 ;
   var minCnt = Math.round(((tmrCnt_g/ 60 ) %  60) - 0.5 ) ;
   var hrCnt = Math.round((tmrCnt_g/ 3600 ) - 0.5 ) ;
   tmrStr_g = (hrCnt < 10 )? '0' + hrCnt : hrCnt  + ':' + (minCnt < 10 )? '0' + minCnt : minCnt + ':' + (secCnt < 10 )? '0' + secCnt : secCnt ;
   if (( tmrCnt_g % 60 ) == 0 ) WFalert() ; 
   tmrId_g = setTimeout("triggerTimer()", 5000);
}

function timerReset() {
   tmrCnt_g= 0 ;
   tmrPause_g = 0 ;
   tmrStr_g = "00:00:00";
}

function timerStart() {
//DebugInfo("timerStart") ;
   timerReset()
   tmrId_g  = setTimeout("triggerTimer()", 1000);
}

function timerPause() {
   if(!(tmrPause_g)) {
      clearTimeout(tmrId_g);
      tmrPause_g = 1 ;
   }
}

function timerRestart() {
   if(tmrPause_g) {
      tmrPause_g = 0 ;
      tmrId_g  = setTimeout("triggerTimer()", 1000);
   }
}

function timerStop() {
   if(tmrId_g) {
      clearTimeout(tmrId_g);
      tmrCnt_g= 0 ;
      tmrPause_g = 0 ;
      tmrId_g  = 0;
   } 
}
function pswdChng( winid, unm, opw, npw, cpw ){
   var win = getElmntById(winid ) ;
   var un = getElmntById( unm, win ).innerHTML ;
   var op = getElmntById( opw, win ).value ;
   var np = getElmntById( npw, win ).value ;
   var cp = getElmntById( cpw, win ).value ;
   if ( op == '' || np == '' || cp == '' ) {
        alertBox("<P>All fields are mandatory !!</P>" ) ;
        return false
   }
   var st = v_pw( win, np, cp ) ;
   if ( st == 'n' ) {
        alertBox("<P>Invalid characters for New Password !!</P>" ) ;
   } else if ( st == 'c' ) {
        alertBox("<P>Invalid characters for Confirmation Password !!</P>" ) ;
   } else if ( st == 'm' ) {
        alertBox("<P>Password Mismatch !!</P>" ) ;
   } else {
      dt['un'] = un ;
      dt['np'] = np ;
      dt['op'] = op ;
      dt['sNm'] = w_g.id ;
      dt['sEv'] = w_g.mod_g ;
      var url = "index.php?f=2&srv=1075&sNm=" + w_g.id + "&sEv=" + w_g.mod_g  ;
      _s(_e(dt), url, false,shwPswdRspns  ) ;
   }
}
function shwPswdRspns() {
   var rs =eval( this.responseText) ;
   if( rs ) {
      if( rs['__Error'] ) {
         alertBox("<P>" + rs['__Error'] + "</P>" ) ;
         return ;
      }
      if( rs['__Notice'] != false ) {
         alertBox("<P>" + rs['__Notice'] + "</P>" ) ;
      }
   }
}
function getToday () {
   var t = new Date();
   var d = t.getDate();
   var m = t.getMonth()+1; 
   var y = t.getFullYear();
   if(d<10){d='0'+d} 
   if(m<10){m='0'+m} 
   return d+'-'+m+'-'+y;
}
//=======================
/** The OssCal object constructor. */
OssCal = function (firstDayOfWeek, dateStr, onSelected, onClose) {
   // member variables
   this.activeDiv = null;
   this.currentDateEl = null;
   this.getDateStatus = null;
   this.timeout = null;
   this.onSelected = onSelected || null;
   this.onClose = onClose || null;
   this.dragging = false;
   this.hidden = false;
   this.minYear = 1970;
   this.maxYear = 2050;
   this.dateFormat = OssCal._TT["DEF_DATE_FORMAT"];
   this.ttDateFormat = OssCal._TT["TT_DATE_FORMAT"];
   this.isPopup = true;
   this.weekNumbers = true;
   this.firstDayOfWeek = firstDayOfWeek; // 0 for Sunday, 1 for Monday, etc.
   this.showsOtherMonths = false;
   this.dateStr = dateStr;
   this.ar_days = null;
   this.showsTime = false;
   this.time24 = true;
   this.yearStep = 2;
   // HTML elements
   this.table = null;
   this.element = null;
   this.tbody = null;
   this.firstdayname = null;
   // Combo boxes
   this.monthsCombo = null;
   this.yearsCombo = null;
   this.hilitedMonth = null;
   this.activeMonth = null;
   this.hilitedYear = null;
   this.activeYear = null;
   // Information
   this.dateClicked = false;

   // one-time initializations
   if (typeof OssCal._SDN == "undefined") {
      // table of short day names
      if (typeof OssCal._SDN_len == "undefined")
         OssCal._SDN_len = 3;
      var ar = new Array();
      for (var i = 8; i > 0;) {
         ar[--i] = OssCal._DN[i].substr(0, OssCal._SDN_len);
      }
      OssCal._SDN = ar;
      // table of short month names
      if (typeof OssCal._SMN_len == "undefined")
         OssCal._SMN_len = 3;
      ar = new Array();
      for (var i = 12; i > 0;) {
         ar[--i] = OssCal._MN[i].substr(0, OssCal._SMN_len);
      }
      OssCal._SMN = ar;
   }
};

OssCal._C = null;

OssCal.is_ie = ( /msie/i.test(navigator.userAgent) &&
         !/opera/i.test(navigator.userAgent) );

OssCal.is_ie5 = ( OssCal.is_ie && /msie 5\.0/i.test(navigator.userAgent) );

OssCal.is_opera = /opera/i.test(navigator.userAgent);

OssCal.is_khtml = /Konqueror|Safari|KHTML/i.test(navigator.userAgent);

OssCal.getAbsolutePos = function(el) {
   var SL = 0, ST = 0;
   var is_div = /^div$/i.test(el.tagName);
   if (is_div && el.scrollLeft)
      SL = el.scrollLeft;
   if (is_div && el.scrollTop)
      ST = el.scrollTop;
   var r = { x: el.offsetLeft - SL, y: el.offsetTop - ST };
   if (el.offsetParent) {
      var tmp = this.getAbsolutePos(el.offsetParent);
      r.x += tmp.x;
      r.y += tmp.y;
   }
   return r;
};

OssCal.isRelated = function (el, evt) {
   var related = evt.relatedTarget;
   if (!related) {
      var type = evt.type;
      if (type == "mouseover") {
         related = evt.fromElement;
      } else if (type == "mouseout") {
         related = evt.toElement;
      }
   }
   while (related) {
      if (related == el) {
         return true;
      }
      related = related.parentNode;
   }
   return false;
};

OssCal.removeClass = function(el, className) {
   if (!(el && el.className)) {
      return;
   }
   var cls = el.className.split(" ");
   var ar = new Array();
   for (var i = cls.length; i > 0;) {
      if (cls[--i] != className) {
         ar[ar.length] = cls[i];
      }
   }
   el.className = ar.join(" ");
};

OssCal.addClass = function(el, className) {
   OssCal.removeClass(el, className);
   el.className += " " + className;
};

OssCal.getElement = function(ev) {
   if (OssCal.is_ie) {
      return window.event.srcElement;
   } else {
      return ev.currentTarget;
   }
};

OssCal.getTargetElement = function(ev) {
   if (OssCal.is_ie) {
      return window.event.srcElement;
   } else {
      return ev.target;
   }
};

OssCal.stopEvent = function(ev) {
   ev || (ev = window.event);
   if (OssCal.is_ie) {
      ev.cancelBubble = true;
      ev.returnValue = false;
   } else {
      ev.preventDefault();
      ev.stopPropagation();
   }
   return false;
};

OssCal.addEvent = function(el, evname, func) {
   if (el.attachEvent) { // IE
      el.attachEvent("on" + evname, func);
   } else if (el.addEventListener) { // Gecko / W3C
      el.addEventListener(evname, func, true);
   } else {
      el["on" + evname] = func;
   }
};

OssCal.removeEvent = function(el, evname, func) {
   if (el.detachEvent) { // IE
      el.detachEvent("on" + evname, func);
   } else if (el.removeEventListener) { // Gecko / W3C
      el.removeEventListener(evname, func, true);
   } else {
      el["on" + evname] = null;
   }
};

OssCal.createElement = function(type, parent) {
   var el = null;
   if (document.createElementNS) {
      el = document.createElementNS("http://www.w3.org/1999/xhtml", type);
   } else {
      el = document.createElement(type);
   }
   if (typeof parent != "undefined") {
      parent.appendChild(el);
   }
   return el;
};

OssCal._add_evs = function(el) {
   with (OssCal) {
      addEvent(el, "mouseover", dayMouseOver);
      addEvent(el, "mousedown", dayMouseDown);
      addEvent(el, "mouseout", dayMouseOut);
      if (is_ie) {
         addEvent(el, "dblclick", dayMouseDblClick);
         el.setAttribute("unselectable", true);
      }
   }
};

OssCal.findMonth = function(el) {
   if (typeof el.month != "undefined") {
      return el;
   } else if (typeof el.parentNode.month != "undefined") {
      return el.parentNode;
   }
   return null;
};

OssCal.findYear = function(el) {
   if (typeof el.year != "undefined") {
      return el;
   } else if (typeof el.parentNode.year != "undefined") {
      return el.parentNode;
   }
   return null;
};

OssCal.showMonthsCombo = function () {
   var cal = OssCal._C;
   if (!cal) {
      return false;
   }
   var cal = cal;
   var cd = cal.activeDiv;
   var mc = cal.monthsCombo;
   if (cal.hilitedMonth) {
      OssCal.removeClass(cal.hilitedMonth, "hilite");
   }
   if (cal.activeMonth) {
      OssCal.removeClass(cal.activeMonth, "active");
   }
   var mon = cal.monthsCombo.getElementsByTagName("div")[cal.date.getMonth()];
   OssCal.addClass(mon, "active");
   cal.activeMonth = mon;
   var s = mc.style;
   s.display = "block";
   if (cd.navtype < 0)
      s.left = cd.offsetLeft + "px";
   else {
      var mcw = mc.offsetWidth;
      if (typeof mcw == "undefined")
         mcw = 50;
      s.left = (cd.offsetLeft + cd.offsetWidth - mcw) + "px";
   }
   s.top = (cd.offsetTop + cd.offsetHeight) + "px";
};

OssCal.showYearsCombo = function (fwd) {
   var cal = OssCal._C;
   if (!cal) {
      return false;
   }
   var cal = cal;
   var cd = cal.activeDiv;
   var yc = cal.yearsCombo;
   if (cal.hilitedYear) {
      OssCal.removeClass(cal.hilitedYear, "hilite");
   }
   if (cal.activeYear) {
      OssCal.removeClass(cal.activeYear, "active");
   }
   cal.activeYear = null;
   var Y = cal.date.getFullYear() + (fwd ? 1 : -1);
   var yr = yc.firstChild;
   var show = false;
   for (var i = 12; i > 0; --i) {
      if (Y >= cal.minYear && Y <= cal.maxYear) {
         yr.firstChild.data = Y;
         yr.year = Y;
         yr.style.display = "block";
         show = true;
      } else {
         yr.style.display = "none";
      }
      yr = yr.nextSibling;
      Y += fwd ? cal.yearStep : -cal.yearStep;
   }
   if (show) {
      var s = yc.style;
      s.display = "block";
      if (cd.navtype < 0)
         s.left = cd.offsetLeft + "px";
      else {
         var ycw = yc.offsetWidth;
         if (typeof ycw == "undefined")
            ycw = 50;
         s.left = (cd.offsetLeft + cd.offsetWidth - ycw) + "px";
      }
      s.top = (cd.offsetTop + cd.offsetHeight) + "px";
   }
};

OssCal.tableMouseUp = function(ev) {
   var cal = OssCal._C;
   if (!cal) {
      return false;
   }
   if (cal.timeout) {
      clearTimeout(cal.timeout);
   }
   var el = cal.activeDiv;
   if (!el) {
      return false;
   }
   var target = OssCal.getTargetElement(ev);
   ev || (ev = window.event);
   OssCal.removeClass(el, "active");
   if (target == el || target.parentNode == el) {
      OssCal.cellClick(el, ev);
   }
   var mon = OssCal.findMonth(target);
   var date = null;
   if (mon) {
      date = new Date(cal.date);
      if (mon.month != date.getMonth()) {
         date.setMonth(mon.month);
         cal.setDate(date);
         cal.dateClicked = false;
         cal.callHandler();
      }
   } else {
      var year = OssCal.findYear(target);
      if (year) {
         date = new Date(cal.date);
         if (year.year != date.getFullYear()) {
            date.setFullYear(year.year);
            cal.setDate(date);
            cal.dateClicked = false;
            cal.callHandler();
         }
      }
   }
   with (OssCal) {
      removeEvent(document, "mouseup", tableMouseUp);
      removeEvent(document, "mouseover", tableMouseOver);
      removeEvent(document, "mousemove", tableMouseOver);
      cal._hideCombos();
      _C = null;
      return stopEvent(ev);
   }
};

OssCal.tableMouseOver = function (ev) {
   var cal = OssCal._C;
   if (!cal) {
      return;
   }
   var el = cal.activeDiv;
   var target = OssCal.getTargetElement(ev);
   if (target == el || target.parentNode == el) {
      OssCal.addClass(el, "hilite active");
      OssCal.addClass(el.parentNode, "rowhilite");
   } else {
      if (typeof el.navtype == "undefined" || (el.navtype != 50 && (el.navtype == 0 || Math.abs(el.navtype) > 2)))
         OssCal.removeClass(el, "active");
      OssCal.removeClass(el, "hilite");
      OssCal.removeClass(el.parentNode, "rowhilite");
   }
   ev || (ev = window.event);
   if (el.navtype == 50 && target != el) {
      var pos = OssCal.getAbsolutePos(el);
      var w = el.offsetWidth;
      var x = ev.clientX;
      var dx;
      var decrease = true;
      if (x > pos.x + w) {
         dx = x - pos.x - w;
         decrease = false;
      } else
         dx = pos.x - x;

      if (dx < 0) dx = 0;
      var range = el._range;
      var current = el._current;
      var count = Math.floor(dx / 10) % range.length;
      for (var i = range.length; --i >= 0;)
         if (range[i] == current)
            break;
      while (count-- > 0)
         if (decrease) {
            if (--i < 0)
               i = range.length - 1;
         } else if ( ++i >= range.length )
            i = 0;
      var newval = range[i];
      el.firstChild.data = newval;

      cal.onUpdateTime();
   }
   var mon = OssCal.findMonth(target);
   if (mon) {
      if (mon.month != cal.date.getMonth()) {
         if (cal.hilitedMonth) {
            OssCal.removeClass(cal.hilitedMonth, "hilite");
         }
         OssCal.addClass(mon, "hilite");
         cal.hilitedMonth = mon;
      } else if (cal.hilitedMonth) {
         OssCal.removeClass(cal.hilitedMonth, "hilite");
      }
   } else {
      if (cal.hilitedMonth) {
         OssCal.removeClass(cal.hilitedMonth, "hilite");
      }
      var year = OssCal.findYear(target);
      if (year) {
         if (year.year != cal.date.getFullYear()) {
            if (cal.hilitedYear) {
               OssCal.removeClass(cal.hilitedYear, "hilite");
            }
            OssCal.addClass(year, "hilite");
            cal.hilitedYear = year;
         } else if (cal.hilitedYear) {
            OssCal.removeClass(cal.hilitedYear, "hilite");
         }
      } else if (cal.hilitedYear) {
         OssCal.removeClass(cal.hilitedYear, "hilite");
      }
   }
   return OssCal.stopEvent(ev);
};

OssCal.tableMouseDown = function (ev) {
   if (OssCal.getTargetElement(ev) == OssCal.getElement(ev)) {
      return OssCal.stopEvent(ev);
   }
};

OssCal.calDragIt = function (ev) {
   var cal = OssCal._C;
   if (!(cal && cal.dragging)) {
      return false;
   }
   var posX;
   var posY;
   if (OssCal.is_ie) {
      posY = window.event.clientY + document.body.scrollTop;
      posX = window.event.clientX + document.body.scrollLeft;
   } else {
      posX = ev.pageX;
      posY = ev.pageY;
   }
   cal.hideShowCovered();
   var st = cal.element.style;
   st.left = (posX - cal.xOffs) + "px";
   st.top = (posY - cal.yOffs) + "px";
   return OssCal.stopEvent(ev);
};

OssCal.calDragEnd = function (ev) {
   var cal = OssCal._C;
   if (!cal) {
      return false;
   }
   cal.dragging = false;
   with (OssCal) {
      removeEvent(document, "mousemove", calDragIt);
      removeEvent(document, "mouseup", calDragEnd);
      tableMouseUp(ev);
   }
   cal.hideShowCovered();
};

OssCal.dayMouseDown = function(ev) {
   var el = OssCal.getElement(ev);
   if (el.disabled) {
      return false;
   }
   var cal = el.calendar;
   cal.activeDiv = el;
   OssCal._C = cal;
   if (el.navtype != 300) with (OssCal) {
      if (el.navtype == 50) {
         el._current = el.firstChild.data;
         addEvent(document, "mousemove", tableMouseOver);
      } else
         addEvent(document, OssCal.is_ie5 ? "mousemove" : "mouseover", tableMouseOver);
      addClass(el, "hilite active");
      addEvent(document, "mouseup", tableMouseUp);
   } else if (cal.isPopup) {
      cal._dragStart(ev);
   }
   if (el.navtype == -1 || el.navtype == 1) {
      if (cal.timeout) clearTimeout(cal.timeout);
      cal.timeout = setTimeout("OssCal.showMonthsCombo()", 250);
   } else if (el.navtype == -2 || el.navtype == 2) {
      if (cal.timeout) clearTimeout(cal.timeout);
      cal.timeout = setTimeout((el.navtype > 0) ? "OssCal.showYearsCombo(true)" : "OssCal.showYearsCombo(false)", 250);
   } else {
      cal.timeout = null;
   }
   return OssCal.stopEvent(ev);
};

OssCal.dayMouseDblClick = function(ev) {
   OssCal.cellClick(OssCal.getElement(ev), ev || window.event);
   if (OssCal.is_ie) {
      document.selection.empty();
   }
};

OssCal.dayMouseOver = function(ev) {
//   var el = OssCal.getElement(ev);
//   if (OssCal.isRelated(el, ev) || OssCal._C || el.disabled) {
//      return false;
//   }
//   if (el.ttip) {
//      if (el.ttip.substr(0, 1) == "_") {
//         el.ttip = el.caldate.print(el.calendar.ttDateFormat) + el.ttip.substr(1);
//      }
//      el.calendar.tooltips.firstChild.data = el.ttip;
//   }
//   if (el.navtype != 300) {
//      OssCal.addClass(el, "hilite");
//      if (el.caldate) {
//         OssCal.addClass(el.parentNode, "rowhilite");
//      }
//   }
//   return OssCal.stopEvent(ev);
};

OssCal.dayMouseOut = function(ev) {
//   with (OssCal) {
//      var el = getElement(ev);
//      if (isRelated(el, ev) || _C || el.disabled) {
//         return false;
//      }
//      removeClass(el, "hilite");
//      if (el.caldate) {
//         removeClass(el.parentNode, "rowhilite");
//      }
//      el.calendar.tooltips.firstChild.data = _TT["SEL_DATE"];
//      return stopEvent(ev);
//   }
};

OssCal.cellClick = function(el, ev) {
   var cal = el.calendar;
   var closing = false;
   var newdate = false;
   var date = null;
   if (typeof el.navtype == "undefined") {
      OssCal.removeClass(cal.currentDateEl, "selected");
      OssCal.addClass(el, "selected");
      closing = (cal.currentDateEl == el);
      if (!closing) {
         cal.currentDateEl = el;
      }
      cal.date = new Date(el.caldate);
      date = cal.date;
      newdate = true;
      if (!(cal.dateClicked = !el.otherMonth))
         cal._init(cal.firstDayOfWeek, date);
   } else {
      if (el.navtype == 200) {
         OssCal.removeClass(el, "hilite");
         cal.callCloseHandler();
         return;
      }
      date = (el.navtype == 0) ? new Date() : new Date(cal.date);
      cal.dateClicked = false;
      var year = date.getFullYear();
      var mon = date.getMonth();
      function setMonth(m) {
         var day = date.getDate();
         var max = date.getMonthDays(m);
         if (day > max) {
            date.setDate(max);
         }
         date.setMonth(m);
      };
      switch (el.navtype) {
//          case 400:
//         OssCal.removeClass(el, "hilite");
//         var text = OssCal._TT["ABOUT"];
//         if (typeof text != "undefined") {
//            text += cal.showsTime ? OssCal._TT["ABOUT_TIME"] : "";
//         } else {
//            text = "Help and about box text is not translated into this language.\n" +
//               "Thank you!\n" ;
//         }
//         alert(text);
//         return;
          case -2:
         if (year > cal.minYear) {
            date.setFullYear(year - 1);
         }
         break;
          case -1:
         if (mon > 0) {
            setMonth(mon - 1);
         } else if (year-- > cal.minYear) {
            date.setFullYear(year);
            setMonth(11);
         }
         break;
          case 1:
         if (mon < 11) {
            setMonth(mon + 1);
         } else if (year < cal.maxYear) {
            date.setFullYear(year + 1);
            setMonth(0);
         }
         break;
          case 2:
         if (year < cal.maxYear) {
            date.setFullYear(year + 1);
         }
         break;
          case 100:
         cal.setFirstDayOfWeek(el.fdow);
         return;
          case 50:
         var range = el._range;
         var current = el.firstChild.data;
         for (var i = range.length; --i >= 0;)
            if (range[i] == current)
               break;
         if (ev && ev.shiftKey) {
            if (--i < 0)
               i = range.length - 1;
         } else if ( ++i >= range.length )
            i = 0;
         var newval = range[i];
         el.firstChild.data = newval;
         cal.onUpdateTime();
         return;
          case 0:
         if ((typeof cal.getDateStatus == "function") && cal.getDateStatus(date, date.getFullYear(), date.getMonth(), date.getDate())) {
            return false;
         }
         break;
      }
      if (!date.equalsTo(cal.date)) {
         cal.setDate(date);
         newdate = true;
      }
   }
   if (newdate) {
      cal.callHandler();
   }
   if (closing) {
      OssCal.removeClass(el, "hilite");
      cal.callCloseHandler();
   }
};

OssCal.prototype.create = function (_par) {
   var parent = null;
   if (! _par) {
      parent = document.getElementsByTagName("body")[0];
      this.isPopup = true;
   } else {
      parent = _par;
      this.isPopup = false;
   }
   this.date = this.dateStr ? new Date(this.dateStr) : new Date();

   var table = OssCal.createElement("table");
   this.table = table;
   table.cellSpacing = 0;
   table.cellPadding = 0;
   table.calendar = this;
   OssCal.addEvent(table, "mousedown", OssCal.tableMouseDown);

   var div = OssCal.createElement("div");
   this.element = div;
   div.className = "calendar";
   if (this.isPopup) {
      div.style.position = "absolute";
      div.style.display = "none";
   }
   div.appendChild(table);

   var thead = OssCal.createElement("thead", table);
   var cell = null;
   var row = null;

   var cal = this;
   var hh = function (text, cs, navtype) {
      cell = OssCal.createElement("td", row);
      cell.colSpan = cs;
      cell.className = "button";
      if (navtype != 0 && Math.abs(navtype) <= 2)
         cell.className += " nav";
      OssCal._add_evs(cell);
      cell.calendar = cal;
      cell.navtype = navtype;
      if (text.substr(0, 1) != "&") {
         cell.appendChild(document.createTextNode(text));
      }
      else {
         cell.innerHTML = text;
      }
      return cell;
   };

   row = OssCal.createElement("tr", thead);
   var title_length = 6;
   (this.isPopup) && --title_length;
   (this.weekNumbers) && ++title_length;

//   hh("Help", 1, 400).ttip = OssCal._TT["INFO"];
   this.title = hh("", title_length, 300);
   this.title.className = "title";
   if (this.isPopup) {
      this.title.ttip = OssCal._TT["DRAG_TO_MOVE"];
      this.title.style.cursor = "move";
      hh("&#x00d7;", 1, 200).ttip = OssCal._TT["CLOSE"];
   }

   row = OssCal.createElement("tr", thead);
   row.className = "headrow";

   this._nav_py = hh("&#x00ab;", 1, -2);
   this._nav_py.ttip = OssCal._TT["PREV_YEAR"];

   this._nav_pm = hh("&#x2039;", 1, -1);
   this._nav_pm.ttip = OssCal._TT["PREV_MONTH"];

   this._nav_now = hh(OssCal._TT["TODAY"], this.weekNumbers ? 4 : 3, 0);
   this._nav_now.ttip = OssCal._TT["GO_TODAY"];

   this._nav_nm = hh("&#x203a;", 1, 1);
   this._nav_nm.ttip = OssCal._TT["NEXT_MONTH"];

   this._nav_ny = hh("&#x00bb;", 1, 2);
   this._nav_ny.ttip = OssCal._TT["NEXT_YEAR"];

   row = OssCal.createElement("tr", thead);
   row.className = "daynames";
   if (this.weekNumbers) {
      cell = OssCal.createElement("td", row);
      cell.className = "name wn";
      cell.appendChild(document.createTextNode(OssCal._TT["WK"]));
   }
   for (var i = 7; i > 0; --i) {
      cell = OssCal.createElement("td", row);
      cell.appendChild(document.createTextNode(""));
      if (!i) {
         cell.navtype = 100;
         cell.calendar = this;
         OssCal._add_evs(cell);
      }
   }
   this.firstdayname = (this.weekNumbers) ? row.firstChild.nextSibling : row.firstChild;
   this._displayWeekdays();

   var tbody = OssCal.createElement("tbody", table);
   this.tbody = tbody;

   for (i = 6; i > 0; --i) {
      row = OssCal.createElement("tr", tbody);
      if (this.weekNumbers) {
         cell = OssCal.createElement("td", row);
         cell.appendChild(document.createTextNode(""));
      }
      for (var j = 7; j > 0; --j) {
         cell = OssCal.createElement("td", row);
         cell.appendChild(document.createTextNode(""));
         cell.calendar = this;
         OssCal._add_evs(cell);
      }
   }

   if (this.showsTime) {
      row = OssCal.createElement("tr", tbody);
      row.className = "time";

      cell = OssCal.createElement("td", row);
      cell.className = "time";
      cell.colSpan = 2;
      cell.innerHTML = OssCal._TT["TIME"] || "&nbsp;";

      cell = OssCal.createElement("td", row);
      cell.className = "time";
      cell.colSpan = this.weekNumbers ? 4 : 3;

      (function(){
         function makeTimePart(className, init, range_start, range_end) {
            var part = OssCal.createElement("span", cell);
            part.className = className;
            part.appendChild(document.createTextNode(init));
            part.calendar = cal;
            part.ttip = OssCal._TT["TIME_PART"];
            part.navtype = 50;
            part._range = [];
            if (typeof range_start != "number")
               part._range = range_start;
            else {
               for (var i = range_start; i <= range_end; ++i) {
                  var txt;
                  if (i < 10 && range_end >= 10) txt = '0' + i;
                  else txt = '' + i;
                  part._range[part._range.length] = txt;
               }
            }
            OssCal._add_evs(part);
            return part;
         };
         var hrs = cal.date.getHours();
         var mins = cal.date.getMinutes();
         var t12 = !cal.time24;
         var pm = (hrs > 12);
         if (t12 && pm) hrs -= 12;
         var H = makeTimePart("hour", hrs, t12 ? 1 : 0, t12 ? 12 : 23);
         var span = OssCal.createElement("span", cell);
         span.appendChild(document.createTextNode(":"));
         span.className = "colon";
         var M = makeTimePart("minute", mins, 0, 59);
         var AP = null;
         cell = OssCal.createElement("td", row);
         cell.className = "time";
         cell.colSpan = 2;
         if (t12)
            AP = makeTimePart("ampm", pm ? "pm" : "am", ["am", "pm"]);
         else
            cell.innerHTML = "&nbsp;";

         cal.onSetTime = function() {
            var hrs = this.date.getHours();
            var mins = this.date.getMinutes();
            var pm = (hrs > 12);
            if (pm && t12) hrs -= 12;
            H.firstChild.data = (hrs < 10) ? ("0" + hrs) : hrs;
            M.firstChild.data = (mins < 10) ? ("0" + mins) : mins;
            if (t12)
               AP.firstChild.data = pm ? "pm" : "am";
         };

         cal.onUpdateTime = function() {
            var date = this.date;
            var h = parseInt(H.firstChild.data, 10);
            if (t12) {
               if (/pm/i.test(AP.firstChild.data) && h < 12)
                  h += 12;
               else if (/am/i.test(AP.firstChild.data) && h == 12)
                  h = 0;
            }
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            date.setHours(h);
            date.setMinutes(parseInt(M.firstChild.data, 10));
            date.setFullYear(y);
            date.setMonth(m);
            date.setDate(d);
            this.dateClicked = false;
            this.callHandler();
         };
      })();
   } else {
      this.onSetTime = this.onUpdateTime = function() {};
   }

   var tfoot = OssCal.createElement("tfoot", table);

   row = OssCal.createElement("tr", tfoot);
   row.className = "footrow";

   cell = hh(OssCal._TT["SEL_DATE"], this.weekNumbers ? 8 : 7, 300);
   cell.className = "ttip";
   if (this.isPopup) {
      cell.ttip = OssCal._TT["DRAG_TO_MOVE"];
      cell.style.cursor = "move";
   }
   this.tooltips = cell;

   div = OssCal.createElement("div", this.element);
   this.monthsCombo = div;
   div.className = "combo";
   for (i = 0; i < OssCal._MN.length; ++i) {
      var mn = OssCal.createElement("div");
      mn.className = OssCal.is_ie ? "label-IEfix" : "label";
      mn.month = i;
      mn.appendChild(document.createTextNode(OssCal._SMN[i]));
      div.appendChild(mn);
   }

   div = OssCal.createElement("div", this.element);
   this.yearsCombo = div;
   div.className = "combo";
   for (i = 12; i > 0; --i) {
      var yr = OssCal.createElement("div");
      yr.className = OssCal.is_ie ? "label-IEfix" : "label";
      yr.appendChild(document.createTextNode(""));
      div.appendChild(yr);
   }

   this._init(this.firstDayOfWeek, this.date);
   parent.appendChild(this.element);
};

/** keyboard navigation, only for popup calendars */
OssCal._keyEvent = function(ev) {
   if (!window.calendar) {
      return false;
   }
   (OssCal.is_ie) && (ev = window.event);
   var cal = window.calendar;
   var act = (OssCal.is_ie || ev.type == "keypress");
   if (ev.ctrlKey) {
      switch (ev.keyCode) {
          case 37: // KEY left
         act && OssCal.cellClick(cal._nav_pm);
         break;
          case 38: // KEY up
         act && OssCal.cellClick(cal._nav_py);
         break;
          case 39: // KEY right
         act && OssCal.cellClick(cal._nav_nm);
         break;
          case 40: // KEY down
         act && OssCal.cellClick(cal._nav_ny);
         break;
          default:
         return false;
      }
   } else switch (ev.keyCode) {
       case 32: // KEY space (now)
      OssCal.cellClick(cal._nav_now);
      break;
       case 27: // KEY esc
      act && cal.callCloseHandler();
      break;
       case 37: // KEY left
       case 38: // KEY up
       case 39: // KEY right
       case 40: // KEY down
      if (act) {
         var date = cal.date.getDate() - 1;
         var el = cal.currentDateEl;
         var ne = null;
         var prev = (ev.keyCode == 37) || (ev.keyCode == 38);
         switch (ev.keyCode) {
             case 37: // KEY left
            (--date >= 0) && (ne = cal.ar_days[date]);
            break;
             case 38: // KEY up
            date -= 7;
            (date >= 0) && (ne = cal.ar_days[date]);
            break;
             case 39: // KEY right
            (++date < cal.ar_days.length) && (ne = cal.ar_days[date]);
            break;
             case 40: // KEY down
            date += 7;
            (date < cal.ar_days.length) && (ne = cal.ar_days[date]);
            break;
         }
         if (!ne) {
            if (prev) {
               OssCal.cellClick(cal._nav_pm);
            } else {
               OssCal.cellClick(cal._nav_nm);
            }
            date = (prev) ? cal.date.getMonthDays() : 1;
            el = cal.currentDateEl;
            ne = cal.ar_days[date - 1];
         }
         OssCal.removeClass(el, "selected");
         OssCal.addClass(ne, "selected");
         cal.date = new Date(ne.caldate);
         cal.callHandler();
         cal.currentDateEl = ne;
      }
      break;
       case 13: // KEY enter
      if (act) {
         cal.callHandler();
         cal.hide();
      }
      break;
       default:
      return false;
   }
   return OssCal.stopEvent(ev);
};

OssCal.prototype._init = function (firstDayOfWeek, date) {
   var today = new Date();
   this.table.style.display = "none";
   var year = date.getFullYear();
   if (year < this.minYear) {
      year = this.minYear;
      date.setFullYear(year);
   } else if (year > this.maxYear) {
      year = this.maxYear;
      date.setFullYear(year);
   }
   this.firstDayOfWeek = firstDayOfWeek;
   this.date = new Date(date);
   var month = date.getMonth();
   var mday = date.getDate();
   var no_days = date.getMonthDays();

   date.setDate(1);
   var day1 = (date.getDay() - this.firstDayOfWeek) % 7;
   if (day1 < 0)
      day1 += 7;
   date.setDate(-day1);
   date.setDate(date.getDate() + 1);

   var row = this.tbody.firstChild;
   var MN = OssCal._SMN[month];
   var ar_days = new Array();
   var weekend = OssCal._TT["WEEKEND"];
   for (var i = 0; i < 6; ++i, row = row.nextSibling) {
      var cell = row.firstChild;
      if (this.weekNumbers) {
         cell.className = "day wn";
         cell.firstChild.data = date.getWeekNumber();
         cell = cell.nextSibling;
      }
      row.className = "daysrow";
      var hasdays = false;
      for (var j = 0; j < 7; ++j, cell = cell.nextSibling, date.setDate(date.getDate() + 1)) {
         var iday = date.getDate();
         var wday = date.getDay();
         cell.className = "day";
         var current_month = (date.getMonth() == month);
         if (!current_month) {
            if (this.showsOtherMonths) {
               cell.className += " othermonth";
               cell.otherMonth = true;
            } else {
               cell.className = "emptycell";
               cell.innerHTML = "&nbsp;";
               cell.disabled = true;
               continue;
            }
         } else {
            cell.otherMonth = false;
            hasdays = true;
         }
         cell.disabled = false;
         cell.firstChild.data = iday;
         if (typeof this.getDateStatus == "function") {
            var status = this.getDateStatus(date, year, month, iday);
            if (status === true) {
               cell.className += " disabled";
               cell.disabled = true;
            } else {
               if (/disabled/i.test(status))
                  cell.disabled = true;
               cell.className += " " + status;
            }
         }
         if (!cell.disabled) {
            ar_days[ar_days.length] = cell;
            cell.caldate = new Date(date);
            cell.ttip = "_";
            if (current_month && iday == mday) {
               cell.className += " selected";
               this.currentDateEl = cell;
            }
            if (date.getFullYear() == today.getFullYear() &&
                date.getMonth() == today.getMonth() &&
                iday == today.getDate()) {
               cell.className += " today";
               cell.ttip += OssCal._TT["PART_TODAY"];
            }
            if (weekend.indexOf(wday.toString()) != -1) {
               cell.className += cell.otherMonth ? " oweekend" : " weekend";
            }
         }
      }
      if (!(hasdays || this.showsOtherMonths))
         row.className = "emptyrow";
   }
   this.ar_days = ar_days;
   this.title.firstChild.data = OssCal._MN[month] + ", " + year;
   this.onSetTime();
   this.table.style.display = "block";
};

OssCal.prototype.setDate = function (date) {
   if (!date.equalsTo(this.date)) {
      this._init(this.firstDayOfWeek, date);
   }
};

OssCal.prototype.refresh = function () {
   this._init(this.firstDayOfWeek, this.date);
};

/** Modifies the "firstDayOfWeek" parameter (pass 0 for Synday, 1 for Monday, etc.). */
OssCal.prototype.setFirstDayOfWeek = function (firstDayOfWeek) {
   this._init(firstDayOfWeek, this.date);
   this._displayWeekdays();
};

OssCal.prototype.setDateStatusHandler = OssCal.prototype.setDisabledHandler = function (unaryFunction) {
   this.getDateStatus = unaryFunction;
};

OssCal.prototype.setRange = function (a, z) {
   this.minYear = a;
   this.maxYear = z;
};

OssCal.prototype.callHandler = function () {
   if (this.onSelected) {
      this.onSelected(this, this.date.print(this.dateFormat));
   }
};

OssCal.prototype.callCloseHandler = function () {
   if (this.onClose) {
      this.onClose(this);
   }
   this.hideShowCovered();
};

OssCal.prototype.destroy = function () {
   var el = this.element.parentNode;
   el.removeChild(this.element);
   OssCal._C = null;
   window.calendar = null;
};

OssCal.prototype.reparent = function (new_parent) {
   var el = this.element;
   el.parentNode.removeChild(el);
   new_parent.appendChild(el);
};

OssCal._checkOssCal = function(ev) {
   if (!window.calendar) {
      return false;
   }
   var el = OssCal.is_ie ? OssCal.getElement(ev) : OssCal.getTargetElement(ev);
   for (; el != null && el != calendar.element; el = el.parentNode);
   if (el == null) {
      // calls closeHandler which should hide the calendar.
      window.calendar.callCloseHandler();
      return OssCal.stopEvent(ev);
   }
};

OssCal.prototype.show = function () {
   var rows = this.table.getElementsByTagName("tr");
   for (var i = rows.length; i > 0;) {
      var row = rows[--i];
      OssCal.removeClass(row, "rowhilite");
      var cells = row.getElementsByTagName("td");
      for (var j = cells.length; j > 0;) {
         var cell = cells[--j];
         OssCal.removeClass(cell, "hilite");
         OssCal.removeClass(cell, "active");
      }
   }
   this.element.style.display = "block";
   this.hidden = false;
   if (this.isPopup) {
      window.calendar = this;
      OssCal.addEvent(document, "keydown", OssCal._keyEvent);
      OssCal.addEvent(document, "keypress", OssCal._keyEvent);
      OssCal.addEvent(document, "mousedown", OssCal._checkOssCal);
   }
   this.hideShowCovered();
};

OssCal.prototype.hide = function () {
   if (this.isPopup) {
      OssCal.removeEvent(document, "keydown", OssCal._keyEvent);
      OssCal.removeEvent(document, "keypress", OssCal._keyEvent);
      OssCal.removeEvent(document, "mousedown", OssCal._checkOssCal);
   }
   this.element.style.display = "none";
   this.hidden = true;
   this.hideShowCovered();
};

OssCal.prototype.showAt = function (x, y) {
   var s = this.element.style;
   s.left = x + "px";
   s.top = y + "px";
   this.show();
};

OssCal.prototype.showAtElement = function (el, opts) {
   var self = this;
   var p = OssCal.getAbsolutePos(el);
   if (!opts || typeof opts != "string") {
      this.showAt(p.x, p.y + el.offsetHeight);
      return true;
   }
   function fixPosition(box) {
      if (box.x < 0)
         box.x = 0;
      if (box.y < 0)
         box.y = 0;
      var cp = document.createElement("div");
      var s = cp.style;
      s.position = "absolute";
      s.right = s.bottom = s.width = s.height = "0px";
      document.body.appendChild(cp);
      var br = OssCal.getAbsolutePos(cp);
      document.body.removeChild(cp);
      if (OssCal.is_ie) {
         br.y += document.body.scrollTop;
         br.x += document.body.scrollLeft;
      } else {
         br.y += window.scrollY;
         br.x += window.scrollX;
      }
      var tmp = box.x + box.width - br.x;
      if (tmp > 0) box.x -= tmp;
      tmp = box.y + box.height - br.y;
      if (tmp > 0) box.y -= tmp;
   };
   this.element.style.display = "block";
   OssCal.khtml_browser = function() {
      var w = self.element.offsetWidth;
      var h = self.element.offsetHeight;
      self.element.style.display = "none";
      var valign = opts.substr(0, 1);
      var halign = "l";
      if (opts.length > 1) {
         halign = opts.substr(1, 1);
      }
      switch (valign) {
          case "T": p.y -= h; break;
          case "B": p.y += el.offsetHeight; break;
          case "C": p.y += (el.offsetHeight - h) / 2; break;
          case "t": p.y += el.offsetHeight - h; break;
          case "b": break; // already there
      }
      switch (halign) {
          case "L": p.x -= w; break;
          case "R": p.x += el.offsetWidth; break;
          case "C": p.x += (el.offsetWidth - w) / 2; break;
          case "r": p.x += el.offsetWidth - w; break;
          case "l": break; // already there
      }
      p.width = w;
      p.height = h + 40;
      self.monthsCombo.style.display = "none";
      fixPosition(p);
      self.showAt(p.x, p.y);
   };
   if (OssCal.is_khtml)
      setTimeout("OssCal.khtml_browser()", 10);
   else
      OssCal.khtml_browser();
};

OssCal.prototype.setDateFormat = function (str) {
   this.dateFormat = str;
};

OssCal.prototype.setTtDateFormat = function (str) {
   this.ttDateFormat = str;
};

OssCal.prototype.parseDate = function (str, fmt) {
   var y = 0;
   var m = -1;
   var d = 0;
   var a = str.split(/\W+/);
   if (!fmt) {
      fmt = this.dateFormat;
   }
   var b = fmt.match(/%./g);
   var i = 0, j = 0;
   var hr = 0;
   var min = 0;
   for (i = 0; i < a.length; ++i) {
      if (!a[i])
         continue;
      switch (b[i]) {
          case "%d":
          case "%e":
         d = parseInt(a[i], 10);
         break;

          case "%m":
         m = parseInt(a[i], 10) - 1;
         break;

          case "%Y":
          case "%y":
         y = parseInt(a[i], 10);
         (y < 100) && (y += (y > 29) ? 1900 : 2000);
         break;

          case "%b":
          case "%B":
         for (j = 0; j < 12; ++j) {
            if (OssCal._MN[j].substr(0, a[i].length).toLowerCase() == a[i].toLowerCase()) { m = j; break; }
         }
         break;

          case "%H":
          case "%I":
          case "%k":
          case "%l":
         hr = parseInt(a[i], 10);
         break;

          case "%P":
          case "%p":
         if (/pm/i.test(a[i]) && hr < 12)
            hr += 12;
         break;

          case "%M":
         min = parseInt(a[i], 10);
         break;
      }
   }
   if (y != 0 && m != -1 && d != 0) {
      this.setDate(new Date(y, m, d, hr, min, 0));
      return;
   }
   y = 0; m = -1; d = 0;
   for (i = 0; i < a.length; ++i) {
      if (a[i].search(/[a-zA-Z]+/) != -1) {
         var t = -1;
         for (j = 0; j < 12; ++j) {
            if (OssCal._MN[j].substr(0, a[i].length).toLowerCase() == a[i].toLowerCase()) { t = j; break; }
         }
         if (t != -1) {
            if (m != -1) {
               d = m+1;
            }
            m = t;
         }
      } else if (parseInt(a[i], 10) <= 12 && m == -1) {
         m = a[i]-1;
      } else if (parseInt(a[i], 10) > 31 && y == 0) {
         y = parseInt(a[i], 10);
         (y < 100) && (y += (y > 29) ? 1900 : 2000);
      } else if (d == 0) {
         d = a[i];
      }
   }
   if (y == 0) {
      var today = new Date();
      y = today.getFullYear();
   }
   if (m != -1 && d != 0) {
      this.setDate(new Date(y, m, d, hr, min, 0));
   }
};

OssCal.prototype.hideShowCovered = function () {
   var self = this;
   OssCal.khtml_browser = function() {
      function getVisib(obj){
         var value = obj.style.visibility;
         if (!value) {
            if (document.defaultView && typeof (document.defaultView.getComputedStyle) == "function") { // Gecko, W3C
               if (!OssCal.is_khtml)
                  value = document.defaultView.
                     getComputedStyle(obj, "").getPropertyValue("visibility");
               else
                  value = '';
            } else if (obj.currentStyle) { // IE
               value = obj.currentStyle.visibility;
            } else
               value = '';
         }
         return value;
      };

      var tags = new Array("applet", "iframe", "select");
      var el = self.element;

      var p = OssCal.getAbsolutePos(el);
      var EX1 = p.x;
      var EX2 = el.offsetWidth + EX1;
      var EY1 = p.y;
      var EY2 = el.offsetHeight + EY1;

      for (var k = tags.length; k > 0; ) {
         var ar = document.getElementsByTagName(tags[--k]);
         var cc = null;

         for (var i = ar.length; i > 0;) {
            cc = ar[--i];

            p = OssCal.getAbsolutePos(cc);
            var CX1 = p.x;
            var CX2 = cc.offsetWidth + CX1;
            var CY1 = p.y;
            var CY2 = cc.offsetHeight + CY1;

            if (self.hidden || (CX1 > EX2) || (CX2 < EX1) || (CY1 > EY2) || (CY2 < EY1)) {
               if (!cc.__msh_save_visibility) {
                  cc.__msh_save_visibility = getVisib(cc);
               }
               cc.style.visibility = cc.__msh_save_visibility;
            } else {
               if (!cc.__msh_save_visibility) {
                  cc.__msh_save_visibility = getVisib(cc);
               }
               cc.style.visibility = "hidden";
            }
         }
      }
   };
   if (OssCal.is_khtml)
      setTimeout("OssCal.khtml_browser()", 10);
   else
      OssCal.khtml_browser();
};

OssCal.prototype._displayWeekdays = function () {
   var fdow = this.firstDayOfWeek;
   var cell = this.firstdayname;
   var weekend = OssCal._TT["WEEKEND"];
   for (var i = 0; i < 7; ++i) {
      cell.className = "day name";
      var realday = (i + fdow) % 7;
      if (i) {
         cell.ttip = OssCal._TT["DAY_FIRST"].replace("%s", OssCal._DN[realday]);
         cell.navtype = 100;
         cell.calendar = this;
         cell.fdow = realday;
         OssCal._add_evs(cell);
      }
      if (weekend.indexOf(realday.toString()) != -1) {
         OssCal.addClass(cell, "weekend");
      }
      cell.firstChild.data = OssCal._SDN[(i + fdow) % 7];
      cell = cell.nextSibling;
   }
};

OssCal.prototype._hideCombos = function () {
   this.monthsCombo.style.display = "none";
   this.yearsCombo.style.display = "none";
};

OssCal.prototype._dragStart = function (ev) {
   if (this.dragging) {
      return;
   }
   this.dragging = true;
   var posX;
   var posY;
   if (OssCal.is_ie) {
      posY = window.event.clientY + document.body.scrollTop;
      posX = window.event.clientX + document.body.scrollLeft;
   } else {
      posY = ev.clientY + window.scrollY;
      posX = ev.clientX + window.scrollX;
   }
   var st = this.element.style;
   this.xOffs = posX - parseInt(st.left);
   this.yOffs = posY - parseInt(st.top);
   with (OssCal) {
      addEvent(document, "mousemove", calDragIt);
      addEvent(document, "mouseup", calDragEnd);
   }
};

Date._MD = new Array(31,28,31,30,31,30,31,31,30,31,30,31);

Date.SECOND = 1000 /* milliseconds */;
Date.MINUTE = 60 * Date.SECOND;
Date.HOUR   = 60 * Date.MINUTE;
Date.DAY    = 24 * Date.HOUR;
Date.WEEK   =  7 * Date.DAY;

Date.prototype.getMonthDays = function(month) {
   var year = this.getFullYear();
   if (typeof month == "undefined") {
      month = this.getMonth();
   }
   if (((0 == (year%4)) && ( (0 != (year%100)) || (0 == (year%400)))) && month == 1) {
      return 29;
   } else {
      return Date._MD[month];
   }
};

Date.prototype.getDayOfYear = function() {
   var now = new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
   var then = new Date(this.getFullYear(), 0, 0, 0, 0, 0);
   var time = now - then;
   return Math.floor(time / Date.DAY);
};

Date.prototype.getWeekNumber = function() {
   var d = new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
   var DoW = d.getDay();
   d.setDate(d.getDate() - (DoW + 6) % 7 + 3); // Nearest Thu
   var ms = d.valueOf(); // GMT
   d.setMonth(0);
   d.setDate(4); // Thu in Week 1
   return Math.round((ms - d.valueOf()) / (7 * 864e5)) + 1;
};

Date.prototype.equalsTo = function(date) {
   return ((this.getFullYear() == date.getFullYear()) &&
      (this.getMonth() == date.getMonth()) &&
      (this.getDate() == date.getDate()) &&
      (this.getHours() == date.getHours()) &&
      (this.getMinutes() == date.getMinutes()));
};

Date.prototype.print = function (str) {
   var m = this.getMonth();
   var d = this.getDate();
   var y = this.getFullYear();
   var wn = this.getWeekNumber();
   var w = this.getDay();
   var s = {};
   var hr = this.getHours();
   var pm = (hr >= 12);
   var ir = (pm) ? (hr - 12) : hr;
   var dy = this.getDayOfYear();
   if (ir == 0)
      ir = 12;
   var min = this.getMinutes();
   var sec = this.getSeconds();
   s["%a"] = OssCal._SDN[w]; // abbreviated weekday name [FIXME: I18N]
   s["%A"] = OssCal._DN[w]; // full weekday name
   s["%b"] = OssCal._SMN[m]; // abbreviated month name [FIXME: I18N]
   s["%B"] = OssCal._MN[m]; // full month name
   s["%C"] = 1 + Math.floor(y / 100); // the century number
   s["%d"] = (d < 10) ? ("0" + d) : d; // the day of the month (range 01 to 31)
   s["%e"] = d; // the day of the month (range 1 to 31)
   s["%H"] = (hr < 10) ? ("0" + hr) : hr; // hour, range 00 to 23 (24h format)
   s["%I"] = (ir < 10) ? ("0" + ir) : ir; // hour, range 01 to 12 (12h format)
   s["%j"] = (dy < 100) ? ((dy < 10) ? ("00" + dy) : ("0" + dy)) : dy; // day of the year (range 001 to 366)
   s["%k"] = hr;      // hour, range 0 to 23 (24h format)
   s["%l"] = ir;      // hour, range 1 to 12 (12h format)
   s["%m"] = (m < 9) ? ("0" + (1+m)) : (1+m); // month, range 01 to 12
   s["%M"] = (min < 10) ? ("0" + min) : min; // minute, range 00 to 59
   s["%n"] = "\n";      // a newline character
   s["%p"] = pm ? "PM" : "AM";
   s["%P"] = pm ? "pm" : "am";
   s["%s"] = Math.floor(this.getTime() / 1000);
   s["%S"] = (sec < 10) ? ("0" + sec) : sec; // seconds, range 00 to 59
   s["%t"] = "\t";      // a tab character
   s["%U"] = s["%W"] = s["%V"] = (wn < 10) ? ("0" + wn) : wn;
   s["%u"] = w + 1;   // the day of the week (range 1 to 7, 1 = MON)
   s["%w"] = w;      // the day of the week (range 0 to 6, 0 = SUN)
   s["%y"] = ('' + y).substr(2, 2); // year without the century (range 00 to 99)
   s["%Y"] = y;      // year with the century
   s["%%"] = "%";      // a literal '%' character

   var re = /%./g;
   if (!OssCal.is_ie5)
      return str.replace(re, function (par) { return s[par] || par; });

   var a = str.match(re);
   for (var i = 0; i < a.length; i++) {
      var tmp = s[a[i]];
      if (tmp) {
         re = new RegExp(a[i], 'g');
         str = str.replace(re, tmp);
      }
   }

   return str;
};

//Date.prototype.__msh_oldSetFullYear = Date.prototype.setFullYear;
if ( Date.prototype.__msh_oldSetFullYear == null ){
    Date.prototype.__msh_oldSetFullYear = Date.prototype.setFullYear;
}
Date.prototype.setFullYear = function(y) {
   var d = new Date(this);
//   d.__msh_oldSetFullYear(y);
   if (d.getMonth() != this.getMonth())
      this.setDate(28);
   this.__msh_oldSetFullYear(y);
};
window.calendar = null;

OssCal._DN = new Array
("Sunday",
 "Monday",
 "Tuesday",
 "Wednesday",
 "Thursday",
 "Friday",
 "Saturday",
 "Sunday");

OssCal._SDN = new Array
("Sun",
 "Mon",
 "Tue",
 "Wed",
 "Thu",
 "Fri",
 "Sat",
 "Sun");

OssCal._MN = new Array
("January",
 "February",
 "March",
 "April",
 "May",
 "June",
 "July",
 "August",
 "September",
 "October",
 "November",
 "December");

OssCal._SMN = new Array
("Jan",
 "Feb",
 "Mar",
 "Apr",
 "May",
 "Jun",
 "Jul",
 "Aug",
 "Sep",
 "Oct",
 "Nov",
 "Dec");

OssCal._TT = {};
OssCal._TT["INFO"] = "Click to get usage information";

OssCal._TT["ABOUT"] =
"Calendar Usage\n" +
"\n\n" +
"Date selection:\n" +
"- Click on the \xab, \xbb buttons to select year\n" +
"- Click on the " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " buttons to select month\n" ; 
OssCal._TT["ABOUT_TIME"] = "\n\n" +
"Time selection:\n" +
"- Click on any of the time parts to increase it\n" +
"- or Shift-click to decrease it\n" +
"- or click and drag for faster selection.";

OssCal._TT["PREV_YEAR"] = "Prev. year (hold for menu)";
OssCal._TT["PREV_MONTH"] = "Prev. month (hold for menu)";
OssCal._TT["GO_TODAY"] = "Go Today";
OssCal._TT["NEXT_MONTH"] = "Next month (hold for menu)";
OssCal._TT["NEXT_YEAR"] = "Next year (hold for menu)";
OssCal._TT["SEL_DATE"] = "Select date";
OssCal._TT["DRAG_TO_MOVE"] = "Drag to move";
OssCal._TT["PART_TODAY"] = " (today)";

OssCal._TT["DAY_FIRST"] = "Display %s first";

OssCal._TT["WEEKEND"] = "0,6";

OssCal._TT["CLOSE"] = "Close";
OssCal._TT["TODAY"] = "Today";
OssCal._TT["TIME_PART"] = "(Shift-)Click or drag to change value";

OssCal._TT["DEF_DATE_FORMAT"] = "%d-%m-%Y";
OssCal._TT["TT_DATE_FORMAT"] = "%a, %b %e";

OssCal._TT["WK"] = "wk";
OssCal._TT["TIME"] = "Time:";

OssCal.setup = function (cWid,  cBtn, cW) {
   function onSelect(c) {
      setWidVal(c.win, c.wid,  c.date.print("%d-%m-%Y"));
//      if (typeof c.wid.onchange == "function") c.wid.onchange();
      if (typeof c.wid.onblur == "function") c.wid.onblur();
      if (c.dateClicked) c.callCloseHandler();
   };
   var triggerEl = cBtn || cWid;
//DebugInfo ("1 "  + cW.id) ;
   triggerEl["onclick"] = function() {
      var mustCreate = false;
////DebugInfo ("2 "  + cW.id) ;
      var cal = window.calendar;
      if (!(cal)) {
         window.calendar = cal = new OssCal(0,null,onSelect, function(cal) { cal.hide(); });
         cal.showsTime = false;
         cal.time24 = "24";
         cal.weekNumbers = false;
         mustCreate = true;
      } else {
         cal.hide();
      }
//DebugInfo ("3 "  + cW.id) ;
      cal.showsOtherMonths = false ;
      cal.yearStep = 2;
      cal.setRange(1900, 2999);
      cal.wid = cWid ;
      cal.win = cW ;
      cal.setDateStatusHandler(null);
      cal.setDateFormat("%d-%m-%Y");
      if (mustCreate) cal.create();
      cal.parseDate( getWidVal(cW, cWid ));
      cal.refresh();
      cal.showAtElement( cBtn || cWid, 'Br');
      return false;
   };
};
function getHelp(o) {
   o = (o) ? o : w_g ;
   if(!(o)) return ;
   if (o.hW == undefined ){
      o.hW = createTag ( 'div', o.id + '_hlp', 'hlpWin' , bdy_g, 'none', null ) ;
      o.hW.o = o ;
      var cB = createTag ( 'div', 'closeBtn', '' , o.hW, 'none', null ) ;
      cB.onclick = function() { this.parentNode.style.display = 'none' ; };
      setDrag(o.hW) ;
   }
   _as(null, "index.php?f=3&srv=1123&sNm=" + o.id + "&sEv" + o.mod_g + "&hid=" + o.srv_g, o, shwHlp, o ) ;
}
function shwHlp(o) {
   o.hW.innerHTML = this.responseText ;
   o.hW.style.display = 'block' ;
}
function setDrag(o) {
   o.drgFlg = false ;
   o.W = o.offsetWidth ;
   o.H = o.offsetHeight ;
   o.P = getPos(o) ;
   o.oX = 0;
   o.oY = 0;
   o.mX = 0;
   o.mY = 0;
   o.onmousemove = function (e){
      if(this.drgFlg == true) {
         var mX = e.pageX||e.clientX + document.documentElement.scrollLeft ;
         var mY = e.pageY||e.clientY + document.documentElement.scrollTop ;
         var dX = mX-this.mX + this.oX ;
         var dY = mY-this.mY + this.oY ;
         this.oX = 0;
         this.oY = 0;
         this.mX = mX ;
         this.mY = mY ;
         this.P.x  += dX ;
         this.P.y  += dY ;
         this.style.left = this.P.x +'px';
         this.style.top = this.P.y +'px';
         this.style.height = this.H +'px';
         this.style.width = this.W +'px';
      }
   }
   o.onmousedown = function (e){
      this.mX = e.pageX ;
      this.mY = e.pageY ;
      this.drgFlg = true ;
      this.style.cursor = 'move';
   }
   o.onmouseup = function () {
      this.drgFlg = false ;
      this.style.left = this.P.x +'px';
      this.style.top = this.P.y +'px';
      this.style.cursor = 'auto';
   }
} 
getPos = function(el) {
   var sL = 0, sT = 0;
   var is_div = /^div$/i.test(el.tagName);
   if (is_div && el.scrollLeft)
      sL = el.scrollLeft;
   if (is_div && el.scrollTop)
      sT = el.scrollTop;
   var r = { x: el.offsetLeft - sL, y: el.offsetTop - sT };
   if (el.offsetParent) {
      var tmp = getPos(el.offsetParent);
      r.x += tmp.x;
      r.y += tmp.y;
   }
   return r;
};
function print_r(a,l) {
   var t = "";
   var lpd = "";
   if(!l) l = 0;
   for(var j=0;j<l+1;j++) lpd += " ";
   if(typeof(a) == 'object') {
      if (a == null) return ("(NULL)") ;
      for(var i=0;i<a.length;i++) {
         var v = a[i];
         if(typeof(v) == 'object') {
            t += lpd + "'" + i + "' ==> ";
            t += "{\n" + print_r(v,l+1) + lpd + "}\n";
         } else {
            t += lpd + "'" + i + "' ==> \"" + v + "\"\n";
         }
      }
   } else t = "==>"+a+"<==("+typeof(a)+")\n";
   return t;
}
//function g(p) { 
//    var s = window.location.search; 
//    var pat = new RegExp('&'+p+'(?:=([^&]*))?(?=&|$)','i');
//    var sts = s.replace(/^?/,'&').match(pat) ;
//    return (s.replace(/^?/,'&').match(pat)) ? ((typeof s[1] == 'undefined') ? '' : decodeURIComponent(s[1])) : undefined;
//     (s=s.replace(/^?/,'&').match(pat)) ? ((typeof s[1] == 'undefined') ? '' : s[1] : undefined;
    //return (s=s.replace(/^?/,'&').match(pat)) ? ((typeof s[1] == 'undefined') ? '' : s[1] : undefined;
//} ;
//function gL(os) {
//  var s = new Error().stack.split('\n'),
//  l = s[(os || 1) + 1].split(':');
//  return new Error().stack ;
//  return parseInt(l[l.length - 2], 10);
//}
 
//__defineGetter__('__LINE__', function () {
//return gL(4);
//}); 
function callSrv(srv, key, mod , mnu, lgn ){
   var url = "index.php?f=1&" + window.location.search.substring(1) + "&srv=" + srv + "&k=" + key + "&dmd=" + mod + "&mnu=" + mnu ;
   _s(null, url,false );
   bdy_g = document.getElementById("bdy_g") ;
   switch_g = document.getElementById("switch_g") ;
   top_g = document.getElementById("top_g") ;
   lft_g = document.getElementById("lft_g") ;
   rgt_g = document.getElementById("rgt_g") ;
   bot_g = document.getElementById("bot_g") ;
   wrn_g = document.getElementById("wrn_g") ;
   fde_g = document.getElementById("fde_g") ;
   var dt = eval(xH.responseText) ; 
   if(bdy_g) bdy_g.login = lgn ;
   if (!dt) return ;
   if(dt['__Caution']) {
      wrn_g.style.display = 'block' ;
      wrn_g.innerHTML = dt['__Caution'] ;
   }
   if(dt['__Invalid']) {
      alertBox( 'Something wrong, Request cannot be allowed !') ;
      return 0 ;
   }
   if(top_g && dt['top'] ) top_g.innerHTML = dt['top'] ;
   if(lft_g && dt['lft'] ) lft_g.innerHTML = dt['lft'] ;
   if(rgt_g && dt['rgt'] ) rgt_g.innerHTML = dt['rgt'] ;
   if(bot_g && dt['bot'] ) bot_g.innerHTML = dt['bot'] ;
   if(bdy_g && dt['bdy'] ) { 
      bdy_g.innerHTML = dt['bdy'] ;
      bdy_g.curSrv = srv ;
   }
   initSrv (null, srv, key, mod ) ;
   if (switch_g) if(switch_g.value !='') alertBox("<P>" + switch_g.value + "</P>" ) ;
   var tglFScr = getElmntById('tglFullScreen', top_g );
   if (tglFScr) {
      tglFScr.li = getElmntById('liFullScreen', top_g );
      _l(tglFScr, "click", FullScreen ) ;
   }
}
function setExe (o,m, V) {
   var qT = m;
   var rwDt;
   var dL = o.w.exDt.length ;
   if (o.sR && (o.sR.qI != undefined) && o.w.exDt[o.sR.qI]) {
      dL = o.sR.qI ;
      qT = (m != 'DEL') ? o.w.exDt[dL]['__e'] : ''  ;
   } else if (o.w == o) {
      o.w.exDt.unshift( new Array()) ;
      dL = 0 ;
   } else {
//      o.w.exDt[dL] = new Array() ;
   }
//   o.w.exDt[dL] = new Array() ;
   if (!(rwDt = getRawPostWithoutForm(o.w, true, (o.w==o)?false:o ))){
      if(m != 'DRT') {
         if(o.w == o) {
            if (o.w.btn['CAN']) o.w.btn['CAN'].disabled = false; 
            if (o.w.btn['SAV']) o.w.btn['SAV'].disabled = false; 
            if (o.w.btn['HLD']) o.w.btn['HLD'].disabled = false; 
            if (o.w.btn['PCD']) o.w.btn['PCD'].disabled = false; 
            if (o.w.btn['RTN']) o.w.btn['RTN'].disabled = false; 
         }
         return 0;
      }
   } ;
//DebugInfo( "Dm " +  rwDt['m'] ) ;
   o.w.exDt[dL] = rwDt ;
//DebugInfo("3 setExe " + o.id + " m " + m + " dL " + dL + " " + o.sR ) ;
   if (o.fP) for ( var c=0 ; c < o.fP.length ; c++ ){
      Wid = o.gN.col[c] ;
      switch ( o.fP[c].tp  ){
         case 'd' :
         case 'i' :
         case 'n' :
         case 'a' :
         case 't' :
         case 'f' :
         case 'T' :
         case 'c' :
            o.w.exDt[dL][c + ":" + o.id] = getWidVal(o.w, Wid) ;
            break ;
         case 'b' :
            o.w.exDt[dL][c + ":" + o.id] = (Wid.checked) ? 't' : 'f' ;
            break ;
      }
      if(  o.oldVal[c] != undefined ) {
         o.w.exDt[dL][c + ":old"] = o.oldVal[c] ;
         o.w.exDt[dL][c + ":" + o.id  + ":old"] = o.oldVal[c] ;
      }
   }
   o.w.exDt[dL]['k'] = getWidVal( o.w, o.w.kw_g ) ;
   o.w.exDt[dL]['__e'] = qT ; // 'DEL' ;
   o.w.exDt[dL]['__s'] = o.w.id ;
   if(o.w != o) o.w.exDt[dL]['__w'] = o.id ;
   if(V) o.w.exDt[dL]['_fno'] = V ; // o.parentNode.rV ;
   if (o.wid) {
      var fS = o.wid.fS ;
      if(m='DEL') {
         o.wid.removeChild(fS[o.c]);
         fS.splice(o.c,1) ;
      }
      o.rV = o.fS[0].rV ;
   }
   return 1 ;
}
function exCf (o, mod) {
   o.w.exDt = new Array() ; 
   var rs = eval(o.xR.responseText) ; 
   if(mod != 'DRT') {
      if (o.w.btn['CAN']) o.w.btn['CAN'].disabled = false; 
      if (o.w.btn['SAV']) o.w.btn['SAV'].disabled = false; 
      if (o.w.btn['RTN']) o.w.btn['RTN'].disabled = false; 
      if (o.w.btn['HLD']) o.w.btn['HLD'].disabled = false; 
      if (o.w.btn['PCD']) o.w.btn['PCD'].disabled = false; 
   }
   if (rs) { 
      if( rs['__Error'] ) {
         var msg = rs['__Error'].split(":")[1] ;
         alertBox("<P>" + msg + "</P>" ) ;
         return ;
      }
      if( rs['__Notice'] ) {
         var msg = rs['__Notice'].split(":")[1] ;
         msg = msg.split("CONTEXT")[0] ;
         alertBox("<P>" + msg + "</P>" ) ;
      }
      o.w.vR = new Array() ;
      if (o.w == o){
         setWidVal (o.w, o.w.kw_g, rs[0] ) ; 
      }
   }
   var aEf = o.parm["af" + o.mod_g ] ;
   if( aEf ) eval( aEf );
   o.w.exDt = new Array() ; 
   if(o.t=='grid') gotoGridPage(0,0,'g', o) ;
   if (o.w == o) {
      if(mod != 'DRT')setScreenMode( o,  (o.mA['NRM']) ? 'NRM' : 'SHW', true ) ;
   }
}
function clstAncstr(el, id) {
   if (el) {  do { if(el.id==id) return el ; } while (el = el.parentNode); }
   return null ;
}
function callMTag( o, v, rsA, col  ) {
//DebugInfo(" callMTag (" + o.id ); 
   if (!( o.gD)) {
      initMTag(o, v) ;
   }
   if(v) o.v = v ;
   o.c = col ;
   rmMTagRow ( o, 0, o.rCnt );
   o.rCnt = 0 ;
   getVw ( o.w, o.v, sMTagDt, o, "cfm", o.pL, 0, o.aQp, o.oStr, o.oFs, o.fStr ) ;
}
function initMTag (o,vno) {
//DebugInfo(" initMTag (" + o.id ); 
   if ( o.gD ) {
      return ;
   }
   addCls(o, 'mtag') ;
   if(o.tabIndex < 0) o.tabIndex = 0 ;
   o.onkeyup = function(ev) {
   } 
   if(!(o.pL) || (o.pL < 1)) o.pL = 10 ;
   o.gD = createTag ( 'div', 'uL', o.stp, o, 'block', null ) ; 
   o.row = new Array() ;
   o.v = vno ;
   o.gN = createTag('input', '0:' + o.id,'a',o.gD,'block','','0:'+o.id);
   o.gN.w = o.w ;
   o.gN.rV = '' ;
   o.rCnt = 0 ;
   if(o.lbl) rmCls(o.lbl,'up') ;
   o.gN.ac = o.ac ;
   o.gN.av = o.av ;
   o.gN.eA = new Array() ;
   o.gN.eA['ONCHANGE'] = "insMTagItem( w_g.wA['" + o.id + "'], null )" ;
   _l(o.gN, "keyup", autoComplete, o.gN);
   _l(o.gN, "click", autoComplete, o.gN);
   _l(o.gN, "focus", autoComplete, o.gN);
//   _l(o.gN, "focus", setWidEvt, o.gN);
//   _l(o.gN, "blur", setWidEvt, o.gN);
//   addCls(o.gN, 'dw') ;
}
function sMTagDt(o,rs ){
//   var rs = o.w.vR[o.v] ;
//DebugInfo(" sMTagDt (" + o.id ); 
//   var rCnt = parseInt (rs[0][0] ) ;
   var rCnt = o.rCnt ;
   var rC = rs[2].length ;
   for ( i=rCnt ; i < rCnt + rC ; i++ ){
      insMTagItem( o, i, rs[2][i-rCnt])
      o.row[i].qI = undefined ;
//      setMTagRow (o, o.row[i], rs[2][i-rCnt], 'r' ) ;
   }
   if ( o.eA['psf'] ) eval( o.eA['psf']) ;
}
//function setMTagRow (o, td, dt, tp ) {
//   if ( tp == 'r') {
//      var a = dt[o.cv].split("||") ;
//      td.rV = a[0] ;
//      td.innerHTML = (a[1] == undefined) ? a[0] : a[1] ;
//   } else if (dt[c] ) {
//      td.innerHTML = dt[c].value ;
//      td.rV = dt[c].rV ;
//   }
//}
function insMTagItem( o, i, dt) {
   if(i == null ) i = o.rCnt ;
   if(!( o.row[i]) ) o.row[i] = createTag ( 'div', 'gR_' + i, "gR_" +  i % 2, o.gD, 'block', null, null, null, 'bl' ) ; 
   o.row[i].style.display = 'block' ;
//DebugInfo(" 2 insMTagItem (" + o.id + "," + i  + " )  " + o.row[i].id ); 
   o.row[i].rN = i ;
   o.rCnt++ ;
   _l(o.row[i], "click", onMTagDel, i, o);
   o.style.height = o.gN.getBoundingClientRect().bottom - o.getBoundingClientRect().top + 'px' ;
   if(dt) {
//DebugInfo(" 2 insMTagItem (" + o.id + "," + i  + " )  " + o.row[i].id + " val (" + dt + ")" ); 
      var a = dt[o.c].split("||") ;
      o.row[i].rV = a[0] ;
      o.row[i].dV = (a[1] == undefined) ? a[0] : a[1] ;
      o.row[i].innerHTML = o.row[i].dV ;
      o.row[i].title = o.row[i].dV ;
   } else {
      o.row[i].rV = o.gN.rV ;
      o.row[i].dV = o.gN.dV ;
      o.row[i].innerHTML = o.gN.value ;
      o.row[i].title = o.gN.value ;
      o.gN.value = '' ;
      o.sR = o.row[i] ;
      if(!(setExe (o,'ADD'))) return  ;
      o.sR.qI = o.w.exDt.length - 1 ;
      o.w.exDt[o.sR.qI][o.id] = o.sR.rV ;
   }
   if ( o.eA['onset'] ) { 
      var p = [o.row[i].rN, o.row[i].rV, o.row[i].title, 'i'] ; 
      eval(o.eA['onset']).apply(o,p) ; 
   } 
   if(o.lbl) addCls(o.lbl,'up') ;
   return o.row[i] ;
}
function  onMTagDel (b,e,i,o) {
   o.style.height = o.gN.getBoundingClientRect().bottom - o.getBoundingClientRect().top + 'px' ;
   var mX = b.getBoundingClientRect().right - (e.pageX || e.clientX)  ;
   var stl = window.getComputedStyle(b, null);
   if( parseInt(stl.paddingRight ) - mX > 0 ) {
      o.sR = o.row[i] ;
      if(!(setExe (o,'DEL'))) return  ;
      //o.sR.qI = o.w.exDt.length - 1 ;
      o.w.exDt[o.w.exDt.length - 1][o.id] = o.sR.rV ;
      rmMTagRow ( o, o.sR.rN, o.sR.rN )
//DebugInfo( o.id + " onMTagDel (" + e.pageX + ")(" + e.clientX  + ")(" + b.getBoundingClientRect().left + ")(" + b.getBoundingClientRect().width + ")" ) ;
   }
   if(o.eA['ondelete']){
      var p = [o.row[i].rN, o.row[i].rV, o.row[i].title, 'r'] ;
      eval(o.eA['ondelete']).apply(o,p) ;
   }
}
function rmMTagRow ( o, frm, to ) {
   for ( i=to ; i >= frm ; i-- ){
      if (!(o.row[i])) continue ;
      o.row[i].qI = undefined ;
      o.row[i].style.display = 'none' ;
      o.rCnt-- ;
   }
}
function isEvtVld(o,eN) {
    eN = 'on' + eN ;
    var isF = (eN in o);
    if (!isF) {
       el.setAttribute(eN, 'return;');
       isF = typeof el[eN] == 'function';
    }
    return isF;
}
function setRollInfo(grp, gnm) {
   var o = getElmntById('roll_g', top_g) ;
    if (o) {
       var a = o.innerHTML.split("::") ;
       o.innerHTML = a[0] + "::" + gnm + "::" + a[2] ;
       getElmntById('AcwUsrGrp',top_g).rV = grp ;
    }
 }
 function setUsrRoll() {
   var wid = getElmntById('AcwUsrGrp', top_g) ;
   dt['gid'] = wid.rV ;
   _s( _e(dt), "index.php?f=3&srv=" + 996 ) ;
   var rs =eval( xH.responseText) ;
   if (rs['srvsts']) {
      window.location.href = "index.php?srv=" + rs['srvsts'] ;
   } else {
      alert ("No Roll Change Occured !!") ;
   }
 } ;
 function logout() {
        window.location.href = "index.php?srv=1001" ;
 };
function tglFulScr(o,wnm) {
  var w = document.getElementById(wnm);
  if (w.fulScr == 1 ){
     w.fulScr = 0 ;
     rmCls(w, "fulScrDW");
     rmCls(o, "fulScrClsBtn");
     o.innerHTML ="⇙" ;
  } else {
     w.fulScr = 1 ;
     addCls(w, "fulScrDW");
     addCls(o, "fulScrClsBtn");
     o.innerHTML ="⇗";
  }
}

//Images.decodeArrayBuffer = function(buffer, onLoad) {
////    var mime;
////    var a = new Uint8Array(buffer);
////    var nb = a.length;
////    if (nb < 4)
////        return null;
////    var b0 = a[0];
////    var b1 = a[1];
////    var b2 = a[2];
////    var b3 = a[3];
////    if (b0 == 0x89 && b1 == 0x50 && b2 == 0x4E && b3 == 0x47)
////        mime = 'image/png';
////    else if (b0 == 0xff && b1 == 0xd8)
////        mime = 'image/jpeg';
////    else if (b0 == 0x47 && b1 == 0x49 && b2 == 0x46)
////        mime = 'image/gif';
////    else
////        return null;
////    var binary = "";
////    for (var i = 0; i < nb; i++)
////        binary += String.fromCharCode(a[i]);
////    var base64 = window.btoa(binary);
////    var image = new Image();
////    image.onload = onLoad;
////    image.src = 'data:' + mime + ';base64,' + base64;
////    return image;
////}
//
//======================
