# PHP Magic Linking Template

**NEVER WORRY ABOUT PUTTING ../ BEFORE LINKS EVER AGAIN!**

You often store each page in a separate folder to allow more readable urls. Small sites can get away with having `index.php`, `about.php`, `gallery1.php`, `gallery2.php` etc in the same folder, but it's not very organized or scalable. Instead of `mySite.com/about_us_page.php`, you want `mySite.com/about/`. You putting an page  `index.php` in the a folder `about/`. (Of course `mySite.com/about` would be identical to `mySite.com/about/index.php` in this case)

You can have multiple layers of sub-directories: a main Gallery Page `mySite.com/gallery` that lists the individual sub-galleries like ``mySite.com/gallery/indoor` or `mySite.com/gallery/outdoor`

But you run into trouble linking between pages. If all pages are in the same folder, you can use **1 copy of global navigation** called `nav.php` & use a **`php include`** to keep the same navigation menu on all pages. But nested sub-directories cause problems.

<s>A cheater "solution"  is to have a copy of `nav.php` for each level of directories</s><br>
DON'T TRY THIS! Trust me, it's a pain to maintain 3 copies of the same file

###The Situation (Problematic Linking)###
 - Going **from the** ***Homepage*** (`index.php` in the root folder of `mySite.com`) **to the** ***About Page***  would be **`href="about/"`** or `href="about/index.php"`
 - Linking **2-directories** down would be **`href=gallery/indoor/`**
 - Linking **from the** ***homepage*** always goes **down into** sub-directories
 - &nbsp;
 - **From the** ***About Page*** back up **to the** ***homepage*** would be: **`href="../"`** or `href="../index.php"`
 - Linking **to a** ***Gallery Page*** would be **`href="../gallery/indoor`**
 - &nbsp;
 - And **from the** ***Indoor galley*** all the way **to the** ***About Page*** would be `href="../../about/"`
 - &nbsp;
 - &nbsp;
 - **3 different links to the same galley page is no good!**
 <br>All based on where the folders are in relationship to each other

##The Solution##
- **Act as if all links start in the root folder**
- This code analyzes the URL to find how many folders below the **root directory** the current page is
- **Add `<?php echo $upFolderPlaceHolder ?>` before any link**
<br>This manual link **from** ***About*** **to** ***Home*** `<a href="index.php">Home</a>`
<br>**Becomes `<a href="<?php echo $upFolderPlaceHolder ?>index.php">Home</a>` & will work from any level directory**
- Code in `top.php` magically prints the correct number or `../` in your link, making wherever the file is located

#[Download The Latest Release (Project Zip)](https://github.com/SleekPanther/php-magic-linking/releases/latest)#

