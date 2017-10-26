module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    
    less: {
      development: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: {
          "css/styles.css": "less/custom/styles.less"
        }
      }
    },
    concat: {
      options: {
        separator: ';'
      },
      dist: {
        src: ['js/src/vendor/bootstrap/*.js'],
        dest: 'js/dist/bootstrap.js'
      }
    },
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
      },
      dist: {
        files: {
          'js/min/<%= pkg.name %>.min.js': ['<%= concat.dist.dest %>']
        }
      }
    },
    watch: {
      styles: {
        files: ['less/**/*.less'],
        tasks: ['less'],
        options: {
          nospawn: true
        }
      }
    },
    clean: {
      main: ['release/<%= pkg.version %>']
    },
    compress: {
      main: {
        options: {
          mode: 'zip',
          archive: './release/pressgang.<%= pkg.version %>.zip'
        },
        expand: true,
        src: [
          '**',
          '!.idea',
          '!node_modules/**',
          '!release/**',
          '!.git/**',
          '!.sass-cache/**',
          '!css/src/**',
          // '!js/src/**',
          '!img/src/**',
          '!Gruntfile.js',
          '!package.json',
          '!.gitignore',
          '!.gitmodules'
        ],
        dest: 'pressgang/'
      }
    },
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-compress');


  grunt.registerTask("your-task-name", "your description", function() {

    // read all subdirectories from your modules folder
    grunt.file.expand("./modules/*").forEach(function (dir) {

      // get the current concat config
      var concat = grunt.config.get('concat') || {};

      // set the config for this modulename-directory
      concat[dir] = {
        src: ['/modules/' + dir + '/js/*.js', '!/modules/' + dir + '/js/compiled.js'],
        dest: '/modules/' + dir + '/js/compiled.js'
      };

      // save the new concat configuration
      grunt.config.set('concat', concat);
    });
    // when finished run the concatinations
    grunt.task.run('concat');
  });

  grunt.registerTask('default', ['less', 'watch', 'concat', 'uglify']);
  grunt.registerTask('build', ['compress']);

};