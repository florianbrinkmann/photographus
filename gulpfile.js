const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const flipper = require('gulp-css-flipper');

gulp.task('sass', function () {
	return gulp.src('assets/css/scss/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass({indentWidth: 1, outputStyle: 'expanded', indentType: 'tab'}).on('error', sass.logError))
		.pipe(autoprefixer({browsers: ['last 3 versions'],}))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest('assets/css'));
});

gulp.task('sass-rtl', function () {
	return gulp.src('assets/css/*.css')
		.pipe(flipper())
		.pipe(rename(
			{suffix: "-rtl"}
		))
		.pipe(gulp.dest('assets/css/'));
});

gulp.task('sass-production', function () {
	return gulp.src('assets/css/scss/*.scss')
		.pipe(sass({indentWidth: 1, outputStyle: 'expanded', indentType: 'tab'}).on('error', sass.logError))
		.pipe(autoprefixer({browsers: ['last 3 versions'],}))
		.pipe(gulp.dest('assets/css'));
});

gulp.task('sass:watch', ['sass', 'sass-rtl'], function () {
	gulp.watch('assets/css/scss/**/*.scss', ['sass']);
});

gulp.task('default', ['sass:watch']);
gulp.task('production', ['sass-production', 'sass-rtl']);
