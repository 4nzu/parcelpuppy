Zappy Framework, quick overview
===============================

/Frameworks - external frameworks, usually can be setup as git modules. Examples: aws.phar, google-api-php, facebook-php, etc.

/sites - websites source. Sites each live in a separate directory with the same structure inside (see more below)

/Zappy - framework code includes classes that can serve all sites, with pretty obvious names like: User.php, DB.php, Cache.php, Template.php etc.



Zappy site structure (for example /sites/puppy)
-----------------------------------------------

/config - contains config.php, a file where some preliminary light weight system configurations and settings happen

/docroot - contains css, images (img), js and .htaccess with rewrite rules. Also contains files that load the framework: init.php, index.php

/library - contains Controller and Model classes specific to this site. Controllers must have the word "Display" in their name, for example: Display.php is the controller for the site's root "/" pages, requestsDisplay.php is a controller for the "/requests/" pages (note closing "/"). API.php is a special controller for facilitating asynchronous calls (see more below)

/templates - templates and template modules for rendering html pages


Page Controller / sending data to templates
-------------------------------------------------------------

Each function inside a controller class (Display.php) will be a page on the site with the name of this function. With the exception of the root "/" page which is rendered with function display_home().

To load a template, call $this->set_template('nameOfTheTemplatePHPFileWithOut[.php]extension'); Use "_" in the name of the template to separate directory path, for example to load a template from /templates/signin/thankyou.php call $this->assign('signin_thankyou');

To make data available for a template, call $this->assign('nameOfTemplateVariable', $nameOfLocalVariable); then $nameOfTemplateVariable will become available inside the loaded template.


API controller
--------------
API controller is implementing end points for asynch api calls. Like in case of Display.php controller, an end point is a function with the same name. It can be accessed by calling the following URL: http://sitename/api/v1/endpoint