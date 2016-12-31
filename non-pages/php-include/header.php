    <!-- header.php -->
    <header>
        <p>Header (logo, title, slideshow, etc. or optional)</p>
    </header>

    <section class="breadcrumbs">
        <?php
            if($folderCount != 0){      //don't bother with breadcrumbs on the homepage
                $tempBreadLink = $domain . '/';     //$tempBreadLink holds the patial URL @ each level, the loop starts @ the top & prints out links adding to the end of this varable 
                for($i = 0; $i < $folderCount; $i++){       //start @ 0 & go until we've runo ut of folders
                    $tempBreadLink .= $split_url_adjusted[$i] .'/';     //add a folder to the to the end
                    echo '<a href="' . $tempBreadLink . '" >' . ($i== 0 ? 'Home' : convFolder2PgTitle($split_url_adjusted[$i]) ) .'</a> > ';    //print link to the partial URL. Using if-then shorthand on 1 line. The "Homepage" level is special. We want to print "Home" instead of whatever $ROOT_DIRECTORY is, so we have a special case when $i = 0.
                    //For all other pages, call convFolder2PgTitle() & send in $split_url_adjusted[$i] to get a human readable link title based on the folder
                    
                    //below is a more verbose version, maybe easier to understand.
    //                if($i == 0){    //homepage case
    //                    echo '<a href="' . $tempBreadLink . '" >' . 'Home'  .'</a> > ';
    //                }
    //                else{
    //                    echo '<a href="' . $tempBreadLink . '" >' . convFolder2PgTitle($split_url_adjusted[$i]) .'</a> > ';
    //                }
                }

                echo '<strong>' . $pageTitle . '</strong>';     //After all the links are printed, just print the current page name (bold is optional)
            }
        ?>
    </section>

    <main id="actualMainContent"><!-- id for accessibility skip button. this div begins the actual content but is actually in header.php -->
    <!-- end header.php -->
