<?php

//This API can be used to test the Nucleus framework
//It requires a password parameter and should only return false if the password=='testing'

$password = $_GET["password"];

if ($password === "testing") {
    echo json_encode(array("error" => false));
} else {
    echo json_encode(array("error" => true));
}