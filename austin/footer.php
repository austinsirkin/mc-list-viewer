 <footer><table style="width: 40%"><tr><td><center>&copy;Austin Sirkin. Last modified on 
<?php 

  // Gotta use that getlastmod function for the footer! Everyone knows that!

echo date("F d, Y \a\\t h:i:s a e", getlastmod()); 
    
    ?></center></td></tr></table></footer>
