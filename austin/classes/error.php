<?php

class Error
{
	
    public $errorTableTop = '<br><table style="outline: thin solid; width: 40%; border-collapse: collapse"><tr bgcolor="#6DC5DC"><td><b>Error</b></td></tr><tr><td>';
    public $firstLine;
    public $errorTableMiddle = '</td></tr><tr><td>';
    public $secondLine1 = '<a href="/austin/';
    public $errorDestination = "index.php";
    public $secondLine2 = '">Click here</a> to go back and start over.';
    public $errorTableBottom = '</td></tr></table><br>';

    public function displayError() 
    {
            return $this->errorTableTop . $this->firstLine . $this->errorTableMiddle . $this->secondLine1 . $this->errorDestination . $this->secondLine2 . $this->errorTableBottom;
    } 

}
