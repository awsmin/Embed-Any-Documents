const mode = process.env.NODE_ENV || "development";
const devtool = mode === "development" ? "source-map" : false;

let webpackConfig = {
	entry: {
		"document-block": "./blocks/document/main.js"
	},
	output: {
		path: __dirname + "/blocks/document/",
		filename: "[name].js"
	},
	mode: mode,
	devtool: devtool,
	watch: mode !== "production",
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: [
					{
						loader: "babel-loader"
					}
				]
			}
		]
	}
};

module.exports = webpackConfig;
