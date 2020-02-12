<!DOCTYPE HTML>
<html lang=en>

<head>
   <title> viewstory </title>
   <style>
      
      body {
         background-color: rgb(217, 132, 155);
         font-family: 'Times New Roman';
         color: rgb(242, 203, 189);
         /* font-weight: bolder; */
         text-align: center;
      }

      h1 {
         font-size: 2em;
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
<form action="storyitself.php" method="post">

   <input name="like" type="submit" value="hoo!">
   <input name="dislike" type='submit' value="boo..">
   <br> <br> <br> </form>

<body>
   <h1>
      <?php
      require "newsweb.php";
      session_start();
      //button that redirects to the main page
      if (isset($_POST['main'])) {
         header("location: main");
      }
      //button to like a story
      //like 
      if (isset($_POST['like'])) {
         header("location:hoo.php");
 
     }
      //dislike
      if (isset($_POST['dislike'])) {
         header("location: boo.php");
      }



      // Display the story and all it's comments
      if (isset($_GET['storynum'])) {

         $stmt = $mysqli->prepare("SELECT title, content, login.username, storynum, time, link FROM stories join login on (stories.usernum=login.usernum) where storynum = ?");

         if (!$stmt) {
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
         }

         $stmt->bind_param('d', $_GET["storynum"]);
         $stmt->execute();
         $stmt->bind_result($stitle, $scontent, $author, $story_num, $writtentime, $link);
         $stmt->fetch();
         $stmt->close();


         // Display the story, who posted it, and when.
         if ($link == 1) {
            $location = "Location: http://" . $scontent . "/";
            header($location);
            exit;
         }

         printf(
            "title: <pre> %s </pre><br><br> content:<pre> %s</pre>Posted by %s \r\n at $writtentime<br>",
            htmlentities($stitle),
            htmlentities($scontent),
            htmlentities($author)

         );

         ?>
      </h1>
      <?php
      $tempstorynum = $_GET["storynum"];
      $_SESSION['storynum'] = $tempstorynum;
      //giving story editing/deleting authorities to the author
      if (isset($_SESSION['loggedin'])) {
         if ($author == $_SESSION['loggedin']) {
         //    echo "<a href='storyedit.php?storynum=$tempstorynum'> edit </a>";

         // }
         // if ($author == $_SESSION['loggedin']) {
         //    echo " || ";
         //    echo "<a href='sdelete.php?storynum=$tempstorynum'> delete </a>";

         echo "<form action=\"storyedit.php\" method=\"POST\">
         <input type=\"submit\" value=\"edit\" name=\"edit\"/>
         <input type=\"hidden\" value=\"".$tempstorynum."\" name=\"storynum\" />
         <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
         </form></tb>";

         echo "<tb id=\"login\"><form action=\"sdelete.php\" method=\"POST\">
         <input type=\"submit\" value=\"delete\" name=\"delete\"/>
         <input type=\"hidden\" value=\"".$tempstorynum."\" name=\"storynum\" />
         <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
         </form>";

         }

         echo "<br> <br>";
         // echo "<a href='addComment.php?storynum=$tempstorynum'> add a new comment here </a>";
         echo "<form action=\"addComment.php\" method=\"POST\" id=\"postComments\">
         <input id = \"submitCmt\" type=\"submit\" name = \"submitCmt\" value = \"add comment\">
         <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
       </form>";
      }
   }

   $stmt = $mysqli->prepare("SELECT content, login.username, timestamp, commentnum FROM comments join login on (comments.usernum=login.usernum) where storynum = ?");

   if (!$stmt) {
      printf("Query Prep Failed: %s\n", $mysqli->error);
      exit;
   }

   $stmt->bind_param('d', $_GET["storynum"]);
   $stmt->execute();
   $stmt->bind_result($comment, $commenter, $commenttime, $comment_num);
   // Display each comment.
   echo "<ul>";
   while ($stmt->fetch()) {

      printf(
         "<li>「%s」  says %s    (at %s)</li>\n",
         htmlentities($comment),
         htmlentities($commenter),
         htmlentities($commenttime)
      );
      $_SESSION['commentnum'] = $comment_num;
      // Display the delete and edit buttons depending on the user.
      //only the commenter can edit and delete his/her comments.
      if (isset($_SESSION['loggedin'])) {
         // if ($commenter == $_SESSION['loggedin']) {
         //    echo "<a href='commentdelete.php?commentnum=$comment_num'>Delete</a> ";
         //    echo " || ";
         // }

         // if ($commenter == $_SESSION['loggedin']) {
         //    echo "<a href='commentedit.php?commentnum=$comment_num&oldcontent=$comment'>Edit</a>";
            echo "<form action=\"commentedit.php\" method=\"POST\">
            <input type=\"submit\" value=\"edit\" name=\"edit\"/>
            <input type=\"hidden\" value=\"".$comment_num."\" name=\"commentnum\" />
            <input type=\"hidden\" value=\"".$comment."\" name=\"old_content\" />
            <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
            </form>";

            echo "<form action=\"commentdelete.php\" method=\"POST\">
            <input type=\"submit\" value=\"delete\" name=\"delete\"/>
            <input type=\"hidden\" value=\"".$comment_num."\" name=\"commentnum\" />
            <input type=\"hidden\" name=\"token\" value=\"".$_SESSION['token']."\">
            </form>";
         // }
      }
   }
   echo "</ul>\n";
   $stmt->close();

   ?>
   <form action="storyitself.php" method="post">
      <input name="main" type="submit" value="take me back to the main page"> </form>
</body>

</html>