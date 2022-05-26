# user-login
![user_login_interface](https://user-images.githubusercontent.com/81708902/169646195-8b9a6537-a4e8-476e-8a60-46f8a5cb854e.jpeg)

## Technologies used:
The entire project is purely created in:

- HTML
- CSS
- PHP
- MYSQL

## Description


This web application is designed in such a way It asks the user to first input their name and  email-id, If the entered email is valid then a verification mail is sent automatically to their respective mail id.

On successful verification, their email-id is registered in a database with a verified status This application also contains a feature to request for re-verification email if by chance any issue they haven't receive any mail at the time of registration.



## Files with brief description:

> *connection.php* : As per the name, this file contains the credentials and creates a successful connection to the database.

> *index.php* : The landing page of the project contains an attractive UI where everyone makes a visit. It contains a subscription form responsible for storing the user's email id for every mail functionality.  

> *controller.php* : It contains the important database operations with security methods (Injection, XSS and other vulnerabilities). It is also responsible for sending verification links via mail. 

> *verify-email.php* : This file is mainly responsible for the user's email-id verification via a button which user gets in the email.




> *resend.php* : It contains another attractive UI with a form responsible for taking user input and allowing them to request re-verification emails again.

> *resend-code.php* : This file contains the functionality of resend.php UI that allows users to request a re-verification email again if they haven't received it either due to server time out or any other reason at the time of registration.



*The entire code is created as per PHP coding standards and assignment submission guidelines*



> Created by: `Mohit Singh`
