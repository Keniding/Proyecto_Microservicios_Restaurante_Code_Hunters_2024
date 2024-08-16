export class ChatWebSocket {
    constructor(url, userId) {
        this.url = url;
        this.userId = userId;
        this.socket = null;
        this.onMessageCallback = null;
        this.onErrorCallback = null;
        this.onConnectCallback = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.messageHandlers = {
            'auth_required': this.handleAuthRequired.bind(this),
            'auth_success': this.handleAuthSuccess.bind(this),
            'auth_failed': this.handleAuthFailed.bind(this),
            'chat': this.handleChat.bind(this),
            'error': this.handleError.bind(this),
            'initial_messages': this.handleInitialMessages.bind(this),
            'more_messages': this.handleMoreMessages.bind(this),
            'chat_list': this.handleChatList.bind(this)
        };
    }

    handleAuthRequired() {
        this.authenticate();
    }

    handleAuthSuccess() {
        console.log('Autenticación exitosa');
        if (this.onConnectCallback) {
            this.onConnectCallback();
        }
    }

    handleAuthFailed(data) {
        console.error('Autenticación fallida:', data.error);
        if (this.onErrorCallback) {
            this.onErrorCallback('Error de autenticación: ' + data.error);
        }
    }

    handleChat(data) {
        if (this.onMessageCallback) {
            this.onMessageCallback(data);
        }
    }

    handleError(data) {
        if (this.onErrorCallback) {
            this.onErrorCallback(data.message);
        }
    }

    handleInitialMessages(data) {
        if (this.onMessageCallback) {
            this.onMessageCallback(data);
        }
    }

    handleMoreMessages(data) {
        if (this.onMessageCallback) {
            this.onMessageCallback(data);
        }
    }

    handleChatList(data) {
        if (this.onMessageCallback) {
            this.onMessageCallback(data);
        }
    }

    connect() {
        this.socket = new WebSocket(this.url);

        this.socket.onopen = () => {
            console.log('WebSocket conexión abierta');
            this.reconnectAttempts = 0;
            this.authenticate();
        };

        this.socket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            console.log('Mensaje recibido:', data);

            if (this.messageHandlers[data.type]) {
                this.messageHandlers[data.type](data);
            } else {
                console.log('Mensaje no manejado:', data);
            }
        };

        this.socket.onerror = (error) => {
            console.error('WebSocket Error:', error);
            if (this.onErrorCallback) {
                this.onErrorCallback('Error de conexión WebSocket');
            }
        };

        this.socket.onclose = () => {
            console.log('WebSocket conexión cerrada');
            this.reconnect();
        };
    }

    reconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            console.log(`Intento de reconexión ${this.reconnectAttempts}...`);
            setTimeout(() => this.connect(), 5000);
        } else {
            console.error('Máximo número de intentos de reconexión alcanzado');
            if (this.onErrorCallback) {
                this.onErrorCallback('No se pudo reconectar al servidor');
            }
        }
    }

    authenticate() {
        console.log('Enviando autenticación para user_id:', this.userId);
        this.socket.send(JSON.stringify({
            type: 'auth',
            user_id: this.userId
        }));
    }

    sendMessage(chatId, content) {
        if (this.socket && this.socket.readyState === WebSocket.OPEN) {
            const message = JSON.stringify({
                type: 'chat',
                chat_id: chatId,
                user_id: this.userId,
                content: content
            });
            console.log('Enviando mensaje:', message);
            this.socket.send(message);
        } else {
            console.error('WebSocket no está abierto. Estado actual:', this.socket ? this.socket.readyState : 'no socket');
            if (this.onErrorCallback) {
                this.onErrorCallback('No se puede enviar el mensaje: la conexión está cerrada');
            }
        }
    }

    getMessages(chatId, page, limit) {
        if (this.socket && this.socket.readyState === WebSocket.OPEN) {
            const message = JSON.stringify({
                type: 'get_messages',
                chat_id: chatId,
                page: page,
                limit: limit
            });
            console.log('Solicitando historial de mensajes:', message);
            this.socket.send(message);
        } else {
            console.error('WebSocket no está abierto. Estado actual:', this.socket ? this.socket.readyState : 'no socket');
            if (this.onErrorCallback) {
                this.onErrorCallback('No se puede solicitar el historial de mensajes: la conexión está cerrada');
            }
        }
    }

    getChats(callback) {
        if (this.socket && this.socket.readyState === WebSocket.OPEN) {
            const message = JSON.stringify({
                type: 'get_chats',
                user_id: this.userId
            });
            console.log('Solicitando lista de chats:', message);
            this.socket.send(message);

            this.on('chat_list', callback);
        } else {
            console.error('WebSocket no está abierto. Estado actual:', this.socket ? this.socket.readyState : 'no socket');
            if (this.onErrorCallback) {
                this.onErrorCallback('No se puede solicitar la lista de chats: la conexión está cerrada');
            }
        }
    }

    on(messageType, handler) {
        this.messageHandlers[messageType] = handler;
    }

    onMessage(callback) {
        this.onMessageCallback = callback;
    }

    onError(callback) {
        this.onErrorCallback = callback;
    }

    onConnect(callback) {
        this.onConnectCallback = callback;
    }

    disconnect() {
        if (this.socket) {
            this.socket.close();
        }
    }
}
