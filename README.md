# PHP Magic Linking Template

**NEVER WORRY ABOUT PUTTING ../ BEFORE LINKS EVER AGAIN!**

You often store each page in a separate folder to allow more readable urls. Small sites can get away with having `index.php`, `about.php`, `gallery1.php`, `gallery2.php` etc in the same folder, but it's not very organized or scalable. Instead of `mySite.com/about_us_page.php`, you want `mySite.com/about/`. You putting an page  `index.php` in the a folder `about/`. (Of course `mySite.com/about` would be identical to `mySite.com/about/index.php` in this case)

You can have multiple layers of sub-directories: a main gallery page `mySite.com/gallery` that lists the individual sub-galleries like ``mySite.com/gallery/indoor` or `mySite.com/gallery/outdoor`

But you run into trouble linking between pages. If all pages are in the same folder, you can use **1 copy of global navigation** called `nav.php` & use a **php include** to keep the same navigation menu on all pages. But nested sub-directories cause problems.

<s>A cheater "solution"  is to have a copy of `nav.php` for each level of directories</s><br>
DON'T TRY THIS! Trust me, it's a pain to maintain 3 copies of the same file

**The Situation (Problematic Linking)**
 - Going from the **homepage** (`index.php` in the root folder of `mySite.com`) to the "about page" would be **`href="about/"`** or `href="about/index.php"`
 - Linking **2-directories** down would be **`href=gallery/indoor/`**
 - Linking **from** the *homepage* always goes **down into** sub-directories
 <br>
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
- **Add `<?php echo $upFolderPlaceHolder ?>` before any link**
- The code in `top.php` will magically print the correct number or `../` in your link to make it work no matter how many folders deep the file is located

#[Download Complete Project Zip](https://github.com/SleekPanther/php-magic-linking/archive/master.zip)#

##Major Features##

1. Consistent nav on all pages (using the same `php include`)
2. Link to any page without worring about navigating up directories <br>
(*also applies to css stylesheets, images & any files on your site*)
3. Automatic breadcrumb links
4. Automatic meta descriptions via easily editable text file
3. `<title>` Tags that match the current page
4. Print the page name as the `<h1>` automatically (no hard-coding)
5. Identify the current page & highlight the current link in nav (so the user knows where they are in the site)
6. Print unique ID's for each page <br>
can use css to apply styles to **target on only 1 page**

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

123

###Linking Usage Examples###

- **Use `<a href="<?php echo $upFolderPlaceHolder ?>index.php">Home</a>` to link to "Homepage"** instead of `<a href="index.php">Home</a>`
- 9099

###Optional URL Features###

- a

##Future Feature Goals##

- del meta name, desc
- Printing Breadcrumbs (separateed by >, current page=bold, maybe just use "page name" variable & print containing folders, MUST HAVE INDEX PAGE IN ALL FOLDERS )
- current page activePage class explain
- fewer pages (home, portfolio, test, about)
- remove most css
- link to menu git project <br>
https://github.com/SleekPanther/css-dropdown    http://www.cssscript.com/pure-css-mobile-compatible-responsive-dropdown-menu/ 
- less css, no bother mobile friendly
- css border on header, <nav, footer
- test boring page with section outlines (**line above**), lipsum paragraphs 
- -meta desccriptions addition
- page structurte (top nav header content footer) EXPLAIN HEADER REARRANGEMENT (start of `<main>`)
- dont worry about less menu file
- list all pages (36714768356)
- more details in php comments
- Search "454521" using `Ctrl+F` or `Cmd+F` to skip to important sections
