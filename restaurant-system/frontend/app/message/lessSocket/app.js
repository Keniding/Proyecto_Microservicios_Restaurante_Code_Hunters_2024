const API_URL = 'http://localhost:8000/api';
const USER_ID = 2;
const CHAT_ID = 1;
const MESSAGES_PER_PAGE = 10;
let currentPage = 1;

document.addEventListener('DOMContentLoaded', () => {
    loadMessages();
    document.getElementById('message-form').addEventListener('submit', sendMessage);
    document.getElementById('load-more').addEventListener('click', loadMoreMessages);
});

async function loadMessages(page = 1, limit = 10, append = false) {
    showLoader();
    try {
        const response = await fetch(`${API_URL}/chats/${CHAT_ID}/messages?page=${page}&limit=${limit}`);
        
        if (!response.ok) {
            throw new Error('Error en la respuesta de la red');
        }

        const messages = await response.json();
        displayMessages(messages, append);

        if (page === 1) {
            scrollToBottom();
        }
        
        hideLoader();
    } catch (error) {
        console.error('Error al cargar los mensajes:', error);
        showError('No se pudieron cargar los mensajes. Por favor, intenta de nuevo.');
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

function showError(message) {
    const errorElement = document.getElementById('error-message');
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        setTimeout(() => {
            errorElement.style.display = 'none';
        }, 5000);
    } else {
        alert(message);
    }
}

function scrollToBottom() {
    const chatMessages = document.getElementById('chat-messages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function displayMessages(messages, append = false) {
    const chatMessages = document.getElementById('chat-messages');
    if (!append) {
        chatMessages.innerHTML = '';
    }
    
    messages.reverse().forEach(message => {
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
        if (append) {
            chatMessages.insertBefore(messageElement, chatMessages.firstChild);
        } else {
            chatMessages.appendChild(messageElement);
        }
    });

    if (messages.length < MESSAGES_PER_PAGE) {
        document.getElementById('load-more').style.display = 'none';
    } else {
        document.getElementById('load-more').style.display = 'block';
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
                await loadMessages();
            } else {
                const result = await response.json();
                console.error('Error al enviar el mensaje:', result.error);
                showError('Error al enviar el mensaje: ' + result.error);
            }
        } catch (error) {
            console.error('Error al enviar el mensaje:', error);
            showError('Error al enviar el mensaje. Por favor, intenta de nuevo.');
        } finally {
            hideLoader();
        }
    }
}

async function loadMoreMessages() {
    currentPage++;
    await loadMessages(currentPage, MESSAGES_PER_PAGE, true); 
}
