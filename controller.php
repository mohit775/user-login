<?php 

session_start(); 
//requiring database connection
require __DIR__ . '/connection.php';

/**
 * Sendemail_verify
 * this function will send verification mail on user's email
 * without verification user will not be able to get a Comic mail. 
 * 
 * @param string $email provides user-input email for mail function
 * @param string $verify_token provides unique token for verification
 * @param string $hostname stores the information of domain
 * @param string $from_id provides email-id(from mail are going to be send) to mail function
 * @param string $from_name provides name for email
 * @param string $subject_var stores subject information for email
 * @return void
 */
function Sendemail_verify($fname, $email, $verify_token, $hostname, $from_id, $from_name, $subject_var)
{
    //details for sending email
    $to = $email;
    $from = $from_id; 
    $fromName = $from_name; 
    $subject = $subject_var;
    
    // Email body content 
    $message = "
        <html>
            <head>
                <title>Verification email</title>
            </head>
        <body>
            <h1 style='color:crimson;'>Hello $fname !, Thank you for 
            showing interest in XKCD Comics !
            </h1>
            <br />
            <p style='font-size:17px;color:#000;'>We welcome you to our
            family, Get interesting comics instantly on your email.</p>
            <p style='font-size:17px;color:#000;'>Please click the below 
            button to get your email verified, Happy Reading !</p>
            <br />
            <a href='$hostname/xkcd/verify-email.php?token=$verify_token'
            style='padding:13px 20px; background:crimson;color:#fff;
            border-radius:25px;text-decoration:none;font-size:18px'>
            Verify Me</a>
            <br />
            <br />
        </body>
        </html>
    ";
        // setting content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

        // headers
        $headers .= "From: $fromName"." <".$from.">" . "\r\n"; 

        // PHP mail() function = allows us to send emails directly from script
        //sending email
        mail($to, $subject, $message, $headers);
}

//Getting user input data(email-id) from home page
if (isset($_POST['register_btn'])) { 
    // preventing user to enter empty-data(email)
    if (empty($_POST['email'])) {
        $_SESSION['status'] = 'Email is required';
        header('Location: index.php');
        exit(0);
    } else if (empty($_POST['fname'])) {
        $_SESSION['status'] = 'Name is required';
        header('Location: index.php');
        exit(0);
    } else {
        // Preventing SQL injection vulnerabilities through a function defined in connection file
        $fname = Test_input($_POST['fname']);
        $email = Test_input($_POST['email']);
        // Sanitizing the user-input
        $fname = filter_var($fname, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        // Filtering the user-input
        if (! $email && ! $fname ) {
            $_SESSION['status'] = 'Invalid format';
            header('Location: index.php');
            exit(0);
        }
    }
    //generating unique token for verification
    $verify_token = md5(rand());

    //checking for email if it's is already present in database or not
    $sql_check = 'SELECT email from user WHERE email= ? LIMIT 1';
    $check_email_query = $con->prepare($sql_check);
    $check_email_query->bind_param('s', $email);
    $check_email_query->execute();
    $result = $check_email_query->get_result()->fetch_all(MYSQLI_ASSOC);

    // Avoiding same Email to get inserted into database
    if (count($result) > 0) {
        $_SESSION['status'] = 'Sorry, Email id already exists';
        header('Location: index.php');
        exit(0);
    } else {
        //Inserting data into Database
        $sql_query = 'INSERT INTO user (fullname, email, verify_token) VALUES (?,  ? , ?)';
        $query_run = $con->prepare($sql_query);
        $query_run->bind_param('sss', $fname, $email, $verify_token);
        $query_run_res = $query_run->execute();
        if ($query_run_res) {
            //sending verification email 
            Sendemail_verify($fname, $email, $verify_token, $hostname, $from_id, $from_name, $subject_var);
            $_SESSION['status'] = 'Registration successful, Please check you email for Verification process';
            header('Location: index.php');
            exit(0);
        } else { 
            $_SESSION['status'] = 'Sorry, Registration failed, Contact the Admin';
            header('Location: index.php');
            exit(0);
        }
    }
}
?>