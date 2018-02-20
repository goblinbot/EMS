<?php
if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  loginRequired();

  $_MODULES["current"]["module"] = "armoury";
  $_MODULES["current"]["page"] = "armoury";


  if(isset($_POST['updateArmoury']) && $_POST['updateArmoury'] != "") {

    if(validateCurrentUser('armoury') == true) {

      foreach($_POST['updateArmoury'] AS $ID => $update) {

        if(!isset($update['status']) || $update['status'] == "") {
          $update['status'] = "In armoury";
        }

        if(is_numeric($ID)) {
          $sql = "UPDATE ems_ar_weapons
                  SET status = '".mysqli_real_escape_string($UPLINK,$update['status'])."'
                    WHERE id = '".mysqli_real_escape_string($UPLINK,$ID)."'
                    LIMIT 1";
          $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));
        }
      }

      header("location: index.php?ref=update");
      exit();

    } else {

      header("location: ".$_CONFIG["header"]."/?error=noAuth");
      exit();
    }

  }


  $weaponArr = ar_initWeapons();
  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">
  <div class="container">

    <h1>Armoury: Weapon inventory</h1>

    <p>&nbsp;</p>

    <hr/>

    <p>&nbsp;</p>
    <?php

    $printresult = "";

    if(isset($weaponArr) && $weaponArr != "") {

      $printresult .= "<form id=\"updateArmoury\" name=\"updateArmoury\" action=\"index.php?ref=butt\" method=\"post\">";

      foreach($weaponArr AS $TITLE => $CATEGORIE) {

        if(!isset($printbox) || $printbox == "") {
          $class = "tab on";
        } else {
          $class = "tab";
        }

        // tabjes maken voor top
        $printresult .= "<a onclick=\"activateTab('".$TITLE."');\" class=\"button button-tab\"><i class=\"fa fa-angle-down\"></i>&nbsp;".$TITLE."</a>";

        // de tables bouwen.
        $printbox .= "<div id=\"tab_".$TITLE."\" class=\"weapondbbox $class\">";
        $printbox .= "<table class=\"table\">";
        $printbox .= "<thead>"
                      ."<tr class=\"\">"
                        ."<th colspan=\"\">#".$TITLE."</th>"
                        ."<th>Title</th>"
                        ."<th>Type</th>"
                        ."<th>Model</th>"
                        ."<th>Status</th>"
                        ."<th>Description</th>"
                        ."<th class=\"oc\">OC owner</th>"
                        ."<th>&nbsp;</th>"
                      ."</tr>"
                      ."</thead>";

          foreach($CATEGORIE AS $KEY => $VALUE) {

            if($VALUE['loan_status'] == 'false') {
              $BUTTONDEPLOY  = "<a class=\"button button-default\" title=\"save all\" style=\"padding: 4px 8px; border-radius: 1px;\" onclick=\"$('#updateArmoury').submit();\"><i class=\"fa fa-save\"></i></a>&nbsp;";
              $BUTTONDEPLOY .= "<a class=\"button\" style=\"padding: 4px 8px; border-radius: 1px;\" href=\"".$_CONFIG["header"]."/_modules/weapon_handout/deploy.php?co=".$VALUE['barcode']."&key=".$KEY."\" title=\"deploy weapon\">"
                ."DEPLOY"
              ."</a>";
            } else {
              $BUTTONDEPLOY = "Deployed.";
            }

            $printbox .= "<tr>"
              . "<td>".$VALUE['barcode']."</td>"
              . "<td>".$VALUE['label']."</td>"
              . "<td>".$VALUE['type']."</td>"
              . "<td>".$VALUE['model']."</td>"
              . "<td><input type=\"text\" class=\"textinput\" name=\"updateArmoury[".$VALUE['id']."][status]\" value=\"".$VALUE['status']."\"/></td>"
              . "<td>".$VALUE['description']."</td>"
              . "<td class=\"oc\">".$VALUE['OC_owner']."</td>"
              . "<td>"
                . $BUTTONDEPLOY
              ."</td>"
            . "</tr>";

          }

          $printbox .= "</table>";
          $printbox .= "</div>";
        }

        $printbox .= "</div>";

      }

    $printresult .= $printbox;
    $printresult .= "</form>";

    echo $printresult;
    ?>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
