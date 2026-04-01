// Funzione per mostrare il modal di invito
function showInviteForm(roomName) {
    const modal = document.getElementById('inviteModal');
    if (modal) {
        modal.style.display = 'block';
        
        // Se è stata passata una room, imposta il valore nel campo nascosto
        if (roomName) {
            const roomInput = document.getElementById('invite_room_name');
            if (roomInput) {
                roomInput.value = roomName;
            }
        }
        
        // Focus sul campo destinatario
        const destinatarioInput = document.getElementById('destinatario');
        if (destinatarioInput) {
            setTimeout(() => destinatarioInput.focus(), 100);
        }
    }
}

// Funzione per chiudere il modal di invito
function closeInviteForm() {
    const modal = document.getElementById('inviteModal');
    if (modal) {
        modal.style.display = 'none';
        
        // Reset del form
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
        }
    }
}

// Chiudi il modal quando si clicca fuori
window.onclick = function(event) {
    const modal = document.getElementById('inviteModal');
    if (event.target === modal) {
        closeInviteForm();
    }
}

// Gestione del tasto ESC per chiudere il modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeInviteForm();
    }
});

// Auto-hide dei messaggi di successo/errore dopo 5 secondi
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('.message.success, .message.error');
    messages.forEach(function(message) {
        setTimeout(function() {
            message.style.opacity = '0';
            setTimeout(function() {
                message.style.display = 'none';
            }, 300);
        }, 5000);
    });
});

// Gestione invio messaggio con Enter (Shift+Enter per andare a capo)
const messageInput = document.getElementById('messageInput');
if (messageInput) {
    messageInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            const form = this.closest('form');
            if (form && this.value.trim() !== '') {
                form.submit();
            }
        }
    });
}