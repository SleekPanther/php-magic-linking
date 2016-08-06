# PHP Magic Linking Template

This PHP template allows linking to any folder or file without manually navigating up &amp; down directories with   ../

In web design you often  store each page in a separate folder to allow more readable urls. Instead of `mySite.com/abous_us_page.php`, you'd want `mySite.com/about` which means putting a page called `index.php` inside a folder called `about`. (Of course `mySite.com/about` would be identical to `mySite.com/about/index.php`)

You can have multiple subdirectories: like a main gallery page `mySite.com/gallery` with a bunch of individual galleries ``mySite.com/gallery/indoor` or `mySite.com/gallery/outdoor`

But the problem of linking between pages soon arises. If everything is in 1 folder, you could use **1 copy of global navigation** and use a **php include** to keep things the same on all pages. But nestes subdirectories cause problems.

**The Situation (Problematic Linking)**
 - Going from the "homepag"e (`index.php` in the root folder of `mySite.com`) to the "about page" would be: **`href="about/"`** or `href="about/index.php"`
 - And navigating to a page 2-directories down would be: `href=gallery/indoor/`
 - Linking **from** the "homepage" would always be going **down into** directories
 - &nbsp;
 - On the **about page**, going to the "homepage" would be: **`href="../"`** or `href="../index.php"`
 - But getting to the other gallery page would be **`href="../gallery/indoor`**
 - &nbsp;
 - And going **from** the "indoor galley" **to** "about" would be: `href="../../about/"`
 - &nbsp;
 - &nbsp;
 - HTML requires 3 different ways to navigate to the same galley page, all based on where the files are in relation to each other

##The Solution##
- **Act as if all links start in the root folder**
- This code analyzes the URL to find how many `/` characters are present
- These tells you how many folders deep a file is stored
- Act as if you are in the root directory for all links
- **Add `<?php echo $upFolderPlaceHolder; ?>` before any link**
- The code in `top.php` will magically print the correct number or `../` in your link to make it work no matter how many folders deep the file is located

#[Download Complete Project Zip](https://github.com/SleekPanther/php-magic-linking/archive/master.zip)#

##Code Details###

- Almost every actual viewable page is called `index.php` <br>
BUT each have their own folder with unique names (**the *containing folder* is very important to identify the current page**)
- **Folder names should be lowercase NO SPACES**
- `-` (hyphen characters) will be replaced with `/` (forward-slashes)
- `_` (underscores) will be replaces with spaces
- The fitst letter of each word will be capitalized
- Every page must manually link to `top.php` <br>
`<?php include("non-pages/php-include/top.php"); ?>` works for the **HomePage** <br>
But for pages 1 level below the homepage folder it must be: `<?php include("../non-pages/php-include/top.php"); ?>`
- Now subsequent PHP Includes can use `$upFolderPlaceHolder` to take care of how many times to prepend `../` to the link. <br>
`<?php include($upFolderPlaceholder . "non-pages/php-include/footer.php"); ?>`

###Linking###

-

###Linking Usage Examples###

- **Use `<a href="<?php echo $upFolderPlaceHolder; ?>index.php">Home</a>` to link to "Homepage"** instead of `<a href="index.php">Home</a>`
- 9099

###Optional URL Features###

- a
- b


##Future Feature Goals##

- Printing Breadcrumbs (separateed by >, current page=bold, maybe just use "page name" variable & print containing folders, MUST HAVE INDEX PAGE IN ALL FOLDERS )
- ALSO USED TO PRINT ID'S IN THE BODY TAG FOR EACH PAGE
