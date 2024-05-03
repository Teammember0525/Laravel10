//====Gulp Template Ver.20190716===
const gulp = require('gulp');  //Gulp核心
const nunjucksRender = require('gulp-nunjucks-render');   //HTML樣板產生
const autoprefixer = require('gulp-autoprefixer');   //自動前綴
const sass = require('gulp-sass');  //SCSS, SASS編譯
const imagemin   = require('gulp-imagemin');
const sourcemaps     = require('gulp-sourcemaps');   //SourceMap
const webserver      = require('gulp-webserver');   //產生WebServer

// 變數路徑(每次專案開始之前先改這裡)
var path = {
  tpl: './template/', // 樣板引擎原始檔
  source: './resources/assets/', // 原始碼
  public: './public/', // 輸出位置
  bootstrap: './node_modules/bootstrap-sass/'  //bootstrap
}

//gulp template engine
gulp.task('layout', function() {
  // Gets .html and .nunjucks files in pages
  return gulp.src( path.tpl + '*.+(html|nunjucks)')
  //Renders template with nunjucks
  .pipe(nunjucksRender({
      path: [ path.tpl + 'layout']
    }))
  // output files in app folder
  .pipe(gulp.dest( path.public ))
});

// SCSS, SASS編譯
gulp.task('sass', function() {
  var processors = [
      // 定義所需要的元件
      autoprefixer({ browsers: ['last 3 versions'], cascade: false }), // 使用 autoprefixer，這邊定義最新的3個版本瀏覽器
  ];
  return gulp
      .src( path.source + 'scss/**/*.{scss,sass}')
      .pipe(sourcemaps.init())
      .pipe(sass({ outputStyle: 'compressed',
      includePaths: [path.bootstrap + 'assets/stylesheets']}).on('error', sass.logError))
      .pipe(autoprefixer())
      .pipe(sourcemaps.write('./'))
      .pipe(gulp.dest( path.public ));
});

//設定 watch 任務
gulp.task('watch', function() {
  gulp.watch( path.source + 'scss/**/*.{scss,sass}' ,gulp.series('sass'));
  gulp.watch( path.tpl + '*.+(html|nunjucks)' ,gulp.series('layout'));
});

// //圖檔壓縮
gulp.task('imageMin', () =>
    gulp.src( path.public + 'images/*')
        .pipe(imagemin())
        .pipe(gulp.dest( path.public + 'images'))
);

// Start Web Server
gulp.task('webserver', function() {
  gulp.src( path.public )
    .pipe(webserver({
      port:1234,
      livereload: true,
      directoryListing: false,
      open: true,
      fallback: 'index.html'
    }));
});

//====任務執行====
gulp.task('default', gulp.series('sass', 'watch' ));   
// gulp.task('default', gulp.series('sass','layout',gulp.parallel('webserver', 'watch'))); //開發用加上樣板
gulp.task('output', gulp.series( 'imageMin' ));  //圖片壓縮
