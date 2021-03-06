﻿<!DOCTYPE HTML>
<?php
session_start();
?>

<html>

<?php

include 'header.php';
include '/classes/error.php';
include '/classes/url.php';

?>

<body>
<?php

// Below, I have a few cases meant to prevent things from going wrong. Either a missing POST, a mission SESSION, or // both. Basically, fix it if it's fixable, or break it in a way that it gives an error.

if ((isset($_SESSION["api"]) == false) && (isset($_POST["apikey"]) == false)) {
$_SESSION["api"] = "broken";
} elseif (isset($_POST["apikey"]) == false) {
// This space purposefully left blank.
 }  elseif (isset($_SESSION["api"]) == false) {
$_SESSION["api"] = $_POST["apikey"];
}

// Unsetting the json session each time this page is loaded prevents the same list data from loading multiple times.
unset($_SESSION["json"]);

// Establishing all the data needed for an API call, and grabbing the shard from the API key given so that it can
// easily accommodate any API key, including single or double digit shards.
$pagesize = 100;
$apikey = $_SESSION["api"];
$shard = substr("$apikey", 33, strlen($apikey));
$url = "https://" . $shard . ".api.mailchimp.com/3.0/lists?count=9999";

// Curling to the data established above: initializing the curl, returning the data, saving it, then closing the curl.

// Turning the returned JSON string into an array is super easy in PHP.
$_SESSION["json"] = json_decode(call($url, $apikey), 1);

$json = $_SESSION["json"];

// Before doing anything with the returned data, check to make sure it a) exists or b) is the right data. 
// If it's an error, return the MailChimp error data.
if ($_SESSION["json"] == false) {
$errorShrug = new Error();
$errorShrug->firstLine = "Invalid API Key.";
unset($_SESSION["api"]);
echo $errorShrug->displayError();
} elseif(isset($json["title"]) == true) {
$errorStatus = new Error();
$errorStatus->firstLine = 'Status '. $json["status"] . ': ' . $json["title"];
unset($_SESSION["api"]);
echo $errorStatus->displayError();
} else {

// If no errors are found, then we establish the framework where the dynamic data will populate.
echo '<table style="width: 40%">
<tr>
<td><a href="/austin/index.php">back</a></td>
<td align="right"><a href="/austin/index.php">log out</a></td>
</tr>
</table>

<table style="outline: thin solid; width: 40%; border-collapse: collapse">
<tr bgcolor="#6DC5DC">
<td width="50%"><b>List Names</b></td>
<td width="30%"><b>List IDs</b></td>
<td width="20%"><b>Subscribers</b></td>
</tr>
</table>

<table style="outline: thin solid; width: 40%">';
   
// Getting the number of total items returned in the API call for tracking purposes (minus one, because arrays 
// start at zero, not one) for use in iteration below.
$totalitems = $json["total_items"] - 1;

/* Since I've never seen an account with more than about 150 lists, I think it's safe to iterate over the
entire set of returned lists. Even if they have a few hundred, it's not a huge deal. No pagination needed at this time. */

for($i = 0; $i <= $totalitems; $i++) {

echo '<tr style="outline: thin solid">
<td width="50%"><a href="/austin/members.php?list=' . $json["lists"]["$i"]["id"] .  '&offset=0">' . $json["lists"]["$i"]["name"] . "</a></td>" . 
'<td width="30%">' . $json["lists"]["$i"]["id"] . '</td>
<td width="20%">' . $json["lists"]["$i"]["stats"]["member_count"] . "</td>";
  
} 

}

?>
    
    
</table>

</body>
<?php 
include 'footer.php'; 
?>
</html>
