<?php
// created by vikram to check if the request is coming from our own server.

session_start();
echo "SID: ".SID."<br>session_id(): ".session_id()."<br>COOKIE: ".$_COOKIE["PHPSESSID"];

echo '</br>cookie id '.$_COOKIE['id'];