# crossWordPuzzle

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

