<?php include("non-pages/php-include/top.php"); ?><!-- MUST MANUALLY LINK TO top.php & USING ../ but use $upFolderPlaceHolder from now on -->
    <h1 class="cent"><?php echo $pageTitle . $tagLine; ?></h1><!-- homepage can be special and also print the tagline if needed -->
    <p>text</p>
</main><!-- close main, opened in header.php -->

<?php include($upFolderPlaceholder . "non-pages/php-include/footer.php"); ?>
