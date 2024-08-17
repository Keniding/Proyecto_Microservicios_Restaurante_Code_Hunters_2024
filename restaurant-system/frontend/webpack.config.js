const path = require('path');

module.exports = {
    mode: 'production',
    entry: {
        main: './assets/js/main.js',
        carta: './app/menu/roles/mesero/modulos/carta/carta.js',
        orden: './app/menu/roles/mesero/modulos/orden/js/script.js',
        order: './app/menu/roles/mesero/modulos/orden/js/order.js',
        dni: './app/menu/roles/mesero/modulos/orden/js/verificarCliente.js',
        mesas: './app/menu/roles/mesero/modulos/mesas/js/script.js',
        etiquetas: './app/menu/roles/mesero/modulos/orden/js/etiquetas.js',
        categoria: './app/menu/roles/administrador/modulos/categoria/js/script.js',
        comidaCRUD: './app/menu/roles/administrador/modulos/comida/js/script.js',
        usuarioCRUD: './app/menu/roles/administrador/modulos/user/js/script.js',

        title: './js/datos.js',

        getroles: './app/register/js/get_roles.js',
        register: './app/register/js/register.js',

        chat: './app/message/fulsocket/app.js',
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'assets/dist'),
        assetModuleFilename: 'images/[name][ext]'
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
                test: /\.(png|jpe?g|gif|svg)$/i,
                type: 'asset/resource',
            },
        ],
    },
    optimization: {
        splitChunks: {
            chunks: 'all',
        },
    },
};
