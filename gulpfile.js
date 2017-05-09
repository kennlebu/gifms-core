const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application as well as publishing vendor resources.
 |
 */

elixir((mix) => {
    mix.sass('app.scss')
       .webpack('app.js');
});






var gulp 	= require ("gulp");
var yaml 	= require ("js-yaml");
var path 	= require ("path");
var fs 		= require ("fs");
var map     = require('map-stream');
var gutil = require("gulp-util");

gulp.task("swagger",function(){

		var doc = yaml.safeLoad(fs.readFileSync(path.join(__dirname,"api/swagger/swagger.yaml")));
		
		fs.writeFileSync(
			path.join(__dirname,"public/api/docs/gifms.json"),
				JSON.stringify(doc,null," ")
			);

	});

gulp.task("watch", function(){
	//gulp.watch("api/swagger/swagger.yaml",["swagger"]);
	gulp.watch("api/swagger/**.yaml",["swagger_multiple"]);
});



gulp.task('swagger_multiple', function(){
  gulp.src('api/swagger/*.yaml')
    .pipe(map(function(file,cb){
      if (file.isNull()) return cb(null, file); // pass along
      if (file.isStream()) return cb(new Error("Streaming not supported"));

      var json;

      try {
        json = yaml.load(String(file.contents.toString('utf8')));
      } catch(e) {
        console.log(e);
        console.log(json);
      }

      file.path = gutil.replaceExtension(file.path, '.json');
      file.contents = new Buffer(JSON.stringify(json,null," "));

      cb(null,file);
    }))
    .pipe(gulp.dest('public/api/docs/'));
});