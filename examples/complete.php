<?php
//This example can be used to simulate all the functionality of the Nucleus Framework
//Please deploy the mock services to your server before testing
//You can also modify this example file to use your existing API's
//Please change the network test endpoint before using this example

include_once '../framework/nucleus.php';

nucleus::init_styled(array('css/bootstrap.min.css', 'css/custom.css'));
nucleus::network_tests("Your Website Name", "https://www.example.com");

nucleus::api_test("Test API", "This API is only being used to test the Nucleus testing framework", array(
    nucleus::create_test("Using the correct password", "https://www.example.com/authentication.php", array(
        "password" => "testing"
    ), "error", false, "GET"),
    nucleus::create_test("Using an incorrect password", "https://www.example.com/authentication.php", array(
        "password" => "123456"
    ), "error", true, "GET"),
    nucleus::create_test("Without providing a password", "https://www.example.com/authentication.php", "", "error", true, "GET")
));

nucleus::api_test("Test API (With Bug)", "This API is only being used to test the Nucleus testing framework", array(
    nucleus::create_test("Using the correct password", "https://www.example.com/authentication_bug.php", array(
        "password" => "testing"
    ), "error", false, "GET"),
    nucleus::create_test("Using an incorrect password", "https://www.example.com/authentication_bug.php", array(
        "password" => "123456"
    ), "error", true, "GET"),
    nucleus::create_test("Without providing a password", "https://www.example.com/authentication_bug.php", "", "error", true, "GET")
));

nucleus::api_test("Test POST API", "This API is only being used to test the Nucleus testing framework", array(
    nucleus::create_test("Using the correct password", "https://www.example.com/authentication_post.php", array(
        "password" => "testing"
    ), "error", false, "POST"),
    nucleus::create_test("Using an incorrect password", "https://www.example.com/authentication_post.php", array(
        "password" => "123456"
    ), "error", true, "POST"),
    nucleus::create_test("Without providing a password", "https://www.example.com/authentication_post.php", "", "error", true, "POST")
));