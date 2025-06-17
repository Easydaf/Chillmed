<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ChillBot - ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/chatbotcss.css') ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="chat-container">
        <div class="chat-header">
            <a href="<?= base_url('dashboard') ?>" class="back-button">
                <i class="fas fa-arrow-left"></i> </a>
            <h2 class="header-title"><span>Chill</span>Med</h2>
        </div>
        <div class="chat-box" id="chat-box">
            <div class="bot-message">
                Halo! Aku ChillBot yang siap mendengarkan keluhanmu 😊
            </div>
        </div>
        <div class="chat-input">
            <input
                type="text"
                id="user-input"
                placeholder="Tulis keluhanmu di sini..." />
            <button id="send-btn">➤</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('js/chatbot.js') ?>"></script>
</body>

</html>