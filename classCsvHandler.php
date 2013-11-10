<?php
# ---------------------------------------------------------------------------------------------------------------------------------- #
# Include DB handling class                                                                                                          #
# ---------------------------------------------------------------------------------------------------------------------------------- #
include('classDBfuncLib.php');

# ---------------------------------------------------------------------------------------------------------------------------------- #
# CsvHandler()                                                                                                                       #
# ---------------------------------------------------------------------------------------------------------------------------------- #
# This class retrieves and handles transaction info in CSV format                                                                    #
# ---------------------------------------------------------------------------------------------------------------------------------- #
# Last edit: 2013.11.09                                                                                                              #
# ---------------------------------------------------------------------------------------------------------------------------------- #
class CsvHandler {
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # weHaveCSV - true if CSV data has already been retrieved                                                                            #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  private $weHaveCSV = false;

  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # arrTransactions - array containing retrieved CSV data if weHaveCSV is true                                                         #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  private $arrTransactions = array();

  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # arrCurrencyRates - array containing currency rates                                                                                 #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  private $arrCurrencyRates = array();

  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # source - indicates where the CSV data comes from: D - Database / F - File / S - Service                                            #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  public $source = 'D';

  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # prepCurrencyRates()                                                                                                                #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # This function simulates the retrieval of current rates and sets the arrCurrencyRates array.                                        #
  # Can be adapted to retrieve from Service, File or DB                                                                                #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # No Parameters                                                                                                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Array - simple array containing rates as array[Currency] = rate                                                                    #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.11.09                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  private function prepCurrencyRates() {
    $this->arrCurrencyRates = array('EUR'=>0.834635335, 'GBP'=>1, 'PLN'=>0.199381289, 'USD'=>0.62496094);
    return true;
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # getCurrencyRate()                                                                                                                  #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # This function returns the current rate for the currency passed as parameter.                                                       #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # No Parameters                                                                                                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Float - rate of currency passed as parameter, extracted from arrCurrencyRates                                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.11.09                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  private function getCurrencyRate($parCurrency) {
    return (float)$this->arrCurrencyRates[$parCurrency]; # no need to double validate currency
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # validInt()                                                                                                                         #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # This function validates if parameter is integer                                                                                    #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Parameters                                                                                                                         #
  #                                                                                                                                    #
  # parValue - parameter to be validated (unknown type or content)                                                                     #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Boolean - true if integer / false otherwise                                                                                        #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.11.09                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  private function validInt($parValue) {
    $tmpValue = (string)$parValue; # cast string type
    if (strlen($tmpValue)==0 || $tmpValue == null) {
      return false; # parameter is null or not convertible to a string
    }
    return preg_match('/^\d+$/', $tmpValue); # validate if string is any quantity of digits
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # validDate()                                                                                                                        #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # This function validates if parameter is date in format YYYY-MM-DD                                                                  #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Parameters                                                                                                                         #
  #                                                                                                                                    #
  # parValue - parameter to be validated (unknown type or content)                                                                     #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Boolean - true if integer / false otherwise                                                                                        #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.11.09                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  private function validDate($parValue) {
    $tmpValue = (string)$parValue; # cast string type
    if (strlen($tmpValue)!=10) {
      return false; # parameter is null or not length 10 (for a less rigid format, this condition can be deleted)
    }
    if (substr($tmpValue, 4, 1)!='-' || substr($tmpValue, 7, 1)!='-') {
      return false; # separators are not hyphen
    }
    return checkdate(substr($tmpValue, 5, 2), substr($tmpValue, 8, 2), substr($tmpValue, 0, 4)); # check if date is format YYYY-MM-DD with valid values for each field
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # validAmount()                                                                                                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # This function validates if parameter is positive float with a maximum of 2 decimals                                                #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Parameters                                                                                                                         #
  #                                                                                                                                    #
  # parValue - parameter to be validated (unknown type or content)                                                                     #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Boolean - true if integer / false otherwise                                                                                        #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.11.09                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  private function validAmount($parValue) {
    $tmpValue = (string)$parValue;                                 # cast string type
    if (strlen($tmpValue)==0 || $tmpValue == null) {
      return false; # parameter is null or not convertible to a string
    }
    return preg_match('/^[0-9]+(?:\.[0-9]{1,2})?$/', $tmpValue); # validate if string is any quantity of digits with a max of 1 dot and a max of 2 decimals
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # validCurrency()                                                                                                                    #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # This function validates if parameter is one of the known valid currencies (3 letters exactly)                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Parameters                                                                                                                         #
  #                                                                                                                                    #
  # parValue - parameter to be validated (unknown type or content)                                                                     #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Boolean - true if integer / false otherwise                                                                                        #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.11.09                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  private function validCurrency($parValue) {
    $tmpValue = (string)$parValue;                                 # cast string type
    if (strlen($tmpValue)!=3) {
      return false; # parameter is null or not 3 letters
    }
    $tmpPos = strpos('EURGBPPLNUSD', $tmpValue);
    if ($tmpPos===false || ($tmpPos%3)!=0) {
      return false; # parameter not found in currency list
    }
    return true;
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # getCsvData()                                                                                                                       #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # This function retrieves transaction data in CSV format from different sources.                                                     #
  # Validates data before returning                                                                                                    #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Parameters                                                                                                                         #
  #                                                                                                                                    #
  # char parSource - F - File / D - Database / S - Service (no login needed)                                                           #
  # array parAccess - Array with access data to access the info source                                                                 #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Array - simple array containing the transaction data                                                                               #
  # or return false if unsuccessful                                                                                                    #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.11.09                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  private function getCsvData($parSource) {
    switch ($parSource) {
      case 'S': # retrieve data from Service
        # (i'm strangely assuming here that this web service doesn't ask for any login info and doesn't send me an access token
        # and, instead, it generously just returns a CSV string full of private transactions info)
        break;
      case 'D': # retrieve data from Database
        $DBO = new DBHandler();
        if ($DBO === false) {return false;} # error opening DB => abort

        $tmpQuery = "select user, date, amount, currency from transactions;";
        $queryHandle = $DBO->DBqueryPrep($tmpQuery);
        if ($queryHandle === false) {return false;} # error compiling DB query => abort

        if ($DBO->DBqueryExec($queryHandle, null, 1, 'CsvHandler.php') === false) {return false;} # error executing DB query => abort

        $tmpIndex = 0;
        while($tmpRow = $queryHandle->fetch()) {
          if ($this->validInt($tmpRow['user']))          {$tmpReturnArray[$tmpIndex]['user']     = $tmpRow['user'];}     else {return false;}
          if ($this->validDate($tmpRow['date']))         {$tmpReturnArray[$tmpIndex]['date']     = $tmpRow['date'];}     else {return false;}
          if ($this->validAmount($tmpRow['amount']))     {$tmpReturnArray[$tmpIndex]['amount']   = $tmpRow['amount'];}   else {return false;}
          if ($this->validCurrency($tmpRow['currency'])) {$tmpReturnArray[$tmpIndex]['currency'] = $tmpRow['currency'];} else {return false;}
          $tmpIndex++;
        }
        return $tmpReturnArray;
        break;
      case 'F': # retrieve data from external File
        if (($tmpFileHandle = fopen("monitestdata.csv", "r")) !== FALSE) {
          fgetcsv($tmpFileHandle); # skip the first row with headers
          $tmpIndex = 0;
          while (($tmpData = fgetcsv($tmpFileHandle, 1000, ",")) !== FALSE) {
            if (count($tmpData)!=4) {
              return false; # line does not have 4 fields => abort
            } else {
              if ($this->validInt($tmpData['0']))      {$tmpReturnArray[$tmpIndex]['user']     = $tmpData['0'];} else {return false;}
              if ($this->validDate($tmpData['1']))     {$tmpReturnArray[$tmpIndex]['date']     = $tmpData['1'];} else {return false;}
              if ($this->validAmount($tmpData['2']))   {$tmpReturnArray[$tmpIndex]['amount']   = $tmpData['2'];} else {return false;}
              if ($this->validCurrency($tmpData['3'])) {$tmpReturnArray[$tmpIndex]['currency'] = $tmpData['3'];} else {return false;}
            }
            $tmpIndex++;
          }
          return $tmpReturnArray;
        } else {
          return false; # CSV file not found or not opened => abort
        }
        break;
      default:  # unspecified or invalid source => abort
        return false;
        break;
    }
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # getTotalSent()                                                                                                                     #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # This function returns the sum of the amounts retrieved in CSV format                                                               #
  # Firs check if data has been retrieved - if not, retrieve data and mark as retrieved by setting weHaveCSV as true                   #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # No Parameters                                                                                                                      #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Float - sum of the amounts / false if error occurred                                                                               #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.11.09                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  public function getTotalSent() {
    if (!$weHaveCSV) {                                     # we only need to retrieve CSV data once for each CsvHandler object
      $this->prepCurrencyRates();                          # retrieving currency rates
      $this->arrTransactions = $this->getCsvData($this->source);     # retrieving data
      if ($this->arrTransactions===false) {
        return false; # an error occurred
      }
      $this->weHaveCSV = true;
    }

