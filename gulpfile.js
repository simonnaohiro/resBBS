'use strict';

const gulp = require("gulp");
const sass = require("gulp-sass");
const plumber = require("gulp-plumber");
const notify = require("gulp-notify");
const browserSync = require('browser-sync');

gulp.task("default" ,gulp.task('browser-sync'));

gulp.task("default" ,function(){
  browserSync({
    server: {
      baseDir: "./htdocs/",
      index: "index.html"
    }
  })
});
//
//ブラウザリロード
//
gulp.task('bs-reload' ,function(){
  browserSync.reload();
});

//
//監視ファイル
//
// gulp.task('default', gulp.series(gulp.task('browser-sync') ,function(){
//  gulp.watch("./htdocs/*.html", gulp.task('bs-reload'));
//  gulp.watch("./htdocs/res_BBS/css/*.css", gulp.task('bs-reload'));
//  gulp.watch("./htdocs/res_BBS/src/*.js", gulp.task('bs-reload'));
// }));

//
//sassコンパイラ
//
gulp.task("default", function() {
  // style.scssファイルを取得
  return (
    gulp
      .src("./src/scss/*.scss")
      // Sassのコンパイルを実行
      .pipe(
        sass({
          outputStyle: "expanded"
        })
      )
      // cssフォルダー以下に保存
      .pipe(gulp.dest("./src/css"))
  );
});
