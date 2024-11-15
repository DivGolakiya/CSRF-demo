# CSRF-demo

1. Install PHP and SQLite.
    -   To check which ```php.ini``` is being used run this command in cmd or terminal: ```php --ini```.
    -   Open that ```php.ini``` and make these changes.
    -   Remove semicolon (;) or uncomment from extension=pdo_sqlite
    -   Remove semicolon (;) or uncomment from extension=sqlite3
2. Navigate to directory where you have saved these php and html and db files.
3. Start PHP built-in Web Server with in cmd or terminal
    ```php -S localhost:8000```
    This will start a local Web server on port 8000.
4. Open your web browser and go to ```http://localhost:8000/index.php```.
5. Login with ```admin:admin123```. There is 1 more user ```user1:user123``` available with which you can login on ```index.php``` but I have hardcoded that user as Recipient. So it's better to login with ```admin:admin123```.
6. After login on ```index.php``` with given credential on step 5, open new tab and open ```attack.html``` and press on button ```Transfer Funds```.
7. This should re-direct you to ```transfer.php``` where you can see updated balance of user ```admin``` without actually transferring the balance.
8. To verify the updated balance, press ```logout``` and login with ```user1:user123``` on index.html and check the balance of ```user1``` user. 
9. For both users starting balance should be ```1000``` and after clicking on ```Transfer Funds``` button, balance should be updated by ```+/- 100``` for each.

If you want to transfer balance between user without using ```attack.html```, you can use ```index.php``` webpage where enter ```amount``` you want to transfer and enter ```username``` as Recipient and press the button ```Transfer``` and it should show you updated balance for that user.

P.S.: if you use ```index.php``` to transfer balance, please enter ```admin``` or ```user1``` as Recipient only, otherwise it will deduce amount but will not add any amount to any users. I have not created validation for it.

**For exercise 4.2 (b): countermeasure to CSRF attack**
1. Remove the comments from line no. 14 to 16 in ```transfer.php``` (PHP code file) and run the php code again same as before as you did to check CSRF attack but this time after pressing a ```Transfer Funds``` button on ```localhost:8000/attack.html``` page, it should redirect you to ```localhost:8000/transfer.php``` page, and it should show you **Unauthorized access or invalid CSRF token**.
