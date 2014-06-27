'use strict';

module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        sass: {
            dist: {
                files: {
                    'sites/puppy/docroot/css/main.css': 'sites/puppy/docroot/css/scss/main.scss'
                }
            }
        },
        watch: {
            css: {
                files: ['sites/puppy/docroot/css/scss/**/*.scss'],
                tasks: ['sass']
            },
            js: {
                files: ['sites/puppy/docroot/js/init.js', 'sites/puppy/docroot/js/modules/**/*.js',
                    'sites/puppy/docroot/js/support/**/*.js', 'sites/puppy/docroot/js/templates/**/*.js'],
                tasks: ['jshint', 'concat']
            }
        },
        concat: {
            options: {
                // define a string to put between each file in the concatenated output
                separator: '\n\n',
                banner: "'use strict';\n",
                process: function (src, filepath) {
                    return '// Source: ' + filepath + '\n' +
                        src.replace(/(^|\n)[ \t]*('use strict'|"use strict");?\s*/g, '$1');
                }
            },
            dist: {
                // the files to concatenate
                src:['sites/puppy/docroot/js/init.js', 'sites/puppy/docroot/js/modules/**/*.js',
                    'sites/puppy/docroot/js/support/**/*.js', 'sites/puppy/docroot/js/templates/**/*.js'],
                // the location of the resulting JS file
                dest: 'sites/puppy/docroot/js/main.js'
            }
        },
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                force: true
            },
            gruntfile: {
                src: 'Gruntfile.js'
            },

            // src
            src: {
                src: ['sites/puppy/docroot/js/templates/*.js']
            }
        },
        uglify: {
            options: {
                compress: true,
                mangle: {
                    toplevel: true,
                    except: ['jQuery']
                }
            },
            my_target: {
                files: {
                    'sites/puppy/docroot/js/parcelpuppy.min.js': ['sites/puppy/docroot/js/main.js']
                }
            }
        },
        cssmin: {
            combine: {
                files: {
                    'sites/puppy/docroot/css/parcelpuppy.min.css': ['sites/puppy/docroot/css/main.css']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.registerTask('default', ['watch']);
    grunt.registerTask('generate-prod-files', ['uglify', 'cssmin']);
};