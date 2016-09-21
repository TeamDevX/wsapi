!function(){"use strict";angular.module("blackFriday",["ngAnimate","ui.router","toastr"])}(),function(){"use strict";function e(e,t,i,n){var o={};return o.userId="",o.userName="",o.userEmail="",o.userScore="",o.typedCoupons=[],o.setUser=function(e){this.userId=e.uid,this.userName=e.name,this.userEmail=e.email,this.userScore=e.score},o.getScore=function(){return this.userScore},o.setScore=function(e){this.userScore=e},o.getUserId=function(){return this.userId},o.getScores=function(){var e=n.defer();return t.post(i.getBaseURL()+"/api/v1/get_Scores",{checksum:checkSum}).then(function(t){t.data?e.resolve(t):e.reject(t)},function(t){e.reject(t)}),e.promise},o}e.$inject=["$window","$http","DeviceService","$q"],angular.module("blackFriday").factory("UserService",e)}(),function(){"use strict";function e(e){var t={};return t.root_Url="",t.dimensions=function(){return{y:e.innerHeight,x:e.innerWidth}},t.getBaseURL=function(){return t.root_Url},t.setBaseURL=function(e){t.root_Url=e},t}e.$inject=["$window"],angular.module("blackFriday").factory("DeviceService",e)}(),function(){"use strict";function e(e){var t={};return t.items=[{id:1,code:"dfgfdfgdf",image:"url"},{id:2,code:"ghjhjgjgh",image:"url"},{id:3,code:"ertertrt",image:"url"},{id:4,code:"cvbvbccvbv",image:"url"},{id:5,code:"ghjghjgh",image:"url"},{id:6,code:"klkjlljkl",image:"url"}],t.typedCoupons=[],t.getRandomCode=function(){return String.fromCharCode(65+parseInt(25*Math.random()))+String.fromCharCode(65+parseInt(25*Math.random()))+String.fromCharCode(65+parseInt(25*Math.random()))},t.selectedItems=[],t.getItems=function(){return this.items},t.addItems=function(e){this.selectedItems=e},t.addCoupons=function(e){t.typedCoupons=e},t.getScore=function(){return this.selectedItems.length+this.typedCoupons.length},t.getRank=function(){return 34},t.getSelectedItems=function(){return this.selectedItems},t.reset=function(){this.selectedItems=[],this.typedCoupons=[]},t}e.$inject=["$window"],angular.module("blackFriday").factory("DataService",e)}(),function(){"use strict";angular.module("blackFriday").directive("scoresAndPrizes",function(){return{scope:{playersSent:"@players"},templateUrl:"app/components/directives/ScoresAndPrizes.html",link:function(e,t,i){e.players=JSON.parse(e.playersSent),e.prizes=[]},controller:["$scope","UserService",function(e,t){e.players=t.getScores().then(function(t){200==t.status&&(e.players=t.data,console.log(t.data))},function(e){})}]}})}(),function(){"use strict";function e(e,t,i,n,o,a,r,s){function d(e){0==s.orientation||180==s.orientation?t.showWarning=!0:t.showWarning=!1,t.$apply()}t.isWaiting=!0,t.players=[],t.faceBookShareMessage="",t.showFBdialog=!1,t.init=function(){0==s.orientation||180==s.orientation?t.showWarning=!0:t.showWarning=!1,o.post(a.getBaseURL()+"/api/v1/insert_Score",{bfscore:i.getScore(),uid:r.getUserId(),checksum:checkSum}).then(function(e){console.log(e),r.getScores().then(function(e){200==e.status&&(t.players=e.data,console.log(e.data))},function(e){}),t.isWaiting=!1},function(e){})},s.addEventListener("orientationchange",d,!1),t.score=i.getScore(),t.rank=i.getRank(),t.shareScore=function(e){"FB"==e?(t.faceBookShareMessage="I just participated in the Black Friday Online Training challenge and scored "+t.score+"!",t.showFBdialog=!0):"TW"==e&&window.open("http://twitter.com/share?url=http://teamdevx.com;text=I just participated in the Black Friday Online Training challenge and scored "+t.score+"!")},t.shareToFaceBook=function(e){console.log(e),FB.ui({method:"share",href:"http://teamdevx.com",message:e,caption:e,name:e,description:" "},function(e){console.log(e),t.faceBookShareMessage="I just participated in the Black Friday Online Training challenge and scored "+t.score+"!",t.showFBdialog=!1,t.$apply()})},t.fbshareCancelled=function(){t.faceBookShareMessage="I just participated in the Black Friday Online Training challenge and scored "+t.score+"!",t.showFBdialog=!1},t.playAgain=function(){i.reset(),s.removeEventListener("orientationchange",d,!1),n.go("add-to-basket")},t.inviteFriend=function(){}}e.$inject=["$timeout","$scope","DataService","$state","$http","DeviceService","UserService","$window"],angular.module("blackFriday").controller("ResultController",e)}(),function(){"use strict";function e(e,t,i,n,o,a,r){function s(e){0==r.orientation||180==r.orientation?t.showWarning=!0:t.showWarning=!1,t.$apply()}t.info={},t.isWaiting=!0,t.showWarning=!1,t.initUser="",t.players=[],t.init=function(){n.get("ajs/config.json").then(function(e){200==e.status&&(a.setBaseURL(e.data.root_URL),t.start())},function(e){})},t.start=function(){0==r.orientation||180==r.orientation?t.showWarning=!0:t.showWarning=!1,r.addEventListener("orientationchange",s,!1),o.getScores().then(function(e){200==e.status&&(t.players=e.data,console.log(e.data))},function(e){});for(var e=document.cookie,d=e.split(";"),l=!1,c=0;c<d.length;c++)if(d[c].indexOf("bFriday")>=0){t.initUser=JSON.parse(d[c].trim()),l=!0;break}1==l?n.post(a.getBaseURL()+"/api/v1/init/",{uid:t.initUser.uid,checksum:checkSum}).then(function(e){null!=e.data.email?(console.log(t.initUser.uid),o.setUser({uid:t.initUser.uid,name:e.data.name,email:e.data.email,score:e.data.score}),r.removeEventListener("orientationchange",s,!1),i.go("add-to-basket")):t.isWaiting=!1},function(e){t.isWaiting=!1}):t.isWaiting=!1},t.validateEmail=function(e){var t=e;if(!t)return alert("Not a valid e-mail address"),!1;var i=t.indexOf("@"),n=t.lastIndexOf(".");return 1>i||i+2>n||n+2>=t.length?(alert("Not a valid e-mail address"),!1):!0},t.takeChallege=function(){console.log(t.info.email,t.info.name,t.info.agree),t.validateEmail(t.info.email)&&""!=t.info.name&&1==t.info.agree&&n.post(a.getBaseURL()+"/api/v1/insert_User",{bfname:t.info.name,bfemail:t.info.email,checksum:checkSum}).then(function(e){console.log("res",e),null!=e.data&&n.post(a.getBaseURL()+"/api/v1/login_user",{bfname:t.info.name,bfemail:t.info.email,checksum:checkSum}).then(function(n){null!=n.data&&(o.setUser({uid:e.data,name:t.info.name,email:t.info.email,score:0}),document.cookie='{"uid":"'+e.data+'","username":"'+t.info.name+'","pj":"bFriday"}',r.removeEventListener("orientationchange",s,!1),i.go("add-to-basket"))},function(e){console.log(e)})},function(e){console.log(e)})}}e.$inject=["$timeout","$scope","$state","$http","UserService","DeviceService","$window"],angular.module("blackFriday").controller("MainController",e)}(),function(){"use strict";function e(e,t,i,n,o,a){function r(e){0==o.orientation||180==o.orientation?(t.showWarning2=!0,t.pauseGame()):(t.showWarning2=!1,t.resumeGame()),t.$apply()}function s(e){if(0==t.timedout){var i=e.keyCode;13==i&&(console.log("gfhfhgfhgfhfgh  "+t.codeTyped+"    "+t.codeDisplayed),t.codeTyped==t.codeDisplayed?(p.removeClass("addRed"),t.couponCodes.push(t.codeDisplayed),t.codeDisplayed=n.getRandomCode(),t.codeTyped=""):p.addClass("addRed"),t.$apply())}}t.test={};var d=null,l=0,c=i.dimensions(),u=angular.element(document.querySelector("#basket-container")),h=angular.element(document.querySelector("#basket-play"));h.addClass("blurred"),u.height(c.y);var g=angular.element(document.querySelector("#game")),p=angular.element(document.querySelector("#code"));$(document).ready(function(){$("#codeTyped").bind("cut copy paste",function(e){e.preventDefault()}),$("#code").bind("cut copy paste",function(e){e.preventDefault()})}),0==o.orientation||180==o.orientation?t.showWarning2=!0:t.showWarning2=!1,o.addEventListener("orientationchange",r,!1),t.couponCodes=[],t.codeTyped="",t.codeDisplayed=n.getRandomCode(),t.items=n.getSelectedItems(),t.countdownCount=0,t.startCountDown=!1,t.gameStarted=!1,t.timedout=!1,t.totalTime=30,o.addEventListener("keyup",s,!1),t.pauseGame=function(e,i){t.gamePaused=!0,d=null},t.resumeGame=function(e,i){t.gamePaused=!1,d=null,1==t.startCountDown&&requestAnimationFrame(t.step)},t.onStart=function(){g.height("90%"),t.startCountDown=!0,t.gameStarted=!1,t.gamePaused=!1,window.requestAnimationFrame(t.step)},t.step=function(e){null==d&&(d=e),l+=e-d,d=e,0==t.gamePaused&&(1==t.gameStarted?(t.totalTime=31-Math.ceil(l/1e3)+3,31-Math.ceil(l/1e3)+3<=0&&(t.timedout=!0,o.removeEventListener("keyup",s,!1),n.addCoupons(t.couponCodes),o.removeEventListener("orientationchange",r,!1),a.go("results"))):1==t.startCountDown&&(console.log(4-Math.ceil(l/1e3)),t.countdownCount=4-Math.ceil(l/1e3),4-Math.ceil(l/1e3)<=0&&(t.gameStarted=!0,h.removeClass("blurred"))),0==t.timedout&&requestAnimationFrame(t.step)),t.$apply()}}e.$inject=["$timeout","$scope","DeviceService","DataService","$window","$state"],angular.module("blackFriday").controller("CheckoutController",e)}(),function(){"use strict";function e(e,t,i,n,o,a,r){function s(e){angular.element(document.querySelector("#basket-main")).height(i.dimensions().y),t.adjustScreenElements(),t.$apply()}function d(e){0==a.orientation||180==a.orientation?(t.showWarning2=!0,t.pauseGame()):1==t.firsttime?(t.firsttime=!1,t.showWarning2=!1,t.$apply(),h.addClass("blurred"),h.height(g.height()),h.width(h.height()*t.aspectR),t.countdownCount=0,t.startCountDown=!1,t.gameStarted=!1,t.timedout=!1,t.totalTime=30,t.addTimeout=0,t.removeTimeout=0,t.gamePaused=!1):(t.showWarning2=!1,t.resumeGame()),t.$apply()}var l,c,u,h,g,p,f,m,v,y,w=null,k=0;t.init=function(){t.showWarning2=!1,t.firsttime=!0,t.prev=0,p=40,f=5,m=80,v=110,t.aspectR=4/3,t.cols=6,t.rows=6,t.gapPercent=1,t.hudheight=100,t.elements=[],t.addedItems=[],y=0,l=i.dimensions(),t.products=n.getItems();var e=angular.element(document.querySelector("#basket-main"));e.height(l.y),c=angular.element(document.querySelector("#basket-container")),u=angular.element(document.querySelector("#basket-countDown")),h=angular.element(document.querySelector("#basket-play")),g=angular.element(document.querySelector("#game")),0==a.orientation||180==a.orientation?t.showWarning2=!0:(t.firsttime=!1,t.showWarning2=!1,h.addClass("blurred"),h.height(g.height()),h.width(h.height()*t.aspectR),t.countdownCount=0,t.startCountDown=!1,t.gameStarted=!1,t.timedout=!1,t.totalTime=30,t.addTimeout=0,t.removeTimeout=0,t.gamePaused=!1)},a.addEventListener("orientationchange",d,!1),t.onStart=function(){g.height("90%"),h.height(g.height()),h.width(h.height()*t.aspectR);var e=h.height();t.minGap=10,t.rows=Math.ceil(t.cols*((e-t.hudheight)/h.width())),t.proheight=(e-t.hudheight-t.minGap*(t.rows+1))/t.rows,t.gapvert=t.minGap+(e-t.hudheight-t.minGap*(t.rows+1)-t.proheight*t.rows)/(t.rows+1),t.proWidth=t.proheight,t.gaphori=t.minGap+(h.width()-t.minGap*(t.cols+1)-t.proWidth*t.cols)/(t.cols+1),t.positions=[];for(var i=0;i<t.rows;i++)for(var n=0;n<t.cols;n++)t.positions.push({x:n,y:i});t.startCountDown=!0,t.gameStarted=!1,t.gamePaused=!1,window.requestAnimationFrame(t.step)},t.adjustScreenElements=function(){h.height(g.height()),h.width(h.height()*t.aspectR);var e=h.height();t.minGap=10,t.rows=Math.ceil(t.cols*((e-t.hudheight)/h.width())),t.proheight=(e-t.hudheight-t.minGap*(t.rows+1))/t.rows,t.gapvert=t.minGap+(e-t.hudheight-t.minGap*(t.rows+1)-t.proheight*t.rows)/(t.rows+1),t.proWidth=t.proheight,t.gaphori=t.minGap+(h.width()-t.minGap*(t.cols+1)-t.proWidth*t.cols)/(t.cols+1),t.positions=[];for(var i=0;i<t.rows;i++)for(var n=0;n<t.cols;n++)t.positions.push({x:n,y:i});for(i=0;i<t.elements.length;i++)1==t.elements[i].isvisible&&(console.log("ghjghjhjhjhj"),t.elements[i].element.style.width=t.proheight+"px",t.elements[i].element.style.height=t.proWidth+"px",t.elements[i].element.style.top=t.hudheight+t.gapvert+t.elements[i].pos.y*(t.proheight+t.gapvert)+"px",t.elements[i].element.style.left=t.gaphori+t.elements[i].pos.x*(t.proWidth+t.gaphori)+"px")},a.addEventListener("resize",s,!1),t.onResizing=function(e){angular.element(document.querySelector("#basket-main")).height(i.dimensions().y),t.adjustScreenElements(),t.$apply()},t.addIcon=function(){var e=document.createElement("div");e.style.width=t.proheight+"px",e.style.height=t.proWidth+"px",e.style.background="#aef321",e.style.position="absolute";var i=parseInt(Math.random()*t.positions.length),n=t.positions.splice(i,1);e.style.top=t.hudheight+t.gapvert+n[0].y*(t.proheight+t.gapvert)+"px",e.style.left=t.gaphori+n[0].x*(t.proWidth+t.gaphori)+"px";var o=m+Math.random()*(v-m);h[0].appendChild(e),t.elements.push({element:e,pos:n[0],element_index:y,productId:t.products[parseInt(t.products.length*Math.random())].id,add:o,isvisible:!0}),e.setAttribute("data-index",y),e.addEventListener("click",function(e){if(0==t.timedout){for(var i=0;i<t.elements.length;i++)if(t.elements[i].element_index==parseInt(e.target.getAttribute("data-index"))){t.elements[i].add>0&&(t.addedItems.push(t.elements[i].productId),t.elements[i].element.style.display="none",t.elements[i].isvisible=!1,t.positions.push(t.elements[i].pos));break}t.$apply()}},!1),y++,n=null},t.step=function(e){if(null==w&&(w=e),k+=e-w,w=e,0==t.gamePaused){if(1==t.gameStarted){t.totalTime=31-Math.ceil(k/1e3)+3;for(var i=0;i<t.elements.length;i++)t.elements[i].add>0&&t.elements[i].add--,t.elements[i].add<=0&&1==t.elements[i].isvisible&&(t.elements[i].element.style.display="none",t.elements[i].isvisible=!1,t.positions.push(t.elements[i].pos));t.addTimeout--,t.addTimeout<=0&&(t.addTimeout=f+Math.random()*(p-f),t.positions.length>0&&t.addIcon()),31-Math.ceil(k/1e3)+3<=0&&(t.timedout=!0)}else 1==t.startCountDown&&(t.countdownCount=4-Math.ceil(k/1e3),4-Math.ceil(k/1e3)<=0&&(t.gameStarted=!0,t.addTimeout=f+Math.random()*(p-f),t.addIcon(),h.removeClass("blurred")));0==t.timedout&&requestAnimationFrame(t.step)}t.$apply()},t.pauseGame=function(e,i){t.gamePaused=!0,w=null},t.resumeGame=function(e,i){t.gamePaused=!1,w=null,1==t.startCountDown&&requestAnimationFrame(t.step)},t.checkout=function(){a.removeEventListener("resize",s,!1),a.removeEventListener("orientationchange",d,!1),n.addItems(t.addedItems),o.go("coupon-typing")}}e.$inject=["$timeout","$scope","DeviceService","DataService","$state","$window","$rootScope"],angular.module("blackFriday").controller("BasketController",e)}(),function(){"use strict";function e(e,t,i,n,o,a,r,s){t.showWarning=!1,0==s.orientation?(t.showWarning=!0,t.$broadcast("pauseScreen")):t.showWarning=!1,s.addEventListener("orientationchange",function(){0==s.orientation?(t.showWarning=!0,t.$broadcast("pauseScreen")):(1==t.showWarning&&t.$broadcast("resumeScreen"),t.showWarning=!1),t.$apply(),alert("working")})}e.$inject=["$timeout","$scope","DataService","$state","$http","DeviceService","UserService","$window"],angular.module("blackFriday").controller("RootController",e)}(),function(){"use strict";function e(e){e.debug("runBlock end")}e.$inject=["$log"],angular.module("blackFriday").run(e)}(),function(){"use strict";function e(e,t,i){e.state("home",{url:"/",templateUrl:"app/main/main.html",controller:"MainController",controllerAs:"main"}).state("add-to-basket",{url:"/add-to-basket",templateUrl:"app/basket/basket.html",controller:"BasketController",controllerAs:"basket"}).state("coupon-typing",{url:"/coupon-typing",templateUrl:"app/checkout/checkout.html",controller:"CheckoutController",controllerAs:"checkout"}).state("results",{url:"/results",templateUrl:"app/results/results.html",controller:"ResultController",controllerAs:"results"}),t.otherwise("/"),i.html5Mode(!0)}e.$inject=["$stateProvider","$urlRouterProvider","$locationProvider"],angular.module("blackFriday").config(e)}(),function(){"use strict";angular.module("blackFriday").constant("malarkey",malarkey).constant("moment",moment)}(),function(){"use strict";function e(e,t,i){e.debugEnabled(!0),i.defaults.headers.post["Content-Type"]="application/x-www-form-urlencoded; charset=UTF-8",i.defaults.transformRequest=function(e){return void 0===e?e:$.param(e)},t.allowHtml=!0,t.timeOut=3e3,t.positionClass="toast-top-right",t.preventDuplicates=!0,t.progressBar=!0}e.$inject=["$logProvider","toastrConfig","$httpProvider"],angular.module("blackFriday").config(e)}(),angular.module("blackFriday").run(["$templateCache",function(e){e.put("app/basket/basket.html",'<div ng-init=init(); style=width:100% id=basket-main><div ng-show="showWarning2==true" style="width: 100%;height:200px;position:absolute;top:0;left:0;background: #00b3ee">orientation warning</div><div ng-show="showWarning2==false" id=basket-container style="background: #acd776;width: 100%;height:100%"><div style="height: 10%;background: #6f8a6c;color: #f8fffd" class=segment>Challenge 1: Add to basket</div><div ng-if="startCountDown==false" style="height: 10%;background: #6f8a6c;color: #f8fffd" class=segment>Instructions</div><div class=game id=game style="height: 80%;position:relative;text-align: center"><div id=basket-play style="background: #ffffff;color: #ffffff;width:100%;height: 100%;position: relative;display: inline-block"><div style="position: absolute;top: 0;left:0;width:100%"><div style="float: left;width:33%;height: 60px;background: #276347">Logo</div><div style="float: left;width:33%;height: 60px;background: #276347"><div>Top deals</div><div>{{totalTime}} secs left.</div></div><div style="float:left;width:33%;height: 60px;background: #276347">{{addedItems.length}} items</div></div></div><div ng-if="startCountDown==false&&gameStarted==false" class=start style="top: 50%;left: 50%; margin-left: -50px; margin-top: -50px"><div ng-click=onStart(); style="color: #ffffff;background: #1b6d85;width: 100px;height:100px">start</div></div><div ng-if="startCountDown==true&&gameStarted==false" class=countDown style="top: 50%;left: 50%"><div id=basket-countDown style="width: 200px;height:200px;background: #657867;color: #ffffff;font-size: 40px;font-weight: bold" ng-bind=countdownCount></div></div><div ng-if="gameStarted==true&&timedout==true" style="top: 100px;left: 200px;width:50%;height:40%;position: fixed;background: #ade576;color: #ffffff"><div>{{addedItems.length}} items in your basket.</div><br><br><div>Its time to checkout and proceed to challenge two</div><div ng-click=checkout(); style="color: #ffffff;background: #1b6d85;width: 100px;height:50px">Checkout</div></div></div></div></div>'),e.put("app/checkout/checkout.html",'<div ng-init=init(); style=width:100% id=checkout-main><div ng-show="showWarning2==true" style="width: 100%;height:200px;position:absolute;top:0;left:0;background: #00b3ee">orientation warning</div><div ng-show="showWarning2==false" id=basket-container style="background: #786876;width: 100%"><div style="height: 10%;background: #6f8a6c;color: #f8fffd">Challenge 2: Apply Coupons</div><div ng-if="startCountDown==false" style="height: 10%;background: #6f8a6c;color: #f8fffd">Instructions</div><div class=game id=game style="height: 80%;position:relative"><div class=play-ground style="width: 96%;height:98%;margin-left: 2%;position:relative"><div id=basket-play style="background: #22aadd;color: #ffffff;width:100%;height: 100%;position: relative"><div style="position: relative;width:100%;height: 20%"><div style="float: left;width:33%;height: 60px;background: #276347">Logo</div><div style="float: left;width:33%;height: 60px;background: #276347"><div>Checkout</div><div>{{totalTime}} secs left.</div></div><div style="float:left;width:33%;height: 60px;background: #276347">{{items.length}} items</div></div><div style="position: relative;height:80%;width: 100%"><div style="position: relative;height:100%;width: 50%;float:left"><div id=code style="width: 100px;height: 50px;border: 1px solid #1b6d85">{{codeDisplayed}}</div></div><div style="position: relative;height: 100%;width: 50%;float:left"><div>Coupons submitted:{{couponCodes.length}}</div><br><br><div>Submit coupon below</div><div><input style="border: 1px solid #1b6d85;background: #56a99d" type=text id=codeTyped ng-model=codeTyped></div></div></div></div></div><div ng-if="startCountDown==false&&gameStarted==false" class=start style="top: 100px;left: 200px"><div ng-click=onStart(); style="color: #ffffff;background: #1b6d85;width: 100px;height:100px">start</div></div><div ng-if="startCountDown==true&&gameStarted==false" class=countDown style="top: 100px;left: 200px"><div id=basket-countDown style="width: 200px;height:200px;background: #657867;color: #ffffff;font-size: 40px;font-weight: bold" ng-bind=countdownCount></div></div></div></div></div>'),e.put("app/main/main.html",'<div class=container ng-init=init();><div ng-if="isWaiting==true" class=wait>Please wait...</div><div ng-if="isWaiting==false"><div ng-if="showWarning==true" style="width: 100%;height:100px;position: fixed;top:0;left:0;background: #00b3ee">Orientation warning</div><div ng-if="showWarning==false"><div>Black Friday Online Training Center</div><div>Get your fingers fit under 1 minute and win amazing prizes.</div><div style="width: 70%;float:left"><div>Description.</div><div><div><input type=text placeholder=Name ng-model=info.name></div><div><input type=email placeholder="Email Address" ng-model=info.email></div><div><span><input type=checkbox ng-model=info.agree> </span><span>I agree to the terms and conditions</span></div><div><button ng-click=takeChallege();>Take Challenge</button></div></div></div><div style="width: 30%;float:left"><scores-and-prizes players={{players}}></scores-and-prizes></div></div></div></div>'),e.put("app/results/results.html",'<div class=container ng-init=init();><div ng-if="isWaiting==true" class=wait>Please wait...</div><div ng-if="isWaiting==false"><div ng-if="showWarning==true" style="width: 100%;height:200px;position: fixed;top:0;left:0;background: #00b3ee">Orientation warning</div><div ng-if="showWarning==false"><div style="width: 70%;float: left"><div>You are now ready for Black friday!</div><div>You\'ve earned your free coupon from SHOP NAME:discount25</div><div>Your final score is :{{score}}</div><div>Your rank is:{{rank}}</div><div><div style="float: left;width: 40%;margin-right: 10%;background: #5c85d6;color: #ffffff" ng-click="shareScore(\'FB\')">Share on FaceBook</div><div style="float: left;width: 40%;margin-right: 10%;background: #4ddbff;color: #ffffff" ng-click="shareScore(\'TW\')">Share on Twitter</div></div><div><div style="width: 80px;height: 50px;background: #fde677;color:#ffffff" ng-click=playAgain()>Play again</div></div><div><div>Invite a friend</div><div><input type=text style="float: left" placeholder="Email address" ng-model=friendEmail><div style="width: 80px;height:30px;float:left;background: #255625;color: #ffffff" ng-click=inviteFriend()>Send</div></div></div><div ng-if="showFBdialog==true" style="position: fixed;background-color: rgba(0, 0, 0, 0.3);width: 100%;height: 100%;top:0;left:0;z-index: 12;text-align: center"><div style="position: absolute;background: #1b6d85;color: #ffffff;width: 50%;height: 50%;display: inline-block;text-align: center;color: #444444;top:50%;left:50%"><input type=text ng-model=faceBookShareMessage style="width: 100%"> <button ng-click=shareToFaceBook(faceBookShareMessage)>Share</button> <button ng-click=fbshareCancelled()>Cancel</button></div></div></div><div style="width: 30%;float: left"><scores-and-prizes players={{players}}></scores-and-prizes></div></div></div></div>'),e.put("app/components/directives/ScoresAndPrizes.html",'<div>Top Scores</div><div ng-repeat="player in players"><span>{{player.name}}</span><span>{{player.score}}</span></div><div><span ng-repeat="p in prizes" style=float:left><img ng-src=p.img><span>{{p.name}}</span></span></div>')}]);
//# sourceMappingURL=../maps/scripts/app.js.map