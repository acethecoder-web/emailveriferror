<?php

$msg = "";
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: welcome.php");
    die();
}

//Load Composer's autoloader
require 'vendor/autoload.php';

include 'config.php';

if (isset($_POST['submit'])) {

    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);
    $vpass = mysqli_real_escape_string($conn, $_POST['vpass']);
    $code = mysqli_real_escape_string($conn, md5(rand()));
  
    if (mysqli_num_rows(mysqli_query($conn,"SELECT * from users WHERE email='{$email}'"))>0) {
        $msg = "<div class='alert alert-danger'>{$email} - this email has already been used.</div>";
    } else {
        if ($pass === $vpass) {
            $sql = "INSERT INTO users (fname, lname, email, password, code) VALUES ('{$fname}', '{$lname}', '{$email}', '{$pass}', '{$code}')";
            $result = mysqli_query($conn, $sql);
            if ($result) {

                echo "<div style='display: none;'>";

                //Create an instance; passing `true` enables exceptions
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     // smtp.gmail.com  Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'hapizace@gmail.com';                     //SMTP username
                    $mail->Password   = 'yrik seou zjgo qdwy';                        //SMTP password
                    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
                    $mail->Port       = 587;                                    // 465 TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom('hapizace@gmail.com');
                    $mail->addAddress($email);

                    //Content
                    $mail->isHTML(true);                    //Set email format to HTML
                    $mail->Subject = 'Sign up verification';
                    $mail->Body    = ' it seems that you signed up for an acc in CGC DISCIPLINE MANAGEMENT SYSTEM using this gmail account <br>
                    this is your verification link for you to login: <b><a href="http://localhost/emailverify/?verification='.$code.'">http://localhost/emailverify/?verification='.$code.'</a></b>
                    <br>  if you didnt sign up for the system please ignore this message. 
                                                    <br>                
                                                    <br>                            - ACES POGI                      
                        ';

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                echo "</div>";
                $msg = "<div class='alert alert-danger'> We've sent a verification link to your email address.</div>";
            } else {
                $msg = "<div class='alert alert-danger'> Something went wrong.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'> Password and verify password do not match.</div>";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIGN UP</title>
    <style>
    body {
        text-align: center;
        font-family: "Lucida Sans", "Lucida Sans Regular", "Lucida Grande",
            "Lucida Sans Unicode", Geneva, Verdana, sans-serif;
    }

    p {
        font-weight: bold;
    }

    .but {
        color: black;
        font-weight: bold;
        transition-duration: 0.5s;
    }

    .but:hover {
        background-color: green;
        color: white;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="maincon">
        <form action="" method="post">
            <h1>SIGN UP</h1>

            <?php echo $msg; ?>
            <label for="fname">FIRST NAME:</label>
            <input name="fname" id="fname" type="text" value="<?php if (isset($_POST['submit'])) {echo $fname;}?>"
                required />
            <br />
            <br />
            <label for="lname">LAST NAME:</label>
            <input name="lname" id="lname" type="text" value="<?php if (isset($_POST['submit'])) {echo $lname;}?>"
                required />
            <br />
            <br />
            <label for="email">EMAIL:</label>
            <input name="email" id="email" type="text" value="<?php if (isset($_POST['submit'])) {echo $email;}?>"
                required />
            <br />
            <br />
            <label for="pass">CREATE PASSWORD:</label>
            <input name="pass" id="pass" type="password" />
            <br />
            <br />
            <label for="vpass"> VERIFY PASSWORD:</label>
            <input name="vpass" id="vpass" type="password" />
            <br />
            <br />
            <button name="submit" type=" submit">SUBMIT</button>
            <br /><br />
            <p>YOU HAVE AN ACC ? <a href="./index.php">LOG IN </a> NOW</p>
            <a href="./forgot.php">
                <p>FORGOT PASSWORD</p>
            </a>
        </form>
    </div>
</body>

</html>