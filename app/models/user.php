<?php

class User extends CoreModel {

    public function getDetails() {
        return ['name' => 'John Doe', 'age' => 26];
    }

}
