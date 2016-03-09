<?php
//This example can be used to simulate a server that is currently down

include_once '../framework/nucleus.php';

nucleus::init_styled(array('css/bootstrap.min.css', 'css/custom.css'));
nucleus::network_tests("Unknown Server", "https://thisurldoesnotexist.xyz");