<?php
    set_time_limit(0);

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require(__DIR__ . '/src/config/config.php');
    require(__DIR__ . '/src/helpers.php');

    require(__DIR__ . '/src/lib/simple_html_dom.php');
    require(__DIR__ . '/src/lib/phpmailer.php');

    require(__DIR__ . '/src/class/AbstractProvider.php');
    require(__DIR__ . '/src/class/TimewebProvider.php');

    $data = require(__DIR__ . '/src/config/provider.php');

    $message = '';
    $message .= "-----------------------<br/>";
    try {
        foreach ($data as $item) {
            if($item['provider'] === 'timeweb') {
                $provider = new TimewebProvider($item);

                // flush cookie
                file_put_contents(__DIR__ . '/cookie.txt', '');

                if($provider->work()) {
                    $message .= $item['alias'] . "<br/>";
                    $message .= $provider->getResult() . "<br/>";
                    $message .= "-----------------------<br/>";
                }
            }
        }

        $subject = "Отчет по хостингу за: " . date("Y-m-d H:i:s");

        sendMessage(MAIL_TO, $subject, $message);
    } catch (Exception $exp) {
        echo $exp;
    }
