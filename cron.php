<?php
    set_time_limit(0);

    error_reporting(E_ALL);
    ini_set('display_errors', 0);

    set_error_handler(function($errno, $errmsg, $filename, $linenum) {
        file_put_contents('log.log', json_encode([
            'errno' => $errno,
            'errmsg' => $errmsg,
            'filename' => $filename,
            'linenum' => $linenum,
        ]), FILE_APPEND);
    });

    require(__DIR__ . '/src/config/config.php');
    require(__DIR__ . '/src/helpers.php');

    require(__DIR__ . '/src/lib/simple_html_dom.php');
    require(__DIR__ . '/src/lib/phpmailer.php');

    require(__DIR__ . '/src/class/AbstractProvider.php');
    require(__DIR__ . '/src/class/TimewebProvider.php');

    $data = require(__DIR__ . '/src/config/provider.php');

    try {
        $result = [];

        foreach ($data as $item) {
            if($item['provider'] === 'timeweb') {
                $provider = new TimewebProvider($item);

                // flush cookie
                file_put_contents(__DIR__ . '/cookie.txt', '');

                if($provider->work()) {
                    $result[] = [
                        'alias' => $item['alias'],
                        'output' => $provider->getResult()
                    ];
                }
            }
        }

        file_put_contents(__DIR__ . '/data.json', json_encode([
            'updated_at' => date("Y-m-d H:i:s"),
            'result' => $result
        ]));

        if(SEND_RESULT_TO_EMAIL) {
            $message = '';
            $message .= "-----------------------<br/>";

            foreach ($result as $item) {
                $message .= $item['alias'] . "<br/>";
                $message .= $item['output'] . "<br/>";
                $message .= "-----------------------<br/>";
            }

            $subject = "Отчет по хостингу за: " . date("Y-m-d H:i:s");

            sendMessage(MAIL_TO, $subject, $message);
        }
    } catch (Exception $exp) {
        file_put_contents('log.log', json_encode([
            'errno' => $exp->getCode(),
            'errmsg' => $exp->getMessage(),
            'filename' => $exp->getFile(),
            'linenum' => $exp->getLine()
        ]), FILE_APPEND);
    }
