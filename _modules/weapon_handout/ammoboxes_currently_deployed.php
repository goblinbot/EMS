<?php
if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  loginRequired();

  $_MODULES["current"]["module"] = "Gear Exchange";
  $_MODULES["current"]["page"] = "Ammo Currently Deployed";



  if(isset($_POST['updateDeployed']) && $_POST['updateDeployed'] != "") {

    if(validateCurrentUser('armoury') == true) {

      foreach($_POST['updateDeployed'] AS $ID => $update) {

        if(is_numeric($ID)) {
          $sql = "UPDATE ar_loans_ammoboxes
                  SET loaned_to = '".mysqli_real_escape_string($UPLINK,$update['loaned_to'])."'
                    WHERE id = '".mysqli_real_escape_string($UPLINK,$ID)."'
                    LIMIT 1";
          $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));
        }

      }

      header("location: ammoboxes_currently_deployed.php?ref=update");
      exit();

    } else {

      header("location: ".$_CONFIG["header"]."/?error=noAuth");
      exit();
    }

  }


  $loanArr = ar_getAmmoBoxLoans("out");
  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">
  <div class="container">

    <h1>AmmoBoxes Currently deployed</h1>

    <?php
      if(isset($loanArr) && $loanArr != "") {

        $printresult = "<p><a class=\"button button-default\" onclick=\"$('#updateDeployed').submit();\"><i class=\"fa fa-save\"></i>&nbsp;Save changes</a>";
        $printresult .= "&nbsp;&nbsp;<a href=\"weapon_deploy.php\" class=\"button button-default\">Deploy</a>";
        //$printresult .= "&nbsp;&nbsp;<a href=\"weapon_return.php\" class=\"button button-default\">Return</a>";
        $printresult .= "</p><br/><table class=\"table\">";
        $printresult .= "<form id=\"updateDeployed\" name=\"updateDeployed\" action=\"ammoboxes_currently_deployed.php?ref=subm\" method=\"post\">";

        $printresult .= "<thead><tr>";
        $printresult .=
           "<th>Code</th>"
          ."<th>Type</th>" 
          ."<th>Name</th>"
          ."<th>Variant</th>"
          ."<th>Capacity</th>" 
          ."<th>Deployed To</th>"
          ."<th>Deployed With</th>"
          ."<th>Qty</th>"
          ."<th>Since</th>"
          ."<th>&nbsp;</th>";
        $printresult .= "</tr></thead>";

        // echo "<pre>"; var_dump($loanArr); echo "</pre>";
        foreach($loanArr AS $KEY => $LOAN ) {

          $printresult .= "<tr>"
            . "<td>".$LOAN['abid']."</td>"
            . "<td>".$LOAN['abtype']."</td>"
            . "<td>".$LOAN['abtname']."</td>"
            . "<td>".$LOAN['abvariant']."</td>"
            . "<td>".$LOAN['capacity']."</td>"
            . "<td>".$LOAN['loaned_to']."</td>"
            . "<td>".$LOAN['deployed_with']."</td>"
            . "<td>".$LOAN['qty']."</td>"
            . "<td>".$LOAN['loan_date']."</td>"
            . "<td class=\"text-right\">"
              ."<a class=\"button\" style=\"padding: 4px 8px; border-radius: 1px;\" href=\"".$_CONFIG["header"]."/_modules/weapon_handout/return_ammobox.php?co=".$LOAN['loan_id']."&key=".$KEY."\" title=\"return\">"
                ."<i class=\"fa fa-check\"></i>&nbsp;RETURN"
              ."</a>"

            ."</td>"
          ."</tr>";
        }

        $printresult .= "</form>";
        $printresult .= "</table>";
        echo $printresult;
      } else {

        echo "<h3>No weapons currently deployed.</h3>";
      }
    ?>

  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
