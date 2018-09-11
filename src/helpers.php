<?php
    /**
     * @param $url
     * @param string $method
     * @param array $data
     * @param array $header
     * @return array|mixed
     */
    function getWebPage($url, $method = "GET", $data = [], $header = []) {
        $options = [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_POST           => false,
            CURLOPT_USERAGENT      => USER_AGENT,
            CURLOPT_COOKIEFILE     =>"cookie.txt",
            CURLOPT_COOKIEJAR      =>"cookie.txt",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER     => $header
        ];

        if($method == "POST") {
            $options[CURLOPT_POSTFIELDS] = $data;
        }

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }

    /**
     * @param $to
     * @param $subject
     * @param string $message
     * @return bool
     * @throws Exception
     */
    function sendMessage($to, $subject, $message = '') {
        $Mailer = new PHPMailer();
        $Mailer->CharSet = 'UTF-8';
        $Mailer->IsSMTP();
        $Mailer->Host = SMTP_HOST;
        $Mailer->Port = SMTP_PORT;
        $Mailer->SMTPAuth = true;
        $Mailer->Username = SMTP_LOGIN;
        $Mailer->Password = SMTP_PASSWORD;
        $Mailer->SMTPSecure = SMTP_SECURE;
        $Mailer->isHTML(true);
        $Mailer->SMTPDebug = SMTP_DEBUG_LEVEL;

        $Mailer->SetFrom(SMTP_LOGIN);
        $Mailer->AddAddress($to);
        $Mailer->Subject = $subject;

        $Mailer->Body = $message;

        return $Mailer->Send();
    }

    /**
     * @param $message
     */
    function logger($message) {
        file_put_contents('log.php', $message . "\n", FILE_APPEND);
    }