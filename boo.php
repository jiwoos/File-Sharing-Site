<?php
session_start();
require("newsweb.php");
//when the user clicks boo button, the user is directed to this page but won't be seeing anything
// because this page directly directs users to main page when all the processes are done.
// this updates the boo count and is reflected on the main page.
$stmt = $mysqli->prepare("SELECT boo FROM stories WHERE storynum=?");
if(!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->bind_param('d', $_SESSION['storynum']);
$stmt->execute();
$stmt->bind_result($curboo);
$stmt->fetch();
$stmt->close();
$newboo = $curboo + 1;
$stmt = $mysqli->prepare("UPDATE stories SET boo = ? WHERE storynum = ?");
if(!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->bind_param('dd', $newboo, $_SESSION['storynum']);
$stmt->execute();
$stmt->close();
header("Location: main.php");
exit;
?>