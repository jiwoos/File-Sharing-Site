<!DOCTYPE HTML>
<html lang=en>

<head>
    <title> Main Page </title>
    <style>
        body {
            background-color: rgb(217, 132, 155);
            font-family: "Times New Roman";
            color: rgb(242, 203, 189);
            /* font-weight: bolder; */
            text-align: center;

        }

        h1 {
            font-size: 3em;
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

    <h1>
        <?php
        session_start();

        /* Here, registered users can: see, delete, edit stories and comment
         unregistered users can: see stories and comment
    users can jump into login/register page by clicking buttons.
*/

        require 'newsweb.php';
        //when the loggedin user enters, it prints out the username and the welcome sign
        if (isset($_SESSION['loggedin'])) {
            echo $_SESSION['loggedin'] . ", welcome to the news web";
        } else {
            echo "news web";
        }

        ?> </h1>
    <!--<form action="main.php" method="GET">
        Sort the posts by:<br> <input type="radio" name="sort" value="post"> Story Title<br>
        <input type="radio" name="sort" value="time">Posted Time<br>
        <input type="submit" value="Change sort!">-->
    </form>
    <p>
        <?php
        //a button that sorts the title of the stories in multiple ways
        // if (isset($_GET['sort'])) {
        //     if ($_GET['sort'] == "post") {
        //         $stmt = $mysqli->prepare("SELECT storynum, title, login.username, hoo, boo FROM stories join login on (stories.usernum=login.usernum) ORDER BY title DESC");
        //     } else if ($_GET['sort'] == "time") {
        //         $stmt = $mysqli->prepare("SELECT storynum, title, login.username, hoo, boo FROM stories join login on (stories.usernum=login.usernum) ORDER BY storynum DESC");
        //     }
        // } else {
        //     $stmt = $mysqli->prepare("SELECT storynum, title, login.username, hoo, boo FROM stories join login on (stories.usernum=login.usernum) ORDER BY storynum DESC");
        // }
        //a button that directs to the logout page
        // only loggedin user can access. if not loggedin, the error msg pops up
        if (isset($_POST['logout'])) {
            if ($_SESSION['session'] != true) {
                echo '<script> alert("you are not logged in"); 
        window.location.href="main.php"; </script>';
                exit;
            }

            header("Location: logout.php");
        }
        //a button that directs to the login page
        // only non-loggedin users can access. if loggedin, the error msg pops up.
        if (isset($_POST['login'])) {
            if ($_SESSION['session'] == true) {
                echo '<script> alert("you are already logged in"); 
        window.location.href="main.php"; </script>';
                exit;
            }
            header("Location: loginpg.php");
        }
        // a button to a page where users can post their stories.
        // only loggedin users can access. if not loggedin, then error msg pops up.
        if (isset($_POST['upload'])) {
            if ($_SESSION['session'] != true) {
                echo "<script>alert('login or register to post stories!');
            main.php='admin/ahm/panel'</script>";
            }

        }
        echo "<form action=\"submitStory.php\" method=\"POST\">
       
        <input id=\"btn\" type=\"submit\" value=\"post\" name=\"token\"/>
        <input type=\"hidden\" value=\"".$_SESSION['token']."\" name=\"token\" />
        </form>";

        //here, the title of each story is displayed. 
        $stmt = $mysqli->prepare("SELECT storynum, title, login.username, hoo, boo FROM stories join login on (stories.usernum=login.usernum)");
        if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->execute();
        $stmt->bind_result($story_num, $title, $author, $hoo, $boo);
        while ($stmt->fetch()) {
            echo "↑" . $hoo . "↓" . $boo . "<br>";
            echo "<a href='storyitself.php?storynum=$story_num'>$title</a>";
            echo " By " . htmlentities($author) .  "<br>";
            echo "<br><br>";
        }
        $stmt->close();


        ?>
    </p>
    <!-- <form action="main.php" method="post">
        <input name='upload' type='submit' value='upload a story'>
        <br><br>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>"> 
    </form> -->

<form action = "main.php" method = "post">
        <input name='logout' type='submit' value='log out'>
        <input name='login' type='submit' value='login'>
        
    </form>
</body>

</html>