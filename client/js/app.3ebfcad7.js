(function(e){function t(t){for(var r,a,s=t[0],u=t[1],c=t[2],l=0,d=[];l<s.length;l++)a=s[l],o[a]&&d.push(o[a][0]),o[a]=0;for(r in u)Object.prototype.hasOwnProperty.call(u,r)&&(e[r]=u[r]);f&&f(t);while(d.length)d.shift()();return i.push.apply(i,c||[]),n()}function n(){for(var e,t=0;t<i.length;t++){for(var n=i[t],r=!0,a=1;a<n.length;a++){var s=n[a];0!==o[s]&&(r=!1)}r&&(i.splice(t--,1),e=u(u.s=n[0]))}return e}var r={},a={4:0},o={4:0},i=[];function s(e){return u.p+"js/"+({}[e]||e)+"."+{1:"53b8ff1d",2:"fb39d6d1",3:"bb6bd007"}[e]+".js"}function u(t){if(r[t])return r[t].exports;var n=r[t]={i:t,l:!1,exports:{}};return e[t].call(n.exports,n,n.exports,u),n.l=!0,n.exports}u.e=function(e){var t=[],n={1:1,2:1,3:1};a[e]?t.push(a[e]):0!==a[e]&&n[e]&&t.push(a[e]=new Promise(function(t,n){for(var r=e+"."+{1:"6e7c6998",2:"888614d2",3:"398fc3e1"}[e]+".css",a=u.p+r,o=document.getElementsByTagName("link"),i=0;i<o.length;i++){var s=o[i],c=s.getAttribute("data-href")||s.getAttribute("href");if("stylesheet"===s.rel&&(c===r||c===a))return t()}var l=document.getElementsByTagName("style");for(i=0;i<l.length;i++){s=l[i],c=s.getAttribute("data-href");if(c===r||c===a)return t()}var d=document.createElement("link");d.rel="stylesheet",d.type="text/css",d.onload=t,d.onerror=function(t){var r=t&&t.target&&t.target.src||a,o=new Error("Loading CSS chunk "+e+" failed.\n("+r+")");o.request=r,n(o)},d.href=a;var f=document.getElementsByTagName("head")[0];f.appendChild(d)}).then(function(){a[e]=0}));var r=o[e];if(0!==r)if(r)t.push(r[2]);else{var i=new Promise(function(t,n){r=o[e]=[t,n]});t.push(r[2]=i);var c=document.getElementsByTagName("head")[0],l=document.createElement("script");l.charset="utf-8",l.timeout=120,u.nc&&l.setAttribute("nonce",u.nc),l.src=s(e);var d=setTimeout(function(){f({type:"timeout",target:l})},12e4);function f(t){l.onerror=l.onload=null,clearTimeout(d);var n=o[e];if(0!==n){if(n){var r=t&&("load"===t.type?"missing":t.type),a=t&&t.target&&t.target.src,i=new Error("Loading chunk "+e+" failed.\n("+r+": "+a+")");i.type=r,i.request=a,n[1](i)}o[e]=void 0}}l.onerror=l.onload=f,c.appendChild(l)}return Promise.all(t)},u.m=e,u.c=r,u.d=function(e,t,n){u.o(e,t)||Object.defineProperty(e,t,{configurable:!1,enumerable:!0,get:n})},u.r=function(e){Object.defineProperty(e,"__esModule",{value:!0})},u.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return u.d(t,"a",t),t},u.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},u.p="/",u.oe=function(e){throw console.error(e),e};var c=window["webpackJsonp"]=window["webpackJsonp"]||[],l=c.push.bind(c);c.push=t,c=c.slice();for(var d=0;d<c.length;d++)t(c[d]);var f=l;i.push([6,0]),n()})({6:function(e,t,n){e.exports=n("JJXq")},H36q:function(e,t){},Hl11:function(e,t,n){},JJXq:function(e,t,n){"use strict";n.r(t);var r={};n.d(r,"setVar",function(){return S}),n.d(r,"setTodayStatsData",function(){return P}),n.d(r,"copyTodayStatsData",function(){return k});var a=n("MVZn"),o=n.n(a),i=(n("SpHO"),n("Kw5r")),s=n("4IOb");i["a"].use(s["L"],{cfg:{},components:{QLayout:s["m"],QLayoutHeader:s["o"],QLayoutDrawer:s["n"],QPageContainer:s["t"],QPage:s["s"],QToolbar:s["G"],QToolbarTitle:s["H"],QBtn:s["b"],QIcon:s["i"],QList:s["p"],QListHeader:s["q"],QItem:s["j"],QItemMain:s["k"],QField:s["h"],QSelect:s["y"],QCard:s["c"],QCardTitle:s["f"],QCardSeparator:s["e"],QCardMain:s["d"],QSlider:s["z"],QPopover:s["u"],QItemSide:s["l"],QTabs:s["C"],QTab:s["A"],QWindowResizeObservable:s["J"],QResizeObservable:s["v"],QModal:s["r"],QTable:s["B"],QTr:s["I"],QTh:s["E"],QTd:s["D"],QCheckbox:s["g"],QSearch:s["x"],QRouteTab:s["w"],QToggle:s["F"]},directives:{Ripple:s["K"]},plugins:{Notify:s["a"]}});var u=n("lIOY");Object(u["a"])("/service-worker.js",{ready:function(){console.log("App is being served from cache by a service worker.")},cached:function(){console.log("Content has been cached for offline use.")},updated:function(){console.log("New content is available; please refresh.")},offline:function(){console.log("No internet connection found. App is running in offline mode.")},error:function(e){console.error("Error during service worker registration:",e)}});n("QBu7"),n("flf9"),n("OKqc"),n("Hl11"),n("fm0S");var c=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{attrs:{id:"q-app"}},[n("router-view")],1)},l=[];c._withStripped=!0;var d={name:"App"},f=d,p=(n("ZL7j"),n("KHd+")),h=Object(p["a"])(f,c,l,!1,null,null,null),m=h.exports,v=n("jE9Z"),g=[{path:"/",component:function(){return n.e(2).then(n.bind(null,"ez0Y"))},children:[{path:"",component:function(){return Promise.all([n.e(0),n.e(1)]).then(n.bind(null,"kmF/"))}},{path:"stats",component:function(){return Promise.all([n.e(0),n.e(3)]).then(n.bind(null,"hr46"))}}]},{path:"*",component:function(){return n.e(2).then(n.bind(null,"ez0Y"))},children:[{path:"",component:function(){return Promise.all([n.e(0),n.e(1)]).then(n.bind(null,"kmF/"))},meta:{error404:!0}}]}];i["a"].use(v["a"]);var b=new v["a"]({mode:"history",base:"/",scrollBehavior:function(){return{y:0}},routes:g}),y=b,Q=n("L2JU"),w=n("DkTT"),T={host:"",vars:{nodesData:{},statsData:{},todayStatsData:{},lastUpdated:null,minimumPercentage:90,minimumDays:7,containerHeight:500,headerHeight:0,mapHeightChanged:!1,ispsOpened:!1,countriesOpened:!1,listOpened:!1,selectionOpened:!1,settingsOpened:!1}},O=n("U7rI"),S=function(e,t){e.vars[t.key]=t.value},P=function(e,t){for(var n in t.value)e.vars.todayStatsData[n]=t.value[n]},k=function(e){for(var t in e.vars.todayStatsData)e.vars.statsData[t][window.moment().format("YYYY-MM-DD")]=e.vars.todayStatsData[t]},D=n("H36q"),j={namespaced:!0,state:T,getters:O,mutations:r,actions:D};i["a"].use(Q["a"]);var E=new Q["a"].Store({modules:{data:j},plugins:[Object(w["a"])()]}),L=E,C=n("qSUR"),H={failed:"Action failed",success:"Action was successful"},q={en:H},A=function(e){var t=e.app,n=(e.store,e.Vue);n.use(C["a"]),t.i18n=new C["a"]({locale:"en",fallbackLocale:"en",messages:q})},x=n("/jzl"),M=n.n(x);i["a"].config.productionTip=!0;var I=o()({el:"#q-app",router:y,store:L},m),z=[];z.push(A),z.forEach(function(e){return e({app:I,router:y,store:L,Vue:i["a"]})}),/iPad|iPhone|iPod/.test(navigator.userAgent)&&!window.MSStream&&window.navigator.standalone&&document.addEventListener("DOMContentLoaded",function(){M.a.attach(document.body)},!1),new i["a"](I)},U7rI:function(e,t){},ZL7j:function(e,t,n){"use strict";var r=n("usnz"),a=n.n(r);a.a},fm0S:function(e,t,n){},usnz:function(e,t,n){}});