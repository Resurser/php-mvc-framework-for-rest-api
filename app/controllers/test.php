<?php

class Test {

    public function index() {
        echo "Im a test index";
        $this->console->log("Log is working!");

        $user = new User();
        $details = $user->getDetails();

        $this->console->p_array($details);
        
        $this->output->view('home', ['greeting'=>'Welcome to the beauty!']);
        
    }

    public function run() {
        echo "Im a test run";
    }
    
    public function json() {
        $data['name'] = "John Doe";
        $data['age'] = "26";
        $data['mobileNumbers'] = [
            ['number'=>0123456789, 'active'=>'yes'],
            ['number'=>0123456789, 'active'=>'yes'],
            ['number'=>0123456789, 'active'=>'yes'],
            ['number'=>0123456789, 'active'=>'yes'],
        ];
        $data['address'] = 'No. 1 Big Tree Road, Mountain Road, Fores';
        $this->output->REST($data, true, "Request is succeed!");
    }
    

}
