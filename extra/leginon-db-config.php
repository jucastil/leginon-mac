<?php    
     	$connection = mysql_connect("$argv[1]", "root","$argv[2]" ) 
		or die( "Sorry - unable to connect to MySQL" );
 	echo( "		Congratulations - you are connected to MySQL \n" );
	echo( "		Creating initial databases and users \n" );

	mysql_query("CREATE DATABASE leginondb");
	mysql_query("CREATE DATABASE projectdb");

	mysql_query("CREATE USER 'usr_object'@'localhost' IDENTIFIED BY 'BIOLBIOL';");
	mysql_query("GRANT ALL PRIVILEGES ON leginondb.* TO 'usr_object'@'localhost';");
	mysql_query("GRANT ALL PRIVILEGES ON projectdb.* TO 'usr_object'@'localhost';");

	mysql_query("GRANT ALL ON leginondb.* TO 'user_object'@'localhost'");
	mysql_query("GRANT ALL ON projectdb.* TO 'user_object'@'localhost'");
	mysql_query("GRANT ALTER, CREATE, INSERT, SELECT, UPDATE, DELETE ON ON `ap%`.* TO 'user_object'@'localhost'");
	mysql_query("GRANT ALL PRIVILEGES ON *.* TO 'usr_object'@'localhost'");
	 
	mysql_query("CREATE USER 'usr_object'@'%' IDENTIFIED BY 'BIOLBIOL';");
	mysql_query("GRANT ALL PRIVILEGES ON leginondb.* TO 'usr_object'@'%';");
	mysql_query("GRANT ALL PRIVILEGES ON projectdb.* TO 'usr_object'@'%';");

	mysql_query("GRANT ALL ON leginondb.* TO 'user_object'@'%'");
	mysql_query("GRANT ALL ON projectdb.* TO 'user_object'@'%'");
	
	mysql_query("GRANT ALTER, CREATE, INSERT, SELECT, UPDATE, DELETE ON ON `ap%`.* TO 'user_object'@'%'");
	mysql_query("GRANT ALL PRIVILEGES ON *.* TO 'usr_object'@'%'");

	mysql_close();
	echo( "	...done \n" );

?>