##Major Features (click link to jump to section)##
1. **[Link to any page](#linking) without worring about navigating up directories <br>**
(*also applies to css, images & any files on your site*)
2. [Consistent nav](#consistent-navigation-navphp) on all pages (using the same `php include`)
3. [Identify the current page](#highlight-current-page-in-nav) & highlight the current link in nav (so the user knows where they are in the site)
4. [Automatic breadcrumb links](#breadcrumb-trail-links)
5. [Automatic meta descriptions](#meta-tag-page-descriptions) stored in easily-editable text file
6. [`<title>` Tags that match the current page](#easy-title-tags)
7. [Print the **page name** in `<h1>`](#print-automatic-page-name-in-h1) automatically (no hard-coding)
8. [Print unique ID's for each page in `<body>` tags](#uniqe-page-ids-in-body-tag) <br>
can use css to apply styles to **target on only 1 page**

##Code Details###

###Your Folder Setup###
- Every viewable should be called `index.php`
- But MUST HAVE UNIQUE PARENT FOLDER<br>
(Since they're all called `index.php`, **the *containing folder* is very important to identify the current page**)
- **Folder names should be lowercase NO SPACES**
- **Folder names BECOME the** ***Page Title***
- `-` (hyphen characters) will be replaced with `/` (forward-slashes)
- `_` (underscores) will be replaces with **spaces**
- The **fitst letter** of each word will be ***C*****apitalized**

**Example:** The folder "our_prices-services" becomes "Our Prices / Services"

###This Project's Default Folder Setup (what files go where)###
- The **main homepage** goes in the root directory of your entire project
- `about/index.php` gets its own folder since it's on the same level of the nav as **Home**
- `portfolio/index.php` and `tests/index.php` are also on the same level of the nav as **Home** <br>
*They're mostly placeholder pages used for breadcrumb trails*
- `portfolio/portfolio_1/index.php` and `tests/test_1/index.php` are **1-level dropdown** pages
- `portfolio/examples/example_1/index.php` is a **2nd-level dropdown** page
- `images/` contains ALL images<br>
There are many ways to organize your images, so modfy as you please
- `non-pages/` contains anything that ISN'T a complete page <br>
`php-include/` has partial components that are assembled to create complete pages
`css/` has the stylesheets
`descriptions/` has a tect file for Meta tag page descriptions

####THINGS YOU MUST EDIT!####
- Rename `php-magic-linking` to the name of your site **(or leave it alone if you don't care)**
- Edit line 12 in `top.php` to match **YOUR ROOT DIRECTORY** <br>
`$ROOT_DIRECTORY = "php-magic-linking";` (this should match, or be changed)
- Every page must manually link to `top.php` BEFORE ANY OTHER PHP CODE! <br>
**HomePage link:** `<?php include("non-pages/php-include/top.php"); ?>` <br>
**1 folder Level below homepage** (from `/about/index.php`, `/portfolio/index.php` etc.):  **`<?php include("../non-pages/php-include/top.php"); ?>`** <br>
**2 Levels below** (from `/portfolio/examples/index.php`):  **`<?php include("../../non-pages/php-include/top.php"); ?>`** <br>
**3 Level below** (from `/portfolio/examples/example_1/index.php`):  **`<?php include("../../../non-pages/php-include/top.php"); ?>`**
- &nbsp;
- Ctrl+F/Find/Search **"36714768356"** in `top.php` (section to list all YOUR pages)

1. Put all top-level pages (INCLUDING $ROOT_DIRECTORY for the **Homepage**) in `$pageArrayTop` array <br>
`$pageArrayTop = array($ROOT_DIRECTORY, 'portfolio', 'tests', 'about');` //this is the default code <br>
Replace with **EXACT FOLDER NAMES (case sensitive)** of YOUR pages
2. Put ALL 1st-level-dropdown pages in `$pageArrayDropDown1` array <br>
`$pageArrayDropDown1 = array ('portfolio_1', 'portfolio_2', 'examples', 'test_1', 'test_2');` //default code
3. Put ALL 2nd-level-dropdown pages in `$pageArrayDropDown1` array <br>
`$pageArrayDropDown2 = array ('example_1');` //default code

- ORDER DOESN'T MATTER, but don't leave out any pages!

####More Optional Things To Edit####
- Choose your Favicon `images/0_components/favicon.png` <br>
`<link rel="icon" type="image/png" href="<?php echo $upFolderPlaceholder ?>images/0_components/favicon.png">` in `top.php`
- Change your logo `images/logo/logo.png` <br>
Search for `<div id="logo">` in `nav.php`
- Update `<meta name="author" content="Your Name">` to your name/company in `top.php`
- Add your site's tagline<br>
`$tagLine = " - Your Tagline";` in `top.php`
This appears in the `<title>` tag. Like " - Wikipedia, the free encyclopedia" at the end of every Wikipedia Page
- Breadcrumbs are optional. **To remove:** Simply delete the `<section class="breadcrumbs>` tag in `header.php` <br>
*Leave the code in `top.php` alone just in case you want them later
- Add page descriptions to appear in `<meta name="description" content="your description here">` <br>
(You edit `non-pages/descriptions/descriptions.txt` [JUMP TO DETAILS SETION &#8659;](#meta-tag-page-descriptions))

###Page Structure (PHP Includes)###
Viewable pages constructed from partials in `non-pages/php-include/` as follows:

1. **`top/php`** begins the HTML file. It has everything in the `<head>` section & important URL magic happens here <br>
It uses 2 php includes at the end to render `nav.php`and `header.php`
2. **`nav.php`** contains global navigation (1 copy for all pages)
3. **`header.php`** can contain a Global logo, site title, slideshow etc. <br>
CURRENTLY IT ALSO CONTAINS **Breadcrumbs** <br>
`<main id="actualMainContent">` at the very end opens a tag to contain the main page content
4. Then there's the unique page content (e.g. homepage, about, etc.) <br>
Must `include` the Footer `<?php include($upFolderPlaceholder . "non-pages/php-include/footer.php"); ?>`
5. **`footer.php`** is for any content you want at the bottom of every page

- **Feel free to rearrange the `php inclusdes` but make sure the tags are nested correctly (like opening `<main>` in `header.php`)**
- **View Page Source** & look for HTML comments like `<!-- header.php -->` and `<!-- end header.php -->`

###Linking###
- Once `top.php` is `included`, use `$upFolderPlaceHolder` before any link to print the correct number of `../`
- Simply add `<?php echo $upFolderPlaceHolder ?>` anywhere you usually put `../`
- &nbsp;
- **Examples**
- **Navigation Links:** `<a href="<?php echo $upFolderPlaceholder ?>index.php">Home</a>`
- **CSS:** `<link href='<?php echo $upFolderPlaceholder ?>non-pages/css/style.css' rel='stylesheet' type='text/css' media='screen' />`
- **Images:** `<img src="<?php echo $upFolderPlaceholder;?>images/logo/logo.png" alt="Your Logo">`
- **PHP Includes:** `<?php include ($upFolderPlaceholder."non-pages/php-include/nav.php");?>` or `<?php include($upFolderPlaceholder . "non-pages/php-include/footer.php"); ?>` <br>
*(this uses `.` to concatenate 2 things & create a complete URL path)*

###Consistent Navigation (`nav.php`)###
- Anatomy of a link:
- `<li><a <?php echo 'href="'.$upFolderPlaceholder.'portfolio/portfolio_1/index.php"' . ' class="'.$activePageArrayDropDown1['portfolio_1'].'"'; ?>>Portfolio 1</a></li>`
- `<a <?php echo 'href="'.$upFolderPlaceholder.'portfolio/portfolio_1/index.php"'` is the part that prints the `href` for the hyperlink
- `. ' class="'.$activePageArrayDropDown1['portfolio_1'].'"'; ?>>Portfolio 1</a>` finishes the link & check if it's an **active Page** (*DETAILS IN NEXT SECTION*)
- REMOVE THIS 2ND PART IF YOU DON'T CARE ABOUT **Active Pages** <br>
- The boring link would just be ``<li><a <?php echo 'href="'.$upFolderPlaceholder.'portfolio/portfolio_1/index.php">Portfolio 1</a></li>``
- MUST HAVE UNIQUE ID'S FOR EACH DROPDOWN <br>
**drop-2** in `<label for="drop-2" class="toggle">Portfolio +</label>` matches **drop-2** in `<input type="checkbox" id="drop-2"/>`

###Highlight Current Page in Nav###
- `. ' class="'.$activePageArrayDropDown1['portfolio_1'].'"'; ?>>Portfolio 1</a>` (this is the 2nd part of the LINK FROM JUST ABOVE)
- It will yield `class="activePage"` **if the folder in the nav matches a folder in the CURRENT URL**
- `$activePageArrayDropDown1` is created in `top.php`
- It's an associative array with **keys* matching the *drop-down 1 level folders*
- Its **values** should be empty, expect for 1 kay which will have **activePage** stored in it
- If **Portfolio 1* is in the path in the URL (really the `portfolio_1` folder ), `$activePageArrayDropDown1['portfolio_1']` will have the value **activePage** stored in it
- **Use CSS to taget the `activePage` class & style the background differently** <br>
*currently accomplished in last lines of* **`non-pages/css/menu.css`**

###Breadcrumb Trail Links###
- Located in `non-pages/php-include/header.php`
- Just remove the php code in the `header.php` file if you don't want them, or move it to another place
- Uses `$split_url_adjusted` array

1. Breaks aprt the URL into folders
2. Starts at the beginnins & prints an href link to each folder
3. The actual text of the link is found by the function `convFolder2PgTitle()` <br>
this takes in a folder & converts it to a human readable title ([exact rules described earlier &#8648;](#your-folder-setup))
4. The final "crumb" in the chain is the current page. This is **bolded** BUT NOT A LINK!

###Meta Tag Page Descriptions###
- These are `<meta name="description" content="your description here">` tags for **search engines**
- **These are based on ** `$pageArrayTop, $pageArrayDropDown1, $pageArrayDropDown2` etc.
- **SO MAKE SURE YOU ADDED ALL YOUR PAGES TO THESE ARRAYS**
- As long as your pages are in these arrays, you just need to edit the text file

`non-pages/descriptions/descriptions.txt` is very simple:

1. Each line contains 1 page description
2. The 1st word is the **exact folder name**
3. Everything **after the 1st space** is considered the description
4. So anything AFTER the 1st word will replace `<?php echo $pageMeteDescriptions[$containing_folder] ?>` in `<meta name="description" content="<?php echo $pageMeteDescriptions[$containing_folder] ?>">` inn the `top.php` file

- If you don't care about page descriptions, replace `<?php echo $pageMeteDescriptions[$containing_folder] ?>` with your generic description for all pages

###Easy `<title>` Tags###
- `top.php` will print the human-readable Page Title (based on the containing folder)
- Then it will append  your tagline to the end <br>
search for `$tagLine = " - Your Tagline";` in `top.php` if you need to change the tagline
- `<title><?php echo $pageTitle.$tagLine ; ?></title>`

###Print Automatic Page Name in `<h1>`###
- Similar to `<title>` tags, you can just print the `$pageTitle` variable in an `<h1>` to start every page
- It's a good practice to have the `<title>` text & prominent `<h1>` match. This is for search engine as well as sie users <br>
This is the whole reason for the `$pageTitle` variable existing
- `<h1><?php echo $pageTitle . $tagLine; ?></h1>`
- This is from 1 of the `index.php` pages, but you choose to add it to `header.php` or move it elsewhere

###Uniqe Page ID's in `<body>` tag###
- Ever need to apply a css rule to **just 1 specific page**?
- It's easily accomplished with an **ID** in the `<body>` tag
- Since all pages are called `index.php` you can't target filenames
- But since every page is in its **own unique folder**, you **use `$containing_folder` as the ID**
- `echo '<body id="'.$containing_folder.'">';` (this is from the end of `top.php`)
- **The `$containing_folder` for `$ROOT_DIRECTORY` has been adjusted to be `"index"`** <br>
This is to allow easy migration of the site. Nothing relie directly on the value of $ROOT_DIRECTORY, we always access it via the variable so it can easily be changed
- ID's have higher [CSS specificity](https://specificity.keegan.st/) than classes, so overriding a global rule **only on the** ***Portfolio 1 Page***
- `echo '<body id="'.$containing_folder.'">';` in `top.php` would become `<body id="portfolio_1">`
```
#portfolio_1 /*optionally add more CSS selectors here */ {
  /*new css rules here*/
}

#index {
  /*CSS rules to override stuff for the HOMEPAGE only*/
}
```

##Extra Notes##
- This is a readme to demo **usage**, detailed comments in actual code
- Search "454521" using `Ctrl+F` or `Cmd+F` to skip to important sections in `top.php`
- The nav menu is adapted from [Pure CSS Mobile-compatible Responsive Dropdown Menu](http://www.cssscript.com/pure-css-mobile-compatible-responsive-dropdown-menu/) <br>
or check out [My adapted version on GitHub](https://github.com/SleekPanther/css-dropdown)
- `non-pages/css/menu.less` can be ignored if you aren't familiar with `.less` files. Basically it's a fancy version of CSS which is compiled to normal CSS yieldind the normal `non-pages/css/menu.css` file <br>
or check out [Less.js](http://lesscss.org/) to learn more

##Future Feature Goals##
