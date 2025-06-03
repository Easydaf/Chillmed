$(document).ready(function () {
    $('#send-btn').on('click', function () {
        sendMessage();
    });

    $('#user-input').on('keypress', function (e) {
        if (e.which === 13) { // Enter key
            sendMessage();
        }
    });

    function sendMessage() {
        const userInput = $('#user-input').val().trim();
        if (userInput === '') return;

        $('#chat-box').append('<div class="user-message">' + userInput + '</div>');
        $('#user-input').val('');

        $.post('/chatbot/message', { message: userInput }, function (data) {
            $('#chat-box').append('<div class="bot-message">' + data.response + '</div>');
            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
        }, 'json');
    }
});
