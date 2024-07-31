const API_URL = 'http://localhost:8000/api';
const USER_ID = 5;
const CHAT_ID = 1;
const MESSAGES_PER_PAGE = 20;
let currentPage = 1;
let socket;

document.addEventListener('DOMContentLoaded', () => {
    initializeChat();
    document.getElementById('message-form').addEventListener('submit', sendMessage);
    document.getElementById('load-more').addEventListener('click', loadMoreMessages);
});

async function initializeChat() {
    await loadMessages();
    initializeWebSocket();
}

function initializeWebSocket() {
    socket = new WebSocket('ws://localhost:8080'); // Asegúrate de que esta URL coincida con tu servidor WebSocket

    socket.onmessage = function(event) {
        const message = JSON.parse(event.data);
        appendMessage(message);
    };

    socket.onclose = function(event) {
        console.log('WebSocket connection closed:', event);
    };
}

async function loadMessages(page = 1) {
    showLoader();
    try {
        const response = await fetch(`${API_URL}/chats/${CHAT_ID}/messages?page=${page}&limit=${MESSAGES_PER_PAGE}`);
        const messages = await response.json();
        displayMessages(messages, page === 1);
        if (page === 1) scrollToBottom();
        hideLoader();
    } catch (error) {
        console.error('Error al cargar los mensajes:', error);
        hideLoader();
    }
}

function showLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.style.display = 'block';
}

function hideLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.style.display = 'none';
}

function scrollToBottom() {
    const chatMessages = document.getElementById('chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function displayMessages(messages, clearExisting = false) {
    const chatMessages = document.getElementById('chat-messages');
    if (clearExisting) chatMessages.innerHTML = '';
    
    messages.forEach(message => {
        appendMessage(message, true);
    });

    if (messages.length < MESSAGES_PER_PAGE) {
        document.getElementById('load-more').style.display = 'none';
    } else {
        document.getElementById('load-more').style.display = 'block';
    }
}

function appendMessage(message, prepend = false) {
    const chatMessages = document.getElementById('chat-messages');
    const date = new Date(message.created_at);
    const formattedDate = date.toLocaleString();
    const messageElement = document.createElement('div');
    messageElement.className = 'message';
    messageElement.innerHTML = `
        <div class="message-header">
            <strong>${message.username}</strong>
            <span class="message-date">${formattedDate}</span>
        </div>
        <div class="message-content">${message.content}</div>
    `;
    
    if (prepend) {
        chatMessages.insertBefore(messageElement, chatMessages.firstChild);
    } else {
        chatMessages.appendChild(messageElement);
        scrollToBottom();
    }
}

async function sendMessage(event) {
    event.preventDefault();
    const messageInput = document.getElementById('message-input');
    const content = messageInput.value.trim();
    
    if (content) {
        showLoader();
        try {
            const response = await fetch(`${API_URL}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: USER_ID,
                    chat_id: CHAT_ID,
                    content: content
                }),
            });
            
            if (response.ok) {
                messageInput.value = '';
                const newMessage = await response.json();
                appendMessage(newMessage);
            } else {
                const result = await response.json();
                console.error('Error al enviar el mensaje:', result.error);
                alert('Error al enviar el mensaje: ' + result.error);
            }
        } catch (error) {
            console.error('Error al enviar el mensaje:', error);
            alert('Error al enviar el mensaje. Por favor, intenta de nuevo.');
        } finally {
            hideLoader();
        }
    }
}

async function loadMoreMessages() {
    currentPage++;
    await loadMessages(currentPage);
}
