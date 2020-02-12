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
//the commentnum is checked to see the exact comment
if(isset($_SESSION['commentnum'])){
  //when the button is clicked -- (continued)
    if(isset($_POST['delete'])){

    $stmt = $mysqli->prepare("delete from comments where commentnum = ?");
    //the comment is deleted
    if(!$stmt){
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
    }
    $stmt->bind_param('d', $_SESSION['commentnum']);
    $stmt->execute();
    $stmt->close();

    header("Location: main.php");
    exit;
}
}
?>

<!doctype html>
<html lang = 'en'>
<head>
<title>delete comment</title>
<style> 
        body {
            background-color: rgb(217, 132, 155);
            font-family: "Times New Roman";
            color: rgb(242, 203, 189);
            /* font-weight: bolder; */
            text-align:center;
            font-size: 3em;
        } 
</style>
</head>
<body>
Would you really delete the comment?
<form action = 'commentdelete.php' method = 'post'>
<input name = 'delete' type = 'submit'>
<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
</form>
</body>
</html>