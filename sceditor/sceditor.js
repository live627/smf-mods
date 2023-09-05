
/* SCEditor v3.0.0 | (C) 2017, Sam Clarke | sceditor.com/license */

!function(){"use strict";function e(e,t){return typeof t===e}var ye=e.bind(null,"string"),be=e.bind(null,"undefined"),xe=e.bind(null,"function"),o=e.bind(null,"number");function t(e){return!Object.keys(e).length}function we(e,t){var n=e===!!e,o=n?2:1,r=n?t:e,i=n&&e;function a(e){return null!==e&&"object"==typeof e&&Object.getPrototypeOf(e)===Object.prototype}for(;o<arguments.length;o++){var l,s=arguments[o];for(l in s){var c,u=r[l],d=s[l],f="__proto__"===l||"constructor"===l;be(d)||f||(c=a(d),f=Array.isArray(d),i&&(c||f)?(c=a(u)===c&&Array.isArray(u)===f,r[l]=we(!0,c?u:f?[]:{},d)):r[l]=d)}}return r}function Te(e,t){t=e.indexOf(t);-1<t&&e.splice(t,1)}function Ce(t,n){if(Array.isArray(t)||"length"in t&&o(t.length))for(var e=0;e<t.length;e++)n(e,t[e]);else Object.keys(t).forEach(function(e){n(e,t[e])})}var r={},Se=1,ke=3;function i(e){return e=parseFloat(e),isFinite(e)?e:0}function De(e,t,n){var o=(n||document).createElement(e);return Ce(t||{},function(e,t){"style"===e?o.style.cssText=t:e in o?o[e]=t:o.setAttribute(e,t)}),o}function Ee(e,t){for(var n=e||{};(n=n.parentNode)&&!/(9|11)/.test(n.nodeType);)if(!t||je(n,t))return n}function Ne(e,t){return je(e,t)?e:Ee(e,t)}function Me(e){e.parentNode&&e.parentNode.removeChild(e)}function Ae(e,t){e.appendChild(t)}function Re(e,t){return e.querySelectorAll(t)}var _e=!0;function Fe(n,e,o,r,i){e.split(" ").forEach(function(e){var t;ye(o)?(t=r["_sce-event-"+e+o]||function(e){for(var t=e.target;t&&t!==n;){if(je(t,o))return void r.call(t,e);t=t.parentNode}},r["_sce-event-"+e+o]=t):(t=o,i=r),n.addEventListener(e,t,i||!1)})}function Oe(n,e,o,r,i){e.split(" ").forEach(function(e){var t;ye(o)?t=r["_sce-event-"+e+o]:(t=o,i=r),n.removeEventListener(e,t,i||!1)})}function He(e,t,n){if(arguments.length<3)return e.getAttribute(t);null==n?ze(e,t):e.setAttribute(t,n)}function ze(e,t){e.removeAttribute(t)}function Le(e){Pe(e,"display","none")}function Ie(e){Pe(e,"display","")}function Be(e){(Ze(e)?Le:Ie)(e)}function Pe(n,e,t){if(arguments.length<3){if(ye(e))return 1===n.nodeType?getComputedStyle(n)[e]:null;Ce(e,function(e,t){Pe(n,e,t)})}else{var o=(t||0===t)&&!isNaN(t);n.style[e]=o?t+"px":t}}function Ue(e,t,n){var o=arguments.length,r={};if(e.nodeType===Se)return 1===o?(Ce(e.attributes,function(e,t){/^data\-/i.test(t.name)&&(r[t.name.substr(5)]=t.value)}),r):2===o?He(e,"data-"+t):void He(e,"data-"+t,String(n))}function je(e,t){var n=!1;return n=e&&e.nodeType===Se?(e.matches||e.msMatchesSelector||e.webkitMatchesSelector).call(e,t):n}function We(e,t){return t.parentNode.insertBefore(e,t)}function a(e){return e.className.trim().split(/\s+/)}function Ve(e,t){return je(e,"."+t)}function qe(e,t){var n=a(e);n.indexOf(t)<0&&n.push(t),e.className=n.join(" ")}function Ge(e,t){var n=a(e);Te(n,t),e.className=n.join(" ")}function $e(e,t,n){((n=be(n)?!Ve(e,t):n)?qe:Ge)(e,t)}function Ye(e,t){if(be(t)){var n=getComputedStyle(e),o=i(n.paddingLeft)+i(n.paddingRight),n=i(n.borderLeftWidth)+i(n.borderRightWidth);return e.offsetWidth-o-n}Pe(e,"width",t)}function Ke(e,t){if(be(t)){var n=getComputedStyle(e),o=i(n.paddingTop)+i(n.paddingBottom),n=i(n.borderTopWidth)+i(n.borderBottomWidth);return e.offsetHeight-o-n}Pe(e,"height",t)}function Xe(e,t,n){var o;xe(window.CustomEvent)?o=new CustomEvent(t,{bubbles:!0,cancelable:!0,detail:n}):(o=e.ownerDocument.createEvent("CustomEvent")).initCustomEvent(t,!0,!0,n),e.dispatchEvent(o)}function Ze(e){return e.getClientRects().length}function Je(e,t,n,o,r){for(e=r?e.lastChild:e.firstChild;e;){var i=r?e.previousSibling:e.nextSibling;if(!n&&!1===t(e)||!o&&!1===Je(e,t,n,o,r)||n&&!1===t(e))return!1;e=i}}function Qe(e,t,n,o){Je(e,t,n,o,!0)}function et(e,t){var n=(t=t||document).createDocumentFragment(),o=De("div",{},t);for(o.innerHTML=e;o.firstChild;)Ae(n,o.firstChild);return n}function tt(e){return e&&(!je(e,"p,div")||e.className||He(e,"style")||!t(Ue(e)))}function nt(e,t){var n=De(t,{},e.ownerDocument);for(Ce(e.attributes,function(e,t){try{He(n,t.name,t.value)}catch(e){}});e.firstChild;)Ae(n,e.firstChild);return e.parentNode.replaceChild(n,e),n}var l="|body|hr|p|div|h1|h2|h3|h4|h5|h6|address|pre|form|table|tbody|thead|tfoot|th|tr|td|li|ol|ul|blockquote|center|details|section|article|aside|nav|main|header|hgroup|footer|fieldset|dl|dt|dd|figure|figcaption|";function ot(e){return!!/11?|9/.test(e.nodeType)&&"|iframe|area|base|basefont|br|col|frame|hr|img|input|wbr|isindex|link|meta|param|command|embed|keygen|source|track|object|".indexOf("|"+e.nodeName.toLowerCase()+"|")<0}function rt(e,t){var n=(e||{}).nodeType||ke;return n!==Se?n===ke:"code"===(e=e.tagName.toLowerCase())?!t:l.indexOf("|"+e+"|")<0}function s(e,t){t.style&&e.style&&(t.style.cssText=e.style.cssText+t.style.cssText)}function it(e){Je(e,function(e){var t,n,o,r=!rt(e,!0);r&&rt(e.parentNode,!0)&&(t=c(o=function(e){for(;rt(e.parentNode,!0);)e=e.parentNode;return e}(e),e),s(o,n=e),We(t,o),We(n,o)),r&&je(e,"ul,ol")&&je(e.parentNode,"ul,ol")&&(o="li",r=(r=e).previousElementSibling,(r=!o||!r||je(r,o)?r:null)||We(r=De("li"),e),Ae(r,e))})}function u(e,t){return e?(t?e.previousSibling:e.nextSibling)||u(e.parentNode,t):null}function at(e){var t,n,o,r,i,a,l=Pe(e,"whiteSpace"),s=/line$/i.test(l),c=e.firstChild;if(!/pre(\-wrap)?$/i.test(l))for(;c;){if(i=c.nextSibling,t=c.nodeValue,(a=c.nodeType)===Se&&c.firstChild&&at(c),a===ke){for(n=u(c),o=u(c,!0),a=!1;Ve(o,"sceditor-ignore");)o=u(o,!0);if(rt(c)&&o){for(r=o;r.lastChild;)for(r=r.lastChild;Ve(r,"sceditor-ignore");)r=u(r,!0);a=r.nodeType===ke?/[\t\n\r ]$/.test(r.nodeValue):!rt(r)}t=t.replace(/\u200B/g,""),o&&rt(o)&&!a||(t=t.replace(s?/^[\t ]+/:/^[\t\n\r ]+/,"")),(t=!n||!rt(n)?t.replace(s?/[\t ]+$/:/[\t\n\r ]+$/,""):t).length?c.nodeValue=t.replace(s?/[\t ]+/g:/[\t\n\r ]+/g," "):Me(c)}c=i}}function c(e,t){var n=e.ownerDocument.createRange();return n.setStartBefore(e),n.setEndAfter(t),n.extractContents()}function lt(e){for(var t=0,n=0;e;)t+=e.offsetLeft,n+=e.offsetTop,e=e.offsetParent;return{left:t,top:n}}function d(e,t){var n=e.style;return r[t]||(r[t]=t.replace(/^-ms-/,"ms-").replace(/-(\w)/g,function(e,t){return t.toUpperCase()})),n=n[t=r[t]],"textAlign"===t&&(n=n||Pe(e,t),Pe(e.parentNode,t)===n||"block"!==Pe(e,"display")||je(e,"hr,th"))?"":n}function st(e,t){var n=e.attributes.length;if(n===t.attributes.length){for(;n--;){var o=e.attributes[n];if("style"===o.name?!function(e,t){var n=e.style.length;if(n===t.style.length){for(;n--;){var o=e.style[n];if(e.style[o]!==t.style[o])return}return 1}}(e,t):o.value!==He(t,o.name))return}return 1}}function ct(e){for(;e.firstChild;)We(e.firstChild,e);Me(e)}var ut={locale:He(document.documentElement,"lang")||"en",charset:"utf-8",emoticonsCompat:!1,emoticonsEnabled:!0,emoticonsRoot:"",width:null,height:null,resizeEnabled:!0,resizeMinWidth:null,resizeMinHeight:null,resizeMaxHeight:null,resizeMaxWidth:null,resizeHeight:!0,resizeWidth:!0,dateFormat:"year-month-day",toolbarContainer:null,enablePasteFiltering:!1,disablePasting:!1,readOnly:!1,rtl:!1,autofocus:!1,autofocusEnd:!0,autoExpand:!1,autoUpdate:!1,spellcheck:!0,runWithoutWysiwygSupport:!1,startInSourceMode:!1,id:null,plugins:"",zIndex:null,bbcodeTrim:!1,disableBlockRemove:!1,allowedIframeUrls:[],parserOptions:{},dropDownCss:{}},f=/^(https?|s?ftp|mailto|spotify|skype|ssh|teamspeak|tel):|(\/\/)|data:image\/(png|bmp|gif|p?jpe?g);/i;function dt(e){return e.replace(/([\-.*+?^=!:${}()|\[\]\/\\])/g,"\\$1")}function ft(e,t){if(!e)return e;var n={"&":"&amp;","<":"&lt;",">":"&gt;","  ":"&nbsp; ","\r\n":"<br />","\r":"<br />","\n":"<br />"};return!1!==t&&(n['"']="&#34;",n["'"]="&#39;",n["`"]="&#96;"),e=e.replace(/ {2}|\r\n|[&<>\r\n'"`]/g,function(e){return n[e]||e})}var p={html:'<!DOCTYPE html><html{attrs}><head><meta http-equiv="Content-Type" content="text/html;charset={charset}" /><link rel="stylesheet" type="text/css" href="{style}" /></head><body contenteditable="true" {spellcheck}><p></p></body></html>',toolbarButton:'<a class="sceditor-button sceditor-button-{name}" data-sceditor-command="{name}" unselectable="on"><div unselectable="on">{dispName}</div></a>',emoticon:'<img src="{url}" data-sceditor-emoticon="{key}" alt="{key}" title="{tooltip}" />',fontOpt:'<a class="sceditor-font-option" href="#" data-font="{font}"><font face="{font}">{font}</font></a>',sizeOpt:'<a class="sceditor-fontsize-option" data-size="{size}" href="#"><font size="{size}">{size}</font></a>',image:'<div><label for="image">{url}</label> <input type="text" class="input_text" id="image" dir="ltr" /></div><div><label for="width">{width}</label> <input type="text" class="input_text" id="width" size="2" dir="ltr" /></div><div><label for="height">{height}</label> <input type="text" class="input_text" id="height" size="2" dir="ltr" /></div><div><input type="button" class="button_submit button" value="{insert}" /></div>',email:'<div><label for="email">{label}</label> <input type="text" class="input_text" id="email" dir="ltr" /></div><div><label for="des">{desc}</label> <input type="text" class="input_text" id="des" /></div><div><input type="button" class="button_submit button" value="{insert}" /></div>',link:'<div><label for="link">{url}</label> <input type="text" class="input_text" id="link" dir="ltr" /></div><div><label for="des">{desc}</label> <input type="text" class="input_text" id="des" /></div><div><input type="button" class="button_submit button" value="{ins}" /></div>'};function pt(e,t,n){var o=p[e];return Object.keys(t).forEach(function(e){o=o.replace(new RegExp(dt("{"+e+"}"),"g"),t[e])}),o=n?et(o):o}function n(e){if("mozHidden"in document)for(var t,n=e.getBody();n;){if((t=n).firstChild)t=t.firstChild;else{for(;t&&!t.nextSibling;)t=t.parentNode;t=t&&t.nextSibling}3===n.nodeType&&/[\n\r\t]+/.test(n.nodeValue)&&(/^pre/.test(Pe(n.parentNode,"whiteSpace"))||Me(n)),n=t}}var mt={bold:{exec:"bold",tooltip:"Bold",shortcut:"Ctrl+B"},italic:{exec:"italic",tooltip:"Italic",shortcut:"Ctrl+I"},underline:{exec:"underline",tooltip:"Underline",shortcut:"Ctrl+U"},strike:{exec:"strikethrough",tooltip:"Strikethrough"},subscript:{exec:"subscript",tooltip:"Subscript"},superscript:{exec:"superscript",tooltip:"Superscript"},left:{state:function(e){if(e=e&&3===e.nodeType?e.parentNode:e){var t="ltr"===Pe(e,"direction"),e=Pe(e,"textAlign");return"left"===e||e===(t?"start":"end")}},exec:"justifyleft",tooltip:"Align left"},center:{exec:"justifycenter",tooltip:"Center"},right:{state:function(e){if(e=e&&3===e.nodeType?e.parentNode:e){var t="ltr"===Pe(e,"direction"),e=Pe(e,"textAlign");return"right"===e||e===(t?"end":"start")}},exec:"justifyright",tooltip:"Align right"},justify:{exec:"justifyfull",tooltip:"Justify"},font:{_dropDown:function(t,e,n){var o=De("div");Fe(o,"click","a",function(e){n(Ue(this,"font")),t.closeDropDown(!0),e.preventDefault()}),t.opts.fonts.split(",").forEach(function(e){Ae(o,pt("fontOpt",{font:e},!0))}),t.createDropDown(e,"font-picker",o)},exec:function(e){var t=this;mt.font._dropDown(t,e,function(e){t.execCommand("fontname",e)})},tooltip:"Font Name"},size:{_dropDown:function(t,e,n){var o=De("div");Fe(o,"click","a",function(e){n(Ue(this,"size")),t.closeDropDown(!0),e.preventDefault()});for(var r=1;r<=7;r++)Ae(o,pt("sizeOpt",{size:r},!0));t.createDropDown(e,"fontsize-picker",o)},exec:function(e){var t=this;mt.size._dropDown(t,e,function(e){t.execCommand("fontsize",e)})},tooltip:"Font Size"},color:{_dropDown:function(t,e,n){var o=De("div"),r="",i=mt.color;i._htmlCache||(t.opts.colors.split("|").forEach(function(e){r+='<div class="sceditor-color-column">',e.split(",").forEach(function(e){r+='<a href="#" class="sceditor-color-option" style="background-color: '+e+'" data-color="'+e+'"></a>'}),r+="</div>"}),i._htmlCache=r),Ae(o,et(i._htmlCache)),Fe(o,"click","a",function(e){n(Ue(this,"color")),t.closeDropDown(!0),e.preventDefault()}),t.createDropDown(e,"color-picker",o)},exec:function(e){var t=this;mt.color._dropDown(t,e,function(e){t.execCommand("forecolor",e)})},tooltip:"Font Color"},removeformat:{exec:"removeformat",tooltip:"Remove Formatting"},cut:{exec:"cut",tooltip:"Cut",errorMessage:"Your browser does not allow the cut command. Please use the keyboard shortcut Ctrl/Cmd-X"},copy:{exec:"copy",tooltip:"Copy",errorMessage:"Your browser does not allow the copy command. Please use the keyboard shortcut Ctrl/Cmd-C"},paste:{exec:"paste",tooltip:"Paste",errorMessage:"Your browser does not allow the paste command. Please use the keyboard shortcut Ctrl/Cmd-V"},pastetext:{exec:function(e){var t,n=De("div"),o=this;Ae(n,pt("pastetext",{label:o._("Paste your text inside the following box:"),insert:o._("Insert")},!0)),Fe(n,"click",".button",function(e){(t=Re(n,"#txt")[0].value)&&o.wysiwygEditorInsertText(t),o.closeDropDown(!0),e.preventDefault()}),o.createDropDown(e,"pastetext",n)},tooltip:"Paste Text"},bulletlist:{exec:function(){n(this),this.execCommand("insertunorderedlist")},tooltip:"Bullet list"},orderedlist:{exec:function(){n(this),this.execCommand("insertorderedlist")},tooltip:"Numbered list"},indent:{state:function(e,t){var n;return je(t,"li")||je(t,"ul,ol,menu")&&(t=(n=this.getRangeHelper().selectedRange()).startContainer.parentNode,n=n.endContainer.parentNode,t!==t.parentNode.firstElementChild||je(n,"li")&&n!==n.parentNode.lastElementChild)?0:-1},exec:function(){var e=this.getRangeHelper().getFirstBlockParent();this.focus(),Ne(e,"ul,ol,menu")&&this.execCommand("indent")},tooltip:"Add indent"},outdent:{state:function(e,t){return Ne(t,"ul,ol,menu")?0:-1},exec:function(){Ne(this.getRangeHelper().getFirstBlockParent(),"ul,ol,menu")&&this.execCommand("outdent")},tooltip:"Remove one indent"},table:{exec:function(e){var r=this,i=De("div");Ae(i,pt("table",{rows:r._("Rows:"),cols:r._("Cols:"),insert:r._("Insert")},!0)),Fe(i,"click",".button",function(e){var t=Number(Re(i,"#rows")[0].value),n=Number(Re(i,"#cols")[0].value),o="<table>";0<t&&0<n&&(o+=Array(t+1).join("<tr>"+Array(n+1).join("<td><br /></td>")+"</tr>"),o+="</table>",r.wysiwygEditorInsertHtml(o),r.closeDropDown(!0),e.preventDefault())}),r.createDropDown(e,"inserttable",i)},tooltip:"Insert a table"},horizontalrule:{exec:"inserthorizontalrule",tooltip:"Insert a horizontal rule"},code:{exec:function(){this.wysiwygEditorInsertHtml("<code>","<br /></code>")},tooltip:"Code"},image:{_dropDown:function(t,e,n,o){var r=De("div");Ae(r,pt("image",{url:t._("URL:"),width:t._("Width (optional):"),height:t._("Height (optional):"),insert:t._("Insert")},!0));var i=Re(r,"#image")[0];i.value=n,Fe(r,"click",".button",function(e){i.value&&o(i.value,Re(r,"#width")[0].value,Re(r,"#height")[0].value),t.closeDropDown(!0),e.preventDefault()}),t.createDropDown(e,"insertimage",r)},exec:function(e){var r=this;mt.image._dropDown(r,e,"",function(e,t,n){var o="";t&&(o+=' width="'+parseInt(t,10)+'"'),n&&(o+=' height="'+parseInt(n,10)+'"'),o+=' src="'+ft(e)+'"',r.wysiwygEditorInsertHtml("<img"+o+" />")})},tooltip:"Insert an image"},email:{_dropDown:function(n,e,o){var r=De("div");Ae(r,pt("email",{label:n._("E-mail:"),desc:n._("Description (optional):"),insert:n._("Insert")},!0)),Fe(r,"click",".button",function(e){var t=Re(r,"#email")[0].value;t&&o(t,Re(r,"#des")[0].value),n.closeDropDown(!0),e.preventDefault()}),n.createDropDown(e,"insertemail",r)},exec:function(e){var n=this;mt.email._dropDown(n,e,function(e,t){!n.getRangeHelper().selectedHtml()||t?n.wysiwygEditorInsertHtml('<a href="mailto:'+ft(e)+'">'+ft(t||e)+"</a>"):n.execCommand("createlink","mailto:"+e)})},tooltip:"Insert an email"},link:{_dropDown:function(t,e,n){var o=De("div");Ae(o,pt("link",{url:t._("URL:"),desc:t._("Description (optional):"),ins:t._("Insert")},!0));var r=Re(o,"#link")[0];function i(e){r.value&&n(r.value,Re(o,"#des")[0].value),t.closeDropDown(!0),e.preventDefault()}Fe(o,"click",".button",i),Fe(o,"keypress",function(e){13===e.which&&r.value&&i(e)},_e),t.createDropDown(e,"insertlink",o)},exec:function(e){var n=this;mt.link._dropDown(n,e,function(e,t){t||!n.getRangeHelper().selectedHtml()?n.wysiwygEditorInsertHtml('<a href="'+ft(e)+'">'+ft(t||e)+"</a>"):n.execCommand("createlink",e)})},tooltip:"Insert a link"},unlink:{state:function(){return Ne(this.currentNode(),"a")?0:-1},exec:function(){var e=Ne(this.currentNode(),"a");if(e){for(;e.firstChild;)We(e.firstChild,e);Me(e)}},tooltip:"Unlink"},quote:{exec:function(e,t,n){var o="<blockquote>",r="</blockquote>";t?(o=o+(n=n?"<cite>"+ft(n)+"</cite>":"")+t+r,r=null):""===this.getRangeHelper().selectedHtml()&&(r="<br />"+r),this.wysiwygEditorInsertHtml(o,r)},tooltip:"Insert a Quote"},maximize:{state:function(){return this.maximize()},exec:function(){this.maximize(!this.maximize())},txtExec:function(){this.maximize(!this.maximize())},tooltip:"Maximize",shortcut:"Ctrl+Shift+M"},source:{state:function(){return this.sourceMode()},exec:function(){this.toggleSourceMode()},txtExec:function(){this.toggleSourceMode()},tooltip:"View source",shortcut:"Ctrl+Shift+S"},ignore:{}},m={};function gt(i){function a(e){return"signal"+e.charAt(0).toUpperCase()+e.slice(1)}function e(e,t){e=[].slice.call(e);for(var n,o=a(e.shift()),r=0;r<l.length;r++)if(o in l[r]&&(n=l[r][o].apply(i,e),t))return n}var r=this,l=[];r.call=function(){e(arguments,!1)},r.callOnlyFirst=function(){return e(arguments,!0)},r.hasHandler=function(e){var t=l.length;for(e=a(e);t--;)if(e in l[t])return!0;return!1},r.exists=function(e){return e in m&&("function"==typeof(e=m[e])&&"object"==typeof e.prototype)},r.isRegistered=function(e){if(r.exists(e))for(var t=l.length;t--;)if(l[t]instanceof m[e])return!0;return!1},r.register=function(e){return!(!r.exists(e)||r.isRegistered(e))&&(e=new m[e],l.push(e),"init"in e&&e.init.call(i),!0)},r.deregister=function(e){var t,n=l.length,o=!1;if(!r.isRegistered(e))return o;for(;n--;)l[n]instanceof m[e]&&(o=!0,"destroy"in(t=l.splice(n,1)[0])&&t.destroy.call(i));return o},r.destroy=function(){for(var e=l.length;e--;)"destroy"in l[e]&&l[e].destroy.call(i);l=[],i=null}}gt.plugins=m;var g=function(e,t,n){var o,r,i,a,l,s="",c=e.startContainer,u=e.startOffset;for(c&&3!==c.nodeType&&(c=c.childNodes[u],u=0),i=a=u;n>s.length&&c&&3===c.nodeType;)o=c.nodeValue,r=n-s.length,l&&(a=o.length,i=0),l=c,c=t?(u=i=Math.max(a-r,0),s=o.substr(i,a-i)+s,l.previousSibling):(u=i+(a=Math.min(r,o.length)),s+=o.substr(i,a),l.nextSibling);return{node:l||c,offset:u,text:s}};function ht(r,e,i){var a,o,l=e||r.contentDocument||r.document,s="sceditor-start-marker",c="sceditor-end-marker",h=this;h.insertHTML=function(e,t){var n,o;if(!h.selectedRange())return!1;for(t&&(e+=h.selectedHtml()+t),o=De("p",{},l),n=l.createDocumentFragment(),o.innerHTML=i(e);o.firstChild;)Ae(n,o.firstChild);h.insertNode(n)},o=function(e,t,n){var o,r=l.createDocumentFragment();if("string"==typeof e?(t&&(e+=h.selectedHtml()+t),r=et(e)):(Ae(r,e),t&&(Ae(r,h.selectedRange().extractContents()),Ae(r,t))),o=r.lastChild){for(;!rt(o.lastChild,!0);)o=o.lastChild;if(ot(o)?o.lastChild||Ae(o,document.createTextNode("​")):o=r,h.removeMarkers(),Ae(o,a(s)),Ae(o,a(c)),n){n=De("div");return Ae(n,r),n.innerHTML}return r}},h.insertNode=function(e,t){var n=o(e,t),e=h.selectedRange(),t=e.commonAncestorContainer;if(!n)return!1;e.deleteContents(),t&&3!==t.nodeType&&!ot(t)?We(n,t):e.insertNode(n),h.restoreRange()},h.cloneSelected=function(){var e=h.selectedRange();if(e)return e.cloneRange()},h.selectedRange=function(){var e,t,n=r.getSelection();if(n){if(n.rangeCount<=0){for(t=l.body;t.firstChild;)t=t.firstChild;(e=l.createRange()).setStartBefore(t),n.addRange(e)}return e=0<n.rangeCount?n.getRangeAt(0):e}},h.hasSelection=function(){var e=r.getSelection();return e&&0<e.rangeCount},h.selectedHtml=function(){var e,t=h.selectedRange();return t?(Ae(e=De("p",{},l),t.cloneContents()),e.innerHTML):""},h.parentNode=function(){var e=h.selectedRange();if(e)return e.commonAncestorContainer},h.getFirstBlockParent=function(e){var t=function(e){return rt(e,!0)?(e=e?e.parentNode:null)&&t(e):e};return t(e||h.parentNode())},h.insertNodeAt=function(e,t){var n=h.selectedRange(),o=h.cloneSelected();if(!o)return!1;o.collapse(e),o.insertNode(t),h.selectRange(n)},a=function(e){h.removeMarker(e);e=De("span",{id:e,className:"sceditor-selection sceditor-ignore",style:"display:none;line-height:0"},l);return e.innerHTML=" ",e},h.insertMarkers=function(){var e=h.selectedRange(),t=a(s);h.removeMarkers(),h.insertNodeAt(!0,t),e&&e.collapsed?t.parentNode.insertBefore(a(c),t.nextSibling):h.insertNodeAt(!1,a(c))},h.getMarker=function(e){return l.getElementById(e)},h.removeMarker=function(e){e=h.getMarker(e);e&&Me(e)},h.removeMarkers=function(){h.removeMarker(s),h.removeMarker(c)},h.saveRange=function(){h.insertMarkers()},h.selectRange=function(e){var t,n=r.getSelection(),o=e.endContainer;if(e.collapsed&&o&&!rt(o,!0)){for(t=o.lastChild;t&&je(t,".sceditor-ignore");)t=t.previousSibling;je(t,"br")&&((o=l.createRange()).setEndAfter(t),o.collapse(!1),h.compare(e,o)&&(e.setStartBefore(t),e.collapse(!0)))}n&&(h.clear(),n.addRange(e))},h.restoreRange=function(){var e,t=h.selectedRange(),n=h.getMarker(s),o=h.getMarker(c);if(!n||!o||!t)return!1;e=n.nextSibling===o,(t=l.createRange()).setStartBefore(n),t.setEndAfter(o),e&&t.collapse(!0),h.selectRange(t),h.removeMarkers()},h.selectOuterText=function(e,t){var n=h.cloneSelected();if(!n)return!1;n.collapse(!1),e=g(n,!0,e),t=g(n,!1,t),n.setStart(e.node,e.offset),n.setEnd(t.node,t.offset),h.selectRange(n)},h.getOuterText=function(e,t){var n=h.cloneSelected();return n?(n.collapse(!e),g(n,e,t).text):""},h.replaceKeyword=function(e,t,n,o,r,i){n||e.sort(function(e,t){return e[0].length-t[0].length});var a,l,s,c,u,d,f,p="(^|[\\s    ])",m=e.length,g=r?1:0,o=o||e[m-1][0].length;for(r&&o++,i=i||"",c=(a=h.getOuterText(!0,o)).length,a+=i,t&&(a+=h.getOuterText(!1,o));m--;)if(d=e[m][0],f=d.length,s=Math.max(0,c-f-g),u=-1,r?(l=a.substr(s).match(new RegExp(p+dt(d)+p)))&&(u=l.index+s+l[1].length):u=a.indexOf(d,s),-1<u&&u<=c&&c<=u+f+g)return u=c-u,h.selectOuterText(u,f-u-(/^\S/.test(i)?1:0)),h.insertHTML(e[m][1]),!0;return!1},h.compare=function(e,t){return t=t||h.selectedRange(),e&&t?0===e.compareBoundaryPoints(Range.END_TO_END,t)&&0===e.compareBoundaryPoints(Range.START_TO_START,t):!e&&!t},h.clear=function(){var e=r.getSelection();e&&(e.removeAllRanges?e.removeAllRanges():e.empty&&e.empty())}}var h,v,y,b=navigator.userAgent,vt=/iPhone|iPod|iPad| wosbrowser\//i.test(b),yt=(v=!!window.document.documentMode,k="-ms-ime-align"in document.documentElement.style,(E=document.createElement("div")).contentEditable=!0,"contentEditable"in document.documentElement&&"true"===E.contentEditable&&(E=/Opera Mobi|Opera Mini/i.test(b),/Android/i.test(b)&&(E=!0,/Safari/.test(b)&&(E=!(h=/Safari\/(\d+)/.exec(b))||!h[1]||h[1]<534)),/ Silk\//i.test(b)&&(E=!(h=/AppleWebKit\/(\d+)/.exec(b))||!h[1]||h[1]<534),vt&&(E=/OS [0-4](_\d)+ like Mac/i.test(b)),/Firefox/i.test(b)&&(E=!1),/OneBrowser/i.test(b)&&(E=!1),"UCWEB"===navigator.vendor&&(E=!1),!(E=v||k?!0:E))),x=Object.hasOwnProperty,w=Object.setPrototypeOf,T=Object.isFrozen,C=Object.getPrototypeOf,S=Object.getOwnPropertyDescriptor,bt=Object.freeze,k=Object.seal,D=Object.create,E="undefined"!=typeof Reflect&&Reflect,N=(N=E.apply)||function(e,t,n){return e.apply(t,n)},bt=bt||function(e){return e},k=k||function(e){return e},M=(M=E.construct)||function(e,t){return new(Function.prototype.bind.apply(e,[null].concat(function(e){if(Array.isArray(e)){for(var t=0,n=Array(e.length);t<e.length;t++)n[t]=e[t];return n}return Array.from(e)}(t))))},xt=A(Array.prototype.forEach),wt=A(Array.prototype.pop),Tt=A(Array.prototype.push),Ct=A(String.prototype.toLowerCase),St=A(String.prototype.match),kt=A(String.prototype.replace),Dt=A(String.prototype.indexOf),Et=A(String.prototype.trim),Nt=A(RegExp.prototype.test),Mt=(y=TypeError,function(){for(var e=arguments.length,t=Array(e),n=0;n<e;n++)t[n]=arguments[n];return M(y,t)});function A(r){return function(e){for(var t=arguments.length,n=Array(1<t?t-1:0),o=1;o<t;o++)n[o-1]=arguments[o];return N(r,e,n)}}function At(e,t){w&&w(e,null);for(var n=t.length;n--;){var o,r=t[n];"string"!=typeof r||(o=Ct(r))!==r&&(T(t)||(t[n]=o),r=o),e[r]=!0}return e}function Rt(e){var t=D(null),n=void 0;for(n in e)N(x,e,[n])&&(t[n]=e[n]);return t}function _t(e,t){for(;null!==e;){var n=S(e,t);if(n){if(n.get)return A(n.get);if("function"==typeof n.value)return A(n.value)}e=C(e)}return null}var Ft=bt(["a","abbr","acronym","address","area","article","aside","audio","b","bdi","bdo","big","blink","blockquote","body","br","button","canvas","caption","center","cite","code","col","colgroup","content","data","datalist","dd","decorator","del","details","dfn","dialog","dir","div","dl","dt","element","em","fieldset","figcaption","figure","font","footer","form","h1","h2","h3","h4","h5","h6","head","header","hgroup","hr","html","i","img","input","ins","kbd","label","legend","li","main","map","mark","marquee","menu","menuitem","meter","nav","nobr","ol","optgroup","option","output","p","picture","pre","progress","q","rp","rt","ruby","s","samp","section","select","shadow","small","source","spacer","span","strike","strong","style","sub","summary","sup","table","tbody","td","template","textarea","tfoot","th","thead","time","tr","track","tt","u","ul","var","video","wbr"]),Ot=bt(["svg","a","altglyph","altglyphdef","altglyphitem","animatecolor","animatemotion","animatetransform","circle","clippath","defs","desc","ellipse","filter","font","g","glyph","glyphref","hkern","image","line","lineargradient","marker","mask","metadata","mpath","path","pattern","polygon","polyline","radialgradient","rect","stop","style","switch","symbol","text","textpath","title","tref","tspan","view","vkern"]),Ht=bt(["feBlend","feColorMatrix","feComponentTransfer","feComposite","feConvolveMatrix","feDiffuseLighting","feDisplacementMap","feDistantLight","feFlood","feFuncA","feFuncB","feFuncG","feFuncR","feGaussianBlur","feMerge","feMergeNode","feMorphology","feOffset","fePointLight","feSpecularLighting","feSpotLight","feTile","feTurbulence"]),zt=bt(["animate","color-profile","cursor","discard","fedropshadow","feimage","font-face","font-face-format","font-face-name","font-face-src","font-face-uri","foreignobject","hatch","hatchpath","mesh","meshgradient","meshpatch","meshrow","missing-glyph","script","set","solidcolor","unknown","use"]),Lt=bt(["math","menclose","merror","mfenced","mfrac","mglyph","mi","mlabeledtr","mmultiscripts","mn","mo","mover","mpadded","mphantom","mroot","mrow","ms","mspace","msqrt","mstyle","msub","msup","msubsup","mtable","mtd","mtext","mtr","munder","munderover"]),It=bt(["maction","maligngroup","malignmark","mlongdiv","mscarries","mscarry","msgroup","mstack","msline","msrow","semantics","annotation","annotation-xml","mprescripts","none"]),Bt=bt(["#text"]),Pt=bt(["accept","action","align","alt","autocapitalize","autocomplete","autopictureinpicture","autoplay","background","bgcolor","border","capture","cellpadding","cellspacing","checked","cite","class","clear","color","cols","colspan","controls","controlslist","coords","crossorigin","datetime","decoding","default","dir","disabled","disablepictureinpicture","disableremoteplayback","download","draggable","enctype","enterkeyhint","face","for","headers","height","hidden","high","href","hreflang","id","inputmode","integrity","ismap","kind","label","lang","list","loading","loop","low","max","maxlength","media","method","min","minlength","multiple","muted","name","noshade","novalidate","nowrap","open","optimum","pattern","placeholder","playsinline","poster","preload","pubdate","radiogroup","readonly","rel","required","rev","reversed","role","rows","rowspan","spellcheck","scope","selected","shape","size","sizes","span","srclang","start","src","srcset","step","style","summary","tabindex","title","translate","type","usemap","valign","value","width","xmlns"]),Ut=bt(["accent-height","accumulate","additive","alignment-baseline","ascent","attributename","attributetype","azimuth","basefrequency","baseline-shift","begin","bias","by","class","clip","clippathunits","clip-path","clip-rule","color","color-interpolation","color-interpolation-filters","color-profile","color-rendering","cx","cy","d","dx","dy","diffuseconstant","direction","display","divisor","dur","edgemode","elevation","end","fill","fill-opacity","fill-rule","filter","filterunits","flood-color","flood-opacity","font-family","font-size","font-size-adjust","font-stretch","font-style","font-variant","font-weight","fx","fy","g1","g2","glyph-name","glyphref","gradientunits","gradienttransform","height","href","id","image-rendering","in","in2","k","k1","k2","k3","k4","kerning","keypoints","keysplines","keytimes","lang","lengthadjust","letter-spacing","kernelmatrix","kernelunitlength","lighting-color","local","marker-end","marker-mid","marker-start","markerheight","markerunits","markerwidth","maskcontentunits","maskunits","max","mask","media","method","mode","min","name","numoctaves","offset","operator","opacity","order","orient","orientation","origin","overflow","paint-order","path","pathlength","patterncontentunits","patterntransform","patternunits","points","preservealpha","preserveaspectratio","primitiveunits","r","rx","ry","radius","refx","refy","repeatcount","repeatdur","restart","result","rotate","scale","seed","shape-rendering","specularconstant","specularexponent","spreadmethod","startoffset","stddeviation","stitchtiles","stop-color","stop-opacity","stroke-dasharray","stroke-dashoffset","stroke-linecap","stroke-linejoin","stroke-miterlimit","stroke-opacity","stroke","stroke-width","style","surfacescale","systemlanguage","tabindex","targetx","targety","transform","text-anchor","text-decoration","text-rendering","textlength","type","u1","u2","unicode","values","viewbox","visibility","version","vert-adv-y","vert-origin-x","vert-origin-y","width","word-spacing","wrap","writing-mode","xchannelselector","ychannelselector","x","x1","x2","xmlns","y","y1","y2","z","zoomandpan"]),jt=bt(["accent","accentunder","align","bevelled","close","columnsalign","columnlines","columnspan","denomalign","depth","dir","display","displaystyle","encoding","fence","frame","height","href","id","largeop","length","linethickness","lspace","lquote","mathbackground","mathcolor","mathsize","mathvariant","maxsize","minsize","movablelimits","notation","numalign","open","rowalign","rowlines","rowspacing","rowspan","rspace","rquote","scriptlevel","scriptminsize","scriptsizemultiplier","selection","separator","separators","stretchy","subscriptshift","supscriptshift","symmetric","voffset","width","xmlns"]),Wt=bt(["xlink:href","xml:id","xlink:title","xml:space","xmlns:xlink"]),Vt=k(/\{\{[\s\S]*|[\s\S]*\}\}/gm),qt=k(/<%[\s\S]*|[\s\S]*%>/gm),Gt=k(/^data-[\-\w.\u00B7-\uFFFF]/),$t=k(/^aria-[\-\w]+$/),Yt=k(/^(?:(?:(?:f|ht)tps?|mailto|tel|callto|cid|xmpp):|[^a-z]|[a-z+.\-]+(?:[^a-z+.\-:]|$))/i),Kt=k(/^(?:\w+script|data):/i),Xt=k(/[\u0000-\u0020\u00A0\u1680\u180E\u2000-\u2029\u205F\u3000]/g),Zt="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e};function Jt(e){if(Array.isArray(e)){for(var t=0,n=Array(e.length);t<e.length;t++)n[t]=e[t];return n}return Array.from(e)}var Qt=function(){return"undefined"==typeof window?null:window},en=function(e,t){if("object"!==(void 0===e?"undefined":Zt(e))||"function"!=typeof e.createPolicy)return null;var n=null,o="data-tt-policy-suffix",r="dompurify"+((n=t.currentScript&&t.currentScript.hasAttribute(o)?t.currentScript.getAttribute(o):n)?"#"+n:"");try{return e.createPolicy(r,{createHTML:function(e){return e}})}catch(e){return console.warn("TrustedTypes policy "+r+" could not be created."),null}},tn=function t(e){function u(e){return t(e)}var l=0<arguments.length&&void 0!==e?e:Qt();if(u.version="2.2.6",u.removed=[],!l||!l.document||9!==l.document.nodeType)return u.isSupported=!1,u;var s=l.document,i=l.document,c=l.DocumentFragment,n=l.HTMLTemplateElement,d=l.Node,a=l.Element,o=l.NodeFilter,r=l.NamedNodeMap,f=void 0===r?l.NamedNodeMap||l.MozNamedAttrMap:r,p=l.Text,m=l.Comment,g=l.DOMParser,e=l.trustedTypes,r=a.prototype,h=_t(r,"cloneNode"),v=_t(r,"nextSibling"),y=_t(r,"childNodes"),b=_t(r,"parentNode");"function"!=typeof n||(n=i.createElement("template")).content&&n.content.ownerDocument&&(i=n.content.ownerDocument);var x=en(e,s),w=x&&ee?x.createHTML(""):"",T=i.implementation,C=i.createNodeIterator,S=i.getElementsByTagName,k=i.createDocumentFragment,D=s.importNode,E={};try{E=Rt(i).documentMode?i.documentMode:{}}catch(e){}var N={};u.isSupported=T&&void 0!==T.createHTMLDocument&&9!==E;function M(e){ce&&ce===e||(e=Rt(e=e&&"object"===(void 0===e?"undefined":Zt(e))?e:{}),I="ALLOWED_TAGS"in e?At({},e.ALLOWED_TAGS):B,P="ALLOWED_ATTR"in e?At({},e.ALLOWED_ATTR):U,le="ADD_URI_SAFE_ATTR"in e?At(Rt(se),e.ADD_URI_SAFE_ATTR):se,ie="ADD_DATA_URI_TAGS"in e?At(Rt(ae),e.ADD_DATA_URI_TAGS):ae,j="FORBID_TAGS"in e?At({},e.FORBID_TAGS):{},W="FORBID_ATTR"in e?At({},e.FORBID_ATTR):{},A="USE_PROFILES"in e&&e.USE_PROFILES,V=!1!==e.ALLOW_ARIA_ATTR,q=!1!==e.ALLOW_DATA_ATTR,G=e.ALLOW_UNKNOWN_PROTOCOLS||!1,$=e.SAFE_FOR_TEMPLATES||!1,Y=e.WHOLE_DOCUMENT||!1,Z=e.RETURN_DOM||!1,J=e.RETURN_DOM_FRAGMENT||!1,Q=!1!==e.RETURN_DOM_IMPORT,ee=e.RETURN_TRUSTED_TYPE||!1,X=e.FORCE_BODY||!1,te=!1!==e.SANITIZE_DOM,ne=!1!==e.KEEP_CONTENT,oe=e.IN_PLACE||!1,L=e.ALLOWED_URI_REGEXP||L,$&&(q=!1),J&&(Z=!0),A&&(I=At({},[].concat(Jt(Bt))),P=[],!0===A.html&&(At(I,Ft),At(P,Pt)),!0===A.svg&&(At(I,Ot),At(P,Ut),At(P,Wt)),!0===A.svgFilters&&(At(I,Ht),At(P,Ut),At(P,Wt)),!0===A.mathMl&&(At(I,Lt),At(P,jt),At(P,Wt))),e.ADD_TAGS&&At(I=I===B?Rt(I):I,e.ADD_TAGS),e.ADD_ATTR&&At(P=P===U?Rt(P):P,e.ADD_ATTR),e.ADD_URI_SAFE_ATTR&&At(le,e.ADD_URI_SAFE_ATTR),ne&&(I["#text"]=!0),Y&&At(I,["html","head","body"]),I.table&&(At(I,["tbody"]),delete j.tbody),bt&&bt(e),ce=e)}var A,R=Vt,_=qt,F=Gt,O=$t,H=Kt,z=Xt,L=Yt,I=null,B=At({},[].concat(Jt(Ft),Jt(Ot),Jt(Ht),Jt(Lt),Jt(Bt))),P=null,U=At({},[].concat(Jt(Pt),Jt(Ut),Jt(jt),Jt(Wt))),j=null,W=null,V=!0,q=!0,G=!1,$=!1,Y=!1,K=!1,X=!1,Z=!1,J=!1,Q=!0,ee=!1,te=!0,ne=!0,oe=!1,re=At({},["annotation-xml","audio","colgroup","desc","foreignobject","head","iframe","math","mi","mn","mo","ms","mtext","noembed","noframes","noscript","plaintext","script","style","svg","template","thead","title","video","xmp"]),ie=null,ae=At({},["audio","video","img","source","image","track"]),le=null,se=At({},["alt","class","for","id","label","name","pattern","placeholder","summary","title","value","style","xmlns"]),ce=null,ue=i.createElement("form"),de=At({},["mi","mo","mn","ms","mtext"]),fe=At({},["foreignobject","desc","title","annotation-xml"]),pe=At({},Ot);At(pe,Ht),At(pe,zt);var me=At({},Lt);At(me,It);function ge(t){Tt(u.removed,{element:t});try{t.parentNode.removeChild(t)}catch(e){try{t.outerHTML=w}catch(e){t.remove()}}}function he(e,t){try{Tt(u.removed,{attribute:t.getAttributeNode(e),from:t})}catch(e){Tt(u.removed,{attribute:null,from:t})}t.removeAttribute(e)}function ve(e){var t=void 0,n=void 0;X?e="<remove></remove>"+e:n=(o=St(e,/^[\r\n\t ]+/))&&o[0];var o,r=x?x.createHTML(e):e;try{t=(new g).parseFromString(r,"text/html")}catch(e){}return t&&t.documentElement||((o=(t=T.createHTMLDocument("")).body).parentNode.removeChild(o.parentNode.firstElementChild),o.outerHTML=r),e&&n&&t.body.insertBefore(i.createTextNode(n),t.body.childNodes[0]||null),S.call(t,Y?"html":"body")[0]}function ye(e){return C.call(e.ownerDocument||e,e,o.SHOW_ELEMENT|o.SHOW_COMMENT|o.SHOW_TEXT,function(){return o.FILTER_ACCEPT},!1)}function be(e){return"object"===(void 0===d?"undefined":Zt(d))?e instanceof d:e&&"object"===(void 0===e?"undefined":Zt(e))&&"number"==typeof e.nodeType&&"string"==typeof e.nodeName}function xe(e,t,n){N[e]&&xt(N[e],function(e){e.call(u,t,n,ce)})}function we(e){var t;if(xe("beforeSanitizeElements",e,null),!((n=e)instanceof p||n instanceof m||"string"==typeof n.nodeName&&"string"==typeof n.textContent&&"function"==typeof n.removeChild&&n.attributes instanceof f&&"function"==typeof n.removeAttribute&&"function"==typeof n.setAttribute&&"string"==typeof n.namespaceURI&&"function"==typeof n.insertBefore))return ge(e),1;if(St(e.nodeName,/[\u0080-\uFFFF]/))return ge(e),1;var n=Ct(e.nodeName);if(xe("uponSanitizeElement",e,{tagName:n,allowedTags:I}),!be(e.firstElementChild)&&(!be(e.content)||!be(e.content.firstElementChild))&&Nt(/<[/\w]/g,e.innerHTML)&&Nt(/<[/\w]/g,e.textContent))return ge(e),1;if(I[n]&&!j[n])return e instanceof a&&!function(e){var t=b(e);t&&t.tagName||(t={namespaceURI:Ee,tagName:"template"});var n=Ct(e.tagName),o=Ct(t.tagName);return e.namespaceURI===De?t.namespaceURI===Ee?"svg"===n:t.namespaceURI===ke?"svg"===n&&("annotation-xml"===o||de[o]):Boolean(pe[n]):e.namespaceURI===ke?t.namespaceURI===Ee?"math"===n:t.namespaceURI===De?"math"===n&&fe[o]:Boolean(me[n]):e.namespaceURI===Ee&&((t.namespaceURI!==De||fe[o])&&((t.namespaceURI!==ke||de[o])&&(o=At({},["title","style","font","a","script"]),!me[n]&&(o[n]||!pe[n]))))}(e)||("noscript"===n||"noembed"===n)&&Nt(/<\/no(script|embed)/i,e.innerHTML)?(ge(e),1):($&&3===e.nodeType&&(t=e.textContent,t=kt(t,R," "),t=kt(t,_," "),e.textContent!==t&&(Tt(u.removed,{element:e.cloneNode()}),e.textContent=t)),xe("afterSanitizeElements",e,null),0);if(ne&&!re[n])for(var o=b(e),r=y(e),i=r.length-1;0<=i;--i)o.insertBefore(h(r[i],!0),v(e));return ge(e),1}function Te(e,t,n){if(te&&("id"===t||"name"===t)&&(n in i||n in ue))return!1;if(!(q&&Nt(F,t)||V&&Nt(O,t))){if(!P[t]||W[t])return!1;if(!le[t]&&!Nt(L,kt(n,z,""))&&("src"!==t&&"xlink:href"!==t&&"href"!==t||"script"===e||0!==Dt(n,"data:")||!ie[e])&&(!G||Nt(H,kt(n,z,"")))&&n)return!1}return!0}function Ce(e){var t=void 0,n=void 0,o=void 0;xe("beforeSanitizeAttributes",e,null);var r=e.attributes;if(r){for(var i={attrName:"",attrValue:"",keepAttr:!0,allowedAttributes:P},o=r.length;o--;){var a=(t=r[o]).name,l=t.namespaceURI,n=Et(t.value),s=Ct(a);if(i.attrName=s,i.attrValue=n,i.keepAttr=!0,i.forceKeepAttr=void 0,xe("uponSanitizeAttribute",e,i),n=i.attrValue,!i.forceKeepAttr&&(he(a,e),i.keepAttr))if(Nt(/\/>/i,n))he(a,e);else{$&&(n=kt(n,R," "),n=kt(n,_," "));var c=e.nodeName.toLowerCase();if(Te(c,s,n))try{l?e.setAttributeNS(l,a,n):e.setAttribute(a,n),wt(u.removed)}catch(e){}}}xe("afterSanitizeAttributes",e,null)}}function Se(e){var t,n=ye(e);for(xe("beforeSanitizeShadowDOM",e,null);t=n.nextNode();)xe("uponSanitizeShadowNode",t,null),we(t)||(t.content instanceof c&&Se(t.content),Ce(t));xe("afterSanitizeShadowDOM",e,null)}var ke="http://www.w3.org/1998/Math/MathML",De="http://www.w3.org/2000/svg",Ee="http://www.w3.org/1999/xhtml";return u.sanitize=function(e,t){var n,o=void 0,r=void 0,i=void 0;if("string"!=typeof(e=e||"\x3c!--\x3e")&&!be(e)){if("function"!=typeof e.toString)throw Mt("toString is not a function");if("string"!=typeof(e=e.toString()))throw Mt("dirty is not a string, aborting")}if(!u.isSupported){if("object"===Zt(l.toStaticHTML)||"function"==typeof l.toStaticHTML){if("string"==typeof e)return l.toStaticHTML(e);if(be(e))return l.toStaticHTML(e.outerHTML)}return e}if(K||M(t),u.removed=[],"string"==typeof e&&(oe=!1),!oe)if(e instanceof d)1===(t=(o=ve("\x3c!----\x3e")).ownerDocument.importNode(e,!0)).nodeType&&"BODY"===t.nodeName||"HTML"===t.nodeName?o=t:o.appendChild(t);else{if(!Z&&!$&&!Y&&-1===e.indexOf("<"))return x&&ee?x.createHTML(e):e;if(!(o=ve(e)))return Z?null:w}o&&X&&ge(o.firstChild);for(var a=ye(oe?e:o);n=a.nextNode();)3===n.nodeType&&n===r||we(n)||(n.content instanceof c&&Se(n.content),Ce(n),r=n);if(r=null,oe)return e;if(Z){if(J)for(i=k.call(o.ownerDocument);o.firstChild;)i.appendChild(o.firstChild);else i=o;return i=Q?D.call(s,i,!0):i}return e=Y?o.outerHTML:o.innerHTML,$&&(e=kt(e,R," "),e=kt(e,_," ")),x&&ee?x.createHTML(e):e},u.setConfig=function(e){M(e),K=!0},u.clearConfig=function(){ce=null,K=!1},u.isValidAttribute=function(e,t,n){return ce||M({}),e=Ct(e),t=Ct(t),Te(e,t,n)},u.addHook=function(e,t){"function"==typeof t&&(N[e]=N[e]||[],Tt(N[e],t))},u.removeHook=function(e){N[e]&&wt(N[e])},u.removeHooks=function(e){N[e]&&(N[e]=[])},u.removeAllHooks=function(){N={}},u}(),nn=window,on=document,rn=/^image\/(p?jpe?g|gif|png|bmp)$/i;function an(r,e){var a,x,u,i,l,f,d,s,o,c,p,t,m,g,h,v,y,b,n,w,T,C,S,k,D,E,N,M,A,R,_,F,O,H,z,L,I,B,P,U,j,W,V,q,G,$,Y,K,X,Z,J,Q,ee,te,ne,oe,re,ie,ae,le,se=this,ce={},ue=[],de=[],fe={},pe={},me={};se.commands=we(!0,{},e.commands||mt);var ge=se.opts=we(!0,{},ut,e);se.opts.emoticons=e.emoticons||ut.emoticons,Array.isArray(ge.allowedIframeUrls)||(ge.allowedIframeUrls=[]),ge.allowedIframeUrls.push("https://www.youtube-nocookie.com/embed/");var he=tn();function ve(e){return he.sanitize(e,{ADD_TAGS:["iframe"],ADD_ATTR:["allowfullscreen","frameborder","target"]})}he.addHook("uponSanitizeElement",function(e,t){var n=ge.allowedIframeUrls;if("iframe"===t.tagName){for(var o=He(e,"src")||"",r=0;r<n.length;r++){var i=n[r];if(ye(i)&&o.substr(0,i.length)===i)return;if(i.test&&i.test(o))return}Me(e)}}),he.addHook("afterSanitizeAttributes",function(e){"target"in e&&He(e,"data-sce-target",He(e,"target")),ze(e,"target")}),e=function(){r._sceditor=se,ge.locale&&"en"!==ge.locale&&A(),We(x=De("div",{className:"sceditor-container"}),r),Pe(x,"z-index",ge.zIndex),n=r.required,r.required=!1;var e=an.formats[ge.format];a=e?new e:{},g=new gt(se),(ge.plugins||"").split(",").forEach(function(e){g.register(e.trim())}),"init"in a&&a.init.call(se),H(),R(),M(),_(),F(),yt||se.toggleSourceMode(),Y();var t=function(){Oe(nn,"load",t),ge.autofocus&&Q(),le(),X(),g.call("ready"),"onReady"in a&&a.onReady.call(se)};Fe(nn,"load",t),"complete"===on.readyState&&t()},A=function(){var e;(t=an.locale[ge.locale])||(e=ge.locale.split("-"),t=an.locale[e[0]]),t&&t.dateFormat&&(ge.dateFormat=t.dateFormat)},M=function(){s=De("textarea"),i=De("iframe",{frameborder:0,allowfullscreen:!0}),ge.startInSourceMode?(qe(x,"sourceMode"),Le(i)):(qe(x,"wysiwygMode"),Le(s)),ge.spellcheck||He(x,"spellcheck","false"),"https:"===nn.location.protocol&&He(i,"src","about:blank"),Ae(x,i),Ae(x,s),se.dimensions(ge.width||Ye(r),ge.height||Ke(r));var e=vt?" ios":"";(d=i.contentDocument).open(),d.write(pt("html",{attrs:' class="'+e+'"',spellcheck:ge.spellcheck?"":'spellcheck="false"',charset:ge.charset,style:ge.style})),d.close(),f=d.body,l=i.contentWindow,se.readOnly(!!ge.readOnly),vt&&(Ke(f,"100%"),Fe(f,"touchend",se.focus));e=He(r,"tabindex");He(s,"tabindex",e),He(i,"tabindex",e),m=new ht(l,null,ve),Le(r),se.val(r.value);e=ge.placeholder||He(r,"placeholder");e&&(s.placeholder=e,He(f,"placeholder",e))},_=function(){ge.autoUpdate&&(Fe(f,"blur",ae),Fe(s,"blur",ae)),null===ge.rtl&&(ge.rtl="rtl"===Pe(s,"direction")),se.rtl(!!ge.rtl),ge.autoExpand&&(Fe(f,"load",le,_e),Fe(f,"input keyup",le)),ge.resizeEnabled&&O(),He(x,"id",ge.id),se.emoticons(ge.emoticonsEnabled)},F=function(){var e=r.form,t="compositionstart compositionend",n="keydown keyup keypress focus blur contextmenu input",o="onselectionchange"in d?"selectionchange":"keyup focus blur contextmenu mouseup touchend click";Fe(on,"click",G),e&&(Fe(e,"reset",j),Fe(e,"submit",se.updateOriginal,_e)),Fe(f,"keypress",U),Fe(f,"keydown",B),Fe(f,"keydown",P),Fe(f,"keyup",X),Fe(f,"blur",re),Fe(f,"keyup",ie),Fe(f,"paste",z),Fe(f,"cut copy",L),Fe(f,t,V),Fe(f,o,Z),Fe(f,n,q),ge.emoticonsCompat&&nn.getSelection&&Fe(f,"keyup",te),Fe(f,"blur",function(){se.val()||qe(f,"placeholder")}),Fe(f,"focus",function(){Ge(f,"placeholder")}),Fe(s,"blur",re),Fe(s,"keyup",ie),Fe(s,"keydown",B),Fe(s,t,V),Fe(s,n,q),Fe(d,"mousedown",W),Fe(d,o,Z),Fe(d,"keyup",X),Fe(x,"selectionchanged",J),Fe(x,"selectionchanged",Y),Fe(x,"selectionchanged valuechanged nodechanged pasteraw paste",q)},R=function(){var i,a=se.commands,l=(ge.toolbarExclude||"").split(","),e=ge.toolbar.split("||");u=De("div",{className:"sceditor-toolbar",unselectable:"on"}),ge.icons in an.icons&&(D=new an.icons[ge.icons]),Ce(e,function(e,t){var n=De("div",{className:"sceditor-row"});Ce(t.split("|"),function(e,t){i=De("div",{className:"sceditor-group"}),Ce(t.split(","),function(e,t){var n,o,r=a[t];!r||-1<l.indexOf(t)||(n=r.shortcut,o=pt("toolbarButton",{name:t,dispName:se._(r.name||r.tooltip||t)},!0).firstChild,D&&D.create&&D.create(t,o)&&(We(D.create(t),o.firstChild),qe(o,"has-icon")),o._sceTxtMode=!!r.txtExec,o._sceWysiwygMode=!!r.exec,$e(o,"disabled",!r.exec),Fe(o,"click",function(e){Ve(o,"disabled")||N(o,r),Y(),e.preventDefault()}),Fe(o,"mousedown",function(e){se.closeDropDown(),e.preventDefault()}),r.tooltip&&He(o,"title",se._(r.tooltip)+(n?" ("+n+")":"")),n&&se.addShortcut(n,t),r.state?de.push({name:t,state:r.state}):ye(r.exec)&&de.push({name:t,state:r.exec}),Ae(i,o),pe[t]=o)}),i.firstChild&&Ae(n,i)}),n.firstChild&&Ae(u,n)}),Ae(ge.toolbarContainer||x,u)},O=function(){var e=De("div",{className:"sceditor-grip"}),t=De("div",{className:"sceditor-resize-cover"}),n="touchmove mousemove",o="touchcancel touchend mouseup",r=0,i=0,a=0,l=0,s=0,c=0,u=Ye(x),d=Ke(x),f=!1,p=se.rtl(),m=ge.resizeMinHeight||d/1.5,g=ge.resizeMaxHeight||2.5*d,h=ge.resizeMinWidth||u/1.25,v=ge.resizeMaxWidth||1.25*u,y=function(e){l="touchmove"===e.type?(e=nn.event,a=e.changedTouches[0].pageX,e.changedTouches[0].pageY):(a=e.pageX,e.pageY);var t=c+(l-i),n=p?s-(a-r):s+(a-r);0<v&&v<n&&(n=v),0<h&&n<h&&(n=h),ge.resizeWidth||(n=!1),0<g&&g<t&&(t=g),0<m&&t<m&&(t=m),ge.resizeHeight||(t=!1),(n||t)&&se.dimensions(n,t),e.preventDefault()},b=function(e){f&&(f=!1,Le(t),Ge(x,"resizing"),Oe(on,n,y),Oe(on,o,b),e.preventDefault())};D&&D.create&&((u=D.create("grip"))&&(Ae(e,u),qe(e,"has-icon"))),Ae(x,e),Ae(x,t),Le(t),Fe(e,"touchstart mousedown",function(e){i="touchstart"===e.type?(e=nn.event,r=e.touches[0].pageX,e.touches[0].pageY):(r=e.pageX,e.pageY),s=Ye(x),c=Ke(x),f=!0,qe(x,"resizing"),Ie(t),Fe(on,n,y),Fe(on,o,b),e.preventDefault()})},H=function(){var e=ge.emoticons,n=ge.emoticonsRoot||"";Ce(me=e?we({},e.more,e.dropdown,e.hidden):me,function(e,t){me[e]=pt("emoticon",{key:e,url:n+(t.url||t),tooltip:t.tooltip||e}),ge.emoticonsEnabled&&ue.push(De("img",{src:n+(t.url||t)}))})},Q=function(){var e,t=f.firstChild,n=!!ge.autofocusEnd;if(Ze(x)){if(se.sourceMode())return e=n?s.value.length:0,void s.setSelectionRange(e,e);if(at(f),n)for((t=f.lastChild)||(t=De("p",{},d),Ae(f,t));t.lastChild;)je(t=t.lastChild,"br")&&t.previousSibling&&(t=t.previousSibling);e=d.createRange(),ot(t)?e.selectNodeContents(t):(e.setStartBefore(t),n&&e.setStartAfter(t)),e.collapse(!n),m.selectRange(e),y=e,n&&(f.scrollTop=f.scrollHeight),se.focus()}},se.readOnly=function(e){return"boolean"!=typeof e?!s.readonly:(f.contentEditable=!e,s.readonly=!e,$(e),se)},se.rtl=function(e){var t=e?"rtl":"ltr";return"boolean"!=typeof e?"rtl"===He(s,"dir"):(He(f,"dir",t),He(s,"dir",t),Ge(x,"rtl"),Ge(x,"ltr"),qe(x,t),D&&D.rtl&&D.rtl(e),se)},$=function(n){var o=se.inSourceMode()?"_sceTxtMode":"_sceWysiwygMode";Ce(pe,function(e,t){$e(t,"disabled",n||!t[o])})},se.width=function(e,t){return e||0===e?(se.dimensions(e,null,t),se):Ye(x)},se.dimensions=function(e,t,n){return t=!(!t&&0!==t)&&t,!1===(e=!(!e&&0!==e)&&e)&&!1===t?{width:se.width(),height:se.height()}:(!1!==e&&(!1!==n&&(ge.width=e),Ye(x,e)),!1!==t&&(!1!==n&&(ge.height=t),Ke(x,t)),se)},se.height=function(e,t){return e||0===e?(se.dimensions(null,e,t),se):Ke(x)},se.maximize=function(e){var t="sceditor-maximize";return be(e)?Ve(x,t):((e=!!e)&&(S=nn.pageYOffset),$e(on.documentElement,t,e),$e(on.body,t,e),$e(x,t,e),se.width(e?"100%":ge.width,!1),se.height(e?"100%":ge.height,!1),e||nn.scrollTo(0,S),le(),se.focus(),se)},le=function(){ge.autoExpand&&!C&&(C=setTimeout(se.expandToContent,200))},se.expandToContent=function(e){var t,n;se.maximize()||(clearTimeout(C),C=!1,T||(t=ge.resizeMinHeight||ge.height||Ke(r),T={min:t,max:ge.resizeMaxHeight||2*t}),(n=on.createRange()).selectNodeContents(f),t=n.getBoundingClientRect(),n=d.documentElement.clientHeight-1,t=t.bottom-t.top,n=se.height()+1+(t-n),e||-1===T.max||(n=Math.min(n,T.max)),se.height(Math.ceil(Math.max(n,T.min))))},se.destroy=function(){var e;g&&(g.destroy(),g=m=null,o&&Me(o),Oe(on,"click",G),(e=r.form)&&(Oe(e,"reset",j),Oe(e,"submit",se.updateOriginal,_e)),Me(s),Me(u),Me(x),delete r._sceditor,Ie(r),r.required=n)},se.createDropDown=function(e,t,n){t="sceditor-"+t;se.closeDropDown(),o&&Ve(o,t)||(e=we({top:e.offsetTop,left:e.offsetLeft,marginTop:e.clientHeight},ge.dropDownCss),Pe(o=De("div",{className:"sceditor-dropdown "+t}),e),Ae(o,n),Ae(document.body,o),Fe(o,"click focusin",function(e){e.stopPropagation()}),!o||(n=Re(o,"input,textarea")[0])&&n.focus())},G=function(e){3!==e.which&&o&&!e.defaultPrevented&&(ae(),se.closeDropDown())},L=function(e){var t=m.selectedRange();if(t){for(var n,o,r=De("div",{},d),i=t.commonAncestorContainer;i&&rt(i,!0);)i.nodeType===Se&&(o=i.cloneNode(),r.firstChild&&Ae(o,r.firstChild),Ae(r,o),n=n||o),i=i.parentNode;Ae(n||r,t.cloneContents()),e.clipboardData.setData("text/html",r.innerHTML),e.clipboardData.setData("text/plain",t.toString()),"cut"===e.type&&t.deleteContents(),e.preventDefault()}},z=function(e){var t,n,o=f,r=e.clipboardData;if(r){var i={},a=r.types,l=r.items;e.preventDefault();for(var s=0;s<a.length;s++){if(a.indexOf("text/html")<0&&nn.FileReader&&l&&rn.test(l[s].type))return t=r.items[s].getAsFile(),n=void 0,(n=new FileReader).onload=function(e){I({html:'<img src="'+e.target.result+'" />'})},void n.readAsDataURL(t);i[a[s]]=r.getData(a[s])}i.text=i["text/plain"],i.html=ve(i["text/html"]),I(i)}else if(!k){var c=o.scrollTop;for(m.saveRange(),k=on.createDocumentFragment();o.firstChild;)Ae(k,o.firstChild);setTimeout(function(){var e=o.innerHTML;o.innerHTML="",Ae(o,k),o.scrollTop=c,k=!1,m.restoreRange(),I({html:ve(e)})},0)}},I=function(e){var t=De("div",{},d);g.call("pasteRaw",e),Xe(x,"pasteraw",e),e.html?(t.innerHTML=ve(e.html),it(t)):t.innerHTML=ft(e.text||"");e={val:t.innerHTML};"fragmentToSource"in a&&(e.val=a.fragmentToSource(e.val,d,h)),g.call("paste",e),Xe(x,"paste",e),"fragmentToHtml"in a&&(e.val=a.fragmentToHtml(e.val,h)),g.call("pasteHtml",e);t=m.getFirstBlockParent();se.wysiwygEditorInsertHtml(e.val,null,!0),it(t),function e(t){if(t.nodeType===Se){for(var n=t.parentNode,o=t.tagName,r=t.childNodes.length;r--;)e(t.childNodes[r]);if(rt(t)){for(r=t.style.length;r--;){var i=t.style[r];Pe(n,i)===Pe(t,i)&&t.style.removeProperty(i)}if(!t.style.length)if(ze(t,"style"),"FONT"===o&&(Pe(t,"fontFamily").toLowerCase()===Pe(n,"fontFamily").toLowerCase()&&ze(t,"face"),Pe(t,"color")===Pe(n,"color")&&ze(t,"color"),Pe(t,"fontSize")===Pe(n,"fontSize")&&ze(t,"size")),!t.attributes.length&&/SPAN|FONT/.test(o))ct(t);else if(/B|STRONG|EM|SPAN|FONT/.test(o))for(var a=/B|STRONG/.test(o),l="EM"===o;n&&rt(n)&&(!a||/bold|700/i.test(Pe(n,"fontWeight")))&&(!l||"italic"===Pe(n,"fontStyle"));){if((n.tagName===o||a&&/B|STRONG/.test(n.tagName))&&st(n,t)){ct(t);break}n=n.parentNode}var s=t.nextSibling;s&&s.tagName===o&&st(s,t)&&(Ae(t,s),ct(s))}}}(t)},se.closeDropDown=function(e){o&&(Me(o),o=null),!0===e&&se.focus()},se.wysiwygEditorInsertHtml=function(e,t,n){var o=Ke(i);se.focus(),!n&&Ne(v,"code")||(m.insertHTML(e,t),m.saveRange(),E(),Ie(n=Re(f,"#sceditor-end-marker")[0]),e=f.scrollTop,t=lt(n).top+1.5*n.offsetHeight-o,Le(n),(e<t||t+o<e)&&(f.scrollTop=t),oe(!1),m.restoreRange(),X())},se.wysiwygEditorInsertText=function(e,t){se.wysiwygEditorInsertHtml(ft(e),ft(t))},se.insertText=function(e,t){return se.inSourceMode()?se.sourceEditorInsertText(e,t):se.wysiwygEditorInsertText(e,t),se},se.sourceEditorInsertText=function(e,t){var n,o=s.selectionStart,r=s.selectionEnd,i=s.scrollTop;s.focus(),n=s.value,t&&(e+=n.substring(o,r)+t),s.value=n.substring(0,o)+e+n.substring(r,n.length),s.selectionStart=o+e.length-(t?t.length:0),s.selectionEnd=s.selectionStart,s.scrollTop=i,s.focus(),oe()},se.getRangeHelper=function(){return m},se.sourceEditorCaret=function(e){return s.focus(),e?(s.selectionStart=e.start,s.selectionEnd=e.end,this):{start:s.selectionStart,end:s.selectionEnd}},se.val=function(e,t){return ye(e)?(se.inSourceMode()?se.setSourceEditorValue(e):(!1!==t&&"toHtml"in a&&(e=a.toHtml(e)),se.setWysiwygEditorValue(e)),se):se.inSourceMode()?se.getSourceEditorValue(!1):se.getWysiwygEditorValue(t)},se.insert=function(e,t,n,o,r){return se.inSourceMode()?se.sourceEditorInsertText(e,t):(t&&(i=m.selectedHtml(),e+=(i=!1!==n&&"fragmentToSource"in a?a.fragmentToSource(i,d,h):i)+t),!1!==n&&"fragmentToHtml"in a&&(e=a.fragmentToHtml(e,h)),!1!==n&&!0===r&&(e=e.replace(/&lt;/g,"<").replace(/&gt;/g,">").replace(/&amp;/g,"&")),se.wysiwygEditorInsertHtml(e)),se;var i},se.getWysiwygEditorValue=function(e){for(var t,n=De("div",{},d),o=f.childNodes,r=0;r<o.length;r++)Ae(n,o[r].cloneNode(!0));return Ae(f,n),it(n),Me(n),t=n.innerHTML,t=!1!==e&&a.hasOwnProperty("toSource")?a.toSource(t,d):t},se.getBody=function(){return f},se.getContentAreaContainer=function(){return i},se.getSourceEditorValue=function(e){var t=s.value;return t=!1!==e&&"toHtml"in a?a.toHtml(t):t},se.setWysiwygEditorValue=function(e){e=e||"<p><br /></p>",f.innerHTML=ve(e),E(),X(),oe(),le()},se.setSourceEditorValue=function(e){s.value=e,oe()},se.updateOriginal=function(){r.value=se.val()},E=function(){var e,l,s,c,t,u,d;ge.emoticonsEnabled&&(e=f,l=me,s=ge.emoticonsCompat,c=e.ownerDocument,t="(^|\\s| | | | |$)",u=[],d={},Ee(e,"code")||(Ce(l,function(e){d[e]=new RegExp(t+dt(e)+t),u.push(e)}),u.sort(function(e,t){return t.length-e.length}),function e(t){for(t=t.firstChild;t;){if(t.nodeType!==Se||je(t,"code")||e(t),t.nodeType===ke)for(var n=0;n<u.length;n++){var o,r=t.nodeValue,i=u[n],a=s?r.search(d[i]):r.indexOf(i);-1<a&&(o=r.indexOf(i,a),a=et(l[i],c),i=r.substr(o+i.length),a.appendChild(c.createTextNode(i)),t.nodeValue=r.substr(0,o),t.parentNode.insertBefore(a,t.nextSibling))}t=t.nextSibling}}(e)))},se.inSourceMode=function(){return Ve(x,"sourceMode")},se.sourceMode=function(e){var t=se.inSourceMode();return"boolean"!=typeof e?t:((t&&!e||!t&&e)&&se.toggleSourceMode(),se)},se.toggleSourceMode=function(){var e=se.inSourceMode();!yt&&e||(e||(m.saveRange(),m.clear()),se.blur(),e?se.setWysiwygEditorValue(se.getSourceEditorValue()):se.setSourceEditorValue(se.getWysiwygEditorValue()),Be(s),Be(i),$e(x,"wysiwygMode",e),$e(x,"sourceMode",!e),$(),Y(),se.focus())},K=function(){return s.focus(),s.value.substring(s.selectionStart,s.selectionEnd)},N=function(e,t){se.inSourceMode()?t.txtExec&&(Array.isArray(t.txtExec)?se.sourceEditorInsertText.apply(se,t.txtExec):t.txtExec.call(se,e,K())):t.exec&&(xe(t.exec)?t.exec.call(se,e):se.execCommand(t.exec,t.hasOwnProperty("execParam")?t.execParam:null))},se.execCommand=function(e,t){var n=!1,o=se.commands[e];if(se.focus(),!Ne(m.parentNode(),"code")){try{n=d.execCommand(e,!1,t)}catch(e){}!n&&o&&o.errorMessage&&alert(se._(o.errorMessage)),Y()}},Z=function(){function e(){if(l.getSelection()&&l.getSelection().rangeCount<=0)y=null;else if(m&&!m.compare(y)){if((y=m.cloneSelected())&&y.collapsed){var e=y.startContainer,t=y.startOffset;for(t&&e.nodeType!==ke&&(e=e.childNodes[t]);e&&e.parentNode!==f;)e=e.parentNode;e&&rt(e,!0)&&(m.saveRange(),n=d,Je(f,function(e){rt(e,!0)?(o||We(o=De("p",{},n),e),e.nodeType===ke&&""===e.nodeValue||Ae(o,e)):o=null},!1,!0),m.restoreRange())}Xe(x,"selectionchanged")}var n,o;b=!1}b||(b=!0,"onselectionchange"in d?e():setTimeout(e,100))},J=function(){var e,t=m.parentNode();h!==t&&(e=h,h=t,v=m.getFirstBlockParent(t),Xe(x,"nodechanged",{oldNode:e,newNode:h}))},se.currentNode=function(){return h},se.currentBlockNode=function(){return v},Y=function(){var e,t,n="active",o=d,r=se.sourceMode();if(se.readOnly())Ce(Re(u,n),function(e,t){Ge(t,n)});else{r||(t=m.parentNode(),e=m.getFirstBlockParent(t));for(var i=0;i<de.length;i++){var a=0,l=pe[de[i].name],s=de[i].state,c=r&&!l._sceTxtMode||!r&&!l._sceWysiwygMode;if(ye(s)){if(!r)try{-1<(a=o.queryCommandEnabled(s)?0:-1)&&(a=o.queryCommandState(s)?1:0)}catch(e){}}else c||(a=s.call(se,t,e));$e(l,"disabled",c||a<0),$e(l,n,0<a)}D&&D.update&&D.update(r,t,e)}},U=function(e){var t,n,o;e.defaultPrevented||(se.closeDropDown(),13!==e.which||!je(v,"li,ul,ol")&&tt(v)&&(t=De("br",{},d),m.insertNode(t),(o=(n=t.parentNode).lastChild)&&o.nodeType===ke&&""===o.nodeValue&&(Me(o),o=n.lastChild),!rt(n,!0)&&o===t&&rt(t.previousSibling)&&m.insertHTML("<br>"),e.preventDefault()))},X=function(){Qe(f,function(e){if(e.nodeType===Se&&!/inline/.test(Pe(e,"display"))&&!je(e,".sceditor-nlf")&&tt(e)){var t=De("p",{},d);return t.className="sceditor-nlf",t.innerHTML="<br />",Ae(f,t),!1}if(3===e.nodeType&&!/^\s*$/.test(e.nodeValue)||je(e,"br"))return!1})},j=function(){se.val(r.value)},W=function(){se.closeDropDown()},se._=function(){var n=arguments;return t&&t[n[0]]&&(n[0]=t[n[0]]),n[0].replace(/\{(\d+)\}/g,function(e,t){return void 0!==n[+t+1]?n[+t+1]:"{"+t+"}"})},q=function(t){g&&g.call(t.type+"Event",t,se);var e=(t.target===s?"scesrc":"scewys")+t.type;ce[e]&&ce[e].forEach(function(e){e.call(se,t)})},se.bind=function(e,t,n,o){for(var r,i,a=(e=e.split(" ")).length;a--;)xe(t)&&(r="scewys"+e[a],i="scesrc"+e[a],n||(ce[r]=ce[r]||[],ce[r].push(t)),o||(ce[i]=ce[i]||[],ce[i].push(t)),"valuechanged"===e[a]&&(oe.hasHandler=!0));return se},se.unbind=function(e,t,n,o){for(var r=(e=e.split(" ")).length;r--;)xe(t)&&(n||Te(ce["scewys"+e[r]]||[],t),o||Te(ce["scesrc"+e[r]]||[],t));return se},se.blur=function(e,t,n){return xe(e)?se.bind("blur",e,t,n):(se.sourceMode()?s:f).blur(),se},se.focus=function(e,t,n){if(xe(e))se.bind("focus",e,t,n);else if(se.inSourceMode())s.focus();else{if(Re(d,":focus").length)return;var o,n=m.selectedRange();y||Q(),n&&1===n.endOffset&&n.collapsed&&(o=n.endContainer)&&1===o.childNodes.length&&je(o.firstChild,"br")&&(n.setStartBefore(o.firstChild),n.collapse(!0),m.selectRange(n)),l.focus(),f.focus()}return Y(),se},se.keyDown=function(e,t,n){return se.bind("keydown",e,t,n)},se.keyPress=function(e,t,n){return se.bind("keypress",e,t,n)},se.keyUp=function(e,t,n){return se.bind("keyup",e,t,n)},se.nodeChanged=function(e){return se.bind("nodechanged",e,!1,!0)},se.selectionChanged=function(e){return se.bind("selectionchanged",e,!1,!0)},se.valueChanged=function(e,t,n){return se.bind("valuechanged",e,t,n)},ee=function(e){var n=0,o=se.emoticonsCache,t=String.fromCharCode(e.which);Ne(v,"code")||(o||(o=[],Ce(me,function(e,t){o[n++]=[e,t]}),o.sort(function(e,t){return e[0].length-t[0].length}),se.emoticonsCache=o,se.longestEmoticonCode=o[o.length-1][0].length),m.replaceKeyword(se.emoticonsCache,!0,!0,se.longestEmoticonCode,ge.emoticonsCompat,t)&&(ge.emoticonsCompat&&/^\s$/.test(t)||e.preventDefault()))},te=function(){!function(e,t){var n=/[^\s\xA0\u2002\u2003\u2009]+/,o=e&&Re(e,"img[data-sceditor-emoticon]");if(e&&o.length)for(var r=0;r<o.length;r++){var i,a,l,s,c=o[r],u=c.parentNode,d=c.previousSibling,f=c.nextSibling;(d&&n.test(d.nodeValue.slice(-1))||f&&n.test((f.nodeValue||"")[0]))&&(a=-1,l=(i=t.cloneSelected()).startContainer,s=d.nodeValue||"",s+=Ue(c,"sceditor-emoticon"),l===f&&(a=s.length+i.startOffset),l===e&&e.childNodes[i.startOffset]===f&&(a=s.length),l===d&&(a=i.startOffset),(f=!f||f.nodeType!==ke?u.insertBefore(u.ownerDocument.createTextNode(""),f):f).insertData(0,s),Me(d),Me(c),-1<a&&(i.setStart(f,a),i.collapse(!0),t.selectRange(i)))}}(v,m)},se.emoticons=function(e){return e||!1===e?((ge.emoticonsEnabled=e)?(Fe(f,"keypress",ee),se.sourceMode()||(m.saveRange(),E(),oe(!1),m.restoreRange())):(Ce(Re(f,"img[data-sceditor-emoticon]"),function(e,t){var n=Ue(t,"sceditor-emoticon"),n=d.createTextNode(n);t.parentNode.replaceChild(n,t)}),Oe(f,"keypress",ee),oe()),se):ge.emoticonsEnabled},se.css=function(e){return w||(w=De("style",{id:"inline"},d),Ae(d.head,w)),ye(e)?(w.styleSheet?w.styleSheet.cssText=e:w.innerHTML=e,se):w.styleSheet?w.styleSheet.cssText:w.innerHTML},B=function(e){var t=[],n={"`":"~",1:"!",2:"@",3:"#",4:"$",5:"%",6:"^",7:"&",8:"*",9:"(",0:")","-":"_","=":"+",";":": ","'":'"',",":"<",".":">","/":"?","\\":"|","[":"{","]":"}"},o={109:"-",110:"del",111:"/",96:"0",97:"1",98:"2",99:"3",100:"4",101:"5",102:"6",103:"7",104:"8",105:"9"},r=e.which,i={8:"backspace",9:"tab",13:"enter",19:"pause",20:"capslock",27:"esc",32:"space",33:"pageup",34:"pagedown",35:"end",36:"home",37:"left",38:"up",39:"right",40:"down",45:"insert",46:"del",91:"win",92:"win",93:"select",96:"0",97:"1",98:"2",99:"3",100:"4",101:"5",102:"6",103:"7",104:"8",105:"9",106:"*",107:"+",109:"-",110:".",111:"/",112:"f1",113:"f2",114:"f3",115:"f4",116:"f5",117:"f6",118:"f7",119:"f8",120:"f9",121:"f10",122:"f11",123:"f12",144:"numlock",145:"scrolllock",186:";",187:"=",188:",",189:"-",190:".",191:"/",192:"`",219:"[",220:"\\",221:"]",222:"'"}[r]||String.fromCharCode(r).toLowerCase();(e.ctrlKey||e.metaKey)&&t.push("ctrl"),e.altKey&&t.push("alt"),e.shiftKey&&(t.push("shift"),o[r]?i=o[r]:n[i]&&(i=n[i])),i&&(r<16||18<r)&&t.push(i),t=t.join("+"),fe[t]&&!1===fe[t].call(se)&&(e.stopPropagation(),e.preventDefault())},se.addShortcut=function(e,t){return e=e.toLowerCase(),ye(t)?fe[e]=function(){return N(pe[t],se.commands[t]),!1}:fe[e]=t,se},se.removeShortcut=function(e){return delete fe[e.toLowerCase()],se},P=function(e){var t,n,o;if(!ge.disableBlockRemove&&8===e.which&&(n=m.selectedRange())&&(t=n.startContainer,0===n.startOffset&&(o=ne())&&!je(o,"body"))){for(;t!==o;){for(;t.previousSibling;)if((t=t.previousSibling).nodeType!==ke||t.nodeValue)return;if(!(t=t.parentNode))return}se.clearBlockFormatting(o),e.preventDefault()}},ne=function(){for(var e=v;!tt(e)||rt(e,!0);)if(!(e=e.parentNode)||je(e,"body"))return;return e},se.clearBlockFormatting=function(e){return!(e=e||ne())||je(e,"body")||(m.saveRange(),e.className="",He(e,"style",""),je(e,"p,div,td")||nt(e,"p"),m.restoreRange()),se},oe=function(e){var t,n,o;g&&(g.hasHandler("valuechangedEvent")||oe.hasHandler)&&(o=!(n=se.sourceMode())&&m.hasSelection(),e=(c=!1)!==e&&!d.getElementById("sceditor-start-marker"),p&&(clearTimeout(p),p=!1),o&&e&&m.saveRange(),(t=n?s.value:f.innerHTML)!==oe.lastVal&&(oe.lastVal=t,Xe(x,"valuechanged",{rawValue:n?se.val():t})),o&&e&&m.removeMarkers())},re=function(){p&&oe()},ie=function(e){var t=e.which,n=ie.lastChar,e=13===n||32===n,n=8===n||46===n;ie.lastChar=t,c||(13===t||32===t?e?ie.triggerNext=!0:oe():8===t||46===t?n?ie.triggerNext=!0:oe():ie.triggerNext&&(oe(),ie.triggerNext=!1),clearTimeout(p),p=setTimeout(function(){c||oe()},1500))},V=function(e){(c=/start/i.test(e.type))||oe()},ae=function(){se.updateOriginal()},e()}an.locale={},an.formats={},an.icons={},an.command={get:function(e){return mt[e]||null},set:function(e,t){return!(!e||!t)&&((t=we(mt[e]||{},t)).remove=function(){an.command.remove(e)},mt[e]=t,this)},remove:function(e){return mt[e]&&delete mt[e],this}},window.sceditor={command:an.command,commands:mt,defaultOptions:ut,ios:vt,isWysiwygSupported:yt,regexEscape:dt,escapeEntities:ft,escapeUriScheme:function(e){var t,n=window.location;return e&&/^[^\/]*:/i.test(e)&&!f.test(e)?((t=n.pathname.split("/")).pop(),n.protocol+"//"+n.host+t.join("/")+"/"+e):e},dom:{css:Pe,attr:He,removeAttr:ze,is:je,closest:Ne,width:Ye,height:Ke,traverse:Je,rTraverse:Qe,parseHTML:et,hasStyling:tt,convertElement:nt,blockLevelList:l,canHaveChildren:ot,isInline:rt,copyCSS:s,fixNesting:it,findCommonAncestor:function(e,t){for(;e=e.parentNode;)if((n=e)!==(o=t)&&n.contains&&n.contains(o))return e;var n,o},getSibling:u,removeWhiteSpace:at,extractContents:c,getOffset:lt,getStyle:d,hasStyle:function(e,t,n){return!!(t=d(e,t))&&(!n||t===n||Array.isArray(n)&&-1<n.indexOf(t))}},locale:an.locale,icons:an.icons,utils:{each:Ce,isEmptyObject:t,extend:we},plugins:gt.plugins,formats:an.formats,create:function(e,t){t=t||{},Ee(e,".sceditor-container")||(t.runWithoutWysiwygSupport||yt)&&new an(e,t)},instance:function(e){return e._sceditor}}}();

/* SCEditor v3.1.1 | (C) 2017, Sam Clarke | sceditor.com/license */

!function(t){"use strict";var f=t.escapeEntities,o=t.escapeUriScheme,d=t.dom,e=t.utils,h=d.css,m=d.attr,p=d.is,n=e.extend,g=e.each,v="data-sceditor-emoticon",l=t.command.get,b={always:1,never:2,auto:3},r={bold:{txtExec:["[b]","[/b]"]},italic:{txtExec:["[i]","[/i]"]},underline:{txtExec:["[u]","[/u]"]},strike:{txtExec:["[s]","[/s]"]},subscript:{txtExec:["[sub]","[/sub]"]},superscript:{txtExec:["[sup]","[/sup]"]},left:{txtExec:["[left]","[/left]"]},center:{txtExec:["[center]","[/center]"]},right:{txtExec:["[right]","[/right]"]},justify:{txtExec:["[justify]","[/justify]"]},font:{txtExec:function(t){var e=this;l("font")._dropDown(e,t,function(t){e.insertText("[font="+t+"]","[/font]")})}},size:{txtExec:function(t){var e=this;l("size")._dropDown(e,t,function(t){e.insertText("[size="+t+"]","[/size]")})}},color:{txtExec:function(t){var e=this;l("color")._dropDown(e,t,function(t){e.insertText("[color="+t+"]","[/color]")})}},bulletlist:{txtExec:function(t,e){this.insertText("[ul]\n[li]"+e.split(/\r?\n/).join("[/li]\n[li]")+"[/li]\n[/ul]")}},orderedlist:{txtExec:function(t,e){this.insertText("[ol]\n[li]"+e.split(/\r?\n/).join("[/li]\n[li]")+"[/li]\n[/ol]")}},table:{txtExec:["[table][tr][td]","[/td][/tr][/table]"]},horizontalrule:{txtExec:["[hr]"]},code:{txtExec:["[code]","[/code]"]},image:{txtExec:function(t,e){var i=this;l("image")._dropDown(i,t,e,function(t,e,n){var r="";e&&(r+=" width="+e),n&&(r+=" height="+n),i.insertText("[img"+r+"]"+t+"[/img]")})}},email:{txtExec:function(t,n){var r=this;l("email")._dropDown(r,t,function(t,e){r.insertText("[email="+t+"]"+(e||n||t)+"[/email]")})}},link:{txtExec:function(t,n){var r=this;l("link")._dropDown(r,t,function(t,e){r.insertText("[url="+t+"]"+(e||n||t)+"[/url]")})}},quote:{txtExec:["[quote]","[/quote]"]},youtube:{txtExec:function(t){var e=this;l("youtube")._dropDown(e,t,function(t){e.insertText("[youtube]"+t+"[/youtube]")})}},rtl:{txtExec:["[rtl]","[/rtl]"]},ltr:{txtExec:["[ltr]","[/ltr]"]}},y={b:{tags:{b:null,strong:null},styles:{"font-weight":["bold","bolder","401","700","800","900"]},format:"[b]{0}[/b]",html:"<strong>{0}</strong>"},i:{tags:{i:null,em:null},styles:{"font-style":["italic","oblique"]},format:"[i]{0}[/i]",html:"<em>{0}</em>"},u:{tags:{u:null},styles:{"text-decoration":["underline"]},format:"[u]{0}[/u]",html:"<u>{0}</u>"},s:{tags:{s:null,strike:null},styles:{"text-decoration":["line-through"]},format:"[s]{0}[/s]",html:"<s>{0}</s>"},sub:{tags:{sub:null},format:"[sub]{0}[/sub]",html:"<sub>{0}</sub>"},sup:{tags:{sup:null},format:"[sup]{0}[/sup]",html:"<sup>{0}</sup>"},font:{tags:{font:{face:null}},styles:{"font-family":null},quoteType:b.never,format:function(t,e){var n;return"[font="+w(n=!p(t,"font")||!(n=m(t,"face"))?h(t,"font-family"):n)+"]"+e+"[/font]"},html:'<font face="{defaultattr}">{0}</font>'},size:{tags:{font:{size:null}},styles:{"font-size":null},format:function(t,e){var n=m(t,"size"),r=2;return-1<(n=n||h(t,"fontSize")).indexOf("px")?((n=+n.replace("px",""))<12&&(r=1),15<n&&(r=3),17<n&&(r=4),23<n&&(r=5),31<n&&(r=6),47<n&&(r=7)):r=n,"[size="+r+"]"+e+"[/size]"},html:'<font size="{defaultattr}">{!0}</font>'},color:{tags:{font:{color:null}},styles:{color:null},quoteType:b.never,format:function(t,e){var n;return"[color="+s(n=!p(t,"font")||!(n=m(t,"color"))?t.style.color||h(t,"color"):n)+"]"+e+"[/color]"},html:function(t,e,n){return'<font color="'+f(s(e.defaultattr),!0)+'">'+n+"</font>"}},ul:{tags:{ul:null},breakStart:!0,isInline:!1,skipLastLineBreak:!0,format:"[ul]{0}[/ul]",html:"<ul>{0}</ul>"},list:{breakStart:!0,isInline:!1,skipLastLineBreak:!0,html:"<ul>{0}</ul>"},ol:{tags:{ol:null},breakStart:!0,isInline:!1,skipLastLineBreak:!0,format:"[ol]{0}[/ol]",html:"<ol>{0}</ol>"},li:{tags:{li:null},isInline:!1,closedBy:["/ul","/ol","/list","*","li"],format:"[li]{0}[/li]",html:"<li>{0}</li>"},"*":{isInline:!1,closedBy:["/ul","/ol","/list","*","li"],html:"<li>{0}</li>"},table:{tags:{table:null},isInline:!1,isHtmlInline:!0,skipLastLineBreak:!0,format:"[table]{0}[/table]",html:"<table>{0}</table>"},tr:{tags:{tr:null},isInline:!1,skipLastLineBreak:!0,format:"[tr]{0}[/tr]",html:"<tr>{0}</tr>"},th:{tags:{th:null},allowsEmpty:!0,isInline:!1,format:"[th]{0}[/th]",html:"<th>{0}</th>"},td:{tags:{td:null},allowsEmpty:!0,isInline:!1,format:"[td]{0}[/td]",html:"<td>{0}</td>"},emoticon:{allowsEmpty:!0,tags:{img:{src:null,"data-sceditor-emoticon":null}},format:function(t,e){return m(t,v)+e},html:"{0}"},hr:{tags:{hr:null},allowsEmpty:!0,isSelfClosing:!0,isInline:!1,format:"[hr]{0}",html:"<hr />"},img:{allowsEmpty:!0,tags:{img:{src:null}},allowedChildren:["#"],quoteType:b.never,format:function(e,t){var n="",r=function(t){return e.style?e.style[t]:null};return m(e,v)?t:(t=m(e,"width")||r("width"),r=m(e,"height")||r("height"),"[img"+(n=e.complete&&(t||r)||t&&r?"="+d.width(e)+"x"+d.height(e):n)+"]"+m(e,"src")+"[/img]")},html:function(t,e,n){var r="",i=e.width,l=e.height;return e.defaultattr&&(i=(e=e.defaultattr.split(/x/i))[0],l=2===e.length?e[1]:e[0]),void 0!==i&&(r+=' width="'+f(i,!0)+'"'),void 0!==l&&(r+=' height="'+f(l,!0)+'"'),"<img"+r+' src="'+o(n)+'" />'}},url:{allowsEmpty:!0,tags:{a:{href:null}},quoteType:b.never,format:function(t,e){t=m(t,"href");return"mailto:"===t.substr(0,7)?'[email="'+t.substr(7)+'"]'+e+"[/email]":"[url="+t+"]"+e+"[/url]"},html:function(t,e,n){return e.defaultattr=f(e.defaultattr,!0)||n,'<a href="'+o(e.defaultattr)+'">'+n+"</a>"}},email:{quoteType:b.never,html:function(t,e,n){return'<a href="mailto:'+(f(e.defaultattr,!0)||n)+'">'+n+"</a>"}},quote:{tags:{blockquote:null},isInline:!1,quoteType:b.never,format:function(t,e){for(var n,r="data-author",i="",l=t.children,o=0;!n&&o<l.length;o++)p(l[o],"cite")&&(n=l[o]);return(n||m(t,r))&&(i=n&&n.textContent||m(t,r),m(t,r,i),n&&t.removeChild(n),e=this.elementToBbcode(t),i="="+i.replace(/(^\s+|\s+$)/g,""),n&&t.insertBefore(n,t.firstChild)),"[quote"+i+"]"+e+"[/quote]"},html:function(t,e,n){return"<blockquote>"+(n=e.defaultattr?"<cite>"+f(e.defaultattr)+"</cite>"+n:n)+"</blockquote>"}},code:{tags:{code:null},isInline:!1,allowedChildren:["#","#newline"],format:"[code]{0}[/code]",html:"<code>{0}</code>"},left:{styles:{"text-align":["left","-webkit-left","-moz-left","-khtml-left"]},isInline:!1,allowsEmpty:!0,format:"[left]{0}[/left]",html:'<div align="left">{0}</div>'},center:{styles:{"text-align":["center","-webkit-center","-moz-center","-khtml-center"]},isInline:!1,allowsEmpty:!0,format:"[center]{0}[/center]",html:'<div align="center">{0}</div>'},right:{styles:{"text-align":["right","-webkit-right","-moz-right","-khtml-right"]},isInline:!1,allowsEmpty:!0,format:"[right]{0}[/right]",html:'<div align="right">{0}</div>'},justify:{styles:{"text-align":["justify","-webkit-justify","-moz-justify","-khtml-justify"]},isInline:!1,allowsEmpty:!0,format:"[justify]{0}[/justify]",html:'<div align="justify">{0}</div>'}};function x(t,r){return t.replace(/\{([^}]+)\}/g,function(t,e){var n=!0;return"!"===e.charAt(0)&&(n=!1,e=e.substring(1)),"0"===e&&(n=!1),void 0===r[e]?t:n?f(r[e],!0):r[e]})}function k(t){return"function"==typeof t}function w(t){return t&&t.replace(/\\(.)/g,"$1").replace(/^(["'])(.*?)\1$/,"$2")}var E="open",B="content",C="newline",I="close";function a(t,e,n,r,i,l){var o=this;o.type=t,o.name=e,o.val=n,o.attrs=r||{},o.children=i||[],o.closing=l||null}function T(t){var m=this;function o(t,e){var n,r,i;return t===E&&(n=e.match(/\[([^\]\s=]+)(?:([^\]]+))?\]/))&&(i=l(n[1]),n[2]&&(n[2]=n[2].trim())&&(r=function(t){var e,n=/([^\s=]+)=(?:(?:(["'])((?:\\\2|[^\2])*?)\2)|((?:.(?!\s\S+=))*.))/g,r={};if("="===t.charAt(0)&&t.indexOf("=",1)<0)r.defaultattr=w(t.substr(1));else for("="===t.charAt(0)&&(t="defaultattr"+t);e=n.exec(t);)r[l(e[1])]=w(e[3])||e[4];return r}(n[2]))),t===I&&(n=e.match(/\[\/([^\[\]]+)\]/))&&(i=l(n[1])),(i=t===C?"#newline":i)&&(t!==E&&t!==I||y[i])||(t=B,i="#"),new a(t,i,e,r)}function d(t,e,n){for(var r=n.length;r--;)if(n[r].type===e&&n[r].name===t)return 1}function h(t,e){t=(t?y[t.name]:{}).allowedChildren;return!m.opts.fixInvalidChildren||!t||-1<t.indexOf(e.name||"#")}function p(t,e,n){var r=/\s|=/.test(t);return k(e)?e(t,n):e===b.never||e===b.auto&&!r?t:'"'+t.replace(/\\/g,"\\\\").replace(/"/g,'\\"')+'"'}function g(t){return t.length?t[t.length-1]:null}function l(t){return t.toLowerCase()}m.opts=n({},T.defaults,t),m.tokenize=function(t){var e,n,r,i=[],l=[{type:B,regex:/^([^\[\r\n]+|\[)/},{type:C,regex:/^(\r\n|\r|\n)/},{type:E,regex:/^\[[^\[\]]+\]/},{type:I,regex:/^\[\/[^\[\]]+\]/}];t:for(;t.length;){for(r=l.length;r--;)if(n=l[r].type,(e=t.match(l[r].regex))&&e[0]){i.push(o(n,e[0])),t=t.substr(e[0].length);continue t}t.length&&i.push(o(B,t)),t=""}return i},m.parse=function(t,e){var n=function(t){function e(){return g(f)}function n(t){(e()?e().children:c).push(t)}function r(t){return e()&&(l=y[e().name])&&l.closedBy&&-1<l.closedBy.indexOf(t)}var i,l,o,a,s,u=[],c=[],f=[];for(;i=t.shift();)switch(s=t[0],h(e(),i)||i.type===I&&e()&&i.name===e().name||(i.name="#",i.type=B),i.type){case E:r(i.name)&&f.pop(),n(i),(l=y[i.name])&&!l.isSelfClosing&&(l.closedBy||d(i.name,I,t))?f.push(i):l&&l.isSelfClosing||(i.type=B);break;case I:if(e()&&i.name!==e().name&&r("/"+i.name)&&f.pop(),e()&&i.name===e().name)e().closing=i,f.pop();else if(d(i.name,E,f)){for(;o=f.pop();){if(o.name===i.name){o.closing=i;break}o=o.clone(),u.length&&o.children.push(g(u)),u.push(o)}for(s&&s.type===C&&(l=y[i.name])&&!1===l.isInline&&(n(s),t.shift()),n(g(u)),a=u.length;a--;)f.push(u[a]);u.length=0}else i.type=B,n(i);break;case C:e()&&s&&r((s.type===I?"/":"")+s.name)&&(s.type===I&&s.name===e().name||((l=y[e().name])&&l.breakAfter||l&&!1===l.isInline&&m.opts.breakAfterBlock&&!1!==l.breakAfter)&&f.pop()),n(i);break;default:n(i)}return c}(m.tokenize(t)),t=m.opts;return t.fixInvalidNesting&&function t(e,n,r,i){var l,o,a,s;var u=function(t){var t=y[t.name];return!t||!1!==t.isInline};n=n||[];i=i||e;for(o=0;o<e.length;o++)if((l=e[o])&&l.type===E){var c;if(r&&!u(l))if(f=g(n),s=f.splitAt(l),a=1<n.length?n[n.length-2].children:i,h(l,f)&&((c=f.clone()).children=l.children,l.children=[c]),-1<(c=a.indexOf(f))){s.children.splice(0,1),a.splice(c+1,0,l,s);var f=s.children[0];return void(f&&f.type===C&&(u(l)||(s.children.splice(0,1),a.splice(c+2,0,f))))}n.push(l),t(l.children,n,r||u(l),i),n.pop()}}(n),function t(e,n,r){var i,l,o,a,s,u,c,f;var d=e.length;n&&(a=y[n.name]);var h=d;for(;h--;)(i=e[h])&&(i.type===C?(l=0<h?e[h-1]:null,o=h<d-1?e[h+1]:null,f=!1,!r&&a&&!0!==a.isSelfClosing&&(l?u||o||(!1===a.isInline&&m.opts.breakEndBlock&&!1!==a.breakEnd&&(f=!0),a.breakEnd&&(f=!0),u=f):(!1===a.isInline&&m.opts.breakStartBlock&&!1!==a.breakStart&&(f=!0),a.breakStart&&(f=!0))),l&&l.type===E&&(s=y[l.name])&&(r?!1===s.isInline&&(f=!0):(!1===s.isInline&&m.opts.breakAfterBlock&&!1!==s.breakAfter&&(f=!0),s.breakAfter&&(f=!0))),!r&&!c&&o&&o.type===E&&(s=y[o.name])&&(!1===s.isInline&&m.opts.breakBeforeBlock&&!1!==s.breakBefore&&(f=!0),s.breakBefore&&(f=!0),c=f)?e.splice(h,1):(f&&e.splice(h,1),c=!1)):i.type===E&&t(i.children,i,r))}(n,null,e),t.removeEmptyTags&&function t(e){var n,r;var i=function(t){for(var e=t.length;e--;){var n=t[e].type;if(n===E||n===I)return!1;if(n===B&&/\S|\u00A0/.test(t[e].val))return!1}return!0};var l=e.length;for(;l--;)(n=e[l])&&n.type===E&&(r=y[n.name],t(n.children),i(n.children)&&r&&!r.isSelfClosing&&!r.allowsEmpty&&e.splice.apply(e,[l,1].concat(n.children)))}(n),n},m.toHTML=function(t,e){return function t(e,n){var r,i,l,o,a,s,u,c="";s=function(t){return!1!==(!t||(void 0!==t.isHtmlInline?t.isHtmlInline:t.isInline))};for(;0<e.length;)if(r=e.shift()){if(r.type===E)u=r.children[r.children.length-1]||{},i=y[r.name],o=n&&s(i),l=t(r.children,!1),l=i&&i.html?(s(i)||!s(y[u.name])||i.isPreFormatted||i.skipLastLineBreak||(l+="<br />"),k(i.html)?i.html.call(m,r,r.attrs,l):(r.attrs[0]=l,x(i.html,r.attrs))):r.val+l+(r.closing?r.closing.val:"");else{if(r.type===C){if(!n){c+="<br />";continue}a||(c+="<div>"),c+="<br />",e.length||(c+="<br />"),c+="</div>\n",a=!1;continue}o=n,l=f(r.val,!0)}o&&!a?(c+="<div>",a=!0):!o&&a&&(c+="</div>\n",a=!1),c+=l}a&&(c+="</div>\n");return c}(m.parse(t,e),!0)},m.toBBCode=function(t,e){return function t(e){var n,r,i,l,o,a,s,u,c,f="";for(;0<e.length;)if(n=e.shift())if(i=y[n.name],c=!(!i||!1!==i.isInline),l=i&&i.isSelfClosing,a=c&&m.opts.breakBeforeBlock&&!1!==i.breakBefore||i&&i.breakBefore,s=c&&!l&&m.opts.breakStartBlock&&!1!==i.breakStart||i&&i.breakStart,u=c&&m.opts.breakEndBlock&&!1!==i.breakEnd||i&&i.breakEnd,c=c&&m.opts.breakAfterBlock&&!1!==i.breakAfter||i&&i.breakAfter,o=(i?i.quoteType:null)||m.opts.quoteType||b.auto,i||n.type!==E)if(n.type===E){if(a&&(f+="\n"),f+="["+n.name,n.attrs)for(r in n.attrs.defaultattr&&(f+="="+p(n.attrs.defaultattr,o,"defaultattr"),delete n.attrs.defaultattr),n.attrs)n.attrs.hasOwnProperty(r)&&(f+=" "+r+"="+p(n.attrs[r],o,r));f+="]",s&&(f+="\n"),n.children&&(f+=t(n.children)),l||i.excludeClosing||(u&&(f+="\n"),f+="[/"+n.name+"]"),c&&(f+="\n"),n.closing&&l&&(f+=n.closing.val)}else f+=n.val;else f+=n.val,n.children&&(f+=t(n.children)),n.closing&&(f+=n.closing.val);return f}(m.parse(t,e))}}function i(t){return t=parseInt(t,10),isNaN(t)?"00":(t=Math.max(0,Math.min(t,255)).toString(16)).length<2?"0"+t:t}function s(t){var e;return(e=(t=t||"#000").match(/rgb\((\d{1,3}),\s*?(\d{1,3}),\s*?(\d{1,3})\)/i))?"#"+i(e[1])+i(e[2])+i(e[3]):(e=t.match(/#([0-f])([0-f])([0-f])\s*?$/i))?"#"+e[1]+e[1]+e[2]+e[2]+e[3]+e[3]:t}function u(){var s=this;s.stripQuotes=w;var a={},c={ul:["li","ol","ul"],ol:["li","ol","ul"],table:["tr"],tr:["td","th"],code:["br","p","div"]};function f(i,l,e){function o(t){var e=t[0],n=t[1],r=d.getStyle(i,e),t=i.parentNode;return!(!r||t&&d.hasStyle(t,e,r))&&(!n||n.includes(r))}function t(t){a[t]&&a[t][e]&&g(a[t][e],function(t,e){var n=y[t].strictMatch;if(void 0===n&&(n=s.opts.strictMatch),!e||e[n?"every":"some"]((r=n,function(t){var e=t[0],t=t[1];if("style"===e&&"CODE"===i.nodeName)return!1;if("style"===e&&t)return t[r?"every":"some"](o);e=m(i,e);return e&&(!t||t.includes(e))}))){var r,t=y[t].format;return l=k(t)?t.call(s,i,l):function(t){var n=arguments;return t.replace(/\{(\d+)\}/g,function(t,e){return void 0!==n[+e+1]?n[+e+1]:"{"+e+"}"})}(t,l),!1}})}return t("*"),t(i.nodeName.toLowerCase()),l}function u(t){var u=function(t,a){var s="";return d.traverse(t,function(t){var e="",n=t.nodeType,r=t.nodeName.toLowerCase(),i=c[r],l=t.firstChild,o=!0;"object"==typeof a&&(o=-1<a.indexOf(r),(o=p(t,"img")&&m(t,v)?!0:o)||(i=a)),3!==n&&1!==n||(1===n?p(t,".sceditor-nlf")&&!l||("iframe"!==r&&(e=u(t,i)),o?("code"!==r&&(e=f(t,e,!1)),e=f(t,e,!0),s+=function(t,e){var n=t.nodeName.toLowerCase(),r=d.isInline;if(!r(t,!0)||"br"===n){for(var i,l,o=t.previousSibling;o&&1===o.nodeType&&!p(o,"br")&&r(o,!0)&&!o.firstChild;)o=o.previousSibling;for(;i=((l=t.parentNode)&&l.lastChild)===t,t=l,l&&i&&r(l,!0););i&&"li"!==n||(e+="\n"),"br"!==n&&o&&!p(o,"br")&&r(o,!0)&&(e="\n"+e)}return e}(t,e)):s+=e):s+=t.nodeValue)},!1,!0),s};return u(t)}function t(t,e,n){var r,i,e=new T(s.opts.parserOptions).toHTML(s.opts.bbcodeTrim?e.trim():e);return t||n?(t=e,i=document.createElement("div"),n=function(t,e){if(!d.hasStyling(t)){if(1!==t.childNodes.length||!p(t.firstChild,"br"))for(;r=t.firstChild;)i.insertBefore(r,t);!e||t!==(e=i.lastChild)&&p(e,"div")&&t.nextSibling===e&&i.insertBefore(document.createElement("br"),t),i.removeChild(t)}},h(i,"display","none"),i.innerHTML=t.replace(/<\/div>\n/g,"</div>"),(t=i.firstChild)&&p(t,"div")&&n(t,!0),(t=i.lastChild)&&p(t,"div")&&n(t),i.innerHTML):e}function e(t,e,n,r){var i,l=(n=n||document).createElement("div"),o=n.createElement("div"),a=new T(s.opts.parserOptions);for(o.innerHTML=e,h(l,"visibility","hidden"),l.appendChild(o),n.body.appendChild(l),t&&(l.insertBefore(n.createTextNode("#"),l.firstChild),l.appendChild(n.createTextNode("#"))),r&&h(o,"whiteSpace",h(r,"whiteSpace")),i=o.getElementsByClassName("sceditor-ignore");i.length;)i[0].parentNode.removeChild(i[0]);return d.removeWhiteSpace(l),o=u(o),n.body.removeChild(l),o=a.toBBCode(o,!0),o=s.opts.bbcodeTrim?o.trim():o}s.init=function(){s.opts=this.opts,s.elementToBbcode=u,g(y,function(n,t){var r=!1===t.isInline,e=y[n].tags,t=y[n].styles;t&&(a["*"]=a["*"]||{},a["*"][r]=a["*"][r]||{},a["*"][r][n]=[["style",Object.entries(t)]]),e&&g(e,function(t,e){e&&e.style&&(e.style=Object.entries(e.style)),a[t]=a[t]||{},a[t][r]=a[t][r]||{},a[t][r][n]=e&&Object.entries(e)})}),this.commands=n(!0,{},r,this.commands),this.toBBCode=s.toSource,this.fromBBCode=s.toHtml},s.toHtml=t.bind(null,!1),s.fragmentToHtml=t.bind(null,!0),s.toSource=e.bind(null,!1),s.fragmentToSource=e.bind(null,!0)}a.prototype={clone:function(){var t=this;return new a(t.type,t.name,t.val,n({},t.attrs),[],t.closing?t.closing.clone():null)},splitAt:function(t){var e=this.clone(),n=this.children.indexOf(t);return-1<n&&(t=this.children.length-n,e.children=this.children.splice(n,t)),e}},T.QuoteType=b,T.defaults={breakBeforeBlock:!1,breakStartBlock:!1,breakEndBlock:!1,breakAfterBlock:!0,removeEmptyTags:!0,fixInvalidNesting:!0,fixInvalidChildren:!0,quoteType:b.auto,strictMatch:!1},u.get=function(t){return y[t]||null},u.set=function(t,e){return t&&e&&((e=n(y[t]||{},e)).remove=function(){delete y[t]},y[t]=e),this},u.rename=function(t,e){return t in y&&(y[e]=y[t],delete y[t]),this},u.remove=function(t){return t in y&&delete y[t],this},u.formatBBCodeString=x,t.formats.bbcode=u,t.BBCodeParser=T}(sceditor);

(sceditor => {
	sceditor.icons.smf = function ()
	{
		let buttons = {};

		return {
			create(command, button)
			{
				buttons[command] = button;
			},
			update(isSourceMode, currentNode)
			{
				buttons['color'].firstChild.style.textDecoration = 'underline ' + (!isSourceMode && currentNode ? currentNode.ownerDocument.queryCommandValue('forecolor') : '');
			}
		};
	};

	sceditor.plugins.emoticons = function ()
	{
		let editor;
		let opts;
		let line;

		const appendEmoticon = (code, {newrow, url, tooltip}) => {
			if (newrow)
				line.appendChild(document.createElement('br'));

			const i = document.createElement("img");
			i.src = opts.emoticonsRoot + url;
			i.alt = code;
			i.title = tooltip;
			i.addEventListener('click', function (e)
			{
				if (editor.inSourceMode())
					editor.insertText(' ' + this.alt + ' ');
				else
					editor.wysiwygEditorInsertHtml(' <img src="' + this.src + '" data-sceditor-emoticon="' + this.alt + '"> ');

				e.preventDefault();
			});
			line.appendChild(i);
		};

		const createPopup = el => {
			const t = document.createElement("div");
			const cover = document.createElement('div');
			const root = document.createElement('div');

			const hide = () => {
				cover.classList.remove('show');
				document.removeEventListener('keydown', esc);
			};

			var esc = ({keyCode}) => {
				if (keyCode === 27)
					hide();
			};

			const a = document.createElement('button');

			root.appendChild(a);
			cover.appendChild(root);
			document.body.appendChild(cover);
			root.id = 'popup-container';
			cover.id = 'popup';
			a.id = 'close';
			cover.addEventListener('click', ({target}) => {
				if (target.id === 'popup')
					hide();
			});
			a.addEventListener('click', hide);
			document.addEventListener('keydown', esc);
			root.appendChild(el);
			root.appendChild(a);
			cover.classList.add('show');
			editor.hidePopup = hide;
		};

		const ev = ({children, nextSibling}, col, row) => {
			for (let i = 1; i <= 144; i++)
				children[i - 1].className = Math.ceil(i / 12) <= col && (i % 12 || 12) <= row ? 'highlight2' : 'windowbg';

			nextSibling.textContent = col + 'x' + row;
		};

		const tbl = callback => {
			const content = document.createElement('div');
			content.className = 'sceditor-insert-table';
			const div = document.createElement('div');
			div.className = 'sceditor-insert-table-grid';
			div.addEventListener('mouseleave', ev.bind(null, div, 0, 0));
			const div2 = document.createElement('div');
			div2.className = 'largetext';
			div2.textContent = '0x0';

			for (let i = 1; i <= 144; i++)
			{
				const row = i % 12 || 12;
				const col = Math.ceil(i / 12);
				const span = document.createElement('span');
				span.className = 'windowbg';
				span.addEventListener('mouseenter', ev.bind(null, div, col, row));
				span.addEventListener('click', function (col, row) {
					callback(col, row);
					editor.hidePopup();
					editor.focus();
				}.bind(null, col, row));
				div.appendChild(span);
			}
			content.appendChild(div);
			content.appendChild(div2);
			createPopup(content);
		};

		this.init = function ()
		{
			editor = this;
			opts = editor.opts;

			if (opts.emoticonsEnabled)
			{
				const emoticons = opts.emoticons;
				content = opts.smileyContainer;
				if (emoticons.dropdown)
				{
					line = document.createElement('div');
					sceditor.utils.each(emoticons.dropdown, appendEmoticon);
					content.appendChild(line);
				}

				if (emoticons.more)
				{
					const moreButton = document.createElement('button');
					moreButton.className = 'button_submit';
					moreButton.textContent = editor._('More');
					moreButton.addEventListener('click', e => {
						line = document.createElement('div');
						sceditor.utils.each(emoticons.more, appendEmoticon);
						createPopup(line);

						e.preventDefault();
					});
					content.appendChild(moreButton);
				}
				content.className = 'sceditor-insertemoticon';
			}
			editor.commands.table = {
				state(parents, firstBlock) {
					return firstBlock && firstBlock.closest('table') ? 1 : 0;
				},
				exec() {
					tbl((cols, rows) => {
						editor.wysiwygEditorInsertHtml(
						'<table><tr><td>',
						'</td>'+Array(cols).join('<td><br></td>')+Array(rows).join('</tr><tr>'+Array(cols+1).join('<td><br></td>'))+'</tr></table>'
						);
					});
				},
				txtExec() {
					tbl((cols, rows) => {
						editor.insertText(
						'[table]\n[tr]\n[td]',
						'[/td]'+Array(cols).join('\n[td][/td]')+Array(rows).join('\n[/tr]\n[tr]'+Array(cols+1).join('\n[td][/td]'))+'\n[/tr]\n[/table]'
						);
					});
				},
			};
		};
	};

	const setCustomTextualCommands = cmds => {
		for (let [code, description, before, after] of cmds)
		{
			const obj = {
				tooltip: description || code
			};
			if (!sceditor.commands[code])
			{
				obj.exec = function ()
				{
					this.insertText(before, after);
				};
				if (before === after)
					obj.txtExec = [before];
				else if (before)
					obj.txtExec = [before, after];
			}
			sceditor.command.set(code, obj);
		}
	};
	const createFn = sceditor.create;
	sceditor.create = (textarea, options) => {
		setCustomTextualCommands(options.customTextualCommands);
		createFn(textarea, options);
	};
})(sceditor);

sceditor.command.set(
	'link', {
		exec(caller) {
			const editor = this;

			editor.commands.link._dropDown(editor, caller, (url, text) => {
				if (!editor.getRangeHelper().selectedHtml() || text) {
					text = text || url;

					editor.wysiwygEditorInsertHtml(
						'<a data-type="url" href="' +
						sceditor.escapeEntities(url) + '">' +
						sceditor.escapeEntities(text, true) + '</a>'
					);
				} else {
					editor.wysiwygEditorInsertHtml(
						'<a data-type="url" href="' +
						sceditor.escapeEntities(url) + '">', '</a>'
					);
				}
			});
		}
	}
).set(
	'ftp', {
		exec(caller) {
			const editor = this;

			editor.commands.link._dropDown(editor, caller, (url, text) => {
				if (!editor.getRangeHelper().selectedHtml() || text) {
					text = text || url;

					editor.wysiwygEditorInsertHtml(
						'<a data-type="ftp" href="' +
						sceditor.escapeEntities(url) + '">' +
						sceditor.escapeEntities(text, true) + '</a>'
					);
				} else {
					editor.wysiwygEditorInsertHtml(
						'<a data-type="ftp" href="' +
						sceditor.escapeEntities(url) + '">',
						'</a>'
					);
				}
			});
		},
		txtExec(caller, selected) {
			const editor = this;

			editor.commands.link._dropDown(editor, caller, (url, text) => {
					editor.insertText(
						'[ftp=' + url + ']' +
							(text || selected || url) +
						'[/ftp]'
					);
				}
			);
		}
	}
).set(
	'bulletlist', {
		txtExec(caller, selected) {
			if (selected)
				this.insertText(
					'[list]\n[li]' +
					selected.split(/\r?\n/).join('[/li]\n[li]') +
					'[/li]\n[/list]'
				);
			else
				this.insertText('[list]\n[li]', '[/li]\n[li][/li]\n[/list]');
		}
	}
).set(
	'orderedlist', {
		txtExec(caller, selected) {
			if (selected)
				this.insertText(
					'[list type=decimal]\n[li]' +
					selected.split(/\r?\n/).join('[/li]\n[li]') +
					'[/li]\n[/list]'
				);
			else
				this.insertText('[list type=decimal]\n[li]', '[/li]\n[li][/li]\n[/list]');
		}
	}
).set(
	'color', {
		_dropDown(editor, caller, callback)
		{
			const content = document.createElement('div');

			for (const [color, name] of editor.opts.colors)
			{
				const link = document.createElement('a');
				const span = document.createElement('span');
				link.setAttribute('data-color', color);
				link.textContent = name;
				span.style.backgroundColor = color;
				link.addEventListener('click', function (e) {
					callback(this.getAttribute('data-color'));
					editor.closeDropDown(true);
					e.preventDefault();
				});
				link.appendChild(span);
				content.appendChild(link);
			}

			editor.createDropDown(caller, 'color-picker', content);
		}
	}
).set(
	'size', {
		_dropDown(editor, caller, callback)
		{
			const content = document.createElement('div');

			for (let i = 1; i <= 7; i++)
			{
				const link = document.createElement('a');
				link.setAttribute('data-size', i);
				link.textContent = i;
				link.addEventListener('click', function (e) {
					callback(this.getAttribute('data-size'));
					editor.closeDropDown(true);
					e.preventDefault();
				});
				content.appendChild(link);
				link.style.fontSize = i * 6 + 'px';
			}

			editor.createDropDown(caller, 'fontsize-picker', content);
		}
	}
).set(
	'image', {
		exec(caller) {
			const editor = this;

			editor.commands.image._dropDown(
				editor,
				caller,
				'',
				(url, width, height) => {
					const attrs = ['src="' + sceditor.escapeEntities(url) + '"'];

					if (width)
						attrs.push('width="' + sceditor.escapeEntities(width, true) + '"');

					if (height)
						attrs.push('height="' + sceditor.escapeEntities(height, true) + '"');
 
					editor.wysiwygEditorInsertHtml(
						'<img ' + attrs.join(' ') + '>'
					);
				}
			);
		}
	}
);
let itemCodes = [
	['*', 'disc'],
	['@', 'disc'],
	['+', 'square'],
	['x', 'square'],
	['o', 'circle'],
	['O', 'circle'],
	['0', 'circle'],
];
for (const [code, attr] of itemCodes)
{
	sceditor.formats.bbcode.set(code, {
		tags: {
			li: {
				'data-itemcode': [code]
			}
		},
		isInline: false,
		closedBy: ['/ul', '/ol', '/list', 'li', '*', '@', '+', 'x', '0', 'o', 'O'],
		excludeClosing: true,
		html: '<li type="' + attr + '" data-itemcode="' + code + '">{0}</li>',
		format: '[' + code + ']{0}',
	});
}
sceditor.formats.bbcode.set(
	'abbr', {
		tags: {
			abbr: {
				title: null
			}
		},
		format(element, content) {
			return '[abbr=' + element.getAttribute('title') + ']' + content + '[/abbr]';
		},
		html: '<abbr title="{defaultattr}">{0}</abbr>'
	}
).set(
	'list', {
		breakStart: true,
		isInline: false,
		// allowedChildren: ['*', 'li'], // Disabled for SCE 2.1.2 because it triggers a bug with inserting extra line breaks
		html(element, {type}, content) {
			let style = '';
			let code = 'ul';
			const olTypes = ['decimal', 'decimal-leading-zero', 'lower-roman', 'upper-roman', 'lower-alpha', 'upper-alpha', 'lower-greek', 'upper-greek', 'lower-latin', 'upper-latin', 'hebrew', 'armenian', 'georgian', 'cjk-ideographic', 'hiragana', 'katakana', 'hiragana-iroha', 'katakana-iroha'];

			if (type) {
				style = ' style="list-style-type: ' + type + '"';

				if (olTypes.includes(type))
					code = 'ol';
			}
			else
				style = ' style="list-style-type: disc"';

			return '<' + code + style + '>' + content + '</' + code + '>';
		}
	}
).set(
	'ul', {
		tags: {
			ul: null
		},
		breakStart: true,
		isInline: false,
		html: '<ul>{0}</ul>',
		format(element, content) {
			const type = element.getAttribute('type') || element.style.listStyleType;
			if (type == 'disc')
				return '[list]' + content + '[/list]';
			else
				return '[list type=' + type + ']' + content + '[/list]';
		}
	}
).set(
	'ol', {
		tags: {
			ol: null
		},
		breakStart: true,
		isInline: false,
		html: '<ol>{0}</ol>',
		format(element, content) {
			const type = element.getAttribute('type') || element.style.listStyleType;
			if (type == 'none')
				type = 'decimal';

			return '[list type=' + type + ']' + content + '[/list]';
		}
	}
).set(
	'li', {
		tags: {
			li: null
		},
		isInline: false,
		closedBy: ['/ul', '/ol', '/list', 'li', '*', '@', '+', 'x', 'o', 'O', '0'],
		html: '<li data-itemcode="li">{0}</li>',
		format(element, content) {
			let token = 'li';
			const tok = element.getAttribute('data-itemcode');
			const allowedTokens = ['li', '*', '@', '+', 'x', 'o', 'O', '0'];

			if (tok && allowedTokens.includes(tok))
				token = tok;

			return '[' + token + ']' + content + (token === 'li' ? '[/' + token + ']' : '');
		},
	}
).set(
	'img', {
		tags: {
			img: {
				src: null
			}
		},
		allowsEmpty: true,
		quoteType: sceditor.BBCodeParser.QuoteType.never,
		format(element, content) {
			// check if this is an emoticon image
			if (element.hasAttribute('data-sceditor-emoticon'))
				return content;

			let attribs = '';
			const width = element.getAttribute('width') || element.style.width;
			const height = element.getAttribute('height') || element.style.height;

			if (width)
				attribs += " width=" + width;
			if (height)
				attribs += " height=" + height;
			if (element.alt)
				attribs += " alt=" + element.alt;
			if (element.title)
				attribs += " title=" + element.title;

			return '[img' + attribs + ']' + element.src + '[/img]';
		},
		html(token, {width, height, alt, title}, content) {
			let parts;
			let attribs = '';

			// handle [img width=340 height=240]url[/img]
			if (typeof width !== "undefined")
				attribs += ' width="' + width + '"';
			if (typeof height !== "undefined")
				attribs += ' height="' + height + '"';
			if (typeof alt !== "undefined")
				attribs += ' alt="' + alt + '"';
			if (typeof title !== "undefined")
				attribs += ' title="' + title + '"';

			return '<img' + attribs + ' src="' + content + '">';
		}
	}
).set(
	'url', {
		allowsEmpty: true,
		quoteType: sceditor.BBCodeParser.QuoteType.never,
		tags: {
			a: {
				href: null
			}
		},
		format(element, content) {
			if (element.getAttribute('data-type') != 'url')
				return content;

			return '[url=' + decodeURI(element.href) + ']' + content + '[/url]';
		},
		html(token, {defaultattr}, content) {
			return '<a data-type="url" href="' + encodeURI(defaultattr || content) + '">' + content + '</a>';
		}
	}
).set(
	'iurl', {
		allowsEmpty: true,
		quoteType: sceditor.BBCodeParser.QuoteType.never,
		tags: {
			a: {
				'data-type': ['iurl']
			}
		},
		format({href}, content) {
			return '[iurl=' + href + ']' + content + '[/iurl]';
		},
		html(token, {defaultattr}, content) {
			return '<a data-type="iurl" href="' + (defaultattr || content) + '">' + content + '</a>';
		}
	})
.set(
	'ftp', {
		allowsEmpty: true,
		quoteType: sceditor.BBCodeParser.QuoteType.never,
		tags: {
			a: {
				'data-type': ['ftp']
			}
		},
		format({href}, content) {
			return (href == content ? '[ftp]' : '[ftp=' + href + ']') + content + '[/ftp]';
		},
		html(token, {defaultattr}, content) {
			return '<a data-type="ftp" href="' + (defaultattr || content) + '">' + content + '</a>';
		}
	})
	.set('table', {
		breakStart: true,
		isHtmlInline: false,
		skipLastLineBreak: false,
	})
	.set('tr', {
		breakStart: true,
	})
	.set('tt', {
		tags: {
			tt: null,
			span: {'class': ['tt']}
		},
		format: '[tt]{0}[/tt]',
		html: '<span class="tt">{0}</span>'
	})
	.set('pre', {
		tags: {
			pre: null
		},
		isInline: false,
		format: '[pre]{0}[/pre]',
		html: '<pre>{0}</pre>'
	})
	.set('me', {
		tags: {
			div: {
				'data-name' : null
			}
		},
		isInline: false,
		format(element, content) {
			return '[me=' + element.getAttribute('data-name') + ']' + content.replace(element.getAttribute('data-name') + ' ', '') + '[/me]';
		},
		html: '<div class="meaction" data-name="{defaultattr}">* {defaultattr} {0}</div>'
	})
.set(
	'php', {
		tags: {
			code: {
				'data-title': ['php']
			}
		},
		isInline: false,
		allowedChildren: ['#', '#newline'],
		format: "[php]{0}[/php]",
		html: '<code data-title="php">{0}</code>'
	}
).set(
	'code', {
		tags: {
			code: null
		},
		isInline: false,
		allowedChildren: ['#', '#newline'],
		format(element, content) {
			const title = element.getAttribute('data-title');
			const from = title ? ' =' + title : '';

			if (title === 'php')
				return '[php]' + content.replace('&#91;', '[') + '[/php]';

			return '[code' + from + ']' + content.replace('&#91;', '[') + '[/code]';
		},
		html(element, {defaultattr}, content) {
			const from = defaultattr ? ' data-title="' + defaultattr + '"'  : '';

			return '<code data-name="' + sceditor.locale.code + '"' + from + '>' + content.replace('[', '&#91;') + '</code>'
		}
	}
).set(
	'quote', {
		tags: {
			blockquote: null,
			cite: null
		},
		quoteType: sceditor.BBCodeParser.QuoteType.never,
		breakBefore: false,
		isInline: false,
		format(element, content) {
			let attrs = '';
			const author = element.getAttribute('data-author');
			const date = element.getAttribute('data-date');
			const link = element.getAttribute('data-link');

			// The <cite> contains only the graphic for the quote, so we can skip it
			if (element.tagName === 'CITE')
				return '';

			if (author)
				attrs += ' author=' + author.php_unhtmlspecialchars();
			if (link)
				attrs += ' link=' + link;
			if (date)
				attrs += ' date=' + date;

			return '[quote' + attrs + ']' + content + '[/quote]';
		},
		html(element, attrs, content) {
			let attr_author = '';
			let author = '';
			let attr_date = '';
			let sDate = '';
			let attr_link = '';
			let link = '';

			if (attrs.author || attrs.defaultattr)
			{
				attr_author = attrs.author || attrs.defaultattr;
				author = bbc_quote_from + ': ' + attr_author;
			}

			if (attrs.link)
			{
				attr_link = attrs.link;
				link = attr_link.substr(0, 7) == 'http://' ? attr_link : smf_prepareScriptUrl(smf_scripturl) + attr_link;
				author = '<a href="' + link + '">' + (author || bbc_quote_from + ': ' + link) + '</a>';
			}

			if (attrs.date)
			{
				attr_date = attrs.date;
				sDate = '<date timestamp="' + attr_date + '">' + new Date(attr_date * 1000).toLocaleString() + '</date>';

				if (author !== '')
					author += ' ' + bbc_search_on;
			}

			return '<blockquote data-author="' + attr_author + '" data-date="' + attr_date + '" data-link="' + attr_link + '"><cite>' + (author || bbc_quote) + ' ' + sDate + '</cite>' + content + '</blockquote>';
		}
	}
);