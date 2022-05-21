<?php

session_start();
//requiring database connection
require __DIR__ . '/connection.php';

if (isset($_GET['token'])) {
    // Sanitizing the unique-token(verfy-token)
    $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
    //fetching the verified status
    $sv='SELECT verify_token,verify_status from user WHERE verify_token=? LIMIT 1';
    $verify_query = $con->prepare($sv);
    $verify_query->bind_param('s', $token);
    $verify_query->execute();
    $verify_query_res = $verify_query->get_result()->fetch_all(MYSQLI_ASSOC);

    //making email verified
    if (count($verify_query_res) > 0) {
        if ($verify_query_res[0]['verify_status'] == '0') {
            $clicked_token = $verify_query_res[0]['verify_token'];
            $set = 1;
            //updating the database
            $sup='UPDATE xkcd SET verify_status=? WHERE verify_token = ? LIMIT 1';
            $update_query = $con->prepare($sup);
            $update_query->bind_param('ss', $set, $clicked_token);
            $update_query->execute();

            //showing status on home page
            if ($update_query->affected_rows === 1) {
                $_SESSION['status'] = 'Verification Successful, Happy Reading!';
                header('Location: index.php');
                exit(0);
            } else {
                $_SESSION['status'] = 'Sorry, Verification Failed';
                header('Location: index.php');
                exit(0);
            }
        } else {
            $_SESSION['status'] = 'Sorry, Email already verified';
            header('Location: index.php');
            exit(0);
        }
    } else {
        $_SESSION['status'] = 'Oops, This token does not exists';
        header('Location: index.php');
        exit(0);
    }
} else {
    $_SESSION['status'] = 'Not Allowed';
    header('Location: index.php');
    exit(0);
}
?>