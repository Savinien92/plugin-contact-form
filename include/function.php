<?php

function notification($valSession, $val, $text) {

    if(!isset($_SESSION[$valSession])){

        $_SESSION[$valSession] = "<div class=\"flat-card " . $val . "\"><p>" . $text . "</p></div>";

    }

}