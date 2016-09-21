<?php
/**
 * Template Name: WPAPI Home Page
 */
 ?>

<!doctype html>
<html ng-app=blackFriday>
<head><meta charset=utf-8><title>blackFriday</title>
<meta name=description content="">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"><!-- Place favicon.ico and apple-touch-icon.png in the root directory --><link rel=stylesheet href=ajs/styles/vendor.css><link rel=stylesheet href=ajs/styles/app.css>
<base href="/" target="_blank">
</head>

<body><!--[if lt IE 10]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
	<div ui-view></div>
	<script src=ajs/scripts/vendor.js></script>
	<script src=ajs/scripts/app.js></script>
	<script>var checkSum='12345';
    document.onselectstart=new Function ("return false");
    //facebook
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '1808121762750163',
            xfbml      : true,
            version    : 'v2.7'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
	</body></html>