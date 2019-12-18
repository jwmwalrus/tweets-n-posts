const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');


var devmode = process.env.NODE_ENV !== 'production';

module.exports = {
    cache: false,
    stats: 'minimal',
    parallelism: 2,
    devtool: devmode ? 'eval-source-map' : 'source-map',
    output: {
        path: path.join(__dirname, 'public/dist'),
        filename: 'js/[name].js',
        publicPath: '/dist/',
    },
    module: {
        rules: [
            {
                test: /node_modules\/.+\/(jquery\.min)\.js$/,
                use: ['script-loader'],
            },
            {
                test: /node_modules\/datatables\.net.*\/.+\.js$/,
                use: ['script-loader'],
            },
            {
                test: /dist\/js\/app\.js$/,
                exclude: /node_modules/,
                use: ['export-loader'],
            },
            {
                test: /\.(s*)css$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                        options: {
                            sourceMap: true,
                        },
                    },
                    // {
                        // loader: 'style-loader',
                    // },
                    {
                        loader: 'css-loader',
                        options: {
                            sourceMap: true,
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true,
                        },
                    },
                ],
            },
            {
                test: /\.(png|jpg|jpeg|gif|ico|svg)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        name: 'images/[name].[ext]',
                    },
                },
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: {
                    loader: 'file-loader',
                    options: {
                        name: 'fonts/[name].[ext]',
                    },
                },
            },
        ],
    },
    resolve: {
        alias: {
            'dist/js/app.js': path.resolve(__dirname, 'assets/js/app.js'),
            'dist/js': path.resolve(__dirname, 'assets/js'),
            'bootstrap/scss': path.resolve(__dirname, 'node_modules/bootstrap/scss'),
        },
        modules: [
            path.resolve(__dirname, 'assets/js/app.js'),
            path.resolve(__dirname, 'node_modules'),
        ],
    },
    name: 'app',
    entry: {
        app: ['./assets/js/app.js'],
        home: ['./assets/js/home.js'],
        login: ['./assets/js/login.js'],
        post_new: ['./assets/js/post_new.js'],
        post_edit: ['./assets/js/post_edit.js'],
        register: ['./assets/js/register.js'],
        user: ['./assets/js/user.js'],
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'css/[name].css',
            // chunkFilename: "css/[id].css"
        }),
    ],
};
