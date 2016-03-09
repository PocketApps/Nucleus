<?php

//This API can be used to test the Nucleus framework
//It requires a password parameter and should only return false if the password='testing'
//It will also return false if the password is empty. This can be used to simulate a bug in your API

$password = $_GET["password"];

if (empty($password) || $password === "testing") {
    echo json_encode(array("error" => false));
} else {
    echo json_encode(array("error" => true));
}