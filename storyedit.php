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
// checks the storynumber of the soon-to-be-edited story.
//it selects the needed information of the story : original title and content of the story
if(isset($_SESSION['storynum'])){
    $stmt = $mysqli->prepare("SELECT title, content from stories where storynum =?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
      }
    $stmt->bind_param('d', $_SESSION['storynum']);
    $stmt->execute();
    $stmt->bind_result($oldtitle, $oldcontent);
    $stmt->fetch();
    $stmt->close();

    //when the user submits the edit button, 
    // newly submitted title and the content replace the original ones.
        if (isset($_POST['edit'])){
            if(isset($_POST['newcontent']) & isset($_POST['newtitle'])){
                $newtitle = $_POST['newtitle'];
                $newcontent = $_POST['newcontent'];
            
            $stmt = $mysqli ->prepare("UPDATE stories set title = ? , content = ? where storynum = ?");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
              }

            $stmt->bind_param('ssd', $newtitle, $newcontent, $_SESSION['storynum']);
            $stmt->execute();
            $stmt->close();
              // back to main page
            header("location:main.php");

        }
    }

    
}
?> 

<!doctype html>
<html lang ='en'>
<head>
<title>edit stories</title>
<style>@import url('https://fonts.googleapis.com/css?family=DM+Serif+Display|Lobster&display=swap');
        body {
            background-color: rgb(217, 132, 155);
            font-family: 'Lobster', cursive;
            color: rgb(242, 203, 189);
            /* font-weight: bolder; */
            text-align:center;
        } 
 </style>
</head>
<body>
<form action = 'storyedit.php' method = 'post'>
<input type = "text" name = "newtitle" value="<?=$oldtitle;?>">
<textarea rows = "10" cols = "50" name = "newcontent"> 
<?=$oldcontent;?>
</textarea>
<br> 
<input type = "submit" name = "edit" value = 'edit'> 
<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">

</form>
</body>
</html>