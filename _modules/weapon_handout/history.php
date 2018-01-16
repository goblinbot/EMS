<?php
if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  loginRequired();

  $_MODULES["current"]["module"] = "gear exchange";
  $_MODULES["current"]["page"] = "history";

  $loanArr = ar_getLoans("done");
  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">



  <div class="container">
    <h1>Check In/Check out history</h1>

    <?php
      if(isset($loanArr) && $loanArr != "") {

        $printresult = "<table class=\"table\">";

        $printresult .= "<thead><tr>";
        $printresult .=
           "<th>Label</th>"
          ."<th>Code</th>"
          ."<th>Model</th>"
          ."<th>Deployed To</th>"
          ."<th>Deploy Date</th>"
          ."<th>Return Date</th>"
          ."<th><i class=\"fa fa-info-circle\"></i></th>";
        $printresult .= "</tr></thead>";

        // echo "<pre>"; var_dump($loanArr); echo "</pre>";
        foreach($loanArr AS $KEY => $LOAN ) {

          $printresult .= "<tr>"
          . "<td>".$LOAN['label']."</td>"
          . "<td>".$LOAN['barcode']."</td>"
          . "<td>".$LOAN['model']."</td>"
          . "<td>".$LOAN['loaned_to']."</td>"
          . "<td>".$LOAN['loan_date']."</td>"
          . "<td>".$LOAN['return_date']."</td>"
          . "<td>".$LOAN['description']."</td>"
          ."</tr>";
        }

        $printresult .= "</table>";
        echo $printresult;
      } else {

        echo "<h3>No history detected.</h3>";
      }
    ?>


  </div>


</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
