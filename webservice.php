<?
# ---------------------------------------------------------------------------------------------------------------------------------- #
# Include DB handling class  #
# ---------------------------------------------------------------------------------------------------------------------------------- #
include('classDBfuncLib.php');

$tmpLineSep = chr(10); # set ascii character 10 as the line separator

$DBO = new DBHandler();
if ($DBO === false) {die();} # error opening DB => abort and return empty result

$tmpQuery = "SELECT concat(user, ',', date, ',', amount, ',', currency) as oneline FROM transactions;";
$queryHandle = $DBO->DBqueryPrep($tmpQuery);
if ($queryHandle === false) {die();} # error compiling DB query => abort and return empty result

if ($DBO->DBqueryExec($queryHandle, null, 1, 'CsvHandler.php') === false) {die();} # error executing DB query => abort and return empty result

$tmpNdx = 0;
$tmpString = "user,date,amount,currency";
while($tmpRow = $queryHandle->fetch()) {

  $tmpString .= $tmpLineSep.$tmpRow['oneline'];
  $tmpNdx++;

}

print $tmpString;

?>
