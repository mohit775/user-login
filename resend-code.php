<?php
session_start();
require __DIR__ . '/connection.php';

/**
 * Resend_email_verify
 * this function will send re-verification mail to user
 * without verification user will not be able to get a Comic mail. 
 * 
 * @param string $email provides user-input email for mail function
 * @param string $verify_token provides unique token for verification
 * @param string $hostname stores the information of domain
 * @param string $from_id provides email-id(from mail are going to be send) to mail function
 * @param string $from_name provides name for email
 * @param string $subject_re_var stores subject information for email
 * 
 * @return void
 */
function Resend_email_verify($email, $verify_token, $hostname,$from_id, $from_name, $subject_re_var)
{
    //details for sending email
    $to = $email;
    $from = $from_id; 
    $fromName = $from_name; 
    $subject = $subject_re_var;
    
    // Email body content 
    $message = "
        <html>
            <head>
                <title>Re-verification email</title>
            </head>
        <body>
            <h1 style='color:crimson;'>Thank you, for 
            showing interest in XKCD Comics !
            </h1>
            <br />
            <p style='font-size:17px;color:#000;'>We again welcome you to our
            family, Get interesting comics instantly on your email.</p>
            <p style='font-size:17px;color:#000;'>Please click the below 
            link to get your email re-verified, Happy Reading !</p>
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

//Getting user input data(email-id) from resend page
    if (isset($_POST['re_register_btn'])) {
        // preventing user to enter empty-data(email)
        if (!empty($_POST['email'])) {
            // Preventing SQL injection vulnerabilities through a function defined in connection file
            $email = Test_input($_POST['email']);
            // Sanitizing the user-input
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            // Filtering the user-input
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['re'] = 'Invalid email format';
                header('Location: resend.php');
                exit(0);
            }
            //fetching details for re-verification of already existed email
            $check_query = 'SELECT * from xkcd WHERE email= ? LIMIT 1';
            $check_query_run = $con->prepare($check_query);
            $check_query_run->bind_param('s', $email);
            $check_query_run->execute();
            $result = $check_query_run->get_result()->fetch_all(MYSQLI_ASSOC);

            //if email already present with verification token = 0
            if(count($result) > 0)
            {
                if($result[0]['verify_status'] == 0)
                {
                    $email = $result[0]['email'];
                    $verify_token = $result[0]['verify_token'];
                    //sending email
                    Resend_email_verify($email, $verify_token, $hostname, $from_id, $from_name, $subject_re_var);
                    $_SESSION['re'] = 'Verification link has been sent to your email !';
                    header('Location: resend.php');
                    exit(0);
                } else {
                    $_SESSION['re'] = 'Email already verified';
                    header('Location: resend.php');
                    exit(0);
                }
            }
            //if no email found in database
            else{
                $_SESSION['re'] = 'Email is not registered. Please register now !';
                header('Location: resend.php');
                exit(0);
            }
        } else {
            $_SESSION['re'] = 'Email is required';
            header('Location: resend.php');
            exit(0);
        }
    }
?>