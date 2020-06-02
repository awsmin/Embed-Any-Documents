/**
 * Automating Development Tasks
 * ----------------------------
 * @package embed-any-document
 * @since 2.6.0
 */

'use strict';

/*============================= Dependencies =============================*/

const gulp = require('gulp'),
	rename = require('gulp-rename'),
	lineEC = require('gulp-line-ending-corrector'),
	bs = require('browser-sync').create();

/* --- Dependencies: css --- */
const cleanCSS = require('gulp-clean-css'), // Minify CSS
	autoprefixer = require('gulp-autoprefixer');

/* --- Dependencies: js --- */
const uglify = require('gulp-uglify'); // Minify JavaScript

/*================================= Tasks =================================*/

let init = (cb) => {
	console.log('-------------------------------------------');
	console.log('<<<<<------- Embed Any Document ------->>>>>');
	console.log('-------------------------------------------');
	cb();
};

/* --- Tasks: Browsersync --- */
const DEV_URL = process.env.DEV_URL || 'localhost';

let browserSync = (cb) => {
	bs.init({
		ghostMode: false,
		proxy: DEV_URL,
		notify: false
	});
	cb();
};
let bsReload = (cb) => {
	bs.reload();
	cb();
};
browserSync.description = 'Initialize Browsersync and proxy: '.DEV_URL;
gulp.task('browser-sync', browserSync);

/* --- Tasks: CSS --- */

let styleTask = () => {
	return gulp
		.src([ './css/*.css', '!./css/*.min.css' ])
		.pipe(autoprefixer())
		.pipe(cleanCSS({ compatibility: 'ie9' }))
		.pipe(rename({ suffix: '.min' }))
		.pipe(lineEC())
		.pipe(gulp.dest('./css/'));
};
styleTask.description = 'Minify styles';
gulp.task('styles', styleTask);

let loadStyleTask = () => {
	return gulp.src('./css/*.css').pipe(bs.stream());
};
gulp.task('load-styles', gulp.series('styles', loadStyleTask));

/* --- Tasks: JS --- */

let scriptTask = () => {
	return gulp
		.src([ './js/*.js', '!./js/*.min.js' ])
		.pipe(uglify({ output: { comments: 'some' } }))
		.pipe(rename({ suffix: '.min' }))
		.pipe(lineEC())
		.pipe(gulp.dest('./js/'));
};
scriptTask.description = 'Minify JS files';
gulp.task('scripts', scriptTask);

gulp.task('load-scripts', gulp.series('scripts', bsReload));

/* --- Tasks: Watch files for any change --- */

let watchFiles = () => {
	gulp.watch('./**/*.php', bsReload);
	gulp.watch([ './css/**/*.css', '!./css/**/*.min.css' ], gulp.series('load-styles'));
	gulp.watch([ './js/**/*.js', '!./js/**/*.min.js' ], gulp.series('load-scripts'));
};
watchFiles.description = 'Watch PHP, JS and CSS files for any change';

gulp.task('watch', gulp.series(browserSync, gulp.parallel('styles', 'scripts'), watchFiles));

/* --- Tasks: Default tasks --- */
gulp.task('default', gulp.series(init, gulp.parallel('styles', 'scripts'), browserSync));

/* --- Tasks: Build tasks --- */
gulp.task('build', gulp.series(init, gulp.parallel('styles', 'scripts')));
