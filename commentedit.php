<?php
require "newsweb.php";
session_start();
//the commentnum is checked to see the exact comment
$token_POST = $_POST['token'];
//CSRF case:
if(!hash_equals($token_POST, $_SESSION['token'])){
  session_unset();
  session_destroy();
  die("Request forgery detected");
}
if(isset($_SESSION['commentnum'])){
//the information needed to edit a comment is selected according to the commentnum
//  information: original content of the comment
    $stmt = $mysqli->prepare("SELECT content from comments where commentnum =?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
    $stmt->bind_param('d', $_SESSION['commentnum']);
    $stmt->execute();
    $stmt->bind_result($oldcontent);
    $stmt->fetch();
    $stmt->close();
    //the original content is replaced with the newly submitted content.
    if (isset($_POST['edit'])) {
        if(isset($_POST['newcontent'])){
            $newcontent = $_POST['newcontent'];
        
        $stmt = $mysqli ->prepare("UPDATE comments set content = ? where commentnum = ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
          }

        $stmt->bind_param('sd', $newcontent, $_SESSION['commentnum']);
        $stmt->execute();
        $stmt->close();

        header("location:storyitself.php?storynum=".$_SESSION['storynum']);
        exit;

    }
   }
 

}
// $commentnum = $_POST['commentnum'];

// echo "<form action = \"".$_SERVER['PHP_SELF']."\" method = \"POST\">
// <input type = \"text\" name = \"newcontent\" value = \"".$oldcontent."\">
// <input type = \"hidden\" name = \"commentnum\" value = \"".$commentnum."\">
// <input type = \"submit\" name = \"edit\" value = \"edit\">
// </form>";

?> 

<!doctype html>
<html lang ='en'>
<head>
<title>edit stories</title>
<style>
        body {
            background-color: rgb(217, 132, 155);
            font-family: "Times New Roman";
            color: rgb(242, 203, 189);
            /* font-weight: bolder; */
            text-align:center;
        } 
 </style>
</head>
<body>
<form action = 'commentedit.php' method = 'post'>
<textarea rows = "10" cols = "50" name = "newcontent"> 
<?=$oldcontent;?>
</textarea>
<br> 
<input type = "submit" name = "edit" value = 'edit'> 
<input type="hidden" value="" name="commentnum" />
<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">



</form>
</body>
</html>