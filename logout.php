<!DOCTYPE HTML>
<html lang=en>

<head>
    <title> log out </title>
    <style>

         body{
            background-color: rgb(217,132,155);
            font-family: "Times New Roman";
            color: rgb(242,203 ,189);
            font-weight: bolder;
            
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
    <h1> logged out! </h1>
<?php 
//session should be completely destroyed.
session_start();

session_unset();

session_destroy();



if (isset($_POST['main'])) {
    header("Location: main.php");
}
if (isset($_POST['login'])) {
    header("Location: loginpg.php");
}

?> 

<form action="logout.php" method="post"> 
        
        <br><br>
        go back to the feed
        <input name="main" type='submit' value= "feed!">
        <br> <br>
        <input name='login' type='submit' value= 'log in'> </form>
</body>

</html>