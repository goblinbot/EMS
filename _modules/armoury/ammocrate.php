<?php
if(!isset($_SESSION)) {
  session_start();
}

// echo "<pre>";var_dump($_SESSION);echo "</pre>";

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  loginRequired();


  $_MODULES["current"]["module"] = "armoury";
  $_MODULES["current"]["page"] = "ammocrate";


  if(isset($_POST['updateInventory']) && $_POST['updateInventory'] != "") {

    if(validateCurrentUser('armoury') == true) {

      foreach($_POST['updateInventory'] AS $ID => $inventoryupdate) {

        if(is_numeric($inventoryupdate['amount']) && is_numeric($ID)) {
          $sql = "UPDATE ar_ammoboxes
                  SET amount = '".mysqli_real_escape_string($UPLINK,(int)$inventoryupdate['amount'])."'
                    , description = '".mysqli_real_escape_string($UPLINK,$inventoryupdate['description'])."'
                    WHERE id = '".mysqli_real_escape_string($UPLINK,$ID)."'
                    LIMIT 1";
          $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));
        }

      }

      header("location: ".$_CONFIG["header"]."/_modules/armoury/ammocrate.php?ref=update");
      exit();

    } else {

      header("location: ".$_CONFIG["header"]."/?error=noAuth");
      exit();
    }


  }



  $ammoArr = ar_initAmmoBox();
  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">
  <div class="container">

    <h1>AmmoCrate: Supplies &amp; Essentials</h1>

    <?php
    if(isset($ammoArr) && $ammoArr != "") {

      $printresult = "<form id=\"inventoryForm\" action=\"ammocrate.php?ref=edit\" method=\"post\" name=\"updateInventory\" >";

      $printresult .= "<button onclick=\"$('#inventoryForm').submit();\" class=\"button button-default\"><i class=\"fa fa-save\"></i>&nbsp;Save Inventory</button>";
      // $printresult .= "&nbsp;&nbsp;<a href=\"ammocrate_add.php?ref=crate\" class=\"button button-default\"><i class=\"fa fa-plus-square\"></i>&nbsp;New</a>";

      $printresult .= "<br/><hr/><br/>";

      $printbox = "";

      foreach($ammoArr AS $TITLE => $CATEGORIE) {

        if($printbox === "") {
          $class = "tab on";
        } else {
          $class = "tab";
        }

        // tabjes maken voor top
        $printresult .= "<a onclick=\"activateTab('".$TITLE."');\" class=\"button button-tab\"><i class=\"fa fa-angle-down\"></i>&nbsp;".$TITLE."</a>";

        // de tables bouwen.
//        $printbox .= "<div id=\"tab_".$TITLE."\" class=\"ammobox $class\">";
        // $printresult .= "<h3 class=\"\">".$TITLE."</h3>";
        $printbox .= "<table class=\"table\">";
        $printbox .= "<thead>"
                        //."<tr class=\"\"><th colspan=\"3\"><h3>".$TITLE."</h3></th></tr>"
                        ."<th>id</th>"
                        ."<th>Name</th>"
                        ."<th>Quantity</th>"
                        ."<th>Description</th>"
                        ."<th>&nbsp;</th>"
                      ."</tr>"
                      ."</thead>";

        foreach($CATEGORIE AS $KEY => $VALUE) {

          $printbox .= "<tr>"
          . "<td style=\"max-width: 4rem;\">".$VALUE['id']."</td>"
            . "<td style=\"max-width: 4rem;\">".$VALUE['name']."</td>"
              . "<td>"
                ."<input type=\"hidden\" class=\"hidden\" name=\"updateInventory[".$KEY."][name]\" value=\"".$VALUE['name']."\"/>"
                ."<input type=\"hidden\" class=\"hidden\" name=\"updateInventory[".$KEY."][id]\" value=\"".$VALUE['id']."\"/>"
                ."<input type=\"number\" class=\"numbers\" name=\"updateInventory[".$KEY."][amount]\" required minimum=\"0\" value=\"".$VALUE['amount']."\"/>"
              ."</td>"
            . "<td style=\"width:50%;\">"
            .   "<input type=\"text\" class=\"textinput\" name=\"updateInventory[".$KEY."][description]\" value=\"".$VALUE['description']."\" \>"
            . "</td>"
          . "</tr>";
        }

        $printbox .= "</table>";
        $printbox .= "</div>";
      }

      // meng de boxes en printresult
      $printresult .= $printbox;

      $printresult .= "<br/><button class=\"button\"><i class=\"fa fa-save\"></i>&nbsp;Save Inventory</button>";

      $printresult .= "</form>";
      echo $printresult;
    }
    ?>

  </div>
</div>
<?php
include_once($_CONFIG["root"] . "/footer.php");
