# Mid-level PHP test

* index.php starts the demo and allows user input to choose data source (File, Database or Service mocker) and also to choose a specific user id.
* classDBfuncLib.php includes 3 main DB commands: open connection using PDO, prepare a statement and execute the statement. it currently connects to a remote DB but can easily be changed
* classCsvHandler.php includes the solution for the PHP test and is fairly documented and commented. It allows access to the CSV data by opening a remote/local file, accessing a remote/local DB and by mocking a web service (for the sake of the test, the login and fetch system was not coded and the pseudo-service returns the CSV data as a string upon being called)
* monitestdata.csv is the remote file with the CSV lines (taken from Michael's Gist). The DB data is the same as here.
