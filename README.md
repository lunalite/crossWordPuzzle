# crossWordPuzzle
Update 23/7/2016
- Add a leave puzzle button for users

Update 19/7/2016
- Changed time_elapsed in studentHistory table to int(11) type, and changed qnStats to varchar(9000) to store more values
- Changed storage for studentHistory to UTF-8 for qnStats
- Added basic student Review
- Increased randomization of auto function
- Auto function bug fixes
- Updates instruction for master template
- Removed link to go back in the page to view crossword

Update 18/7/2016
- Resolved the live time update on the score list
- Changed HTTPRequest to ajax of jQuery
- Deleted addFinalScore.php and addTime.php since everything is implemented in addScore.php now
- Added the sessionHistory SQL that will record stats for users and historise.php for addition to respective table
- Solved some bugs regarding time and stuff not being saved after closing browser

Update 17/7/2016
- Added auto mode for Xword

Update 13/7/2016
- Users belonging to a particular group can only view sessions created for that group.

Update 12/7/2016
- Added a real-time checking function for creation of new group
- Allowed for adding of users to group in grant user page

Update 11/7/2016
- Added .htaccess for redirection to https site and protection
- Solved some minor bug  

Update 8/7/2016
- Added some checking of codes for grantUser.php page
- Added password change for other users
- Removed autoload to puzzle page for users
- Changed to https server through cloudflare
- Added classGroupOpen options for availablesessions table to check allow only related users for joining sessions
- Added classGroup table for ease of reference
- Added group creation page

Update 1/7/2016
- Moved session creation page to crosswordView.php page
- Allowed for hiding of the crosswordList
- Added a group option for Users
- Allow for admin to change to other user mode
- Created a new section to manage users
- Allowed for changing pw  for other users

Update 30/6/16
- View crosswords in crosswords/crosswordView.php opens up in new page for easy reference
- Added a new warning line when questions/answers are updated in crosswordView.php
- Added a reviews section for reviewing the past scores.
- Change a bit of button colours for ensuring blue = superuser and red = admin
- Added crosswordID under availableSessions for easy viewing
- Added review by sessions function
- Added a view for obtaining time which session started
- Reduced some bugs

Update 28/6/16
- Added undo function for crossword addition
- Added delete button fof created sessions
- Changed regex for question number to @ instead.
- Allowed for enter submission in index.php

Update 27/6/16
- Made more table names into global variables stored in psl-config.php
- Added a delete Crossword function	
- Moved crosswordAddition.php to ./crosswords folder
- Added a deleteCrossword prompt in case of accidental deletion

Update 26/6/16
- Moved crosswords.php to ./crosswords from main folder. This folder will be for all crosswords-related stuff.
- added crosswordView for checking current crosswords
- Moved phpVariables to ./includes/ folder from ./phpretrieval/includes & trying to migrate all the tablenames to phpVariables.php for easy reference and changes
- Added crosswordCheck function in ./includes/functions.php
- Added crosswordQEdit for editing of questions

Update 21/6/16
- Added some changes like allowing users to leave session
- Added session destroyer after game is finished
- Added time as scores
- All scores are being pushed real time using pusher app
- Changed the layout using bootstrap

Update 14/6/16                                                                                                                        
-Added re-formation of tiles from sessionStorage after refresh                                                                        
-Displays score on each player's page                                                                                                 
-Navigate users to Scores Page after game ends                                                                                        
-Navigates Super User to Live Scores Page after opening gate.(Max latency of 5s)                                                      
-Allows both suers and super users to return to index.php from Scores Page                                                            

Update 12/06/16
- Changed name of getIDFromTitle to getIdFromsSess
- Changed session ID to AUTO_INCREMENT so that every session will be unique
- Added attempts and answered array to Xword mainscript.js and save it in a session storage so that refreshing the browser will not change the number of attempts and correctly answered questions
- Added in change password function for super users
- removed puzzlename from availableSessions as it doesn't make sense for it to remain as a primary key. Instead, crosswordID is used to replace puzzleName as the identifier
- added questionAnswered table for database which is used to keep track of who answered what correctly and at what time
- Scoring given only for first team to answer qn correctly.

Update 9/6/16
- Merged all together
- Xword basic scoring system added

Update 4/6/16
- Allowed for users to join available sessions.
- Allowed for superusers to create new sessions.
- Error checking is not done yet.

Update 2/6/16
- Added the master page for superuser such that they can create sessions for users to join.
- Have not done users joining of session yet.

Update 1/6/16
Players should be able to enter and check their answers for the crossword
Admin is able to create xword with questions and answers and save to db

Update 29/05/2016                                                   
-Uploaded main_xword.html which is the page players will see                         
-Added ability to save puzzles to and load from database                                            
NOTE* To test out main_xword.html on chrome, u have to install Allow Origin Addon as currently, for testing purposes, the php scripts are executed from another domain which chrome disallows by default.
Valid Puzzle Names: P2,P3,MyPuzzle

Update 27/05/2016
- Done with login page, made it look nicer with Bootstrap
- WH did basic Xword puzzle fill-in.
- Added 2 roles - super user and basic user.

Update 26/05/2016
- Created the github for sharing of files.
- Added basic login page from online with all the various protection. Not fully debugged
- Currently, the test is done on Microsoft WebMatrix with localhost.

To Do:  
 1. Template
    A template that allows for easy usage in the case of someone without having the required know-how, to form the required puzzle. (Master-copy)
 2. Creation of crossword puzzle
    Dynamic creation of crossword puzzles based on words input, using random sorting algorithms
 3. Scoring system
    - A scoring system that aids in ensuring only 1 player is able to obtain the right answer based on the fastest speed. 
    - Have to ensure that rigidly, after a player obtains right answer, no other players’ answers are accepted.
    - Also, scoring system will be shown such that the top scorer will flash on the top of the screen, and the ranking, etc.
 4. Themes
    Have different graphical themes to improve design and user-friendliness of the crossword puzzles.
 5. Audio
    Sounds effects and background music for the crossword.
 6. Login system 
    System that allows a player to have a session connected per team, without another team being able to cut into the other session
 7. Submission system 
    Professor needs to be able to record individual student/team scores based on submissions or fastest-fingers-first
 8. Security (Future)
    Use a secure database/connection to ensure data transmission is encrypted and protected from hacking.
 9. Time system
    - A clock to show the time remaining and is synchronised on every individual player’s screen + on master screen. 
    - Add in emergency effects (coupled with audio and visual) to create anxiety.
 10. Difficulty level
     Ability to set the difficulty level of the crossword, changing the hint letters provided
 11. Hints
     Providing hints based on difficulty level, or redeem hints using points/limited hint requests
 12. Burning questions
    A box to post burning questions like why is answer this and that.
 13. Administrator Functionalities
    Administrator (professor) able to start/stop/reset timer, override hints, etc. (Superuser)

