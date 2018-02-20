<?php
if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  loginRequired();

  $_MODULES["current"]["module"] = "armoury";
  $_MODULES["current"]["page"] = "add_weapon";



  if(isset($_POST['addWeapon']) && $_POST['addWeapon'] !="") {
    // echo"<pre style=\"color:white;\">";var_dump($_POST['addWeapon']);echo"</pre>";

    $ADD = $_POST['addWeapon'];

    $sql = "SELECT barcode FROM ar_weapons WHERE barcode = '".$ADD['barcode']."' LIMIT 1";
    $result = $UPLINK->query($sql);

    if(mysqli_num_rows($result) > 0) {

      $ERROR = "<h2>Code already exists.</h2><p>We are sorry for the inconvenience.</p>";

    } else {

      if(!isset($ADD['description'])) {
        $ADD['description'] = "";
      }

      $sql =
      "INSERT INTO ar_weapons
      (label, type, skilltype, barcode, model, description, OC_owner, status, loan_status
        ) VALUES (
          '".mysqli_real_escape_string($UPLINK,$ADD['label'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['type'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['skilltype'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['barcode'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['model'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['description'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['OC_owner'])."'
          ,'In armoury'
          ,'false'
        );";
      $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));

      header("location: index.php?ref=updated");
      exit();

    }

  } else {

    $ADD = array();
    $ADD['label'] = '';
    $ADD['type'] = '';
    $ADD['model'] = '';

  }



  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">
  <div class="container">

    <?php
      if(isset($ERROR) && $ERROR != "") {
        echo "<div class=\"alert-dialog\">"
        . $ERROR
        . "</div>";
      }
    ?>

    <form id="addWeapon" name="addWeapon" method="post" action="add_weapon.php?ref=form">
    <div class="main-row">

      <div class="row">
        <h1>Add new weapon</h1>
      </div>
      <div class="row">
        <hr/>
      </div>

      <div class="row">
        <label>Title</label>
      </div>
      <div class="row">
        <input
          type="text" class="textinput" style="max-width: 15rem;"
          max="49" required="required" value="<?=$ADD['label']?>"
          name="addWeapon[label]" placeholder="Intara direction-action"
        />
      </div>

      <div class="row">
        <label>type</label>
      </div>
      <div class="row">
        <input
          type="text" class="textinput" style="max-width: 15rem;"
          max="24" required="required" value="<?=$ADD['type']?>"
          name="addWeapon[type]" placeholder="SMG"
        />
      </div>

      <div class="row">
        <label>skilltype</label>
      </div>
      <div class="row">
        <select name="addWeapon[skilltype]" style="max-width: 15rem;">
          <option value="ranged">Ranged</option>
          <option value="melee">Melee</option>
          <option value="protection">Protection</option>
        </select>
      </div>

      <?php
        //random barcode.
        $randomKeys = array("0","1","2","3","4","5","6","7","8","9","0","A","B","C","D","E","F");

        $BARCODE = "";
        for($i = 0; $i < 5; $i++){
          $KEY[$i] = $randomKeys[array_rand($randomKeys, 1)];

          $BARCODE .= $KEY[$i];
        }

      ?>
      <div class="row">
        <label>Code</label>
      </div>
      <div class="row">
        <input
          type="text" class="textinput" style="max-width: 15rem;"
          max="9" required="required"
          value="<?=$BARCODE?>"
          name="addWeapon[barcode]"
        />
      </div>

      <!-- ammotype -->

      <div class="row">
        <label>Model</label>
      </div>
      <div class="row">
        <input
          type="text" class="textinput" style="max-width: 15rem;"
          max="24" required="required" value="<?=$ADD['model']?>"
          name="addWeapon[model]" placeholder="Stryfe"
        />
      </div>

      <div class="row">
        <label>Description</label>
      </div>
      <div class="row">
        <textarea name="addWeapon[description]" placeholder="optional description"></textarea>
      </div>


      <div class="row">
        <label>((OC OWNER))</label>
      </div>
      <div class="row">
        <input
          type="text" class="textinput" style="max-width: 15rem;"
          max="24" required="required"
          name="addWeapon[OC_owner]"
        />
      </div>

      <div class="row">
        <input type="submit" value="Add weapon to arsenal" class="button">
      </div>

    </div>
    </form>

  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
