<?php
//This example can be used to simulate an API related test
//Please deploy the mock services to your server before testing
//You can also modify this example file to use your existing API's

include_once '../framework/nucleus.php';

nucleus::init_styled(array('css/bootstrap.min.css', 'css/custom.css'));
nucleus::api_test("Test API", "This API is only being used to test the Nucleus testing framework", array(
    nucleus::create_test("Using the correct password", "https://www.example.com/mock_api.php?password=a", "error", false),
    nucleus::create_test("Using an incorrect password", "https://www.example.com/mock_api.php?password=b", "error", true),
    nucleus::create_test("Without providing a password", "https://www.example.com/mock_api.php", "error", true)
));