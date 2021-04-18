const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

module.exports = {
  mode: "development",

  entry: {
    admin: "./assets/src/admin.js",
    public: "./assets/src/public.js"
  }, 

  output: {
    filename: "[name].js",
    path: path.resolve(__dirname, "assets/bundle"),
    // publicPath: ''
  },

  devtool: 'inline-source-map',

  // devServer: {
  //   contentBase: path.resolve(__dirname, 'dist'),
  //   //index: index.html,
  //   port: 9000
  // },

  module: {
    rules: [
      {
        test: /\.css$/,
        use: [
          MiniCssExtractPlugin.loader,
          // Translates CSS into CommonJS
          "css-loader",
        ],
      },
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          // Translates CSS into CommonJS
          "css-loader",
          // Compiles Sass to CSS
          "sass-loader",
        ],
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"],
          },
        },
      },
    ],
  },

  plugins: [
    // Extract css into a seperate file
    new MiniCssExtractPlugin({
      filename: "[name].css",
    }),

    // Clean dist folder
    new CleanWebpackPlugin(),
  ],
};
