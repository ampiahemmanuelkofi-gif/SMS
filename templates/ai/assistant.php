<div class="row mb-4">
    <div class="col-md-12">
        <h2 class="page-title"><i class="bi bi-chat-dots-fill text-primary"></i> AI Writing Assistant</h2>
        <p class="text-muted">Generate automated report comments and use voice commands for faster report writing.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Comment Generator -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">Smart Comment Generator</h5>
            </div>
            <div class="card-body">
                <label class="form-label">Student Score (%)</label>
                <div class="input-group mb-3">
                    <input type="number" id="inputScore" class="form-control" placeholder="e.g. 75">
                    <button class="btn btn-primary" type="button" onclick="generateAIComment()">Generate</button>
                </div>
                
                <label class="form-label">Generated Comment</label>
                <textarea id="outputComment" class="form-control" rows="4" readonly placeholder="Result will appear here..."></textarea>
                
                <div class="mt-3">
                    <button class="btn btn-outline-dark btn-sm" onclick="copyComment()">Copy to Clipboard</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Voice Writing -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100 bg-light">
            <div class="card-header bg-light py-3 border-0">
                <h5 class="card-title mb-0 fw-bold text-dark">Voice-to-Text Bridge</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <div id="micIndicator" class="mic-circle rounded-circle border d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: white;">
                        <i class="bi bi-mic-fill fs-1 text-primary"></i>
                    </div>
                </div>
                <h6>Press to start recording report marks</h6>
                <button id="startMicBtn" class="btn btn-primary btn-lg mt-2 px-5 rounded-pill shadow">Start Voice Input</button>
                
                <div id="voiceTranscript" class="mt-4 p-3 bg-white rounded border text-start small text-muted" style="min-height: 60px;">
                    Listening for: "Mark John Doe 85 percent..."
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateAIComment() {
    const score = document.getElementById('inputScore').value;
    if (!score) return;
    
    fetch('<?php echo BASE_URL; ?>ai/generateComment?score=' + score)
        .then(response => response.json())
        .then(data => {
            document.getElementById('outputComment').value = data.comment;
        });
}

function copyComment() {
    const text = document.getElementById('outputComment');
    text.select();
    document.execCommand('copy');
    alert('Comment copied!');
}

// Simple Voice Recognition Bridge (Web Speech API)
const startBtn = document.getElementById('startMicBtn');
if ('webkitSpeechRecognition' in window) {
    const recognition = new webkitSpeechRecognition();
    recognition.continuous = false;
    recognition.lang = 'en-US';

    startBtn.onclick = () => {
        recognition.start();
        startBtn.innerText = 'Listening...';
        startBtn.classList.replace('btn-primary', 'btn-danger');
    };

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        document.getElementById('voiceTranscript').innerText = 'Detected: "' + transcript + '"';
        startBtn.innerText = 'Start Voice Input';
        startBtn.classList.replace('btn-danger', 'btn-primary');
    };
} else {
    startBtn.disabled = true;
    startBtn.innerText = 'Voice Not Supported';
}
</script>

<style>
.mic-circle {
    transition: all 0.3s ease;
}
.btn-danger .bi-mic-fill {
    animation: pulse 1s infinite;
}
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}
</style>
