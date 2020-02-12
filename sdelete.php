<?php
session_start();
require "newsweb.php";
$token_POST = $_POST['token'];
//CSRF case:
if(!hash_equals($token_POST, $_SESSION['token'])){
  session_unset();
  session_destroy();
  die("Request forgery detected");
}
//when the user intend to delete the story -- (continued)
if(isset($_SESSION['storynum'])){
  //checks the storynumber of the soon-to-be-deleted story.
    if(isset($_POST['delete'])){
    $stmt = $mysqli->prepare("delete from comments where storynum = ?");
    if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }
   
    $stmt->bind_param('d',$_SESSION['storynum'] );
    $stmt->execute();
    $stmt->close();
    //the story is deleted
    $stmt1 = $mysqli->prepare("delete from stories where storynum = ?");
    if(!$stmt1){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }
    $stmt1->bind_param('d',$_SESSION['storynum'] );
    $stmt1->execute();
    $stmt1->close();
    // back to the main page
    header("Location: main.php");
    exit;

}
}
?>

<!doctype html>
<html lang ='en'>
<head>
<title>delete stories</title>
<style>
@import url('https://fonts.googleapis.com/css?family=DM+Serif+Display|Lobster&display=swap');
        body {
            background-color: rgb(217, 132, 155);
            font-family: 'Lobster', cursive;
            color: rgb(242, 203, 189);
            /* font-weight: bolder; */
            text-align:center;
            font-size: 2em;
        }  </style>
</head>
<body>
Will you really delete the post?
<form action = 'sdelete.php' method = 'post'>
<input name = 'delete' type = 'submit' value = 'delete'>
<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">

</form>
</body>
</html>