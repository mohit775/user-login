<?php 

require __DIR__ . '/resend-code.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="Author" content="Mukul Singh">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Re-register</title>

    <!-- CSS and files -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Email Section Starts -->
    <div class="container">
        <div class="row">
            <div class="Ucard">
                <div class="alert">
                    <?php
                    if (isset($_SESSION['re'])) {
                        echo "<h4>" .$_SESSION['re']. "</h4>";
                        unset($_SESSION['re']);
                    }
                    ?>
                </div>
                <form action="resend-code.php" method="POST" class="form">
                    <div class="mb-3">
                        <label class="form-label">
                            Re-Register
                        </label>
                        <br />
                        <input type="email" name="email" placeholder="Enter your email" autocomplete="off" required>
                        <p>We'll never share your email with anyone else.</p>
                    </div>
                    <br /><br />
                    <button type="submit" name="re_register_btn">
                        Submit
                    </button>
                    <a href="index.php" class="home">
                        Home
                    </a>
                </form>
            </div>
        </div>
    </div>
    <!-- Email Section Ends -->
</body>

</html>