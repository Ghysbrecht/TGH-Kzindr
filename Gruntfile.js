
module.exports = function(grunt) {

    grunt.initConfig({
        phpcs: {
            application: {
                src: ['lib/**/*.php']
            },
            options: {
                bin: 'phpcs',
                standard: 'PSR2'
            }
        },
        watch: {
            scripts: {
                files: ['lib/**/*.php'],
                tasks: ['phpcs']
            },
            sass: {
                files: ['assets/scss/**/*.scss'],
                tasks: ['sass']
            },
            js: {
                files: ['assets/js/**/*.js'],
                tasks: ['concat']
            }
        },
        sass: {
            options: {
                sourceMap: true,
                includePaths: ['bower_components/foundation-sites/scss/']
            },
            dist: {
                files: {
                    'public/css/app.css': 'assets/scss/app.scss'
                }
            }
        },
        concat: {
            dist: {
                src: [
                    'bower_components/jquery/dist/jquery.min.js',
                    'bower_components/foundation-sites/dist/foundation.js',
                    'assets/js/*.js'
                ],
                dest: 'public/js/app.js'
            },
        }
    });

    grunt.loadNpmTasks('grunt-phpcs');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-concat');

    grunt.registerTask('assets', ['sass', 'concat']);
    grunt.registerTask('default', ['assets', 'phpcs']);
};
