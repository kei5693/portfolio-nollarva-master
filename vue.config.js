const ansiRegex = require('ansi-regex');

let buildOptions = {};

if (process.env.NODE_ENV === "production" || process.env.NODE_ENV === "development") {
  buildOptions = Object.assign(buildOptions, {
    publicPath: "./",
    outputDir: undefined,
    assetsDir: undefined,
    runtimeCompiler: undefined,
    productionSourceMap: undefined,
    parallel: undefined,
    css: undefined
  });
}

module.exports = {
  transpileDependencies: [ansiRegex],
  devServer: {
    // proxy: 'http://localhost:3000'
  },
  ...buildOptions
};