<?php

session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: welcome.php");
    die();
}

include 'config.php';
$msg = "";

if (isset($_GET['verification'])) {
    $verification_code = mysqli_real_escape_string($conn, $_GET['verification']);
    $verification_query = "SELECT * FROM users WHERE code='{$verification_code}'";
    $verification_result = mysqli_query($conn, $verification_query);
    if (mysqli_num_rows($verification_result) > 0) {
        $update_query = "UPDATE users SET code='' WHERE code='{$verification_code}'";
        $update_result = mysqli_query($conn, $update_query);
        if ($update_result) {
            $msg = "<div class='alert alert-success'>Account verification has been successfully completed.</div>";
        }
    } else {
        header("Location: index.php");
    }
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='{$email}'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if ($row['password'] === $password) {
            if (empty($row['code'])) {
                $_SESSION['SESSION_EMAIL'] = $email;
                header("Location: welcome.php");
            } else {
                $msg = "<div class='alert alert-info'>First verify your account and try again.</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Email or password do not match.</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LOGIN</title>
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
        <form action="">
            <h1>LOGIN</h1>
            <?php echo $msg; ?>
            <label for="email">EMAIL:</label>
            <input name="email" id="email" type="text" />
            <br />
            <br />
            <label for="email">PASSWORD:</label>
            <input name="email" id="email" type="password" />
            <br />
            <br />
            <button name="submit" type="submit">SUBMIT</button>
            <br /><br />
            <p>NO ACC ? <a href="./signup.php">CREATE ONE</a></p>
            <a href="./forgot.php">
                <p>FORGOT PASSWORD</p>
            </a>
        </form>
    </div>
</body>

</html>