    $tmpNdx = 0;
    while ($this->arrTransactions[$tmpNdx]['amount']) {
      $tmpSum += round($this->arrTransactions[$tmpNdx]['amount']*$this->getCurrencyRate($this->arrTransactions[$tmpNdx]['currency']), 2);
      $tmpNdx++;
    }
    return $tmpSum;
  }
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # getTotalSentForUser()                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # This function returns the sum of the amounts retrieved in CSV format for a given user id                                           #
  # Firs check if data has been retrieved - if not, retrieve data and mark as retrieved by setting weHaveCSV as true                   #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Parameters                                                                                                                         #
  #                                                                                                                                    #
  # parUserId - an integer that represents a specific user                                                                             #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Returns                                                                                                                            #
  #                                                                                                                                    #
  # Float - sum of the amounts / false if error occurred                                                                               #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  # Last edit: 2013.11.09                                                                                                              #
  # ---------------------------------------------------------------------------------------------------------------------------------- #
  public function getTotalSentForUser($parUserId) {
    if (!$weHaveCSV) {                                     # we only need to retrieve CSV data once for each CsvHandler object
      $this->prepCurrencyRates();                          # retrieving currency rates
      $this->arrTransactions = $this->getCsvData($this->source);     # retrieving data
      if ($this->arrTransactions===false) {return false;}  # an error occurred
      $this->weHaveCSV = true;
    }

    $parUserId = (string)$parUserId;
    $tmpNdx = 0;
    while ($this->arrTransactions[$tmpNdx]['amount']) {
      if ($this->arrTransactions[$tmpNdx]['user']==$parUserId) {
        $tmpSum += round($this->arrTransactions[$tmpNdx]['amount']*$this->getCurrencyRate($this->arrTransactions[$tmpNdx]['currency']), 2);
      }
      $tmpNdx++;
    }
    return $tmpSum;
  }
}
?>
