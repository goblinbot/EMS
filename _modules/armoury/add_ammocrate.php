<?php

if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  loginRequired();

  $_MODULES["current"]["module"] = "armoury";
  $_MODULES["current"]["page"] = "add_ammocrate";



  if(isset($_POST['addAmmoCrate']) && $_POST['addAmmoCrate'] !="") {

    $ADD = $_POST['addAmmoCrate'];

   if(!isset($ADD['description'])) {
        $ADD['description'] = "";
      }

      $sql =
      "INSERT INTO ar_ammoboxes
      (ammobox_type,variant,description,amount
        ) VALUES (
          '".mysqli_real_escape_string($UPLINK,$ADD['ammobox_type'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['variant'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['description'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['amount'])."'
          );";
      $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));

      header("location: ammocrate.php?ref=updated");
      exit();

    }

  else {

    $ADD = array();
    $ADD['ammobox_type'] = '';
    $ADD['variant'] = '';
    $ADD['description'] = '';
    $ADD['amount'] = '';
  }


  $ammoArr = ar_initAmmoBox();
  $ammoboxtypes = ar_initAmmoBoxTypes();
  $ammoboxcategories = ar_initAmmoBoxCategories();
  $ammoboxvariants = ar_initAmmoBoxVariants();

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

    <form id="addAmmoCrate" name="addAmmoCrate" method="post" action="add_ammocrate.php?ref=form">
    <div class="main-row">

      <div class="row">
        <h1>Add new AmmoCrate</h1>
      </div>
      <div class="row">
        <hr/>
      </div>
      <div class="row">
    </div>
      <div class="row">
     <label>Type</label>&nbsp;
        <select name="addAmmoCrate[category]" style="max-width: 15rem;">
         <?php
            foreach($ammoboxcategories as $ammoboxtype) {
              echo "<option value=\"{$ammoboxtype['type']}\">{$ammoboxtype['type']}</option>"; 
            };
          ?> 
        </select>
      </div> 
    
      <div class="row">
        <label>Name</label>&nbsp;
        <select name="addAmmoCrate[ammobox_type]" style="max-width: 15rem;">
          <?php
            foreach($ammoboxtypes as $ammoboxtype) {
              echo "<option value=\"{$ammoboxtype['typeid']}\">{$ammoboxtype['name']}</option>"; 
            };
          ?>
        </select>
      </div>
      
      <div class="row">
        <label>Variant</label>&nbsp;
        <select name="addAmmoCrate[variant]" style="max-width: 15rem;">
          <?php
            foreach($ammoboxvariants as $ammoboxvariant) {
              echo "<option value=\"{$ammoboxvariant['id']}\">{$ammoboxvariant['name']}</option>"; 
            };
          ?>
        </select>
      </div>
      <div class="row">
        <label>Description</label>
      </div>
      <div class="row">
        <textarea name="addAmmoCrate[description]" placeholder="optional description"></textarea>
      </div>

      <div class="row">
        <label>Amount</label>&nbsp;
        <input
        type="number" class="numbers" style="max-width: 15rem;"
           required="required" value="<?=$ADD['amount']?>"
          name="addAmmoCrate[amount]" placeholder="50"
        />
      </div>

      <div class="row">
        <input type="submit" value="Add AmmoCrate to Inventory" class="button">
      </div>

    </div>
    </form>

  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
