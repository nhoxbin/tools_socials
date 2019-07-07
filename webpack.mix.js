const mix = require('laravel-mix');

mix.webpackConfig({
	resolve: {
		alias: {
			'Api': path.resolve(__dirname, 'resources/js/api/'),
			'Components': path.resolve(__dirname, 'resources/js/components/'),
			'MyComponents': path.resolve(__dirname, 'resources/js/myComponents/'),
			'Helpers': path.resolve(__dirname, 'resources/js/helpers/'),
			'Constants': path.resolve(__dirname, 'resources/js/constants/'),
			'Container': path.resolve(__dirname, 'resources/js/container/'),
			'Views': path.resolve(__dirname, 'resources/js/views/'),
			'Themes': path.resolve(__dirname, 'resources/js/themes/'),
			'WebServices': path.resolve(__dirname, 'resources/js/webServices/'),
		}
	},
	output: {
		chunkFilename: 'js/chunks/' + (mix.inProduction() ? '[name].[hash]' : '[name]') + '.js'
	}
});

mix.js('resources/js/main.js', 'public/js')
	.sass('resources/sass/app.scss', 'public/css');