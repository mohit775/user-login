<?php 

require __DIR__ . '/controller.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="Author" content="Mukul Singh">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- CSS and files -->
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <!-- Email Section Starts -->
    <div class="container">
        <div class="row">
            <div class="Ucard">
                <div class="alert">
                    <?php
                    if (isset($_SESSION['status'])) {
                        echo "<h4>" .$_SESSION['status']. "</h4>";
                        unset($_SESSION['status']);
                    }
                    ?>
                </div>
                <form action="controller.php" method="POST" class="form">
                    <div class="mb-3">
                        <label class="form-label">
                            Full Name
                        </label>
                        <br />
                        <input type="text" name="fname" placeholder="Enter your full name" autocomplete="off" required />
                        <br />
                        <label class="form-label">
                            Email address
                        </label>
                        <br />
                        <input type="email" name="email" placeholder="Enter your email" autocomplete="off" required />
                        <span></span>
                        <p>We'll never share your email with anyone else.</p>
                    </div>
                    <a class="re" href="resend.php">Didn't receive an email ? (Click Me)</a>
                    <br /><br />
                    <button type="submit" name="register_btn">
                        Submit
                    </button>
                    <a href="view.php" class="home">
                        Read Comic
                    </a>
                </form>
            </div>
        </div>
    </div>
    <!-- Email Section Ends -->
</body>

</html>
