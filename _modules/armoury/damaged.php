<?php
  if(!isset($_SESSION)) {
    session_start();
  }

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");

  loginRequired();

  $_MODULES["current"]["module"] = "armoury";
  $_MODULES["current"]["page"] = "damaged";

  if(isset($_GET['repair']) && $_GET['repair'] == true) {
    if(isset($_GET['key']) && $_GET['key'] != "") {

      // zet wapen op beschikbaar
      $sql = "UPDATE ar_weapons SET status = 'In armoury' WHERE id = '".mysqli_real_escape_string($UPLINK,$_GET['key'])."' LIMIT 1";
      $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));

    }
  }

  $weaponArr = ar_initWeapons('broken');
  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">
  <div class="container">

    <h1>Gear currently out of order</h1>

    <hr/>
    <br/>

    <style>
      .table tr {
        border-bottom: 1px solid #CCC;
      }
      .table tr td {
        padding: 17px;
      }
      .table th {
        text-align: left;
      }
    </style>

    <?php
      if(isset($weaponArr['inactive']) && $weaponArr['inactive'] != "") {

        $printresult = "<table class=\"table\">";

        $printresult .=
          "<tr>"
            . "<th>&nbsp;Title</th>"
            . "<th>&nbsp;Code</th>"
            . "<th>&nbsp;Model</th>"
            . "<th>&nbsp;Type</th>"
            . "<th>&nbsp;</th>"
          . "</tr>";

        foreach($weaponArr['inactive'] AS $KEY => $VALUE) {

          $printresult .= "<tr style=\"min-height: 5rem;\">"
          . "<td>".$VALUE['label']."</td>"
          . "<td>".$VALUE['barcode']."</td>"
          . "<td>".$VALUE['model']."</td>"
          . "<td>".$VALUE['type']."</td>"
          . "<td><a class=\"button\" href=\"damaged.php?repair=true&key=".$KEY."\"><i class=\"fa fa-legal\"></i>&nbsp;REPAIR</a></td>";
          $printresult .= "</tr>";

        }

        $printresult .= "</table>";

      } else {
        $printresult = "<h2><i class=\"fa fa-check\"></i>&nbsp;No weapons appear to be out of order. Great job!</h2>";
      }

      echo $printresult;
    ?>
  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
