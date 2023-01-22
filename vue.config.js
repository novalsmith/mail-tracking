module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ?
    '' : '/',
  transpileDependencies: [
    'vuetify'
  ],
  devServer: {
    proxy: process.env.VUE_APP_PROXY_SERVICE_URL
  },
  chainWebpack: config => {
    config.plugin('html').tap(args => {
      args[0].title = "Mail Tracking Minerba";
      return args;
    });
  },
  chainWebpack: config => {
    config.plugins.delete('pwa');
    config.plugins.delete('workbox');
  },
  pwa: {
    name: "Mail Tracking Minerba"
  }
}