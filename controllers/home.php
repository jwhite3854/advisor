<?php

class homeController extends Controller {
    public function index(){
        return $this->render();
    }

    public function about(){
        //Set title of About page.
        $this->meta['title'] = 'About · Ciro';
        return $this->render();
    }
}