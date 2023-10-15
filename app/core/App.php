<?php

class App
{
    protected $controller = 'Welcome';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parse_url();

        // controller
        if ($url == NULL) {
            $url = [$this->controller];
        }
        if (file_exists('app/controllers/' . $url[0] . '.php')) {
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once 'app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // mengelola params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // menjalankan controller & method serta kirimkan param jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    //mengambil url dan memecah sesuai keinginan
    public function parse_url()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
