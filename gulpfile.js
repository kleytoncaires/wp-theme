const themeName = 'caires-digital'

const gulp = require('gulp')
const { parallel, series } = require('gulp')
require('dotenv').config()
const plumber = require('gulp-plumber')
const notify = require('gulp-notify')
const fs = require('fs')
const path = require('path')

const webp = require('gulp-webp')
const tinypng = require('gulp-tinypng-compress')
const uglify = require('gulp-uglify')
const sass = require('gulp-sass')(require('sass'))
const concat = require('gulp-concat')
const autoprefixer = require('gulp-autoprefixer')
const babel = require('gulp-babel')
const wpPot = require('gulp-wp-pot')
const sort = require('gulp-sort')
const ftp = require('vinyl-ftp')
const rename = require('gulp-rename')

const srcPath = 'assets/js'
const destPath = './'

const translateOpts = {
    phpSrc: './**/*.php',
    textDomain: themeName,
    destFile: themeName + '.pot',
    destDir: './languages',
    packageName: themeName,
    bugReport: '',
    lastTranslator: 'Kleyton Caires',
    team: 'Caires Digital',
}

function handleErrors() {
    return plumber({
        errorHandler: notify.onError((error) => `Error: ${error.message}`),
    })
}

function js() {
    const files = fs
        .readdirSync(srcPath)
        .filter((file) => path.extname(file) === '.js')

    const jsTasks = files.map((file) => {
        return gulp
            .src(path.join(srcPath, file), { sourcemaps: true })
            .pipe(handleErrors())
            .pipe(babel({ presets: ['@babel/preset-env'] }))
            .pipe(concat(file))
            .pipe(uglify())
            .pipe(gulp.dest(destPath, { sourcemaps: '.' }))
    })

    return Promise.all(jsTasks)
}

function css() {
    return gulp
        .src('assets/css/*.scss', { sourcemaps: true })
        .pipe(handleErrors())
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(
            autoprefixer({
                browserlist: ['last 2 versions'],
                cascade: false,
            })
        )
        .pipe(gulp.dest('./', { sourcemaps: '.' }))
}

function translate() {
    return gulp
        .src(translateOpts.phpSrc)
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
        .pipe(gulp.dest(translateOpts.destDir + '/' + translateOpts.destFile))
}

function optimizeImages() {
    return gulp
        .src('assets/img/**/*.+(png|jpg|jpeg|gif|svg)')
        .pipe(
            tinypng({
                key: process.env.TINYPNG_API_KEY,
                sigFile: 'images/.tinypng-sigs',
                log: true,
            })
        )
        .pipe(gulp.dest('assets/img'))
}

function convertToWebP() {
    return gulp
        .src('assets/img/**/*.+(jpg|jpeg|png)')
        .pipe(webp())
        .pipe(gulp.dest('assets/img/webp'))
}

function watchFiles() {
    gulp.watch('assets/css/**/*.scss', css)
    gulp.watch('assets/js/*.js', js)
}

const conn = ftp.create({
    host: process.env.FTP_HOST,
    user: process.env.FTP_USER,
    password: process.env.FTP_PASSWORD,
    parallel: 10,
    log: console.log,
})

function deploy() {
    return gulp
        .src([
            '**',
            '!node_modules/**',
            '!**/.env',
            '!**/.git',
            '!**/.gitignore',
            '!**/README.md',
        ])
        .pipe(
            rename((path) => {
                path.dirname = path.dirname.replace(/\\/g, '/')
            })
        )
        .pipe(conn.dest(process.env.FTP_PATH))
}

exports.default = series(translate, parallel(css, js), watchFiles)
exports.build = parallel(css, js, translate, optimizeImages, convertToWebP)
exports.deploy = deploy
