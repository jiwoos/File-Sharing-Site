<?php
session_start();
require("newsweb.php");
//when the user clicks hoo button, the user is directed to this page but won't be seeing anything
// because this page directly directs users to main page when all the processes are done.
// this updates the hoo count and is reflected on the main page.
$stmt = $mysqli->prepare("SELECT hoo FROM stories WHERE storynum=?");
if(!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->bind_param('d', $_SESSION['storynum']);
$stmt->execute();
$stmt->bind_result($curhoo);
$stmt->fetch();
$stmt->close();
$newhoo = $curhoo + 1;
$stmt = $mysqli->prepare("UPDATE stories SET hoo = ? WHERE storynum = ?");
if(!$stmt){
  printf("Query Prep Failed: %s\n", $mysqli->error);
  exit;
}
$stmt->bind_param('dd', $newhoo, $_SESSION['storynum']);
$stmt->execute();
$stmt->close();
header("Location: main.php");
exit;
?>