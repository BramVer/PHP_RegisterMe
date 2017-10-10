# blogmephp
Implementation of the PHP tutorial based at  
https://ilovephp.jondh.me.uk/en/tutorial/make-your-own-blog/introduction  
All credits belong to the original author, this is merely the result of a walkthrough of said tutorial.

### Requirements
To run this project, the following requirements need to be met.

* Running apache web server
* PHP > 5.4
* SQLite PHP module

### Setup
Assuming Apache, SQLite and PHP are setup/running correctly:

1. Navigate to served directories (can differ per os)  
`cd /srv/http`

2. Clone repository  
`git clone https://github.com/BramVer/blogmephp`

3. Give correct access rights to folder  
`sudo chmod 755 /srv/http/blogmephp/data`

4. Trigger database setup  
Visit [localhost/blogmephp/install.php](localhost/blogmephp/install.php)
