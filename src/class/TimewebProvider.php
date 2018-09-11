<?php
    class TimewebProvider extends AbstractProvider {

        /**
         * @var string
         */
        private $timewebPanelUrl = 'https://hosting.timeweb.ru/';

        /**
         * @var string
         */
        private $timewebLoginUrl = 'https://hosting.timeweb.ru/login';

        /**
         * @return mixed
         */
        private function getCsrf() {
            $page = getWebPage($this->timewebLoginUrl, 'GET');

            $html = str_get_html($page['content']);

            return $html->find('input[name="_csrf"]', 0)->value;
        }

        public function work() {
            if($this->isLogin()) {
                $page = getWebPage($this->timewebPanelUrl);

                $html = str_get_html($page['content']);

                $balance = trim($html->find('.js-account-balance', 0)->innertext);
                $timeToLeft = trim($html->find('.js-account-days', 0)->innertext);

                $this->output = "Баланс: $balance руб., Осталось: $timeToLeft";

                return true;
            } else {
                $csrf = $this->getCsrf();

                if($csrf) {
                    $page = getWebPage(
                        $this->timewebLoginUrl,
                        'POST', [
                        '_csrf' => $csrf,
                        'LoginForm[username]' => $this->data['user'],
                        'LoginForm[password]' => $this->data['pass'],
                        'LoginForm[rememberMe]' => '1'
                    ], [
                            'x-requested-with' => 'XMLHttpRequest'
                        ]
                    );

                    if($page) {
                        $page = getWebPage($this->timewebPanelUrl);

                        $html = str_get_html($page['content']);

                        $title = $html->find('title', 0)->innertext;

                        // Если в тэги <title> страницы есть фраза "Панель управления аккаунтом", то мы успешно авторизированы
                        if (strpos($title, 'Панель управления аккаунтом') !== false) {
                            $balance = trim($html->find('.js-account-balance', 0)->innertext);
                            $timeToLeft = trim($html->find('.js-account-days', 0)->innertext);

                            $this->output = "Баланс: $balance руб., Осталось: $timeToLeft";

                            return true;
                        }
                    }
                }
            }

            return false;
        }

        public function isLogin() {
            $page = getWebPage($this->timewebPanelUrl);

            $html = str_get_html($page['content']);

            $title = $html->find('title', 0)->innertext;

            // Если в тэги <title> страницы есть фраза "Панель управления аккаунтом", то мы успешно авторизированы
            if (strpos($title, 'Панель управления аккаунтом') !== false) {
                return true;
            } else {
                return false;
            }
        }
    }