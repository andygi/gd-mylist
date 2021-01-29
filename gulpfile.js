var gulp = require('gulp');
var uglify = require('gulp-uglify');
var cleanCSS = require('gulp-clean-css');
var gulpCopy = require('gulp-copy');
var rm = require('gulp-rm');

gulp.task('compress-js', function() {
    return gulp.src('js/gd-script.js')
        .pipe(uglify())
        .pipe(gulp.dest('dist/js'));
});

gulp.task('minify-css', function() {
    return gulp.src('css/app.css')
        .pipe(cleanCSS({
            compatibility: 'ie8'
        }))
        .pipe(gulp.dest('dist/css'));
});

gulp.task('copy', function() {
    return gulp
        .src([
            'include/*',
            'lang/*',
            'lib/*',
            'template/*',
            'webfonts/*',
            'gd-mylist.php',
            'readme.txt'
        ])
        .pipe(gulpCopy('dist/'));
});

gulp.task('copy_icons', function() {
    return gulp
        .src([
            'node_modules/@fortawesome/fontawesome-free/css/all.min.css'
        ])
        .pipe(gulpCopy('dist/css/', { prefix: 4 }));
});

gulp.task('copy_fonts', function() {
    return gulp
        .src([
            'node_modules/@fortawesome/fontawesome-free/webfonts/*'
        ])
        .pipe(gulpCopy('dist/webfonts/', { prefix: 4 }));
});

gulp.task('remove', function() {
    return gulp.src( 'dist/**/*', { read: false })
        .pipe( rm() )
});

gulp.task('prod', gulp.series(
        'remove',
        'compress-js',
        'minify-css',
        'copy',
        'copy_icons',
        'copy_fonts'
    )
);

gulp.task('default', function() {
    return gulp.watch([
        'css/*.css', 
        'js/*.js',
        'template/*',
        'include/*',
        'lang/*',
        'gd-mylist.php'
    ], gulp.series('prod'));
});
