<?php
if(!isset($_SESSION)) {
  session_start();
}

// echo "<pre>";var_dump($_SESSION);echo "</pre>";

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  loginRequired();


  $_MODULES["current"]["module"] = "settings";
  $_MODULES["current"]["page"] = "Usergroups";


  if(isset($_POST['updateUsergroups']) && $_POST['updateUsergroups'] != "") {

    if(validateCurrentUser('admin') == true) {

      foreach($_POST['updateUsergroups'] AS $ID => $Usergroupsupdate) {

        if(is_numeric($Usergroupsupdate['group_id']) && is_numeric($ID)) {
          $sql = "UPDATE user_koppeling
                  SET user_koppeling = '".mysqli_real_escape_string($UPLINK,(int)$Usergroupsupdate['group_id'])."'
                    WHERE id = '".mysqli_real_escape_string($UPLINK,$ID)."'
                    LIMIT 1";
          $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));
        }

      }

      header("location: ".$_CONFIG["header"]."/_modules/settings/usergroups.php?ref=update");
      exit();

    } else {

      header("location: ".$_CONFIG["header"]."/?error=noAuth");
      exit();
    }


  }



  $userArr = ar_initUsergroups();
  
  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">
  <div class="container">

    <h1>Usergroups: View &amp; Update</h1>

    <?php
    if(isset($userArr) && $userArr != "") {

      $printresult = "<form id=\"UsergroupsForm\" action=\"usergroups.php?ref=edit\" method=\"post\" name=\"updateUsergroups\" >";

      $printresult .= "<button onclick=\"$('#UsergroupsForm').submit();\" class=\"button button-default\"><i class=\"fa fa-save\"></i>&nbsp;Save Usergroups</button>";
      // $printresult .= "&nbsp;&nbsp;<a href=\"ammocrate_add.php?ref=crate\" class=\"button button-default\"><i class=\"fa fa-plus-square\"></i>&nbsp;New</a>";

      $printresult .= "<br/><hr/><br/>";

      $printbox = "";

       foreach($userArr AS $TITLE => $CATEGORIE) {

        if($printbox === "") {
          $class = "tab on";
        } else {
          $class = "tab";
        }

        // tabjes maken voor top
        $printresult .= "<a onclick=\"activateTab('".$TITLE."');\" class=\"button button-tab\"><i class=\"fa fa-angle-down\"></i>&nbsp;".$TITLE."</a>";

        // de tables bouwen.
        $printbox .= "<div id=\"tab_".$TITLE."\" class=\"ammobox $class\">";
        // $printresult .= "<h3 class=\"\">".$TITLE."</h3>";
        $printbox .= "<table class=\"table\">";
        $printbox .= "<thead>"
                        ."<th>Name</th>"
                        ."<th>Group</th>"
                        ."<th>&nbsp;</th>"
                      ."</tr>"
                      ."</thead>";

        foreach($CATEGORIE AS $KEY => $VALUE) {

          $printbox .= "<tr>"
              //. "<td style=\"max-width: 4rem;\">".$VALUE['id']."</td>"
              . "<td style=\"max-width: 4rem;\">".$VALUE['fullname']."</td>"
              . "<td>"
              ."<input type=\"hidden\" class=\"hidden\" name=\"updateInventory[".$KEY."][id]\" value=\"".$VALUE['id']."\"/>"
              ."<input type=\"hidden\" class=\"hidden\" name=\"updateInventory[".$KEY."][fullname]\" value=\"".$VALUE['fullname']."\"/>"
              ."<select name=\"updateUsergroups[".$KEY."][group_id]\" value=\"".$VALUE['group_id']."\>
                <option value=\"312\">Admin<\/option>
                <option value=\"318\">Armoury<\/option>
                <\/select>"           
              ."</td>"
            . "</tr>";
        }

        $printbox .= "</table>";
        $printbox .= "</div>";
      }

      // meng de boxes en printresult
      $printresult .= $printbox;

      $printresult .= "<br/><button class=\"button\"><i class=\"fa fa-save\"></i>&nbsp;Save Usergroups</button>";

      $printresult .= "</form>";
      echo $printresult;
    }
    ?>

  </div>
</div>
<?php
include_once($_CONFIG["root"] . "/footer.php");
