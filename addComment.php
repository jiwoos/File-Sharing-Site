<?php
require "newsweb.php";
session_start();
$token_POST = $_POST['token'];
//CSRF case:
if(!hash_equals($token_POST, $_SESSION['token'])){
  session_unset();
  session_destroy();
  die("Request forgery detected");
}
//when the user submit the add comment button, 
// the page checks the session variable 'loggedin' to check the username and 'storynum' to check the story number.
if(isset ($_POST['submit'])){
    $comment = $_POST['comment'];
    $username = $_SESSION['loggedin'];
    $storynum = $_SESSION["storynum"];

    $stmt = $mysqli->prepare('SELECT num FROM login WHERE username = ?');
    if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($usernum);
    $stmt->fetch();
    $stmt->close();

//inserts the submitted content of comments 
    $stmt = $mysqli ->prepare('insert into comments (storynum, usernum, content) values (?, ?, ?)');
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
    $stmt->bind_param('dds', $storynum, $usernum, $comment);
    $stmt->execute();
    $stmt->close();
    header("Location: main.php");
    exit;
}
?>

<!doctype html>
<html lang='en'>
<head>
<title>Add a comment!</title>
<style>
@import url('https://fonts.googleapis.com/css?family=DM+Serif+Display|Lobster&display=swap');
        body {
            background-color: rgb(217, 132, 155);
            font-family: 'Lobster', cursive;
            color: rgb(242, 203, 189);
            /* font-weight: bolder; */
            text-align:center;
        }  </style>
</head>

<body>
<form action = 'addComment.php' method = 'post'>
<input type = text placeholder = comment name = comment>
<input type = submit name = submit value = submit>
<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">

</form>


</body>

</html>