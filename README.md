# News-Web

http://ec2-52-14-212-154.us-east-2.compute.amazonaws.com/~jiwooseo/News%20Web/loginpg.php
Login Details
        username: we
        password: we
        but one can create a new account by clicking 'register' button on the login page.

          Basic Features:
          -A session is created when a user logs in 
          -New users can register 
          -Passwords are hashed, salted, and checked securely (does not use ==)
          -Users can log out 
          -A user can edit and delete his/her own stories and comments but cannot edit or delete the stories or comments of another user (8 points)
          Story and Comment Management :
          -Relational database is configured with correct data types and foreign keys
          -A link can be associated with each story, and is stored in a separate database field from the story 
          -Comments can be posted in association with a story 
          -Stories can be edited and deleted 
          -Comments can be edited and deleted 
          
          Best Practices :
          -Code is well formatted and easy to read, with proper commenting 
          -Safe from SQL Injection attacks
          -Site follows the FIEO philosophy 
          -All pages pass the W3C HTML and CSS validators
          -CSRF tokens are passed when creating, editing, and deleting comments and stories 
          
          
          
          Special Features:
          1) Email Verification Code
          When registering, users will receive verification link through email.
          without verification, users cannot login.

          2) like/dislike button 
          it is called hoo! and boo..
          non users can also participate and the results are reported on the main page.
