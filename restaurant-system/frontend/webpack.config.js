const path = require('path');

module.exports = {
    mode: 'production',
    entry: {
        main: './assets/js/main.js',
        carta: './app/menu/roles/mesero/modulos/carta/carta.js',
        orden: './app/menu/roles/mesero/modulos/orden/js/script.js',

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
                ],
            },
        ],
    },
};
