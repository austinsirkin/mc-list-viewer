<!DOCTYPE HTML>
<html>

<?php

include 'header.php';
include '/classes/error.php';
include '/classes/url.php';

?>

<body>
<?php
session_start();

// First things first, if the session is no longer active or is missing an API key in the URL, 
// they need to start over.
if ((isset($_SESSION["api"]) == false) && (isset($_GET["list"]) == false)) {
$errorApi = new Error();
$errorApi->firstLine = "You're missing a valid API key.";
echo $errorApi->displayError();
} elseif ((isset($_GET["list"]) == false) && (isset($_SESSION["api"]) == true)) {
$errorList = new Error();
$errorList->firstLine = "Invalid list selection. Select a new list.";
$errorList->errorDestination = "lists.php";
echo $errorList->displayError();
} else {

// Checking against the offset to prevent errors in the API call below, specifically to avoid people 
// manually manipulating the offset in the URL.
if (isset($_GET["offset"]) == false) {
$offset = 0;
} elseif ($_GET["offset"] <= 0) {
$offset = 0;
} else {
$offset = $_GET["offset"];
}

/*
Establishing all the data needed for an API call, as well as grabbing the shard from the API key given so that it can easily accommodate any API key, including single or double digit shards. Also, since lists can be huge, setting a pagesize variable in case I later want to offer other options for how many records to view at once.
*/

include 'classes/apivars.php';
$list = $_GET["list"];
$url = 'https://' . $shard . '.api.mailchimp.com/3.0/lists/' . $list . '/members?offset=' . $offset . '&count=' . ($offset + $pagesize);

// Making the API call using a function directly into the decoder.
$_SESSION["json"] = json_decode(call($url, $apikey), 1);

$json = $_SESSION["json"];

// Check to make sure that the call was actually successful, and that we have the correct data. 
// If not, pass along the MailChimp error data.

if(isset($json["title"]) == true) {
$errorStatus = new Error();
$errorStatus->firstLine = 'Status '. $json["status"] . ': ' . $json["title"];
echo $errorStatus->displayError();
} else {
// Getting the number of total items (minus one, because arrays start at zero) for use in iterations below.
$totalitems = $json["total_items"] - 1;

// Now that we know what the total items are, I can close any remaining loopholes for exploiting the offset parameter in the URL.
if ($offset <= 0 || $offset == false) {
$offset = 0;
} elseif ($offset >= ($totalitems)) {
$offset = ($totalitems);
}

?>

<!-- Establishing a table to hold the navigation and pagination display -->

<table style="width: 40%">
<tr>
<td><a href="/austin/lists.php">back</a></td>
<td><center>

<?php 
include 'recordnumbers.php'; 
?>

</center></td>
<td align="right"><a href="/austin/index.php">log out</a></td>
</tr>
</table>

<!-- Now starting the static HTML header information for the dynamic PHP data below -->
  
<table style="outline: thin solid; width: 40%; border-collapse: collapse">
<tr bgcolor="#6DC5DC">
<td><b>Email Addresses</b></td>
<td width="20%"><b>Status</b></td>
</tr>
</table>

<table style="outline: thin solid; width: 40%">
<?php

// Since the offset is stored in the URL and the heavy lifting has been pushed to MailChimp, we can 
// iterate over 0-99 for every page. If a record doesn't exist, it simply won't be printed.
for($i = 0; $i <= 99; $i++) {

if (isset($json["members"]["$i"]["email_address"]) == true) {
echo '<tr style="outline: thin solid">
<td>' . $json["members"]["$i"]["email_address"] . '</td>
<td width="20%">' . $json["members"]["$i"]["status"] . "</td>
</tr>";
} else {
// This space left intentionally blank.
}
}

// Closing out the dynamic table, and starting a static table below to contain navigation and pagination.
echo '</table>

<table style="width: 40%">';

// A few different cases for navigation, as we want different options to display at different times.
if (($totalitems - $pagesize) <= 0) {

// This space left intentionally blank, as we don't need any navigation if the total number of items is less 
// than the page size. One page will suffice; no need to navigate.

} elseif ($offset <= 0) {

// If the offset is less than or equal to zero, there's no need for a "previous" button.
echo '<tr>
<td style="width: 20%">    </td>
<td><center>';

include 'recordnumbers.php';

echo '</center></td>
<td style="width: 20%" align="right"><a href="/austin/members.php?list=' . $_GET["list"] . '&offset=' . ($offset + $pagesize) . '">next</a></td>
</tr>';

} elseif ($offset >= ($totalitems - $pagesize)) {

// Here the offset is greater than or equal to the total items minus the page size, meaning that 
// we're on the last past of the list, and don't want a next button.

echo '<tr>
<td style="width: 20%"><a href="/austin/members.php?list=' . $_GET["list"] . '&offset=' . ($offset - $pagesize) . '">prev</a></td>
<td><center>';

include 'recordnumbers.php';

echo '</center></td>
<td style="width: 20%">    </td>
</tr>';

} elseif ($offset > 0) {

// If the offset is greater than zero, but not between the total items minus the page size range, it means 
// we're in the middle of the list, and want both a previous and a next button.
echo '<tr>
<td style="width: 20%"><a href="/austin/members.php?list=' . $_GET["list"] . '&offset=' . ($offset - $pagesize) . '">prev</a></td>
<td><center>';

include 'recordnumbers.php';

echo '</center></td>
<td style="width: 20%" align="right"><a href="/austin/members.php?list=' . $_GET["list"] . '&offset=' . ($offset + $pagesize) . '">next</a></td>
</tr>';

}
}
}

  
?>    
</table>

</body> 
<?php include 'footer.php'; ?>
</html>
