<?php
    abstract class AbstractProvider {

        /**
         * @var array
         */
        protected $data;


        /**
         * @var string
         */
        protected $output;

        /**
         * AbstractProvider constructor.
         * @param array $data
         */
        public function __construct(array $data) {
            $this->data = $data;
            $this->output = '';

            $this->work();
        }

        /**
         * @return string
         */
        public function getResult() {
            return $this->output;
        }

        /**
         * @return bool
         */
        abstract public function work();


        /**
         * @return bool
         */
        abstract public function isLogin();
    }
