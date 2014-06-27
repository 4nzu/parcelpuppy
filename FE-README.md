Parcel Puppy Front-End README
=============================

The front-dnd uses Grunt to manage compiling it's `SASS` and `JS` code. It's been configured to automatically watch the 
project's files for changes and automatically recompile on changes.

Setup
-----

To use the front-end tasks, start by installing [Grunt](http://gruntjs.com/getting-started). You'll also need to install 
command-line support for [SASS](http://sass-lang.com/install). Parcel Puppy's front-end also utilizes a number of open 
 sourced grunt task frameworks:

- [grunt-contrib-concat](https://github.com/gruntjs/grunt-contrib-concat): For concatenating the JS files together.
- [grunt-contrib-watch](https://github.com/gruntjs/grunt-contrib-watch): For watching the project files for changes.
- [grunt-contrib-sass](https://github.com/gruntjs/grunt-contrib-sass): For compiling the SASS code into a CSS file.
- [grunt-contrib-cssmin](https://github.com/gruntjs/grunt-contrib-cssmin): For minimizing the css for production use
- [grunt-contrib-jshint](https://github.com/gruntjs/grunt-contrib-jshint): For linting the JS code.
- [grunt-contrib-uglify](https://github.com/gruntjs/grunt-contrib-uglify): For minifying the js code for production.


Each of these frameworks also need to be installed (see Github pages for details).

Running Tasks
-------------

Once everything is installed, run the command `grunt` from the project's root folder in a terminal. This start an active 
process that will `watch` the JS and SASS files. On changes, the window will show logs relating to the running tasks 
including lint errors.


Building for Production
-----------------------

By running `grunt generate-prod-files`, tasks will be executed to minify the the CSS and JS code for use in production. 
 The JS production file is `/sites/puppy/docroot/js/parcelpuppy.min.js` and the CSS production file is 
  `/sites/puppy/docroot/css/main.min.css`. Production versions of `header.php` and `footer.php` will need to be update 
to include the minified files.  