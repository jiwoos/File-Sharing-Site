<!DOCTYPE HTML>
<html lang=en>

<head>
    <title> log out </title>
    <style>
         body{
            background-color: rgb(217,132,155);
            font-family: "Times New Roman" ;
            color: rgb(242,203 ,189);
            font-weight: bolder;
            text-align: center;
        }
        h1{
            font-size:2em;
            text-align: center;
        }
        p{
            font-size: 1.5em;
            text-align: center;
        }
        form{
            text-align: center;
        }
    </style>
</head>

<body>
    <h1> verified! </h1>
<?php 
require 'newsweb.php';
if (isset($_GET['token'])) {
    $token = $_GET['token'] ;
    $result = $mysqli->query("select verified, token from login where verified = 0 AND token = '$token'");
    //verify the email
    // if ($result ->num_rows ==1) {
        $update = $mysqli-> query("update login set verified = 1 where token = '$token'");
        if ($update) {
            echo "You have been verified. You may log in now.";
        }
        else {
            echo $mysqli->error;
        }
}
    else{
        echo "this email has been already verified";
    
    }

if(isset($_POST['login'])) {
    header("location:loginpg");
}



?> 

<form action="verify.php" method="post">
       <br>
        <input name='login' type='submit' value= 'log in'></form>
</body>

</html>