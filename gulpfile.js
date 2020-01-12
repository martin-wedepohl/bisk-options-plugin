// Load Gulp...of course
const { src, dest, task, series, watch, parallel } = require('gulp');

// CSS related plugins
var sass         = require( 'gulp-sass' );
var autoprefixer = require( 'gulp-autoprefixer' );

// JS related plugins
var uglify       = require( 'gulp-uglify' );
var babelify     = require( 'babelify' );
var browserify   = require( 'browserify' );
var source       = require( 'vinyl-source-stream' );
var buffer       = require( 'vinyl-buffer' );
var stripDebug   = require( 'gulp-strip-debug' );

// Utility plugins
var rename       = require( 'gulp-rename' );
var sourcemaps   = require( 'gulp-sourcemaps' );
var notify       = require( 'gulp-notify' );
var options      = require( 'gulp-options' );
var gulpif       = require( 'gulp-if' );
var image        = require( 'gulp-image' );

// Project related variables
var projectURL   = 'https://bisk.local/';

// Destination direcotry
var baseDir = '../../wp-content/plugins/bisk-options-plugin/';

// Style Sheets
var adminStyleSRC = 'src/scss/admin.scss';
var styleSRC      = 'src/scss/style.scss';
var datepickerSRC = 'src/scss/jquery-ui.scss';
var styleURL      = baseDir + 'dist/css/';
var mapURL        = './';

// Javascript
var jsSRC        = 'src/js/';
var jsAdmin      = 'admin.js';
var jsScripts    = 'scripts.js';
var jsFiles      = [jsAdmin, jsScripts];
var jsURL        = baseDir + 'dist/js/';

// Index files
var srcIndexWatch    = 'src/index.php';
var distIndex        = baseDir + 'dist/';
var srcJSIndexWatch  = 'src/js/index.php';
var distJSIndex      = baseDir + 'dist/js/';
var srcSccsIndexWatch = 'src/scss/index.php';
var distCssIndex     = baseDir + 'dist/css/';

// Watches
var styleWatch       = 'src/scss/**/*.scss';
var jsWatch          = 'src/js/**/*.js';
var txtWatch         = '*.txt';
var phpWatch         = '*.php';
var phpIncludesWatch = 'includes/**/*.php';
var phpVendorWatch   = 'vendor/**/*.php';
var languagesWatch   = 'languages/**/*.*';

function css(done) {
	src([datepickerSRC,styleSRC,adminStyleSRC])
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'expanded'
		}) )
		.pipe( dest( styleURL ) )   // If want to write uncompressed file
		.pipe( sourcemaps.init() )
		.pipe( sass({
			errLogToConsole: true,
			outputStyle: 'compressed'
		}) )
		.on( 'error', console.error.bind( console ) )
        .pipe( autoprefixer() )
        .pipe( rename( { suffix: '.min' } ) )
		.pipe( sourcemaps.write( mapURL ) )
		.pipe( dest( styleURL ) )
	done();
}

function js(done) {
	jsFiles.map(function (entry) {
		return browserify({
			entries: [jsSRC + entry]
		})
		.transform( babelify, { presets: [ '@babel/preset-env' ] } )
		.bundle()
		.pipe( source( entry ) )
		.pipe( buffer() )
		.pipe( gulpif( options.has( 'production' ), stripDebug() ) )
//		.pipe( dest( jsURL ) )      // If want to write uncompressed file
		.pipe( sourcemaps.init({ loadMaps: true }) )
		.pipe( uglify() )
        .pipe( rename( { suffix: '.min' } ) )
		.pipe( sourcemaps.write( '.' ) )
		.pipe( dest( jsURL ) )
	});
	done();
}

//function compress_images( done ) {
//    src( imageSRC )
//        .pipe(image())
//        .pipe( dest( imageURL ) );
//    done();
//}

function watch_files() {
	watch( txtWatch, copyTxt );
	watch( phpWatch, copyPhp );
	watch( phpIncludesWatch, copyIncludesPhp );
    watch( phpVendorWatch, copyVendorPhp);
	watch( languagesWatch, copyLanguages );
    watch( srcIndexWatch, copyDistIndex );
    watch( srcJSIndexWatch, copyDistJSIndex );
    watch( srcSccsIndexWatch, copyDistCssIndex);
	watch( styleWatch, css );
	watch( jsWatch, js );
	src( styleSRC )
		.pipe( notify({ message: 'Gulp is Watching, Happy Coding!' }) );
};

function copyTxt(done) {
    src( txtWatch )
        .pipe( dest( baseDir ) );
    done();
}

function copyPhp(done) {
    src( phpWatch )
        .pipe( dest( baseDir ) );
    done();
}

function copyIncludesPhp(done) {
    src( phpIncludesWatch )
        .pipe( dest( baseDir + 'includes' ) );
    done();
}

function copyVendorPhp(done) {
    src( phpVendorWatch )
        .pipe( dest( baseDir + 'vendor' ) );
    done();
}

function copyLanguages(done) {
    src( languagesWatch )
        .pipe( dest( baseDir + 'languages' ) );
    done();
}

function copyDistIndex(done) {
    src( srcIndexWatch, {allowEmpty: true} )
        .pipe( dest( distIndex ) );
    done();
}

function copyDistJSIndex(done) {
    src( srcJSIndexWatch )
        .pipe( dest( distJSIndex ) );
    done();
}

function copyDistCssIndex(done) {
    src( srcSccsIndexWatch, {allowEmpty: true} )
        .pipe( dest( distCssIndex ) );
    done();
}

task("css", css);
task("js", js);
//task("compress_images", compress_images);
//task("default", series(css, js, compress_images));
task("default", series(css, js, copyTxt, copyPhp, copyIncludesPhp, copyVendorPhp, copyLanguages, copyDistIndex, copyDistJSIndex, copyDistCssIndex));
task("watch", series(css, js, copyTxt, copyPhp, copyIncludesPhp, copyVendorPhp, copyLanguages, copyDistIndex, copyDistJSIndex, copyDistCssIndex, watch_files));