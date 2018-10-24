const gulp = require('gulp');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const rename = require('gulp-rename');
const flipper = require('gulp-css-flipper');

function sassTask() {
	return (
		gulp
			.src('assets/css/scss/*.scss')
			.pipe(sourcemaps.init())
			.pipe(sass({indentWidth: 1, outputStyle: 'expanded', indentType: 'tab'}).on('error', sass.logError))
			.pipe(autoprefixer({browsers: ['last 3 versions'],}))
			.pipe(sourcemaps.write('.'))
			.pipe(gulp.dest('assets/css'))
	);
}

function rtlTask() {
	return (
		gulp
			.src(['assets/css/*.css', '!assets/css/*-rtl.css'])
			.pipe(flipper())
			.pipe(rename(
				{suffix: "-rtl"}
			))
			.pipe(gulp.dest('assets/css/'))
	);
}

function sassProduction() {
	return (
		gulp
			.src('assets/css/scss/*.scss')
			.pipe(sass({indentWidth: 1, outputStyle: 'expanded', indentType: 'tab'}).on('error', sass.logError))
			.pipe(autoprefixer({browsers: ['last 3 versions'],}))
			.pipe(gulp.dest('assets/css'))
	);
}

function watchTask() {
	return gulp.watch('assets/css/scss/**/*.scss', gulp.series(
		sassTask,
		rtlTask
	))
}

gulp.task('default', gulp.series(
		sassTask,
		rtlTask,
		watchTask,
	),
);

gulp.task('production', gulp.series(
		sassProduction,
		rtlTask
	),
);
