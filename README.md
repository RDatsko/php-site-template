### About This PHP Template

I've assisted with many website developments and one thing I notice when I first start is that the structure is often very difficult to follow, especially if multiple people have worked on the site before.  As site maintainers are changed, this makes editing and locating necessary changes difficult to find and very messy.

This is a template that I use when developing my websites.  It's designed to to keep all the pages in a page folder to make it easier to locate the file you are trying to access.

The website directories are broken down into  single files where the "/" is replaced by two underscores "__".  This allows for all the files to be located in a single folder but be mapped to the appropriate URL.  Canonical URL implementation is also implemented.

This means that a URL such as:
http://127.0.0.1/dir/subdir/
will load the file in
/pages/dir__subdir.php

As nearly all websites use a template for consistency, this template breaks out the template in a layout folder.  All the files that are used for the template are stored here.  This allows for the template to be changed quickly by replacing the folder.  This also allows for the contents of the pages to remain the same without needing to modify the pages themselves.

### Template Structure

Each PHP page follows a specific structure.  This structure allows for social media tags to be included for the page to be shared easily.  It also allows for additional things to be included on the page such as additional JavaScript or CSS files that may be needed for that specific page.  Basically, just include the required information structure for the page and include the header and footer files for each page and the PHP will handle the rest of the rendering.

### Multiple Language Support

Also, I have worked on websites that required multiple languages.  This template allows for multiple languages to be used.  This is done by silently adding "?lang={code}" to the end of the ".php".  This can be toggled by changing the "$supportsMultiLang" to either true or false.

For example, a Japanese site that wishes to also include English can have the same single HTML file which means that the site can be exactly identical without the need of separate files.  In this code, included is an example of using both English Japanese.  It will add the lanuage to be used directly after the domain and use the prefix on which is chosen.  This can also be disabled for sites that are only a single language.

**Single Language Site**
http://127.0.0.1/dir/subdir/
**Multiple Language Site**
http://127.0.0.1/en/dir/subdir/
http://127.0.0.1/jp/dir/subdir/

### Admin Folder

This is very much a work in progress, especially the admin folder.  The admin folder is a loose testing ground which I have not implemented much of other than layout testing.  The idea behind this is to allow for non PHP developers to make and adjust the pages in a simple GUI interface.  Currently not much is implemented.  Much more is to be added later.

The "admin/data" folder is restricted by the .htacceess file so it can not be accessed directly from a web browser.  This is to ensure security while still allowing for admin access.
