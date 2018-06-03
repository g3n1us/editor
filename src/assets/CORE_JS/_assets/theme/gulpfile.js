const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const concat = require('gulp-concat');

/*
const clean = require('gulp-clean');
	
const cssnano = require('gulp-cssnano');
const sourcemaps = require('gulp-sourcemaps');
const awspublish = require('gulp-awspublish');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');
const gulpCopy = require('gulp-copy');
const useref = require('gulp-useref');
const gulpif = require('gulp-if');
const babel = require("gulp-babel");
const iife = require("gulp-iife");
const filter = require('gulp-filter');
const tap = require('gulp-tap');
const rev = require('gulp-rev');
const revReplace = require('gulp-rev-replace');
*/

const fs = require('fs');
const path = require('path');

const destination = 'dist';




gulp.task('default', function() {
	gulp.src(['node_modules/handlebars/dist/handlebars.js'])
	  .pipe(concat('public-compiled.js'))
	  .pipe(gulp.dest(destination + '/js'))
	  //.pipe(concat('public-compiled.min.js'))
	  // .pipe(uglify())
	  .pipe(gulp.dest(destination + '/js'));
	  
/*
	return gulp.src('sass/theme.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer({
		  browsers: ['last 4 versions'],
		}))
// 		.pipe(sourcemaps.init())
		.pipe(concat('compiled.css'))
		.pipe(gulp.dest(destination + '/css'));
*/
// 		.pipe(cssnano())
// 		.pipe(concat(minified_outputfilename))	  
// 		.pipe(sourcemaps.write('.'))
// 		.pipe(gulp.dest(destination + '/css'));  
	  
	  
/*
	gulp.src('node_modules/bootstrap/dist/scss/bootstrap.js')
	  .pipe(concat('public-compiled.js'))
	  .pipe(gulp.dest(destination + '/js'))
	  //.pipe(concat('public-compiled.min.js'))
	  // .pipe(uglify())
	  .pipe(gulp.dest(destination + '/js'));
*/
});


gulp.task('watch', function() {
	var watcher = gulp.watch(['./sass/*'], ['default']);
	watcher.on('change', function(event) {
	  console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
	});
	
});


