#Install for linux
# logistic
## You must install apache2 
sudo apt-get update
sudo apt-get install apache2
sudo systemctl restart apache2
## you install php 5.6
sudo apt-get install -y php5.6
## you install mysql 5.6 driver 
sudo apt install php5.6-mysql
## Create DB and add sql.sql 
## Config DB
<?php
return array(
	'connectionString' => 'mysql:host=127.0.0.1;dbname=orderhip',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => 'Welcome1',
	'charset' => 'utf8',
	"tablePrefix" => "tbl_"
);


#Install for window

