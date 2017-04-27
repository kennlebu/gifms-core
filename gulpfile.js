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

gulp.task("swagger",function(){

		var doc = yaml.safeLoad(fs.readFileSync(path.join(__dirname,"api/swagger/swagger.yaml")));
		
		fs.writeFileSync(
			path.join(__dirname,"public/api/docs/gifms.json"),
				JSON.stringify(doc,null," ")
			);

	});

gulp.task("watch", function(){
	gulp.watch("api/swagger/swagger.yaml",["swagger"]);
});