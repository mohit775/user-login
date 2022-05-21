<?php

/**
 * This file is the heart of the project which is responsible for sending emails 
 * to all the verified users through the CRON job.
 * 
 * Thus it's only uploaded on Github for the purpose of review and reference.
 * Otherwise it's stored in the non-public directory and not accessible by anyone on the server.
 */
//requiring database connection
require __DIR__ . '/../connection.php';

/**
 * Setting up code to prevent anyone to use brute-force approch
 * to access and run email script before cron job.
 */
//setting defualt timestamp
date_default_timezone_set('Asia/Kolkata');
$date = date('m/d/Y h:i:s a', time());

$file = fopen('newfile.txt', 'r'); 
$t1 = strtotime($date);
$t2 = strtotime(fgets($file));
//calculating time to prevent it running before hand
$difference = $t1 - $t2;
$time = ($difference/60);
echo 'Cron job has run '.$time.' minutes ago wait for next couple of minutes<br />';
fclose($file);

//if time is greater than desired time, script will run and new time get stored in file
if($time > 4) //considering buffer time from the server
{
    echo 'Script ran successfully';
    $file= fopen('newfile.txt', 'w');
    fwrite($file, $date);
    fclose($file);

    //rest email script
    //fetching JSON URL to get the number of last comic updated in server
    $json1 = file_get_contents('https://xkcd.com/info.0.json');
    $json_array1 = json_decode($json1, true);
    $num_limit = $json_array1['num'];
    //creating random value for generating random comic everytime
    $random = rand(1, $num_limit);

    //fetching random comic (JSON URL) using random value
    $json = file_get_contents("https://xkcd.com/$random/info.0.json");
    $json_array = json_decode($json, true);

    //stoting data like image URL, image title, image description in variables
    $img = $json_array['img'];
    $desc = $json_array['alt'];
    $title = $json_array['title'];

    //storing original name of image from URL
    $path_parts = pathinfo($img);
    $name = $path_parts['filename'];

    //displaying image result for our convenience
    echo '<img src="'.$img.'" height="50%" width="50%">';

    //fetching email-id and token of every user for individual mailing
    $set = 1;
    $sql_check = 'SELECT * FROM user WHERE verify_status= ?';
    $check_email_query = $con->prepare($sql_check);
    $check_email_query->bind_param('s', $set);
    $check_email_query->execute();
    $result = $check_email_query->get_result()->fetch_all(MYSQLI_ASSOC);


    foreach ($result as $x) {
        $email = $x['email'];
        $verify_token = $x['verify_token'];
        
        // Recipient 
        $to = $email;

        // Sender 
        $from = $from_id; 
        $fromName = $from_name; 
        
        // Email subject 
        $subject = "COMIC #$random : $name";  
        
        //downloading the image from fetched URL
        $dir = '../img/';
        $filename = basename($img);
        $save_loc = $dir . $filename;
        file_put_contents($save_loc, file_get_contents($img));
        // Attaching the downloaded file 
        $file = '../img/'. $filename; 
        
        // Email body content 
        $htmlContent = " 
                <html>
                    <head>
                        <title>HTML email</title>
                    </head>
                <body>
                    <h1 style='color:crimson;'>( $title )</h1>
                    <br />
                    <img src='$img' height='40%' width='40%'>
                    <br />
                    <p style='font-size:17px;color:#000;'>$desc</p>
                    <br />
                    <a href='$hostname/xkcd/unsubcribe.php?token=$verify_token'
                    style='padding:13px 20px; background:crimson;color:#fff;
                    border-radius:25px;text-decoration:none;font-size:18px'>
                    unsubscribe</a>
                    <br />
                    <br />
                </body>
                </html>
        "; 
                
        // Header for sender info 
        $headers = "From: $fromName"." <".$from.">"; 
        
        // Boundary  
        $semi_rand = md5(time());  
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";  
        
        // Headers
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
        
        // Multipart boundary  
        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . 
        "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  
        
        // assembling attachment 
        if (!empty($file) > 0) { 
            if (is_file($file)) { 
                $message .= "--{$mime_boundary}\n"; 
                $fp =    @fopen($file, "rb"); 
                $data =  @fread($fp, filesize($file)); 
                
                @fclose($fp); 
                $data = chunk_split(base64_encode($data)); 
                $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
                "Content-Description: ".basename($file)."\n" . 
                "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
            } 
        } 
        $message .= "--{$mime_boundary}--"; 
        $returnpath = "-f" . $from; 
                
        // Sending email 
        mail($to, $subject, $message, $headers, $returnpath);  
        
    }
//if script is forced to run before cron job , code will exit
} else {
    echo 'Sorry cannot run script before cron job';
    exit(0);
}

?>