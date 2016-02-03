var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();
plugins.imagemin = require('gulp-imagemin');

var paths = {
    images: {
        tiles: 'web/assets/images/tiles/**/*',
    },
};

gulp.task('optimize-tile-images', function() {
    return gulp
        .src(paths.images.tiles)
        .pipe(plugins.imagemin({ optimizationLevel: 5 }))
        .pipe(gulp.dest(paths.images.tiles.replace('/**/*', '')))
    ;
});
