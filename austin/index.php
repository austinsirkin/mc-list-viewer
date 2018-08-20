<?php
// Destroying any existing session will allow the user to start over fresh each time they hit index.
session_start();
session_unset();
session_destroy();

?>

<html>
<head>
<style>

body {
    font-family: "Helvetica Neue", "HelveticaNeue-Light", "Helvetica Neue Light", Helvetica, Arial, "Lucida Grande", sans-serif;  
    font-weight: 400;
    }

</style>
</head>

<body>
<table style="width: 40%"><tr><td align="right"><a href="http://www.mailchimp.com">visit mailchimp</a></td></tr></table>

<!-- Using a table for the form, as I just thought it looked nice, and it was quick -->

<table style="outline: thin solid; width: 40%">
<tr bgcolor="#6DC5DC"><th height="30">Please Enter a <sub><img src="/austin/mclogo.png" alt="MailChimp" height="20" width="80"></sub> API Key</th></tr>
<tr style="outline: thin solid"><td><br>

<!-- Including a form action using the htmlspecialchars to prevent scripting exploits in the URL. A cool trick I saw on the internet! -->

<form action="

<?php 
echo htmlspecialchars("/austin/lists.php"); 
?>

" method="post">
<center><input type="text" size=60" name="apikey"></center>
<br>
<center><input type="submit"></center>
</form>
</td></tr>
</table>
</body>

<?php
include 'footer.php'; 
?>

</html>
