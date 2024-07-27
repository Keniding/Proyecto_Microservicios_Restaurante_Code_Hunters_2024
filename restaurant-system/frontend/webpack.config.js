const path = require('path');

module.exports = {
    mode: 'production',
    entry: {
        main: './assets/js/main.js',
        carta: './app/menu/roles/mesero/modulos/carta/carta.js',
        orden: './app/menu/roles/mesero/modulos/orden/js/script.js',
        order: './app/menu/roles/mesero/modulos/orden/js/order.js',
        etiquetas: './app/menu/roles/mesero/modulos/orden/js/etiquetas.js',
        dni: './app/menu/roles/mesero/modulos/orden/js/verificarCliente.js',
        categoria: './app/menu/roles/administrador/modulos/categoria/js/script.js',

        img: './assets/icons/tomato.png'
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'assets/dist'),
    },
    resolve: {
        alias: {
            config: path.resolve(__dirname, 'config/'),
        },
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env'],
                    },
                },
            },
            {
                test: /\.(png|jpe?g|gif)$/i,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'images',
                        },
                    },
                    {
                        loader: 'image-webpack-loader',
                        options: {
                            mozjpeg: {
                                progressive: true,
                                quality: 65
                            },
                            optipng: {
                                enabled: true,
                            },
                            pngquant: {
                                quality: [0.65, 0.90],
                                speed: 4
                            },
                            gifsicle: {
                                interlaced: false,
                            },
                            webp: {
                                quality: 75
                            }
                        }
                    },
                ],
            },
        ],
    },
    optimization: {
        splitChunks: {
            chunks: 'all',
        },
    },
};
