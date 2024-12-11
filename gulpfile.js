const gulp = require('gulp');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const postcss = require('gulp-postcss');
const tailwindcss = require('tailwindcss');
const autoprefixer = require('autoprefixer');
const rename = require('gulp-rename');

// Task to compile and process Tailwind CSS along with other CSS files
gulp.task('css', function() {
    return gulp.src('resources/css/**/*.css')
        .pipe(postcss([
            tailwindcss(),
            autoprefixer(),
        ]))
        .pipe(concat('app.css'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('public/wsql-contents/assets/css'));
});

// Task to compile and minify JavaScript files
gulp.task('scripts', function() {
    return gulp.src('resources/js/**/*.js')
        .pipe(concat('app.js'))
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('public/wsql-contents/assets/js'));
});

// Task to copy fonts
gulp.task('fonts', function() {
    return gulp.src('resources/fonts/**/*', {encoding: false})
        .pipe(gulp.dest('public/wsql-contents/assets/fonts'));
});

// Default task to run all tasks
gulp.task('default', gulp.parallel('css', 'scripts', 'fonts'));

// Watch task to watch for changes in files
gulp.task('watch', function() {
    gulp.watch('resources/css/**/*.css', gulp.series('css'));
    gulp.watch('resources/views/**/*.php', gulp.series('css'));
    gulp.watch('resources/views/**/*.html', gulp.series('css'));
    gulp.watch('resources/js/**/*.js', gulp.series('css'));
    gulp.watch('resources/js/**/*.js', gulp.series('scripts'));
    gulp.watch('resources/fonts/**/*', gulp.series('fonts'));
    gulp.watch('src/**/*.php', gulp.series('css'));
    gulp.watch('src/**/*.html', gulp.series('css'));
});