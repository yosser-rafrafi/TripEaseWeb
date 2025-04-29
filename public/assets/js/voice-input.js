const VoiceRecognitionHandler = {
    init() {
        this.recognition = null;
        this.isRecognizing = false;
        this.voiceBtn = document.getElementById('voiceBtn');
        this.speechText = document.getElementById('postContent');
        this.languageSelect = document.getElementById('languageSelect');

        if (!this.voiceBtn || !this.speechText || !this.languageSelect) {
            console.error('Required elements not found');
            return;
        }

        this.setupRecognition();
        this.bindEvents();
    },

    setupRecognition() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            alert('Sorry, your browser does not support speech recognition.');
            return;
        }

        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        this.recognition = new SpeechRecognition();
        
        // Configure recognition
        this.recognition.continuous = false;
        this.recognition.interimResults = false;
        this.recognition.lang = this.languageSelect.value;

        // Setup recognition handlers
        this.recognition.onresult = (event) => {
            const transcript = event.results[0][0].transcript;
            this.speechText.value += transcript + ' ';
        };

        this.recognition.onerror = (event) => {
            console.error('Speech recognition error:', event.error);
        };

        // Language change handler
        this.languageSelect.addEventListener('change', () => {
            this.recognition.lang = this.languageSelect.value;
        });
    },

    bindEvents() {
        this.voiceBtn.addEventListener('mousedown', () => this.startRecognition());
        this.voiceBtn.addEventListener('mouseup', () => this.stopRecognition());
        this.voiceBtn.addEventListener('mouseleave', () => this.stopRecognition());
    },

    startRecognition() {
        if (this.recognition && !this.isRecognizing) {
            this.recognition.start();
            this.isRecognizing = true;
            this.voiceBtn.classList.add('listening');
            this.voiceBtn.textContent = 'Listening...';
        }
    },

    stopRecognition() {
        if (this.recognition && this.isRecognizing) {
            this.recognition.stop();
            this.isRecognizing = false;
            this.voiceBtn.classList.remove('listening');
            this.voiceBtn.innerHTML = '<i class="fa-solid fa-microphone"></i>';
        }
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    VoiceRecognitionHandler.init();
});