var gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    sass = require('gulp-sass'),
    minifyCSS = require('gulp-minify-css'),
    browserify = require('gulp-browserify2'),
    babelify = require('babelify'),
    jshint = require('gulp-jshint'),
    util = require('gulp-util'),
    buffer = require('vinyl-buffer'),
    source = require('vinyl-source-stream'),
    uglify = require('gulp-uglify'),
    sourcemaps = require('gulp-sourcemaps'),
    rename = require('gulp-rename');

//PRODUCTION TASKS


//DEVELOPMENT TASKS

gulp.task('lint', function () {
    gulp.src('./src/js/**/*.es6.js')
        .pipe(jshint({
        	esnext: true,
			expr: true,
            jquery: true
            }))
        .pipe(jshint.reporter('default'))
        .pipe(jshint.reporter('fail'));
});

gulp.task('transpile_global', function () {
    gulp.src('./src/js/global/app.es6.js')
    .pipe(browserify({
        fileName: 'app.js',
        transform: {
            tr: babelify,
            options: {
                loose: ['es6.modules']
            }
        },
        options: {
            debug: true
        }
    }))
	.pipe(buffer())
    .pipe(gulp.dest('./build/resources/js'));
});

gulp.task('transpile_artwork', function () {
    gulp.src('./src/js/artwork/artwork.es6.js')
    .pipe(browserify({
        fileName: 'artwork.js',
        transform: {
            tr: babelify,
            options: {
                loose: ['es6.modules']
            }
        },
        options: {
            debug: true
        }
    }))
	.pipe(buffer())
    .pipe(gulp.dest('./build/resources/js'));
});

gulp.task('sass', function () {
    gulp.src('./src/sass/**/*.scss')
        .pipe(sass({
            style: 'expanded',
            sourceComments: 'normal',
            precision: 2,
            onError: sass.logError
        }).on('error', sass.logError))
        .pipe(autoprefixer({ browsers: ['last 2 versions', 'Explorer 9']}))
        .pipe(gulp.dest('./build/resources/css'));
});

gulp.task('blog_sass', function () {
    gulp.src('./src/blog_sass/**/*.scss')
        .pipe(sass({
            style: 'expanded',
            sourceComments: 'normal',
            precision: 2,
            onError: sass.logError
        }).on('error', sass.logError))
        .pipe(autoprefixer({ browsers: ['last 2 versions', 'Explorer 9']}))
        .pipe(gulp.dest('./build/blog/wp-content/themes/chip/'));
});

gulp.task('watch', function () {
    gulp.watch(['./src/sass/**/*.scss'], ['sass']);
	gulp.watch(['./src/blog_sass/**/*.scss'], ['blog_sass']);
    gulp.watch(['./src/js/global/**/*.es6.js'], ['lint', 'transpile_global']);
	gulp.watch(['./src/js/artwork/**/*.es6.js'], ['lint', 'transpile_artwork']);
});

gulp.task('default', ['sass', 'transpile_global', 'transpile_artwork', 'watch']);