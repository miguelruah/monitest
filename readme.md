# Mid-level PHP test

The solution is currently available at www.fogodavida.com/monitest/index.php and uses the files below:

- index.php starts the demo and allows the user to choose the data source (File, Database or Service) and the user id.
     File accesses a local file, both Database and Service access a remote database in www.dancingfire.org

- classDBfuncLib.php is available in both domains and it includes 3 main DB commands: open connection using PDO,
     prepare a statement with named parameters and execute the statement.
     It currently connects to a remote DB but that can easily be changed in __construct().
     For that, transactions.sql quickly creates the table in a local DB. Amount was defined as float in the DB
     (they were kept as integer in the file).
     monitestdata.csv is the remote file with the CSV lines (taken from Michael's Gist). The DB data is the same as here,
     though all Amounts in the file were kept as integers (they are float in the DB).

- classCsvHandler.php includes the solution for the PHP test and is fairly documented and commented inside.
     It allows access to the CSV data by opening a remote/local file, accessing a remote/local DB or by mocking
     a web service, user decides that through a drop-down in index.php
     In the data provided from the service, amount also comes as a float because the data comes from the DB.
     In order not to stray from the main focus of the test, the login and download service functions were not developed
     (the pseudo-service returns the CSV data as a string upon being called).
     Currency rates are mocked through a function.

- webservice.php is in www.dancingfire.org and simulates a web service, retrieving data from the remote DB using the
     same classes in classDBfuncLib.php and returns data as a CSV string where lines are separated by chr(10)

- monitestdata.csv is stored in www.fogodavida.com and contains the data in CSV format.

- transactions.sql creates the MySQL table with the same simulation data.