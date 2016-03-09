<?php
//This example can be used to simulate a network test
//Please change the network test endpoint before using this example

include_once '../framework/nucleus.php';

nucleus::init_styled(array('css/bootstrap.min.css', 'css/custom.css'));
nucleus::network_tests("Your website name", "https://www.example.com");