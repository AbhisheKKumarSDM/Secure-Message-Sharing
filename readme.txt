<Secure Message Sharing App>

This project is a secure message sharing application built with PHP and MySQL. It allows users to securely share messages with recipients using encryption and decryption techniques.

Installation
	To run this project locally, follow these steps:

	1. Clone the repository: Use the following command to clone the repository to your local machine:

		command: git clone https://github.com/AbhisheKKumarSDM/Secure-Message-Sharing.git

	2. Configure Database: Set up a MySQL database and import the provided SQL file (database.sql) to create				the necessary tables.

	3. Configure Database Connection: Update the database configuration in config/database.php with your MySQL 				host,username, password, and database name.

Run the Application: Start your local server (e.g., Apache, Nginx) and navigate to the project directory in your web browser.
 For eg. :  http://localhost/chat.


Important Instruction :
 1. Recipient : Recipient is user name need to send and recieve message.
 2. Encryption/Decryption Key: Decryption Key is must be same Encryption Key of user for recieve message.

How to Use:
	1.) Set Key: Navigate to the "Set Key" page, enter the recipient, encryption key.
	2.) Sharing Messages: Navigate to the "Share Message" page, enter the message, recipient(existed user).
	3.) Retrieving Messages: Navigate to the "Retrieve Message" page, enter the recipient email and decryption key,
			then click "Retrieve Message" to decrypt and view the message.
	4.) Marking Messages as Read: After retrieving a message, it will be automatically marked as read. No additional action is required.

That's all Folks!