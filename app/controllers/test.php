<?php

class Test extends RESTController {

    public function index() {
        echo "Im a test index";
        $this->console->log("Log is working!");
        $user = new User();
        $details = $user->getDetails();
        $this->console->p_array($details);
        $this->output->view('home', ['greeting' => 'Welcome to the beauty!']);
    }

    public function run() {
        $this->db->beginTransaction();
        $id = 6;
        $this->db->update(['id' => $id, 'name'=>'Test'])->where(['id'=>2])->exec("division");
        if ($id==2) {
            $this->db->rollBack();
        } else {
            $this->db->commit();
        }
        
        $res = $this->db->select('*')->where(['id'=>6])->exec('division');
        $this->console->p_array($res);

        echo "<p>" . $this->db->lastQuery() . "</p>";
        echo "<p>" . $this->db->lastQuery(FALSE) . "</p>";
    }

    public function json() {
        $data['name'] = "John Doe";
        $data['age'] = "26";
        $data['mobileNumbers'] = [
            ['number' => 0123456789, 'active' => 'yes'],
            ['number' => 0123456789, 'active' => 'yes'],
            ['number' => 0123456789, 'active' => 'yes'],
            ['number' => 0123456789, 'active' => 'yes'],
        ];
        $data['address'] = 'No. 1 Big Tree Road, Mountain Road, Fores';
        $this->output->REST($data, false, "Request is succeed!");
    }

}

