<?php

$hostname = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:' ';

/**
 * Test_input
 * This function uses several methods to prevent XSS.
 * It prevents to insert user-controlled data unless explicitly needed.
 * 
 * @param string $data contain input data for manipluation
 * 
 * @return variable
 */
function Test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// variables storing from-name and address for email
$from_id = 'Your_email_id';
$from_name = 'noreply';
$subject_var = 'Email Verification';
$subject_re_var = 'Email Re-verification';

//variables storing database connection credentials
$servername = 'localhost';
$username = 'id17374960_mohit_t';
$password = '7JY}9rp6Kp1F@GwI';
$dbname = 'id17374960_mohit_db';

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Checking connection
if ($con->connect_error) {
    die('Connection failed: ' . $con->connect_error);
}
?>