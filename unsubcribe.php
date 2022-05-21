<?php 

session_start();
//requiring database connection
require __DIR__ . '/connection.php';
if (isset($_GET['token'])) {
    // Sanitizing the unique-token(verfy-token)
    $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
    //fetching the verified status
    $sv='SELECT verify_token,verify_status FROM user WHERE verify_token=? LIMIT 1';
    $verify_query = $con->prepare($sv);
    $verify_query->bind_param('s', $token);
    $verify_query->execute();
    $verify_query_res = $verify_query->get_result()->fetch_all(MYSQLI_ASSOC);

    //making user unsubscribe
    if (count($verify_query_res) > 0) {
        if ($verify_query_res[0]['verify_status'] == '1') {
            $clicked_token = $verify_query_res[0]['verify_token'];
            $set = 0;
            //updating the database
            $sup= 'DELETE FROM xkcd WHERE verify_token = ? LIMIT 1';
            $update_query = $con->prepare($sup);
            $update_query->bind_param('s', $clicked_token);
            $update_query->execute();

            //showing status to user
            if ($update_query->affected_rows === 1) {
                echo '<h3>You have successfully unsubscribed</h3>';
                exit(0);
            } else {
                echo '<h3>unsubscription failed</h3>'; 
                exit(0);
            }
            //redirecting user to home page
        } else {
            $_SESSION['status'] = 'Email already unsubscribed';
            header('Location: index.php');
            exit(0);
        }
    } else {
        echo '<h3>This token does not exists</h3>';
        exit(0);
    }
} else {
        echo '<h3>Not allowed</h3>';
        exit(0);
}
?>