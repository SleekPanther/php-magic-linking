#PHP Magic Linking Template

**NEVER WORRY ABOUT PUTTING ../ BEFORE LINKS EVER AGAIN!**

#[Video Walkthroug/Demo (watch 1st)](https://www.youtube.com/watch?v=PEczo1pLobo)

#[Download Latest Release (Project Zip)](https://github.com/SleekPanther/php-magic-linking/releases/latest)

You often store each page in a separate folder to allow more readable URLs. Instead of `mySite.com/about_us_page.php`, you want `mySite.com/about`. So put an `index.php` in the folder `about/` (`mySite.com/about` is equivalent to `mySite.com/about/index.php`)

You can have multiple sub-directories: a main Gallery Page `mySite.com/gallery` that lists the individual sub-galleries like `mySite.com/gallery/indoor` or `mySite.com/gallery/outdoor`

But you run into trouble **linking between pages**. Small sites can get away with `index.php`, `about.php`, `gallery1.php`, `gallery2.php` etc. in the same folder, but it's not organized or scalable. If all pages were in the same folder, you could use **1 copy of global navigation** called `nav.php` & use a **`php include`** to keep the navigation consistent. But **nested sub-directories cause problems.**

<s>A cheater "solution"  is to have a copy of `nav.php` for each level of directories</s>  
DON'T TRY THIS! Trust me, it's a pain to maintain 3 copies of `nav.php` when you just want to change 1 link.

###The Situation (Problematic Linking)
 - Going **from the** ***Homepage*** (`index.php` in the root folder of `mySite.com`) **to** ***About***  would be **`href="about/"`** or `href="about/index.php"`
 - Linking **2-directories down** would be **`href=gallery/indoor/`**
 - Linking **from the** ***Homepage*** always goes **down into** sub-directories, so **no `../` are needed**
 - &nbsp;
 - **From the** ***About Page*** back up **to the** ***homepage*** would be **`href="../"`** or `href="../index.php"`
 - Linking **to a** ***Gallery Page*** would be **`href="../gallery/indoor`**
 - &nbsp;
 - And **from the** ***Indoor Galley*** all the way **to** ***About*** would be **`href="../../about/"`**
 - &nbsp;
 - **3 different links to the same** ***Galley Page*** **is no good!**

##The Solution
- **Act as if all links start in the root folder (where the** ***Homepage*** **is)**
- This code analyzes the URL to find how many folders **below the root directory** the current page is
- **Add `<?php echo $upFolderPlaceHolder ?>` before any link**
 - The manual link **from** ***About*** **to** ***Home*** `<a href="index.php">Home</a>`
 - **Becomes `<a href="<?php echo $upFolderPlaceHolder ?>index.php">Home</a>` & will work from any level directory**
- Code in `top.php` magically prints the correct number of `../` in your link

