// vue.config.js

const path = require('path');

/**
 * @type {import('@vue/cli-service').ProjectOptions}
 */
module.exports = {
  // frontend app is built separately from backend -- backend exposes API for
  //  frontend to talk to -- so we use api folder
  //  https://cli.vuejs.org/guide/deployment.html
  devServer: {
    proxy: {
      '^/api/': {
        target: 'http://localhost',
        changeOrigin: true, // for CORS
      },
    },
    watchContentBase: true,
    writeToDisk: true,
    // writeToDisk: (filePath) => {
    //   return /.*(?<!hot-update.json)$/.test(filePath);
    // },
  },

  outputDir: 'C:/xampp/htdocs',
};
