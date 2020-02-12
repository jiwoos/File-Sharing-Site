<!DOCTYPE HTML>
<html lang=en>

<head>
    <title> Log in </title>
    <style>
  
        body {
            background-color: rgb(217, 132, 155);
            font-family: "Times New Roman";
            color: rgb(242, 203, 189);
            /* font-weight: bolder; */

        }
    
        h1 {
            font-size: 3.5em;
            text-align: center;
        }

        p {
            font-size: 1.6em;
            text-align: center;
        }

        form {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1> news web</h1>
    <p> log in page </p>
    <?php
    session_start();
    require 'newsweb.php';
    //token is given for the first time
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 
    $_SESSION['session'] = false;
  
    if (isset($_SESSION['loggedin'])) {
        header("Location: main.php");
    }
    if (isset($_POST['register'])) {
        header("location: register.php");
    }
    if (isset($_POST['feed'])) {
        header("location:main.php");
    }

    require 'newsweb.php';

    // if the usernmae and password is not typed in, then error msg pops up
    if (isset($_POST['login'])) {

        if (empty($_POST['username']) || empty($_POST['pasword'])) {
            echo '<script>alert ("try again") </script>';
        }
        $stmt = $mysqli->prepare("SELECT username, password, verified FROM login WHERE username=?");
        if (!$stmt) {
            echo '<script> alert("login error - try again"); 
    window.location.href="loginpg.php"; </script>';
        }
        // Bind the parameter
        $stmt->bind_param('s', $user);
        $user = $mysqli->real_escape_string($_POST['username']);
        $stmt->execute();

        // Bind the results
        $stmt->bind_result($user_id, $pwd_hash, $verify);
        $stmt->fetch();
        // check whether the account has been verified


        // Compare the submitted password to the actual password hash
        $pwd_guess = $_POST['password'];
        if (password_verify($pwd_guess, $pwd_hash) && $verify == 1) {
            // Login succeeded!
            $_SESSION['loggedin'] = $user_id;
            $_SESSION['session'] = true;
            header("location: main.php");
            // Redirect to your target page
        } else {
            // Login failed; redirect back to the login screen
            echo '<script>alert ("login failed") </script>';
        }
    }
    ?>


    <form action="loginpg.php" method="post">
        <input name="username" type="text" placeholder="enter username">
        <input name="password" type="password" placeholder="enter password">
        <input name="login" type="submit" value="Log In">
        <br><br>
        --- <input name="feed" type="submit" value="jump into the feed!">---
        <br><br>
        Are you not registered yet?
        <input name="register" type='submit' value="register today!"> 
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>"></form>
</body>

</html>