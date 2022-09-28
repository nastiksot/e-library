const fs = require('fs');
const path = require('path');
const glob = require('glob');
const _ = require('lodash');
const Encore = require('@symfony/webpack-encore');
const {VuetifyLoaderPlugin} = require('vuetify-loader');
const Dotenv = require('dotenv');

const config = Dotenv.parse(fs.readFileSync('./.env'));

if (fs.existsSync('.env.local')) {
    let localConfig = Dotenv.parse(fs.readFileSync('./.env.local'));

    for (let k in localConfig) {
        config[k] = localConfig[k];
    }
}

for (let k in config) {
    process.env[k] = config[k];
}

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/assets/')
    .setPublicPath('/assets')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('assets/')

    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    /*
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
        config.presets.push('@babel/preset-flow');
    })

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .configureTerserPlugin((options) => {
        options.terserOptions = {
            output: {
                comments: false,
            },
        };
    })

    .enableSassLoader()
    .enableVueLoader()
    .enableIntegrityHashes(Encore.isProduction())

    .copyFiles([{
        from: './assets/images',

        // optional target path, relative to the output dir
        to: 'images/[path][name].[hash:8].[ext]',

        // only copy files matching this pattern
        //pattern: /\.(png|jpg|jpeg)$/
    }])

    .autoProvidejQuery()
    .addPlugin(new VuetifyLoaderPlugin())
;

if (fs.existsSync(__dirname + '/assets/js/pages')) {
    _.forEach(
        glob.sync(__dirname + '/assets/js/pages/**/entry.point.js'),
        (jsFile) => {
            const name = jsFile.replace(new RegExp('^.*/assets/(.+)/entry.point.js$'), '$1');
            Encore.addEntry(name, jsFile);
        }
    );
}

if (fs.existsSync(__dirname + '/assets/js/admin')) {
    let jsFiles = getFiles(__dirname + '/assets/js/admin', [], 'js');
    _.forEach(jsFiles, (fileName) => {
        Encore.addEntry('js/admin/' + fileName, ['./assets/js/admin/' + fileName + '.js']);
    });
}

if (fs.existsSync(__dirname + '/assets/scss')) {
    let scssFiles = getFiles(__dirname + '/assets/scss', [], 'scss');
    _.forEach(scssFiles, function (fileName) {
        Encore.addStyleEntry('css/' + fileName, ['./assets/scss/' + fileName + '.scss']);
    });
}

module.exports = Encore.getWebpackConfig();

///// Functions

function getFiles(dir, exclude, fileType) {
    let files = fs.readdirSync(dir)
        .filter(function (file) {
            return -1 === exclude.indexOf(file) && !fs.statSync(path.join(dir, file)).isDirectory() && !_.startsWith(file, '_');
        });

    return _.map(files, function (filename) {
        let lastDotPosition = filename.lastIndexOf(".");
        let ext;
        if (lastDotPosition !== -1) {
            ext = filename.substr(lastDotPosition + 1);
            if (ext === fileType) {
                return filename.substr(0, lastDotPosition);
            }
        }
    });
}
