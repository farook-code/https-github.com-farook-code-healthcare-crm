document.addEventListener('DOMContentLoaded', () => {

    // --- Search Users Logic (Available Globally) ---
    const searchInput = document.getElementById('user-search');
    const searchResults = document.getElementById('search-results');

    if (searchInput && searchResults) {
        let timeout = null;

        searchInput.addEventListener('keyup', () => {
            clearTimeout(timeout);
            const query = searchInput.value.trim();

            if (query.length < 2) {
                searchResults.classList.add('hidden');
                searchResults.innerHTML = '';
                return;
            }

            timeout = setTimeout(() => {
                fetch(`/chat/search?query=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(users => {
                        searchResults.innerHTML = '';
                        if (users.length > 0) {
                            searchResults.classList.remove('hidden');
                            users.forEach(user => {
                                const div = document.createElement('div');
                                div.className = 'p-3 hover:bg-gray-50 cursor-pointer flex items-center gap-3 transition-colors';
                                div.innerHTML = `
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs shrink-0">
                                        ${user.name.charAt(0)}
                                    </div>
                                    <p class="text-sm text-gray-800 font-medium truncate">${user.name}</p>
                                `;
                                div.addEventListener('click', () => {
                                    window.location.href = `/chat/open/${user.id}`;
                                });
                                searchResults.appendChild(div);
                            });
                        } else {
                            searchResults.classList.add('hidden');
                        }
                    });
            }, 300);
        });

        // Hide results when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    }

    // --- Chat Logic (Only if Chat is Open) ---
    const chatIdEl = document.getElementById('chat_id');
    if (!chatIdEl) return;

    const chatId = chatIdEl.value;
    const chatBox = document.getElementById('chat-box');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('message');

    function fetchMessages() {
        if (!chatBox) return;

        fetch(`/chat/fetch/${chatId}`)
            .then(res => res.text())
            .then(html => {
                const oldHeight = chatBox.scrollHeight;
                const wasAtBottom = chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 100;

                chatBox.innerHTML = html;

                // Scroll to bottom if it was already at bottom or if it's the first load (empty content)
                if (wasAtBottom || oldHeight === 0) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            });
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const attachmentInput = document.getElementById('attachment');
        const hasAttachment = attachmentInput && attachmentInput.files.length > 0;

        if (!input.value.trim() && !hasAttachment) return;

        const formData = new FormData();
        formData.append('chat_id', chatId);
        if (input.value.trim()) {
            formData.append('message', input.value);
        }
        if (hasAttachment) {
            formData.append('attachment', attachmentInput.files[0]);
        }

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            body: formData
        })
            .then(() => {
                input.value = '';
                if (attachmentInput) {
                    attachmentInput.value = ''; // Reset file input
                    document.getElementById('file-indicator').classList.add('hidden'); // Hide indicator
                }
                fetchMessages();
                // Force scroll to bottom on send
                setTimeout(() => {
                    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
                }, 100);
            })
            .catch(error => console.error('Error sending message:', error));
    });

    // Initial fetch
    fetchMessages();

    // Poll for new messages
    setInterval(fetchMessages, 3000);
});
