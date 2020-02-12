<!doctype html>
<html lang ='en'>
<head>
<title>Add a new story!</title>
<style>
body {
            background-color: rgb(217, 132, 155);
            font-family: "Times New Roman";
            color: rgb(242, 203, 189);
            /* font-weight: bolder; */

        }
        h1{
            font-size:3em;
            text-align: center;
        }
        p{
            font-size: 1.6em;
            text-align: center;
        }
        form{
            text-align: center;
        }
</style>
</head>

<body>
  <h1>  share your thoughts </h1>
<form action = 'submitStory.php' method = 'post'>
<input type = 'text' name = 'stitle' placeholder = 'title'/> <br><br>
<textarea rows ='10' cols = '50' name = 'scontent'  >
</textarea><br>
does this involve link?  yes<input name ='link' type = 'radio' value = 'yes'>
no<input name ='link' type = 'radio' value = 'no'>
<input name = 'submit' type = 'submit' value = 'upload!'>



<?php
session_start();
$token_POST = $_POST['token'];
//CSRF case:
if(!hash_equals($token_POST, $_SESSION['token'])){
  session_unset();
  session_destroy();
  die("Request forgery detected");
}
if ($_SESSION['session'] != true) {
    // echo "<script>alert('unregistered users cannot post stories');
    //         main.php='admin/ahm/panel'</script>";

    echo '<script> alert("unregitered users cannot post stories"); 
    window.location.href="main.php"; </script>';
    exit;
}
require 'newsweb.php';


if (isset($_POST['main'])) {
    header("location:main.php");
} 

$user = $_SESSION['loggedin'];
$stmt = $mysqli->prepare('SELECT usernum FROM login WHERE username = ?');
$stmt->bind_param('s', $user);
$stmt->execute();
$stmt->bind_result($user_num);
$stmt->fetch();
$stmt->close();
echo "<br>";
//if one of the blanks is left empty, the error msg pops up
if(isset ($_POST['submit'])){
    if (empty($_POST['stitle']) || empty($_POST['scontent'])) {
        echo '<script> alert("please fill in every blank!"); 
        window.location.href="submitStory.php"; </script>';
        exit;
    }
    $stitle = $_POST['stitle'];
    $scontent = $_POST['scontent'];
    $slink=0;
    if($_POST['link']=='yes'){
        $slink =1;
    }

//submits new story into the database.
    $stmt = $mysqli ->prepare('insert into stories (title, content, usernum, link) values (?, ?, ?, ?)');
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
    $stmt->bind_param('ssss', $stitle, $scontent, $user_num, $slink);
    $stmt->execute();
    $stmt->close();
  echo "the story was posted";

}


?>
<br> <br>
<input name = 'main' type = 'submit' value = 'back to feed'>
<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">

</form>
</body>

</html>