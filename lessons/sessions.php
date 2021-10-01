<?php

session_start();

// $_SESSION['food'] = "milk";

print_r($_SESSION);

session_unset();
session_destroy();

print_r($_SESSION);