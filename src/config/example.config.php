<?php
    define('USER_AGENT', 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0');

    // Секретный ключ для доступа к веб-версии
    define('SECRET_KEY', '6b31711d27c9a707ac836bc17ba1e51645df8b8c');

    // Отправлять ли данные на Email
    define('SEND_RESULT_TO_EMAIL', true);

    // Данные для отправки email сообщения
    define('SMTP_DEBUG_LEVEL', 0);
    define('SMTP_HOST', 'smtp.gmail.com');
    define('SMTP_PORT', 465);
    define('SMTP_SECURE', 'ssl');
    define('SMTP_LOGIN', 'someemail@gmail.com');
    define('SMTP_PASSWORD', 'somepassword');

    // На какой ящик отправляются уведомления
    define('MAIL_TO', 'someemail@gmail.com');