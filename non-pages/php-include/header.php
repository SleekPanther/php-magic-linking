<!-- header.php -->
<header>
    <p>Header (logo, title, slideshow, etc. or optional)</p>
</header>

<!--breadcrumbs trail -->
<section class="breadcrumbs">
    <?php
        if($folderCountAdjusted != 0){      //don't bother with breadcrumbs on the homepage
            $tempBreadLink = $domain . '/';
            for($i = 0; $i < $folderCountAdjusted; $i++){
                $tempBreadLink .= $split_url_adjusted[$i] .'/';
                echo '<a href="' . $tempBreadLink . '" >' .$tempBreadLink  .'</a> > ';
            }

            echo '<strong>' . $pageTitle . '</strong>';
        }
        
    ?>
</section>

<main id="actualMainContent"><!-- id for accessibility skip button. this div begins the actual content but is actually in header.php -->
<!-- end header.php -->
