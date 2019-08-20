module.exports = function( grunt ) {

    'use strict';
      // Project configuration
    var autoprefixer    = require('autoprefixer');
    var flexibility     = require('postcss-flexibility');
    const sass = require('node-sass');

    var pkgInfo = grunt.file.readJSON('package.json');

    var banner = '/**\n * <%= pkg.homepage %>\n * Copyright (c) <%= grunt.template.today("yyyy") %>\n * This file is generated automatically. Do not edit.\n */\n';
    // Project configuration
    grunt.initConfig( {

        pkg: grunt.file.readJSON( 'package.json' ),

        rtlcss: {
                options: {
                    // rtlcss options
                    config: {
                        preserveComments: true,
                        greedy: true
                    },
                    // generate source maps
                    map: false
                },
                dist: {
                    files: [
                         {
                            expand: true,
                            cwd: 'assets/css/unminified/',
                            src: [
                                    '*.css',
                                    '!*-rtl.css',
                                    '!font-awesome.css',
                                    '!astra-fonts.css',
                                ],
                            dest: 'assets/css/unminified',
                            ext: '-rtl.css'

                        },
                        {
                            expand: true,
                            cwd: 'inc/assets/css',
                            src: [
                                    '*.css',
                                    '!*-rtl.css',
                                ],
                            dest: 'inc/assets/css',
                            ext: '-rtl.css'
                        },
                    ]
                }
            },

            sass: {
                options: {
                    implementation: sass,
                    sourcemap: 'none',
                    outputStyle: 'expanded',
                    linefeed: 'lf',
                },
                dist: {
                    files: [

                        /*{
                        'style.css': 'sass/style.scss'
                        },*/

                        /* Common Style */
                        {
                            expand: true,
                            cwd: 'assets/sass/',
                            src: ['style.scss'],
                            dest: 'assets/css/unminified',
                            ext: '.css'
                        },
                    ]
                }
            },

            postcss: {
                options: {
                    map: false,
                    processors: [
                        flexibility,
                        autoprefixer({
                            overrideBrowserslist: [
                                '> 1%',
                                'ie >= 11',
                                'last 1 Android versions',
                                'last 1 ChromeAndroid versions',
                                'last 2 Chrome versions',
                                'last 2 Firefox versions',
                                'last 2 Safari versions',
                                'last 2 iOS versions',
                                'last 2 Edge versions',
                                'last 2 Opera versions'
                            ],
                            cascade: false
                        })
                    ]
                },
                style: {
                    expand: true,
                    src: [
                        'assets/css/unminified/style.css',
                        'assets/css/unminified/*.css',
                    ]
                }
            },

            uglify: {
                js: {
                    files: [
                        { // all .js to min.js
                            expand: true,
                            src: [
                                '**.js',
                            ],
                            dest: 'assets/js/minified',
                            cwd: 'assets/js/unminified',
                            ext: '.min.js'
                        },
                    ]
                }
            },

            cssmin: {
                options: {
                    keepSpecialComments: 0
                },
                css: {
                    files: [

                        // Generated '.min.css' files from '.css' files.
                        // NOTE: Avoided '-rtl.css' files.
                        {
                            expand: true,
                            src: [
                                '**/*.css',
                                '!**/*-rtl.css',
                            ],
                            dest: 'assets/css/minified',
                            cwd: 'assets/css/unminified',
                            ext: '.min.css'
                        },

                        // Generating RTL files from '/unminified/' into '/minified/'
                        // NOTE: Not possible to generate bulk .min-rtl.css files from '.min.css'
                        {
                            src: 'assets/css/unminified/style-rtl.css',
                            dest: 'assets/css/minified/style.min-rtl.css',
                        },
                    ]
                }
            },

        addtextdomain: {
            options: {
                textdomain: 'astra-widgets',
            },
            target: {
                files: {
                    src: [
                        '*.php',
                        '**/*.php',
                        '!node_modules/**',
                        '!php-tests/**',
                        '!bin/**',
                    ]
                }
            }
        },

        wp_readme_to_markdown: {
            your_target: {
                files: {
                    'README.md': 'readme.txt'
                }
            },
        },

        makepot: {
            target: {
                options: {
                    domainPath: '/languages',
                    mainFile: 'astra-widgets.php',
                    potFilename: 'astra-widgets.pot',
                    potHeaders: {
                        poedit: true,
                        'x-poedit-keywordslist': true
                    },
                    type: 'wp-plugin',
                    updateTimestamp: true
                }
            }
        },

        copy: {
                main: {
                    options: {
                        mode: true
                    },
                    src: [
                        '**',
                        '!node_modules/**',
                        '!build/**',
                        '!css/sourcemap/**',
                        '!.git/**',
                        '!bin/**',
                        '!.gitlab-ci.yml',
                        '!bin/**',
                        '!tests/**',
                        '!phpunit.xml.dist',
                        '!*.sh',
                        '!*.map',
                        '!Gruntfile.js',
                        '!package.json',
                        '!.gitignore',
                        '!phpunit.xml',
                        '!README.md',
                        '!sass/**',
                        '!codesniffer.ruleset.xml',
                        '!vendor/**',
                        '!composer.json',
                        '!composer.lock',
                        '!package-lock.json',
                        '!phpcs.xml.dist',
                    ],
                    dest: 'astra-widgets/'
                }
        },

        compress: {
            main: {
                options: {
                    archive: 'astra-widgets.zip',
                    mode: 'zip'
                },
                files: [
                    {
                        src: [
                            './astra-widgets/**'
                        ]

                    }
                ]
            }
        },

        clean: {
            main: ["astra-widgets"],
            zip: ["astra-widgets.zip"]

        },
        bumpup: {
            options: {
                updateProps: {
                    pkg: "package.json"
                }
            },
            file: "package.json"
        },
        replace: {

            plugin_main: {
                src: [ "astra-widgets.php" ],
                overwrite: true,
                replacements: [
                {
                    from: /Version: \d{1,1}\.\d{1,2}\.\d{1,2}/g,
                    to: "Version: <%= pkg.version %>"
                }
                ]
            },

            plugin_const: {
                src: [ "astra-widgets.php" ],
                overwrite: true,
                replacements: [
                {
                    from: /ASTRA_WIDGETS_VER', '.*?'/g,
                    to: "ASTRA_WIDGETS_VER', '<%= pkg.version %>'"
                }
                ]
            },
            plugin_function_comment_since: {
                src: [
                        '*.php',
                        '**/*.php',
                        '!node_modules/**',
                        '!php-tests/**',
                        '!bin/**',
                        '!admin/bsf-core/**'
                    ],
                overwrite: true,
                replacements: [
                {
                    from: 'x.x.x',
                    to: "<%= pkg.version %>"
                }
                ]
            },
        },

    } );
    
    grunt.loadNpmTasks('grunt-rtlcss');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.loadNpmTasks( 'grunt-wp-i18n' );
    grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
    grunt.loadNpmTasks( 'grunt-contrib-copy' );
    grunt.loadNpmTasks( 'grunt-contrib-compress' );
    grunt.loadNpmTasks( 'grunt-contrib-clean' );

    grunt.loadNpmTasks( "grunt-bumpup" );
    grunt.loadNpmTasks( "grunt-text-replace" );

     // rtlcss, you will still need to install ruby and sass on your system manually to run this
    grunt.registerTask('rtl', ['rtlcss']);

    // SASS compile
    grunt.registerTask('scss', ['sass']);

    // Style
    grunt.registerTask('style', ['scss', 'postcss:style', 'rtl']);

    // min all
    grunt.registerTask('minify', ['style', 'uglify:js', 'cssmin:css']);

    // Generate README.md file.
    grunt.registerTask( 'readme', ['wp_readme_to_markdown'] );

    // Generate .pot file.
    grunt.registerTask( 'i18n', ['addtextdomain', 'makepot'] );

    // Grunt release - Create installable package of the local files
    grunt.registerTask('release', ['clean:zip', 'copy', 'compress', 'clean:main']);

    // Version Bump `grunt bump-version --ver=<version-number>`
    grunt.registerTask( 'bump-version', function() {
        var newVersion = grunt.option("ver");

        if ( newVersion ) {     
            grunt.task.run( "bumpup:" + newVersion );
            grunt.task.run( "replace" );
        }
    } );

    grunt.util.linefeed = '\n';

};