<?
# ---------------------------------------------------------------------------------------------------------------------------------- #
# DBHandler()                                                                                                                        #
# ---------------------------------------------------------------------------------------------------------------------------------- #
# This class handles the DB interface                                                                                                #
# ---------------------------------------------------------------------------------------------------------------------------------- #
# Last edit: 2013.11.09                                                                                                              #
# ---------------------------------------------------------------------------------------------------------------------------------- #
class DBHandler {
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # DBH - holds the handle after opening the DB connection (set by __construct()                                                       #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  public $DBH; # DB Handle

  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # __contruct()                                                                                                                       #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Creates a new PDO MySQL connection and sets the public DB handle $DBH                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # No Parameters                                                                                                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # DB handle if success / Boolean false if failure                                                                                    #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.10.31                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  public function __construct() {
    $tmpDbHost = 'www.dancingfire.org';
    $tmpDbUser = "dancingf_moniusr";
    $tmpDbPass = "m0n1_u5r";
    $tmpDbName = "dancingf_moni";
    try {
      $this->DBH = new PDO("mysql:host=".$tmpDbHost.";dbname=".$tmpDbName, $tmpDbUser, $tmpDbPass);
      $this->DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->DBH;
    } catch(PDOException $e) {
      DBprocessError(1, 'inclDBfuncLib.php', $e->getMessage(), '*** DB Connect ***');
      return false;
    }
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # __destruct()                                                                                                                       #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Destroys the PDO MySQL connection by setting the DB handle to null                                                                 #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # No Parameters                                                                                                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Boolean - true                                                                                                                     #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.10.31                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  public function __destruct() {
    $this->DBH = null;
    return true;
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # DBqueryPrep()                                                                                                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Compiles a DB query with named parameters                                                                                          #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # No Parameters                                                                                                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Query handle or Boolean - false if failure                                                                                         #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.10.31                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  public function DBqueryPrep($parQuery) {
    try {
      $tmpQueryHandle = $this->DBH->prepare($parQuery);
      $tmpQueryHandle->setFetchMode(PDO::FETCH_ASSOC);
      return $tmpQueryHandle;
    } catch(PDOException $e) {
      DBprocessError(3, 'inclDBfuncLib.php', $e->getMessage(), $parQuery);
      return false;
    }
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # DBqueryExec()                                                                                                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Executes a pre-compiled DB query with named parameters                                                                             #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Parameters                                                                                                                         #
  #                                                                                                                                    #
  # parQueryHandle - the handle that points to the pre-compiled query                                                                  #
  # parValuesArray - an array with the named parameters to execute the query                                                           #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Boolean - true if success / false if failure                                                                                       #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.10.31                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  public function DBqueryExec($parQueryHandle, $parValuesArray, $parErrorNum, $parProgName) {
    try {
      $parQueryHandle->execute($parValuesArray);
      return true;
    } catch(PDOException $e) {
      DBprocessError($parErrorNum, $parProgName, $e->getMessage(), $parQueryHandle->queryString);
      return false;
    }
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # DBprocessError()                                                                                                                   #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Registers the relevant vars in the DB (if possible) and sends a mail to the webmaster with all the relevant details                #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Parameters                                                                                                                         #
  #                                                                                                                                    #
  # parErrorNum   - sequential number that helps locate the error within the error page                                                #
  # parErrorPage  - indicates the code page where the error ocurred                                                                    #
  # parErrorText  - auto error text returned by the system                                                                             #
  # parErrorQuery - the query in use when the error occured                                                                            #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # String - DB failure message in current language                                                                                    #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.10.31                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  public function DBprocessError($parErrorNum, $parErrorPage, $parErrorText, $parErrorQuery) {
     $tmpSendEmail       = false;
     $tmpCreateErrorInDB = false;

     $tmpErrorDate       = date('Y-m-d');
     $tmpErrorTime       = date('G:i:s');
     $tmpErrorRefer      = $_SERVER['HTTP_REFERER'];
     $tmpErrorIP         = $_SERVER['REMOTE_ADDR'];
     $tmpErrorSelf       = $_SERVER['PHP_SELF'];
     $tmpErrorRequest    = $_SERVER['REQUEST_URI'];
     $tmpErrorAutoMsg    = $php_errormsg;

     $tmpReturnText = "<span class=normal>An error occurred while accessing the Database and an automatic report was created.<br/>Please retry again soon.</span>";
     return $tmpReturnText;
  }
}
?>
