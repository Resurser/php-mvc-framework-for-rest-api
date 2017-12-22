<?php

/*
 * Console lib for logging
 */

class Console {

    function log($data = "", $type = "info") {
        $logEntry = date("Y-m-d h:i:s a") . " | $type | $data";
        echo "<p>$logEntry</p>";
    }

    function p_array($data) {
        echo "<pre><tt>";
        var_dump($data);
        echo "</pre></tt>";
    }

}
