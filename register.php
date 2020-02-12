<!DOCTYPE HTML>
<html lang=en>

<head>
    <title> Register </title>
    <style>
        body {
            background-color: rgb(217, 132, 155);
            font-family: "Times New Roman";
            color: rgb(242, 203, 189);
            font-weight: bolder;
        }

        h1 {
            font-weight: 2em;
            text-align: center;
        }

        p {
            font-size: 1.5em;
            text-align: center;
        }

        form {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1> register! </h1>
    <?php
    session_start();
    $connect = mysqli_connect("localhost", "wustl_inst", "wustl_pass", "module5");

    if (isset($_POST['register'])) {
        
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
            //check if the email is valid
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                echo '<script> alert ("Type in valid email") ;
        window.location.href="register.php"; </script>';
            }

          //check if the username already exists
        $input = $_POST['username'];
        $query = "select username from login where username = ? limit 1";
        $stmt = $connect-> prepare($query);
        $stmt->bind_param('s',$input);
        $stmt->execute();
        $result = $stmt->get_result();
        $usercheck = $result->num_rows;
        $stmt->close();
        if ($usercheck > 0 ) {
            echo '<script> alert ("The username already exists") ;
            window.location.href="register.php"; </script>';
        } 


        //check if the email already exists
        $emailput = $_POST['email'];
        $verified = false;
        $emailquery = "select email from login where email = ? limit 1";
        $stmt = $connect->prepare($emailquery);
        $stmt->bind_param('s', $emailput);
        $stmt->execute();
        $e_sult = $stmt->get_result();
        $emailcheck = $e_sult->num_rows;
        $stmt->close();
        if ($emailcheck > 0) {
            echo '<script> alert ("The email already exists") ;
            window.location.href="register.php"; </script>';
        }


        //registration is successful
        else {
            $token = bin2hex(openssl_random_pseudo_bytes(32));
            $username = mysqli_real_escape_string($connect, $_POST['username']);
            $password = mysqli_real_escape_string($connect, $_POST['password']);

            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "insert into login (username, password, email, verified, token ) 
                values('$username',  '$password', '$emailput', '$verified', '$token')";

            //send email verification 
            $to = $_POST['email'];
            $subject = "Email Verification";
            $message = "<a href='http://ec2-52-14-19-121.us-east-2.compute.amazonaws.com/~jiwooseo/NewsWeb/News%20Web/verify.php?token=$token'>Verify Email</a>";
            $headers = "From: itsjiwoo@gmail.com \r\n";
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html;charset=UTF-8" . "\r\n";
            mail($to, $subject, $message, $headers);

            if (mysqli_query($connect, $query)) {
                header("Location: regicomplete.php");
            }
        }
    }
    //if one of the blank is left empty, the error msg pops up.
    echo '<script> alert ("Please fill in every blank") ;
    window.location.href="register.php"; </script>';

    }
    ?>



    <form action="register.php" method="post">
        <br>
        email: <input name="email" type="text" placeholder="enter email" class="form-control">
        <br><br>
        username: <input name="username" type="text" placeholder="enter username" class="form-control">
        <br> <br>
        password: <input name="password" type="password" placeholder="enter password" class="form-control">

        <br> <br>
        <input name="register" type="submit" value="register">

</body>

</html>