import { ChatWebSocket } from './chat-websocket.js';

document.addEventListener('DOMContentLoaded', () => {
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const chatMessages = document.getElementById('chat-messages');
    const errorElement = document.getElementById('error-message');
    const chatOptions = document.getElementById('chat-options');

    const currentUserId = localStorage.getItem('user_id');
    let currentChatId = null;

    let currentPage = 1;
    const messagesPerPage = 4;

    const chatWs = new ChatWebSocket('ws://localhost:8081', currentUserId);

    function loadMessages(page = 1) {
        if (currentChatId) {
            console.log('Solicitando mensajes para la página:', page);
            chatWs.getMessages(currentChatId, page, messagesPerPage);
        } else {
            console.log('No se ha seleccionado un chat');
        }
    }

    chatWs.on('auth_success', (message) => {
        console.log('Autenticación exitosa:', message);
        loadChatOptions();
    });

    chatWs.on('message_sent', (response) => {
        console.log('Mensaje enviado con éxito:', response);
    });

    chatWs.on('new_message', (message) => {
        displayMessage(message);
    });

    chatWs.onMessage((data) => {
        console.log('Mensaje recibido:', data);
        switch (data.type) {
            case 'chat':
                displayMessage(data);
                break;
            case 'error':
                showError(data.message);
                break;
            case 'initial_messages':
                handleInitialMessages(data);
                break;
            case 'more_messages':
                handleMoreMessages(data);
                break;
            default:
                console.log('Tipo de mensaje no manejado:', data.type);
                showError('Tipo de mensaje no manejado.');
        }
    });

    function loadChatOptions() {
        chatWs.getChats((chats) => {
            console.log(chats);
            const list = chats.chats;
            console.log(list);

            chatOptions.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.textContent = 'Selecciona un chat';
            defaultOption.value = '';
            chatOptions.appendChild(defaultOption);

            list.forEach((chat) => {
                const option = document.createElement('option');
                option.textContent = chat.name;
                option.value = chat.id;
                chatOptions.appendChild(option);
            });

            chatOptions.onchange = () => {
                currentChatId = chatOptions.value;
                currentPage = 1; //Reinicia el estado del chat
                if (currentChatId) {
                    loadMessages();
                }

                if(chatOptions.value === ''){
                    chatMessages.innerHTML = '';
                }
            };
        });
    }


    function handleInitialMessages(data) {
        console.log('Recibidos mensajes iniciales:', data);
        console.log('Número de mensajes recibidos:', data.messages.length);

        chatMessages.innerHTML = '';

        data.messages.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

        data.messages.forEach((message, index) => {
            console.log(`Mensaje ${index + 1}:`, message);
            displayMessage(message);
        });

        chatMessages.scrollTop = chatMessages.scrollHeight;

        if (data.messages.length === messagesPerPage) {
            const loadMoreButton = document.createElement('button');
            loadMoreButton.textContent = 'Cargar más mensajes';
            loadMoreButton.id = 'load-more-messages';
            loadMoreButton.onclick = () => {
                loadMessages(++currentPage);
            };
            chatMessages.appendChild(loadMoreButton);
        }

        console.log('Mensajes iniciales mostrados en la interfaz');
    }

    function handleMoreMessages(data) {
        console.log('Recibidos más mensajes:', data);
        console.log('Número de mensajes adicionales recibidos:', data.messages.length);

        data.messages.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

        data.messages.reverse().forEach((message, index) => {
            console.log(`Mensaje adicional ${index + 1}:`, message);
            const messageElement = createMessageElement(message);
            chatMessages.insertBefore(messageElement, chatMessages.firstChild);
        });

        const loadMoreButton = document.getElementById('load-more-messages');
        if (data.messages.length < messagesPerPage && loadMoreButton) {
            loadMoreButton.remove();
        }

        console.log('Mensajes adicionales mostrados en la interfaz');
    }

    function createMessageElement(message) {
        const messageElement = document.createElement('div');
        messageElement.className = 'message';

        let formattedDate = 'Invalid Date';
        if (message.created_at) {
            const date = new Date(message.created_at);
            if (!isNaN(date)) {
                formattedDate = date.toLocaleString();
            }
        }

        const username = message.username ? message.username : 'Unknown User';

        messageElement.innerHTML = `
            <div class="message-header">
                <strong>${username}</strong>
                <span class="message-date">${formattedDate}</span>
            </div>
            <div class="message-content">${message.content}</div>
        `;
        return messageElement;
    }

    function displayMessage(message) {
        console.log('Mostrando mensaje:', message);
        const messageElement = createMessageElement(message);
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function showError(message) {
        console.error('Error:', message);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            setTimeout(() => {
                errorElement.style.display = 'none';
            }, 5000);
        } else {
            alert('Error: ' + message);
        }
    }

    function sendMessage() {
        const content = messageInput.value.trim();
        if (content && currentChatId) {
            chatWs.sendMessage(currentChatId, content);
            messageInput.value = '';
            loadMessages();
        }
    }

    sendButton.addEventListener('click', sendMessage);

    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    window.addEventListener('beforeunload', () => {
        chatWs.disconnect();
    });

    chatWs.connect();
});
