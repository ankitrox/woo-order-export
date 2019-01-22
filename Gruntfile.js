// our wrapper function (required by grunt and its plugins)
// all configuration goes inside this function
module.exports = function(grunt) {
    
   // CONFIGURE GRUNT
   grunt.initConfig({
      // get the configuration info from package.json file
      // this way we can use things like name and version (pkg.name)
      pkg: grunt.file.readJSON('package.json'),

    // all of our configuration goes here
    jshint:{

      files: ['Gruntfile.js', 'assets/js/woooe.js'],
      options: {
        // options here to override JSHint defaults
        globals: {
          jQuery: true,
          console: true,
          module: true,
          document: true
        }
      }
    },

      uglify: {
         dist: {
             files: {
                 'assets/js/dest/woooe.min.js': ['assets/js/woooe.js']
             }
         }
      },
      
      cssmin: {
          target: {
              files: {
                  'assets/css/dest/woooe.css': ['assets/css/woooe.css']
              }
          }
      },
      
      watch: {
          files: ['<%= jshint.files %>'],
          tasks:['jshint', 'uglify', 'cssmin']
      }
      
   });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task(s).
    grunt.registerTask('default', ['jshint, uglify', 'cssmin']);
};