<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <!--[if lt IE 9]>
            <script src="//html5shim.googlecode.com/sin/trunk/html5.js"></script>
        <![endif]-->
        <meta name="robots" content="index, follow">
        
        <?php   //454521 URL SET_UP, the important stuff for linking
//=============IMPORTANT variable $ROOT_DIRECTORY must be where the project is housed (where the homepage is). ==========================
        $ROOT_DIRECTORY = "php-magic-linking";        //MUST CHANGE THIS OR THE ENTIRE PROJECT WON'T WORK! Default is "php-magic-linking" because that's the name of the git repo, but you can rename the root folder and everything else will work as long as this variable matches the new name
        
        //======Magical code to display errors========\\
        //======COMMENT OUT WHEN YOU'RE FINISHED TESTING========\\
        //error_reporting(E_ALL);           //longer version = 2 lines
        //ini_set('display_errors', '1');
        ini_set('error_reporting', E_ALL);  //short version
        
        
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
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
        
        
        $split_url = explode('/', $path_parts['dirname']);  //split dirname part of the array at each / character (creates array)
        
        $baseLevelIndex = 0;        //used to find the "base directory" in the url. If the site's home is in "topLevel/level1/level2/ROOT_SITE_FOLDER_HERE" then it's 3 folders down, so everything should relate the the url array from index 3. We iterate through the URL array to find the $ROOT_FOLDER, then adjust and make a new array
        for ($i = 0; $i < count($split_url); $i++){     //loop through the URL
            if ($split_url[$i] == $ROOT_DIRECTORY){     //SUPER IMPORTANT ($ROOT_DIRECTORY must match the BASE folder that the site lives inside)
                $baseLevelIndex = $i;
            }
        }
        $folderCount = count($split_url); //this gives an int of how many folders are in the URL
	$folderCountAdjusted = $folderCount - $baseLevelIndex - 1; //subtract $baseLevelIndex to get the base directory (no matter how deep the file structure, this resets it to a base folder. Then subtract 1 to make the "home" directory be 0 folders up from anything
        //0 means the homepage, 1 means top level pages (file is located in 1 folder below $ROOT_DIRECTORY), 2 means 2 levels down, etc.
	
        $split_url_adjusted = $split_url;       //array to hold the URL parts AFTER the $ROOT_DIRECTORY (remove any directories ABOVE $ROOT_DIRECTORY)
        for($i = 0; $i< ($folderCount - $folderCountAdjusted -1); $i++){   //remove the beginning indices of the array (anything before $ROOT_DIRETORY)
            unset($split_url_adjusted[$i]);     //actually remove the element, but the indices will be messed up
        }
        $split_url_adjusted= array_values($split_url_adjusted);     //array_values re-indexes the array. Now this contains a list folderis in the the URL including & AFTER the $ROOT_DIRECTORY
        
        $containing_folder =  $split_url_adjusted[count($split_url_adjusted) -1] ; //IMPORTANT this gets the very last folder in the $split_url_adjusted array (the very last index of an array is 1 less than its size, hence: count($split_url_adjusted) -1 ). This folder "contains" the current page file. Used almost everywhere to tell what page I'm on since all my pages are called 'index.php' but have unique cotaining-folder names
        if($folderCountAdjusted == 0){      //special case for the homepage. Since its actual containing folder is the contents of $ROOT_DIRECTORY, it must be overridden to equal "index". This is to avoid confusion if $ROOT_DIRECTOY is NOT a a good name for the site. This disregards where the site is located & just make the homepage's containing folder = "index". ALSO USED TO PRINT ID'S IN THE BODY TAG FOR EACH PAGE
            $containing_folder = 'index';
        }
	$fileName = $path_parts['filename'];		//not used much, but just in case
        $dirName = $path_parts['dirname'];              //the url of folders (excluding filename). Not used much
	
        $cdUpRefArray = array("", "../", "../../", "../../../", "../../../../");        //"Change Directories Up Reference Array" hold string value of what to type to navigate up a directory. 0 folders down corresponds to the 0th index or "", 1 folder down corresponds to the 1st index or "../" (so that links go to the right place)
        $upFolderPlaceholder = $cdUpRefArray[ $folderCountAdjusted ];   //this is used extensively to make links in subfolders go to the right location. It checks how many folders down it is, then prints the correct number or ../ to get back to the $ROOT_DIRECTORY before going down any more directories
        //end path setup
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        
        
        //454521 Debugging section, ignore if not needed
        $debug = false;  //Localhost says error if not define here, hope it doesn't hurt
        if ($debug) {
            print "<p>Domain" . $domain;
            print "<p>php Self" . $phpSelf;
            print "<p>Path Parts<pre>";
            print_r($path_parts);
            print "</pre>";
        }
        //end debugging
        
        
        //454521 CONVERTING CONTAIING FOLDERS TO PAGE NAMES section
        function convFolder2PgTitle($folder2Convert){       //function takes in a string (folder name) & makes it more readable. 
            $pgTitleOutput = str_replace("-" , " / ", $folder2Convert);     //replace dashes with slashes and spaces
            $pgTitleOutput = str_replace("_" , " ", $pgTitleOutput);        //replace underscores with spaces (multi-word title)
            return ucwords($pgTitleOutput);                                 //capitalize 1st letter of each word
        }
        $pageTitle = convFolder2PgTitle($containing_folder);    //This is basiaclly the "Page Name". Can easily be added to all <title> tags & printed as the 1st <h1> on pages. ALL BASED ON CONTAINING FOLDER
        if ($folderCountAdjusted == 0) {    //if it's the homepage, hardcode it instead of the base folder where the site's located
            $pageTitle = "Home";
        }
        
        $tagLine = " - Your Tagline";  //Change this to match the tagline/slogan of your site. This will appear @ the end of every title page. Like the " - Wikipedia, the free encyclopedia" at the end of every Wikipedia Page
        ?>
        
        <title><?php echo $pageTitle.$tagLine ; ?></title><!-- print the title by concatenating the current page title, & global site tagline/slogan -->
    <?php       //454521 ACTIVE PAGE / CURRENT PAGE setion
        //-----MUST EDIT THIS (ctrl+F & search for  36714768356 ---------------------------------
        //YOU MUST LIST ALL THE PAGES ON THE SITE! But $pageArrayDropDown1 means anything that's in a 1st level dropdown. You DON'T have to organize them into sepatate arrays for each individual dropdown, just put pages that are the same distance down from the $ROOT_DIRECTORY in appropriate arrays.
        //THIS ALSO MEANS PUT $ROOT_DIRECTORY IN $pageArrayTop EVEN THOUGH IT'S NOT 1-LEVEL DOWN FROM ITSELF, IT'S ON THE SAME LEVEL OF THE NAV
        //unused keys are OK, but pages that AREN'T in these arrays aren't so good
        $pageArrayTop = array($ROOT_DIRECTORY, 'portfolio', 'tests', 'about');   //make a list of the ALL pages
        $pageArrayDropDown1 = array ('portfolio_1', 'portfolio_2', 'examples', 'test_1', 'test_2');     //1st level of dropdown
        $pageArrayDropDown2 = array ('example_1');
        $activePageArrayTop = array_fill_keys($pageArrayTop, '');       //initialize associative array to hold the page name & the text "activePage" when that page is folder in the URL. ("activePage" is a css class ). Initialize it to have keys matching $pageArrayTop, but empty
        $activePageArrayDropDown1 = array_fill_keys($pageArrayDropDown1, '');
        $activePageArrayDropDown2 = array_fill_keys($pageArrayDropDown2, '');

        //This function analyzes a level of the folder tree (whether its top level nav, or a dropdown) to find any current active folders in the URL. We want we want an indicator to the user that knows where they are. This is done by printing the class="activePage" on active links in the nav menu, then styling the class "activePage" with css to have a highlight background color. So if it's the homepage, the "Home" link will be highlighted. If it's a dropdown, all levels of nav required to get to that page ar highlighted. Like the "Example 1" page is in "/portfolio/examples/example_1/index.php", so the top-level link to "Portfolio", the 1st-level dropdown to "Examples" and the 2nd-level dropdown to "Example 1" are all highlighted.
        //$folderLevelToCheck is important & must match the level of the arrays that are being passed in. For instance, to analyze $pageArrayTop (the links on the top-level nav), you must pass in 0 as the 4th argument when calling the function
        function fillActivePageArrays(&$arrayOfPages, &$activeArrayToFill, $split_url_adjusted2, $folderLevelToCheck){  //use & to pass arrays BY REFERENCE
            for($i = 0; $i < count($arrayOfPages); $i++){      //loop through the page array that was passed in
                if($split_url_adjusted2[$folderLevelToCheck] == $arrayOfPages[$i]){   //if a folder of the URL matches the key stored in the page Array
                    $activeArrayToFill[ $split_url_adjusted2[$folderLevelToCheck] ]= "activePage";     //print "activePage" in the $activeArrayToFill, at the index of "$folderLevelToCheck"
                    break;      //break out of the loop when it finds an active page. (Avoids 2 pages on the same folder level both being "active")
                }
            }   //at this point, $activeArrayToFill should have '' stored in all indecies & 'activePage' in 1 place
        }
        
        //call function to fill arrays with "activePage" in correct place. But only bother analyzing multiple folder levels if the current page is a dropdown. Homepage is a special case, but for all deeper folder levels, they check >=. For instance, the "About Page" is a top-level page, so dropdowns shouldn't even be analyzed. But "Portfolio 1" is a Level 1 Dropdown so 2 levels of folders should be searched to find 'activePage'
        if($folderCountAdjusted == 0){      //special case for the homepage. Since it's in $ROOT_DIRETORY, it's essentialy 0 folders down from itself
            $activePageArrayTop[$ROOT_DIRECTORY] = 'activePage';    //must hardcode activePage
        }
        if($folderCountAdjusted >= 1){
            fillActivePageArrays($pageArrayTop, $activePageArrayTop, $split_url_adjusted, 1);
        }
        if($folderCountAdjusted >= 2){
            fillActivePageArrays($pageArrayDropDown1, $activePageArrayDropDown1, $split_url_adjusted, 2);
        }
        if($folderCountAdjusted >= 3){
            fillActivePageArrays($pageArrayDropDown2, $activePageArrayDropDown2, $split_url_adjusted, 3);
        }   //add more if statements for deeper level dropdowns (if needed)
    ?>
        
    <?php   //454521 META DESCRIPTIONS section
        $pagesArrayAll = array_merge($pageArrayTop, $pageArrayDropDown1, $pageArrayDropDown2);      //list of all pages (folders on the entire site)
        $pageMeteDescriptions = array_fill_keys($pagesArrayAll, '');    //fill the array with empty descriptions in case the text file doesn't have them
        unset($pageMeteDescriptions[$ROOT_DIRECTORY]);      //the line abovve copied the value of $ROOT_DIRECTORY as the homepage description key, but we want to refer to it as "index" instead. So, remove this key & add a new blank one
        $pageMeteDescriptions['index'] = '';
        // All other pages are set in the text file "meta_descriptions.txt". ORDER DOESN'T MATTER
        
        $descFile = fopen($upFolderPlaceholder."non-pages/descriptions/meta_descriptions.txt", "r");    //open the description file
        //FILE CONTENTS MUST MATCH THIS FORMAT:     the first word is the containing-folder, then a space, THEN your description.
        //1 PAGE PER LINE! So 'Portfolio 1' description is stored: "portfolio_1 This is the Portfolio 1 description". (The "portfolio_1" and the space will be removed, leaving you with whatever comes next as the actual description
        //If the page isn't in $pageMeteDescriptions but IS IN THE TEXT FILE, it will be added
        //If a page is in $pageMeteDescriptions but not in the text file, description will be blank
        //IF IT ISN'T IN EITHER, THERE WILL BE AN ERROR
        //MAKE SURE TO LIST ALL PAGES
            //preferably, you add them to $pageArrayTop, $pageArrayDropDown1 etc.
        while( ($line = fgets($descFile)) !== false){  //read through file until reaches end, & copy each line to $line variable
            $line = explode(' ', $line, 2);     //Split @ 1st space. Now $line is an array until the end of the loop
            $arrayKey = $line[0];       //this is the 1st word, the folder name (or page name)
            $description = str_replace("\n", "", $line[1]);     //description is everything after the 1st word on the line. Also remove newline characters
            $pageMeteDescriptions[$arrayKey] = $description;    //FINALLY store the description in the array with corresponding key
        }
        fclose($descFile);
    ?>

        <meta name="author" content="Your Name"><!-- Add your name/company -->
        <meta name="description" content="<?php echo $pageMeteDescriptions[$containing_folder] ?>">     <!-- description from text file (lines above) -->

        <link rel="icon" type="image/png" href="<?php echo $upFolderPlaceholder ?>images/0_components/favicon.png">

        <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- for mobile friendly -->
        <link href='<?php echo $upFolderPlaceholder ?>non-pages/css/menu.css' rel='stylesheet' type='text/css' media='screen' /><!-- nav menu -->
        <link href='<?php echo $upFolderPlaceholder ?>non-pages/css/style.css' rel='stylesheet' type='text/css' media='screen' />
    
    </head>
    <!-- ################ begin body section ######################### -->
    <?php
        echo '<body id="'.$containing_folder.'">';      //prints a unique id for each page
    ?>
    <a class="skipToContent" href="#actualMainContent">Skip to Main Content</a><!-- accessibility skip button, positioned off screen -->
    <?php
        include ($upFolderPlaceholder."non-pages/php-include/nav.php");
        include ($upFolderPlaceholder."non-pages/php-include/header.php");
    ?>