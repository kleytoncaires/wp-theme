// Initialize modules
const themeName = 'theme-name';
// Importing specific gulp API functions lets us write them below as series() instead of gulp.series()
const { src, dest, watch, series, parallel } = require('gulp');
// Importing all the Gulp-related packages we want to use
const sass = require('gulp-sass')(require('sass'));
const concat = require('gulp-concat');
const terser = require('gulp-terser');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const imagemin = require('gulp-imagemin');
const notify = require('gulp-notify');
const wpPot = require('gulp-wp-pot');
const sort = require('gulp-sort');

// File paths
const files = {
	scssPath: 'assets/css/**/*.scss',
	jsPath: 'assets/js/**/*.js',
	imgPath: 'assets/img/**/*.{jpg,jpeg,png,svg,gif}',
	imgDest: 'assets/img/',
	phpSrc: './**/*.php',
};

// Tranlate Settings
translateOpts = {
	textDomain: themeName,
	destFile: themeName + '.pot',
	destDir: './languages',
	packageName: themeName,
	bugReport: '',
	lastTranslator: 'Kleyton Caires',
	team: 'Caires Digital',
};

// Autoprefixer Settings
autoPrefixerOpts = [
	'last 2 version',
	'> 1%',
	'ie >= 9',
	'ie_mob >= 10',
	'ff >= 30',
	'chrome >= 34',
	'safari >= 7',
	'opera >= 23',
	'ios >= 7',
	'android >= 4',
	'bb >= 10',
];

// Sass task: compiles the style.scss file into style.css
function scssTask() {
	return src(files.scssPath, { sourcemaps: true }) // set source and turn on sourcemaps
		.pipe(sass()) // compile SCSS to CSS
		.pipe(postcss([autoprefixer(autoPrefixerOpts), cssnano()])) // PostCSS plugins
		.pipe(dest('./', { sourcemaps: '.' })) // put final CSS in dist folder with sourcemap
		.pipe(notify({ message: 'TASK: CSS Completed! ✅💯', onLast: true }));
}

// JS task: concatenates and uglifies JS files to script.js
function jsTask() {
	return src(
		[
			files.jsPath,
			//,'!' + 'includes/js/jquery.min.js', // to exclude any specific files
		],
		{ sourcemaps: true }
	)
		.pipe(concat('script.js'))
		.pipe(terser())
		.pipe(dest('assets/js/min', { sourcemaps: '.' }))
		.pipe(notify({ message: 'TASK: JS Completed! ✅💯', onLast: true }));
}

// IMG task: compress images
function imgTask() {
	return src(files.imgPath)
		.pipe(
			imagemin({
				progressive: true,
				optimizationLevel: 3, // 0-7 low-high
				interlaced: true,
				svgoPlugins: [{ removeViewBox: false }],
			})
		)
		.pipe(dest(files.imgDest))
		.pipe(
			notify({ message: 'TASK: Images Completed! ✅💯', onLast: true })
		);
}

function translateTask() {
	return src(files.phpSrc)
		.pipe(sort())
		.pipe(
			wpPot({
				domain: translateOpts.textDomain,
				package: translateOpts.packageName,
				bugReport: translateOpts.bugReport,
				lastTranslator: translateOpts.lastTranslator,
				team: translateOpts.team,
			})
		)
		.pipe(dest(translateOpts.destDir + '/' + translateOpts.destFile))
		.pipe(
			notify({
				message: 'TASK: Translation Completed! ✅💯',
				onLast: true,
			})
		);
}

// Watch task: watch SCSS and JS files for changes
// If any change, run scss and js tasks simultaneously
function watchTask() {
	watch(
		[files.scssPath, files.jsPath, files.imgPath],
		{ interval: 1000, usePolling: true }, //Makes docker work
		series(parallel(scssTask, jsTask, imgTask, translateTask))
	);
}

// Export the default Gulp task so it can be run
// Runs the scss and js tasks simultaneously
// then runs cacheBust, then watch task
exports.default = series(
	parallel(scssTask, jsTask, imgTask, translateTask),
	watchTask
);
