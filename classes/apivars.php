<?php

$pagesize = 100;
$apikey = $_SESSION["api"];
$shard = substr("$apikey", 33, strlen($apikey));
