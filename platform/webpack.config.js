const Encore = require('@symfony/webpack-encore')
const WebpackBar = require('webpackbar')
const path = require('path')
const webpack = require('webpack')

// Manually configure the runtime environment if not already configured yet by the "encore" command. It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev')
}

function getConfig ({ brand, color, entries }) {
  Encore.reset()

  Encore
    .addPlugin(new webpack.DefinePlugin({
      __VUE_OPTIONS_API__: false,
      __VUE_PROD_DEVTOOLS__: false
    }))
    .addPlugin(new WebpackBar({
      name: brand,
      color
    }))
    .setOutputPath('public/build/' + brand)
    .setPublicPath('/fragment/pwa/build/' + brand)
    .enableVersioning()
    .setManifestKeyPrefix(brand)
    .configureManifestPlugin(options => {
      options.seed = require(`./assets/brands/${brand}/manifest.json`)
    })

    .addEntry('app-' + brand, `./assets/brands/${brand}/app.ts`)
    .enableVueLoader()
    .addAliases({
      '@': path.resolve(__dirname, 'assets', 'js'),
      styles: path.resolve(__dirname, 'assets', 'css'),
      fonts: path.resolve(__dirname, 'assets', 'fonts')
    })

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment if you use TypeScript
    .enableTypeScriptLoader()
    .enableForkedTypeScriptTypesChecking()

    .configureWatchOptions(watchOptions => {
      watchOptions.poll = 250
    })
    .configureDevServerOptions(options => {
      options.allowedHosts = 'all'
    })

  for (const entry in entries) {
    Encore.addEntry(entry, entries[entry])
  }

  const config = Encore.getWebpackConfig()
  config.name = brand

  return config
}

const yuugado = getConfig({
  brand: 'yuugado',
  color: 'blue',
  entries: {
  }
})

const vj = getConfig({
  brand: 'vj',
  color: 'red',
  entries: {
  }
})

module.exports = [vj, yuugado]
