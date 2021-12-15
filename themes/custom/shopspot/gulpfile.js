const gulp = require("gulp");
const sass = require("gulp-sass")(require("sass"));
const jshint = require("gulp-jshint");
function style() {
  return gulp
    .src("./src/sass/**/*.scss")
    .pipe(sass())
    .on("error", sass.logError)
    .pipe(gulp.dest("./dist/css"));
}
function hint() {
  return gulp
  .src("./src/js/**/*.js")
  .pipe(jshint())
  .pipe(gulp.dest("./dist/js"))
  .pipe(jshint.reporter("jshint-stylish"));
}
function watch() {
  gulp.watch("./src/sass/**/*.scss", style);
  gulp.watch("./src/js/**/*.js", hint);

}
exports.style = style;
exports.watch = watch;
exports.hint = hint;