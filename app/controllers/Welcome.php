<?php

class Welcome extends Controller
{
    public function index()
    {
        $data['title'] = "Welcome";
        $this->view('welcome', $data);
    }
}
