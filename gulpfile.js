var gulp 			= require('gulp'),
	sass 			= require('gulp-sass'),
	autoprefixer 	= require('gulp-autoprefixer'),
	input 			= './sass/**/*.scss',
	output 			= './css';

gulp.task('sass', function() {
    return gulp
        .src(input)
        .pipe(sass({
            outputStyle: 'expanded'
        }).on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(gulp.dest(output));
});

gulp.task('watch', function() {
    return gulp
        .watch(input, ['sass']);
});

gulp.task('default', ['sass', 'watch']);