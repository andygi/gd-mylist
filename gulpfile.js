var gulp = require('gulp');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');

gulp.task('compress-js', function() {
    return gulp.src('src_js/gd-script.js')
        .pipe(uglify())
        .pipe(gulp.dest('js'));
});

gulp.task('minify-css', function() {
    return gulp.src('src_css/app.css')
        .pipe(cleanCSS({
            compatibility: 'ie8'
        }))
        .pipe(gulp.dest('css'));
});

gulp.task('dev', ['compress-js', 'minify-css']);

gulp.task('default', function() {
    gulp.watch([
        'src_css/*.css', 
        'src_js/*.js'
    ], ['dev']);
});
