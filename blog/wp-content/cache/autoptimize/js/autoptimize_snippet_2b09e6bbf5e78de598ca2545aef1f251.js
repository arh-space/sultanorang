var gsettings,dsettings={elements:"body",selector:"a:not(.no-ajaxy)",forms:"form:not(.no-ajaxy)",canonical:!1,refresh:!1,requestDelay:0,scrolltop:"s",bodyClasses:!1,deltas:!0,asyncdef:!1,alwayshints:!1,inline:!0,inlinehints:!1,inlineskip:"adsbygoogle",inlineappend:!0,style:!0,prefetchoff:!1,verbosity:0,memoryoff:!1,cb:0,pluginon:!0,passCount:!1};String.prototype.iO=function(t){return this.toString().indexOf(t)+1};var lvl=0,pass=0,currentURL="",rootUrl=location.origin,api=window.history&&window.history.pushState&&window.history.replaceState,docType=/<\!DOCTYPE[^>]*>/i,tagso=/<(html|head|link)([\s\>])/gi,tagsod=/<(body)([\s\>])/gi,tagsc=/<\/(html|head|body|link)\>/gi,div12='<div class="ajy-$1"$2',divid12='<div id="ajy-$1"$2',linki='<link rel="stylesheet" type="text/css" href="*" />',scri='<script src="*"><\/script>',linkr='link[href*="!"]',scrr='script[src*="!"]',inlineclass="ajy-inline";let pages,memory,cache1,getPage,fn,scripts,detScripts,addAll,Rq,frms,offsets,scrolly,hApi,pronto,slides,doc=document,bdy,qa=(t,e=doc)=>e.querySelectorAll(t),qs=(t,e=doc)=>e.querySelector(t),_parse=(t,e)=>(e=document.createElement("div"),e.insertAdjacentHTML("afterbegin",t),e.firstElementChild);function _trigger(t,e){let s=document.createEvent("HTMLEvents");s.initEvent("pronto."+t,!0,!1),s.data=e||Rq.a("e"),window.dispatchEvent(s)}function _internal(t){return!!t&&("object"==typeof t&&(t=t.href),""===t||(t.substring(0,rootUrl.length)===rootUrl||!t.iO(":")))}function _copyAttributes(t,e,s){s&&[...t.attributes].forEach(e=>t.removeAttribute(e.name)),[...e.attributes].forEach(e=>t.setAttribute(e.nodeName,e.nodeValue))}function _on(t,e,s,r=document){r.addEventListener(t,function(t){for(var r=t.target;r&&r!=this;r=r.parentNode)if(r.matches(e)){s(r,t);break}},!!t.iO("mo"))}function Hints(t){var e=!("string"!=typeof t||!t.length)&&t.split(", ");this.find=(t=>!(!t||!e)&&e.some(e=>t.iO(e)))}function lg(t){gsettings.verbosity&&console&&console.log(t)}class classCache1{constructor(){let t=!1;this.a=function(e){return e?"string"==typeof e?("f"===e?(pages.a("f"),lg("Cache flushed")):t=pages.a(memory.a(e)),t):"object"==typeof e?t=e:void 0:t}}}class classMemory{constructor(t){let e=0,s=gsettings.memoryoff;this.a=function(t){return e||(e=new Hints(s)),!(!t||!0===s)&&(!1===s?t:!e.find(t)&&t)}}}class classPages{constructor(){let t=[],e=-1;this.a=function(r){if("string"==typeof r)if("f"===r)t=[];else if(-1!==(e=s(r)))return t[e][1];if("object"==typeof r&&(-1===(e=s(r))?t.push(r):t[e]=r),"boolean"==typeof r)return!1};let s=e=>t.findIndex(t=>t[0]==e)}}class classGetPage{constructor(){let t=0,e=0,s=0,r="",n=0,i=0,a=0;this.a=function(n,l,d){if(!n)return cache1.a();if(n.iO("/")){if(e=l,s==n)return;return c(n)}if("+"===n)return s=l,e=d,c(l,!0);if("a"!==n){if("s"===n)return(i?1:0)+r;if("-"===n)return o(l);if("x"===n)return t;if(cache1.a())return"body"===n?qs("#ajy-"+n,cache1.a()):"script"===n?qa(n,cache1.a()):qs("title"===n?n:".ajy-"+n,cache1.a())}else i>0&&(u(),a.abort())};let o=t=>(pass++,d(t),qa("body > script").forEach(t=>!!t.classList.contains(inlineclass)&&t.parentNode.removeChild(t)),scripts.a(!0),scripts.a("s"),scripts.a("c")),c=(t,r)=>(t.iO("#")&&(t=t.split("#")[0]),Rq.a("is")||!cache1.a(t)?p(t,r):(s=0,e?e():void 0)),l=(t,e)=>{if(e){var s=e.cloneNode(!0);qa("script",s).forEach(t=>t.parentNode.removeChild(t)),_copyAttributes(t,s,!0),t.innerHTML=s.innerHTML}else{lg("Inserting placeholder for ID: "+t.getAttribute("id"));var r=t.tagName.toLowerCase();t.parentNode.replaceChild(_parse("<"+r+" id='"+t.getAttribute("id")+"'></"+r+">"),t)}},d=t=>cache1.a()&&!f(t)&&t.forEach(function(t){l(t,qs("#"+t.getAttribute("id"),cache1.a()))}),f=t=>"body"==t[0].tagName.toLowerCase()&&(l(bdy,qs("#ajy-body",cache1.a())),1),p=(e,s)=>{var n=Rq.a("is");r=s?"p":"c",a=new AbortController,i++,fetch(e,{method:n?"POST":"GET",cache:"default",mode:"same-origin",headers:{"X-Requested-With":"XMLHttpRequest"},body:n?Rq.a("d"):null,signal:a.signal}).then(r=>{if(r.ok&&h(r))return t=r,r.text();s||(location.href=e,u(),pronto.a(0,currentURL))}).then(s=>{if(u(1),s)return t.responseText=s,g(e,s)}).catch(t=>{if("AbortError"!==t.name)try{return _trigger("error",t),lg("Response text : "+t.message),g(e,t.message,t)}catch(t){}}).finally(()=>i--)},u=t=>(s=0,t?0:e=0),g=(t,s,r)=>cache1.a(_parse(y(s)))&&(pages.a([t,cache1.a()]),1)&&e&&e(r),h=t=>(n=t.headers.get("content-type"))&&(n.iO("html")||n.iO("form-")),y=t=>document.createElement("html").innerHTML=m(t).trim(),m=t=>String(t).replace(docType,"").replace(tagso,div12).replace(tagsod,divid12).replace(tagsc,"</div>")}}class Ajaxify{constructor(t){let e,s,r,n,i,a=this;a.init=(()=>{e=Object.assign({pluginon:!0,deltas:!0,verbosity:0},t),s=e.elements,r=e.pluginon,n=e.deltas,i=e.verbosity;var c=t;return c&&"string"==typeof c?pronto.a(0,c):("complete"===document.readyState?o():window.onload=(()=>o()),a)});let o=()=>{gsettings=Object.assign(dsettings,e),pages=new classPages,pronto=new classPronto,c(e)&&(pronto.a(s,"i"),n&&scripts.a("1"))},c=t=>api&&r?(lg("Ajaxify loaded..."),(scripts=new classScripts).a("i"),cache1=new classCache1,memory=new classMemory,fn=getPage=new classGetPage,detScripts=new classDetScripts,addAll=new classAddAll,Rq=new classRq,!0):(lg("Gracefully exiting..."),!1);a.init()}}class classScripts{constructor(){let $s=!1,inlhints=0,skphints=0,txt=0,canonical=gsettings.canonical,inline=gsettings.inline,inlinehints=gsettings.inlinehints,inlineskip=gsettings.inlineskip,inlineappend=gsettings.inlineappend,style=gsettings.style;this.a=function(t){return"i"===t?($s||($s={}),inlhints||(inlhints=new Hints(inlinehints)),skphints||(skphints=new Hints(inlineskip)),!0):"s"===t?_allstyle($s.y):"1"===t?(detScripts.a($s),_addScripts($s)):"c"===t?!(!canonical||!$s.can)&&$s.can.getAttribute("href"):"d"===t?detScripts.a($s):t&&"object"==typeof t?_onetxt(t):void(scripts.a("d")||_addScripts($s))};let _allstyle=t=>!style||!t||(qa("style",qs("head")).forEach(t=>t.parentNode.removeChild(t)),t.forEach(t=>_addstyle(t.textContent))),_onetxt=t=>!(txt=t.textContent).iO(").ajaxify(")&&!txt.iO("new Ajaxify(")&&(inline&&!skphints.find(txt)||t.classList.contains("ajaxy")||inlhints.find(txt))&&_addtxt(t),_addtxt=$s=>{if(txt&&txt.length){if(inlineappend||$s.getAttribute("type")&&!$s.getAttribute("type").iO("text/javascript"))try{return _apptxt($s)}catch(t){}try{eval(txt)}catch(t){lg("Error in inline script : "+txt+"\nError code : "+t)}}},_apptxt=t=>{let e=document.createElement("script");_copyAttributes(e,t),e.classList.add(inlineclass);try{e.appendChild(document.createTextNode(t.textContent))}catch(s){e.text=t.textContent}return qs("body").appendChild(e)},_addstyle=t=>qs("head").appendChild(_parse("<style>"+t+"</style>")),_addScripts=t=>(addAll.a(t.c,"href"),addAll.a(t.j,"src"))}}class classDetScripts{constructor(){let t=0,e=0,s=0;this.a=function(n){if(!(t=pass?fn.a("head"):qs("head")))return!0;e=qa(pass?".ajy-link":"link",t),s=pass?fn.a("script"):qa("script"),n.c=r(e,"stylesheet"),n.y=qa("style",t),n.can=r(e,"canonical"),n.j=s};let r=(t,e)=>Array.prototype.filter.call(t,t=>t.getAttribute("rel").iO(e))}}class classAddAll{constructor(){let t=[],e=[],s=[],r=0,n=0,i=0,a=gsettings.deltas,o=gsettings.asyncdef,c=gsettings.alwayshints;this.a=function(o,g){if(i||(i=new Hints(c)),o.length){if("n"===a)return!0;if(r=g,!a)return l(o);t="href"==r?e:s,pass?o.forEach(function(e){var s=e;if(n=s.getAttribute(r),f(s))return u(),void p(s);n?t.some(t=>t==n)||(t.push(n),p(s)):"href"==r||s.classList.contains("no-ajaxy")||scripts.a(s)}):d(o)}};let l=t=>t.forEach(t=>p(t)),d=e=>e.forEach(e=>(n=e.getAttribute(r))?t.push(n):0),f=t=>"always"==t.getAttribute("data-class")||i.find(n),p=t=>{if(n=t.getAttribute(r),"href"==r)return qs("head").appendChild(_parse(linki.replace("*",n)));if(!n)return scripts.a(t);var e=document.createElement("script");e.async=o,_copyAttributes(e,t),qs("head").appendChild(e)},u=()=>qa(("href"==r?linkr:scrr).replace("!",n)).forEach(t=>t.parentNode.removeChild(t))}}class classRq{constructor(){let t=0,e=0,s=0,r=0,n=0,i=0,a=!1;this.a=function(c,l,d){if("="===c)return l?i===currentURL||i===a:i===currentURL;if("!"===c)return a=i;if("?"===c){let t=fn.a("s");return t.iO("0")||l||fn.a("a"),"1c"!==t||!l}if("v"===c){if(!l)return!1;if(o(l,d),!_internal(i))return!1;c="i"}return"i"===c?(t=!1,e=null,s=!0,r=!1,i):"h"===c?(l&&("string"==typeof l&&(n=0),i=l.href?l.href:l),i):"e"===c?(l&&o(l,d),n||i):"p"===c?(void 0!==l&&(s=l),s):"is"===c?(void 0!==l&&(t=l),t):"d"===c?(l&&(e=l),e):"C"===c?(void 0!==l&&(r=l),r):"c"===c?!r||r===l||l.iO("#")||l.iO("?")?l:r:void 0};let o=(t,e)=>i="string"!=typeof(n=t)?n.currentTarget&&n.currentTarget.href||e&&e.href||n.currentTarget.action||n.originalEvent.state.url:n}}class classFrms{constructor(){let t=0,e=0,s=gsettings.forms;this.a=function(i,a){s&&i&&("d"===i&&(e=a),"a"===i&&e.forEach(e=>{Array.prototype.filter.call(qa(s,e),function(t){let e=t.getAttribute("action");return _internal(e&&e.length>0?e:currentURL)}).forEach(e=>{e.addEventListener("submit",e=>{t=e.target,a=r();var s="get",i=t.getAttribute("method");i.length>0&&"post"==i.toLowerCase()&&(s="post");var o,c=t.getAttribute("action");return o=c&&c.length>0?c:currentURL,Rq.a("v",e),"get"==s?o=n(o,a):(Rq.a("is",!0),Rq.a("d",a)),_trigger("submit",o),pronto.a(0,{href:o}),e.preventDefault(),!1})})}))};let r=()=>{let e=new FormData(t),s=qs("input[name][type=submit]",t);return s&&e.append(s.getAttribute("name"),s.value),e},n=(t,e)=>{let s="";for(var[r,n]of(t.iO("?")&&(t=t.substring(0,t.iO("?"))),e.entries()))s+=`${r}=${encodeURIComponent(n)}&`;return`${t}?${s.slice(0,-1)}`}}}class classOffsets{constructor(){let t=[],e=-1;this.a=function(r){if("string"==typeof r)return r=r.iO("?")?r.split("?")[0]:r,-1===(e=s(r))?0:t[e][1];var n=currentURL,i=n.iO("?")?n.split("?")[0]:n,a=i.iO("#")?i.split("#")[0]:i,o=[a,document.documentElement&&document.documentElement.scrollTop||document.body.scrollTop];-1===(e=s(a))?t.push(o):t[e]=o};let s=e=>t.findIndex(t=>t[0]==e)}}class classScrolly{constructor(){let t=gsettings.scrolltop;this.a=function(s){if(s){var r=s;if("+"!==s&&"!"!==s||(s=currentURL),"+"!==r&&s.iO("#")&&s.iO("#")<s.length-1){let t=qs("#"+s.split("#")[1]);if(!t)return;let r=t.getBoundingClientRect();e(r.top+window.pageYOffset-document.documentElement.clientTop)}else{if("s"===t)return"+"===r&&offsets.a(),void("!"===r&&e(offsets.a(s)));"+"!==r&&t&&e(0)}}};let e=t=>window.scrollTo(0,t)}}class classHApi{constructor(){this.a=function(t,e){t&&(e&&(currentURL=e),"="===t?history.replaceState({url:currentURL},"state-"+currentURL,currentURL):currentURL!==window.location.href&&history.pushState({url:currentURL},"state-"+currentURL,currentURL))}}}class classPronto{constructor(){let t=0,e=0,s=0,r=0,n=gsettings.selector,i=gsettings.prefetchoff,a=gsettings.refresh,o=gsettings.cb,c=gsettings.bodyClasses,l=gsettings.requestDelay,d=gsettings.passCount;this.a=function(e,r){if(r)return"i"===r?(bdy=document.body,e.length||(e="body"),t=qa(e),s||(s=new Hints(i)),frms=new classFrms,gsettings.idleTime&&(slides=new classSlides),scrolly=new classScrolly,offsets=new classOffsets,hApi=new classHApi,f(),e):"object"==typeof r?(Rq.a("h",r),void y()):void(r.iO("/")&&(Rq.a("h",r),y(!0)))};let f=()=>{hApi.a("=",window.location.href),window.addEventListener("popstate",b),!0!==i&&(_on("mouseenter",n,p),_on("mouseleave",n,u),_on("touchstart",n,g)),_on("click",n,h,bdy),frms.a("d",qa("body")),frms.a("a"),frms.a("d",t),gsettings.idleTime&&slides.a("i")},p=(t,e)=>r=setTimeout(()=>g(t,e),150),u=()=>clearTimeout(r),g=(t,e)=>{if(!0!==i&&Rq.a("?",!0)){var r=Rq.a("v",e,t);Rq.a("=",!0)||!r||s.find(r)||fn.a("+",r,()=>!1)}},h=(t,e,s)=>{if(Rq.a("?")){var r=Rq.a("v",e,t);if(r&&!w(t)){if("#"===r.substr(-1))return!0;if(R())return hApi.a("=",r),!0;scrolly.a("+"),(t=>(t.preventDefault(),t.stopPropagation(),t.stopImmediatePropagation()))(e),Rq.a("=")&&hApi.a("="),!a&&Rq.a("=")||y(s)}}},y=t=>{Rq.a("!"),t&&Rq.a("p",!1),_trigger("request"),fn.a(Rq.a("h"),t=>{t&&(lg("Error in _request : "+t),_trigger("error",t)),m()})},m=()=>{_trigger("beforeload"),l?(e&&clearTimeout(e),e=setTimeout(v,l)):v()},b=t=>{var e=window.location.href;Rq.a("i"),Rq.a("h",e),Rq.a("p",!1),scrolly.a("+"),e&&e!==currentURL&&(_trigger("request"),fn.a(e,m))},v=()=>{if(_trigger("load"),c){var e=fn.a("body").getAttribute("class");bdy.setAttribute("class",e||"")}var s,r=Rq.a("h");r=Rq.a("c",r),hApi.a(Rq.a("p")?"+":"=",r),(s=fn.a("title"))&&(qs("title").innerHTML=s.innerHTML),Rq.a("C",fn.a("-",t)),frms.a("a"),scrolly.a("!"),q(r),_trigger("render"),d&&(qs("#"+d).innerHTML="Pass: "+pass),o&&o()},q=t=>{t="/"+t.replace(rootUrl,""),void 0!==window.ga?window.ga("send","pageview",t):void 0!==window._gaq&&window._gaq.push(["_trackPageview",t])},w=t=>{var e=Rq.a("h"),s=Rq.a("e"),r=s.currentTarget.target||t.target;return s.which>1||s.metaKey||s.ctrlKey||s.shiftKey||s.altKey||"_blank"===r||e.iO("wp-login")||e.iO("wp-admin")},R=()=>{var t=Rq.a("e");return t.hash&&t.href.replace(t.hash,"")===window.location.href.replace(location.hash,"")||t.href===window.location.href+"#"}}};