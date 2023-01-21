module.exports = {

  transpileDependencies: [
    'vuetify'
  ],
  devServer: {
    proxy: "https://service.test.mailtrackminerba.com/",
    https: true
  },
  // chainWebpack: config => {
  //   config.plugin('html').tap(args => {
  //     args[0].title = "Mail Tracking Minerba";
  //     return args;
  //   });
  // },
  chainWebpack: config => {
    config.plugins.delete('pwa');
    config.plugins.delete('workbox');
  }
}