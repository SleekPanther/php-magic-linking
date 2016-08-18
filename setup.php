<?php 
    //Place this file in the root directory of your site to identify what your server thinks is the "root directory"

	ini_set('error_reporting', E_ALL);  //short version to show errors

    
    // PATH SETUP, (making sure it uses https)
    $domain = "http://";     //commenting out next 5 lines didn't work
    if (isset($_SERVER['HTTPS'])) {   //OLD WAY, DIDN'T USE
        if ($_SERVER['HTTPS']) {
            $domain = "https://";
        }
    }
    
    $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, "UTF-8");
    $domain .= $server;     //concatenate server to domain yielding "http://[your_domain_here]" or "https://[your_domain_here]"
    
    $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");     // Yields string of the url AFTER the domain (so just the folders & exact file). Use htmlentites to remove any suspicous vales that someone may try to pass in. htmlentites helps avoid security issues. //## $_SERVER['PHP_SELF'] returns full url path and file extension, htmlentities() just converts special characters
    $path_parts = pathinfo($phpSelf);       //get an associative array of the url with dirname, basename, extension & filename
    
    
    $split_url = explode('/', $path_parts['dirname']);		//split dirname part of the array at each / character (creates array)

    print_r($split_url);        //print contets of the array. This shows you the folders in the URL

    echo "<br>". $split_url[count($split_url) -1]." &nbsp;&nbsp;&nbsp;&nbsp;" .'Is the last folder in this array. It tells you what the value of $ROOT_DIRECTORY needs to be';
?>