##Major Features (click link to jump to section)
1. **[Link to any page](#linking) without using `../` EVER AGAIN!**  
(*also applies to css, images & any files on your site*)
2. [Consistent nav](#consistent-navigation-navphp) on all pages (using the **same `php include`**)
3. [Identify the **current page**](#highlight-current-page-in-nav) & highlight the current link in nav menu (so the user knows where they are)
4. [Automatic **Breadcrumb** links](#breadcrumb-trail-links)
5. [Automatic **Meta descriptions**](#meta-tag-page-descriptions) stored in easily-editable text file
6. [**`<title>` Tags** that match the current page](#easy-title-tags)
7. [Print the **page name** in `<h1>`](#print-automatic-page-name-in-h1) automatically (no hard-coding)
8. [Print **unique IDs for each page** in `<body>` tags](#unique-page-ids-in-body-tag)  
*Use css to* **apply styles to only 1 page**

#Code Details

###Your Folder Setup
- Every viewable page should be called `index.php`
- But MUST HAVE UNIQUE PARENT FOLDER  
(Since all pages are `index.php`, **a file's *parent folder*/*containing folder* is VERY important to identify the current page**)
- **Folder names should be lowercase, NO SPACES**

- **Folder names BECOME the** ***Page Title*** (*stored in `$pageTitle`*

1. `-` (hyphen characters) will be replaced with `/` (forward-slashes)
2. `_` (underscores) will be replaced with **`spaces`**
3. **First letter** of each word will be ***C*****apitalized**

**Example:**  
The folder "our_prices-services" becomes "Our Prices / Services"  
Search/`Ctrl+F` **"convFolder2PgTitle"** in `top.php` to edit the function.  (Maybe you don't want to replace underscores)

###This Project's Default Folder Setup (what files go where)
- The **Homepage** goes in your site's **root directory** (default is `php-magic-linking` for THIS project)
- `about/index.php` gets its own folder since it's on the same level of the nav as **Home**
- `portfolio/index.php` and `tests/index.php` are also on the same level of the nav as **Home**  
(*They're mostly placeholder pages used for breadcrumb trails*)
- `portfolio/portfolio_1/index.php` and `tests/test_1/index.php` are **1-level dropdown** pages
- `portfolio/examples/example_1/index.php` is a **2nd-level dropdown** page
- `images/` contains ALL images  
*There are many ways to organize your images, so modify as you wish*
- `non-pages/` contains anything that **ISN'T** a complete page
 - `php-include/` has partial components that are assembled to create complete pages
 - `css/` has the stylesheets
 - `descriptions/` has a text file for Meta tag page descriptions

###Finding Your Root directory (`$ROOT_DIRECTORY` variable)
- Upload `setup.php` to your web server & **view the page**
- Or just view `setup.php` on `localhost`
- This tells you the containing folder (*like `pwd` in `command line`*), the value you should store in `$ROOT_DIRECTORY`
- Some common folders are `public_html` or `www-root`

####**THINGS YOU MUST EDIT!**
- Rename `php-magic-linking` to your site's **root directory** **(found just above)**
- Edit line 12 in `top.php` to match  
`$ROOT_DIRECTORY = "php-magic-linking";` (this should match, or be changed)
- **IF YOUR ROOT DIRECTORY HAS A PARENT FOLDER WITH THE** ***EXACT SAME NAME***
 - (e.g. `$ROOT_DIRECTORY` is `my-folder` but the complete path is `mysite.com/my-folder/dev/site1/my-folder`
 - Notice 2 occurrences of `my-folder`
 - Search/`Ctrl+F` for the line: `for ($i = 0; $i < count($split_url); $i++){`
 - Look 3 lines later for &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; **`break;`**
 - **COMMENT OUT OR REMOVE the &nbsp;&nbsp;&nbsp;&nbsp; `break;`**
- Every page must **manually link to `top.php`** BEFORE ANY OTHER PHP CODE!  
*This allows you to use `$upFolderPlaceholder` later in the page*
 - **Homepage link:** `<?php include("non-pages/php-include/top.php"); ?>`
 - **1 folder Level below homepage** (e.g. `/about/index.php`, `/portfolio/index.php` etc.) is  
 **`<?php include("../non-pages/php-include/top.php"); ?>`**
 - **2 Levels below** (e.g. `/portfolio/examples/index.php`) is  
 **`<?php include("../../non-pages/php-include/top.php"); ?>`**
 - **3 Levels below** (e.g. `/portfolio/examples/example_1/index.php`) is  
 **`<?php include("../../../non-pages/php-include/top.php"); ?>`**
- Search/`Ctrl+F` **"36714768356"** in `top.php` (section to list all YOUR pages)

1. Put all top-level pages (including $ROOT_DIRECTORY for **Homepage**) in `$pageArrayTop`  
`$pageArrayTop = array($ROOT_DIRECTORY, 'portfolio', 'tests', 'about');` //this is the default code  
Replace with your pages, **EXACT FOLDER NAMES (case sensitive)**
2. Put ALL 1st-level-dropdown pages in `$pageArrayDropDown1`  
`$pageArrayDropDown1 = array ('portfolio_1', 'portfolio_2', 'examples', 'test_1', 'test_2');` //default code
3. Put ALL 2nd-level-dropdown pages in `$pageArrayDropDown1`  
`$pageArrayDropDown2 = array ('example_1');` //default code

- ORDER DOESN'T MATTER, but **don't leave out any pages!**

####More Optional Things To Edit
- Replace default Favicon in `images/0_components/favicon.png`  
`<link rel="icon" type="image/png" href="<?php echo $upFolderPlaceholder ?>images/0_components/favicon.png">` in `top.php`
- Replace default logo in `images/logo/logo.png`  
Search for `<div id="logo">` in `nav.php` to see where it's used
- Update `<meta name="author" content="Your Name">` to your name/company in `top.php`
- Add your site's tagline  
`$tagLine = " - Your Tagline";` in `top.php`  
This appears in the `<title>` tag. Like " - Wikipedia, the free encyclopedia" at the end of every Wikipedia Page
- Breadcrumbs are optional. **To remove:** Simply delete or move the `<section class="breadcrumbs">...</section>` tag in `header.php`
- Add page descriptions to appear in `<meta name="description" content="your description here">`  
(You edit `non-pages/descriptions/descriptions.txt` [JUMP TO DETAILS SECTION &#8659;](#meta-tag-page-descriptions))

###Page Structure (Anatomy of a Page, PHP Includes)
Viewable pages constructed from partials in `non-pages/php-include/` as follows:

1. **`top.php`** begins the HTML file. It has everything in the `<head>` section & important URL magic happens here  
It uses 2 php includes at the end to render `nav.php` and `header.php`
2. **`nav.php`** contains Global Navigation (1 copy for all pages)
3. **`header.php`** can contain a Global logo, site title, slideshow, etc.  
CURRENTLY IT ALSO CONTAINS **Breadcrumbs**. [See details to remove them &#8659;](#breadcrumb-trail-links)  
`<main id="actualMainContent">` tag is opened in `header.php` but closed in each `index.php` page
4. Unique page content goes in each `index.php` file (e.g. homepage, about, etc.)  
**Must `include` the Footer near end `<?php include($upFolderPlaceholder . "non-pages/php-include/footer.php"); ?>`**
5. **`footer.php`** is for any content you want at the bottom of every page

- **Feel free to rearrange the `php includes` but make sure the tags are nested correctly**  
 - Notably, opening `<main>` in `header.php`, currently closed in each `index.php` page**  
 - **View Page Source** & look for HTML comments like `<!-- header.php -->` and `<!-- end header.php -->`  
If the `PHP includes` are confusing, this helps show how render the actual page

###Linking
- Once `top.php` is `included`, use `$upFolderPlaceHolder` to print the correct number of `../`
- **Simply add `<?php echo $upFolderPlaceHolder ?>` anywhere you usually put `../`**
- &nbsp;
- **Navigation Links:**  
`<a href="<?php echo $upFolderPlaceholder ?>index.php">Home</a>`
- **CSS:**  
`<link href='<?php echo $upFolderPlaceholder ?>non-pages/css/style.css' rel='stylesheet' type='text/css' media='screen' />`
- **Images:**  
`<img src="<?php echo $upFolderPlaceholder ?>images/logo/logo.png" alt="Your Logo">`
- **PHP Includes:**  
`<?php include ($upFolderPlaceholder."non-pages/php-include/nav.php");?>` or `<?php include($upFolderPlaceholder . "non-pages/php-include/footer.php"); ?>`  
*(this uses `.` to concatenate 2 things & create a complete URL path)*

###Consistent Navigation (`nav.php`)
- **Anatomy of a link:**
- `<li><a <?php echo 'href="'.$upFolderPlaceholder.'portfolio/portfolio_1/index.php"' . ' class="'.$activePageArrayDropDown1['portfolio_1'].'"'; ?>>Portfolio 1</a></li>`
- `<a <?php echo 'href="'.$upFolderPlaceholder.'portfolio/portfolio_1/index.php"'` is the part that prints the `href`
- `. ' class="'.$activePageArrayDropDown1['portfolio_1'].'"'; ?>>Portfolio 1</a>` finishes the link & checks if it's an **Active Page** (DETAILS IN [NEXT SECTION&#8659;](#highlight-current-page-in-nav))  
*Starts with `.` since PHP is concatenating things together*
- REMOVE THIS 2ND PART IF YOU DON'T CARE ABOUT **Active Pages**  
- The boring link would just be  
``<li><a <?php echo 'href="'.$upFolderPlaceholder.'portfolio/portfolio_1/index.php">Portfolio 1</a></li>``
- **MUST HAVE UNIQUE IDs FOR EACH DROPDOWN**  
**"drop-2"** in `<label for="drop-2" class="toggle">Portfolio +</label>` matches **"drop-2"** in `<input type="checkbox" id="drop-2"/>`
- This is really a feature of the current menu, which is [another git project](https://github.com/SleekPanther/css-dropdown)
- The point is: **each dropdown has a unique `<input>` ID matching the `for="___"` in the `<label>`**

###Highlight Current Page in Nav
- `. ' class="'.$activePageArrayDropDown1['portfolio_1'].'"'; ?>>Portfolio 1</a>` (this is the 2nd part of the LINK FROM JUST ABOVE)
- It will yield `class="activePage"` **if the folder in the nav matches a folder in the CURRENT URL**
- `$activePageArrayDropDown1` is created in `top.php`
- It's an associative array with **keys** matching the *1st-level dropdown folders*
- Its **values** should be empty, EXCEPT for 1 key which will have **activePage** stored in it
- If **Portfolio 1** is in the path in the URL (the `portfolio_1` folder ), `$activePageArrayDropDown1['portfolio_1']` will have the value **activePage** stored in it
- **Use CSS to style the `activePage` class background differently**  
*currently accomplished in last lines of* **`non-pages/css/menu.css`**
```
.activePage {
    background: #178f26;
}
```
- `non-pages/css/menu.less` can be ignored if you aren't familiar with `.less` files. Basically it's a fancy version of CSS which is compiled to normal CSS yielding the `non-pages/css/menu.css` file  
Check out [Less.js](http://lesscss.org/) to learn more

###Breadcrumb Trail Links
- Located in `non-pages/php-include/header.php`
- Prints **breadcrumbs** to show how to user got to the current page [(breadcrumbs explained)](https://www.smashingmagazine.com/2009/03/breadcrumbs-in-web-design-examples-and-best-practices/)
- **None appear on the** ***Homepage***  
(I mean, what's the point. It's the highest level page)
- If you **don't** want breadcrumbs, just remove the php code inside `<section class="breadcrumbs">...</section>` in `header.php`
- Or move that code anywhere else to display **breadcrumbs** in a different location on the page
- **I recommend keeping `<section class="breadcrumbs">...</section>` in some type of `php include`** That way you only have to change 1 file (e.g. `header.php`) to modify links in the future
- Uses `$split_url_adjusted` array

1. Breaks apart the URL into folders
2. Starts at the beginning & prints an `href` link to each folder
3. Actual link text is found by calling the function `convFolder2PgTitle()`  
This converts a folder like `portfolio_1` to a human readable title like **Portfolio 1** ([exact rules described earlier &#8648;](#your-folder-setup))
4. The final "crumb" in the chain is the current page. This is **bolded**, BUT NOT A LINK!

###Meta Tag Page Descriptions
- These are `<meta name="description" content="your description here">` tags for **search engines**
- **Based on** `$pageArrayTop`, `$pageArrayDropDown1`, `$pageArrayDropDown2` etc.
- **SO MAKE SURE YOU ADDED ALL YOUR PAGES TO THESE ARRAYS**
- As long as your pages are in these arrays, you just need to edit the text file

`non-pages/descriptions/descriptions.txt` is very simple:

1. Each line contains **1 page description**
2. The 1st word is the **exact folder name**
3. Everything **AFTER the 1st space** is the **description**
4. So anything AFTER the 1st word will replace `<?php echo $pageMeteDescriptions[$containing_folder] ?>` in `<meta name="description" content="<?php echo $pageMeteDescriptions[$containing_folder] ?>">` in the `top.php` file

- If you don't care about page descriptions, replace `<?php echo $pageMeteDescriptions[$containing_folder] ?>` with a Global description for all pages.
- Like this: `<meta name="description" content="My awesome site description ...">`

###Easy `<title>` Tags
- `top.php` will print the human-readable Page Title (based on the containing folder)
- Then it will append your tagline to the end  
Search for `$tagLine = " - Your Tagline";` in `top.php` if you need to change the tagline
- `<title><?php echo $pageTitle.$tagLine ; ?></title>` actually prints it

###Print Automatic Page Name in `<h1>`
- Similar to `<title>` tags, you can just print the `$pageTitle` variable in an `<h1>` to start every page
- It's a good practice for `<title>` text & `<h1>` for the Page Title to match.  
This helps search engine as well as visitors  
*This is the whole purpose for the `$pageTitle` variable*
- `<h1><?php echo $pageTitle . $tagLine; ?></h1>`
- This is from 1 of the `index.php` pages, but you choose to add it to `header.php` or move it elsewhere

###Unique Page IDs in `<body>` tag
- Ever need to apply a css rule to **just 1 specific page**?
- It's easily accomplished with an **ID** in the `<body>` tag
- Since all pages are called `index.php` you can't use filenames
- So you **use `$containing_folder` as the ID since they're all unique**
- `echo '<body id="'.$containing_folder.'">';` (from the end of `top.php`)
- **The `$containing_folder` for `$ROOT_DIRECTORY` (the *Homepage*) has been adjusted to `"index"`**  
This is to allow easy migration of the site. Nothing relies directly on the value of $ROOT_DIRECTORY, we always access it via the variable so it can easily be changed
- IDs have higher [CSS specificity](https://specificity.keegan.st/) than classes, so overriding a global rule **only on the** ***Portfolio 1 Page***
- `echo '<body id="'.$containing_folder.'">';` in `top.php` will become **`<body id="portfolio_1">`**
```
#portfolio_1 /*optionally add more CSS selectors here */ {
  /*new css rules here*/
}

#index {
  /*CSS rules to override stuff for the HOMEPAGE only*/
}
```

##Extra Notes
- This is a readme to demo **usage**, more detailed comments in actual code
- **Search "454521" using `Ctrl+F` or `Cmd+F` to skip to important sections in `top.php`**
- The nav menu is adapted from [Pure CSS Mobile-compatible Responsive Dropdown Menu](http://www.cssscript.com/pure-css-mobile-compatible-responsive-dropdown-menu/)  
or check out [My adapted version on GitHub](https://github.com/SleekPanther/css-dropdown)

##Future Feature Goals
