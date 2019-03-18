<?php
if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  loginRequired();

  $_MODULES["current"]["module"] = "gear exchange";
  $_MODULES["current"]["page"] = "Ammobox History";

  $loanArr = ar_getAmmoBoxLoans("done");
  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">



  <div class="container">
    <h1>AmmoBox Check In/Out history</h1>

    <?php
      if(isset($loanArr) && $loanArr != "") {

        $printresult = "<table class=\"table\">";

        $printresult .= "<thead><tr>";
        $printresult .=
        "<th>Loan ID</th>"
        ."<th>Code</th>"
        ."<th>Type</th>" 
        ."<th>Name</th>"
        ."<th>Variant</th>"
        ."<th>Capacity</th>" 
        ."<th>Deployed To</th>"
        ."<th>Deployed With</th>"
          ."<th>Return Date</th>"
          ."<th><i class=\"fa fa-info-circle\"></i></th>";
        $printresult .= "</tr></thead>";

        // echo "<pre>"; var_dump($loanArr); echo "</pre>";
        foreach($loanArr AS $KEY => $LOAN ) {

          $printresult .= "<tr>"
          . "<td>".$LOAN['loan_id']."</td>"
            . "<td>".$LOAN['abid']."</td>"
            . "<td>".$LOAN['abtype']."</td>"
            . "<td>".$LOAN['abtname']."</td>"
            . "<td>".$LOAN['abvariant']."</td>"
            . "<td>".$LOAN['capacity']."</td>"
            . "<td>".$LOAN['loaned_to']."</td>"
            . "<td>".$LOAN['deployed_with']."</td>"
          . "<td>".$LOAN['return_date']."</td>"
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
