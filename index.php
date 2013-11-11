<?php
print "<!DOCTYPE HTML>\n";
print "<html lang=\"en\">\n";
print "  <head>\n";
print "    <title>Moni Test</title>\n";
print "    <meta charset=\"windows-1252\">\n";
print "  </head>\n\n";
print "  <body>\n";

# ---------------------------------------------------------------------------------------------------------------------------------- #
# Include CsvHandler class                                                                                                           #
# ---------------------------------------------------------------------------------------------------------------------------------- #
include('classCsvHandler.php');

print "    <form id=mainform method=post action='index.php'>\n";
print "      <div style='border:1px dotted #333;padding:10px;'>\n";
print "        Data Source: <select name=\"datasource\" id=\"datasource\">\n";
print "          <option value=\"0\">--- Choose ---</option>\n";
print "          <option value=\"D\">Database</option>\n";
print "          <option value=\"F\">File</option>\n";
print "          <option value=\"S\">Service</option>\n";
print "        </select>&nbsp;&nbsp;\n";
print "        User Id: <input type=\"text\" name=userid id=userid size=\"2\" maxlen=\"2\">\n";
print "        <input type=\"text\" name=\"ihavebeenherebefore\" id=\"ihavebeenherebefore\" value=\"Yes!!\" style=\"display:none;\">\n";
print "        <button type=\"submit\" id=sendbutton>Send</button>\n";
print "      </div>\n";
print "    </form>\n";


if ($_POST['ihavebeenherebefore']=='Yes!!') {
  $CsvH = new CsvHandler();
  if ($_POST['datasource']) {
    $CsvH->source = $_POST['datasource'];
  } else {
    $CsvH->source = 'D';
  }

  print "      <div style='border:1px dotted #333;padding:10px;clear:both;'>\n";
  print "        Total amount sent = ".number_format($CsvH->getTotalSent(), 2, '.', ',')."\n";
  print "      </div>\n";

  if ($_POST['userid']) {
    print "      <div style='border:1px dotted #333;padding:10px;'>\n";
    print "        Total amount sent by user ".$_POST['userid']." = ".number_format($CsvH->getTotalSentForUser($_POST['userid']), 2, '.', ',')."\n";
    print "      </div>\n";
  }

  print "    <script type=\"text/javascript\">\n";
  print "      for(var i=0;i<document.getElementById('datasource').options.length;i++) {\n";
  print "        if(document.getElementById('datasource').options[i].value == '".$_POST['datasource']."') {document.getElementById('datasource').selectedIndex=i;break;}\n";
  print "      }\n";
  print "      document.getElementById('userid').value='".$_POST['userid']."';\n";
  print "    </script>\n";
}

print "  </body>\n";
print "</html>\n";
?>
