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
    // echo"<pre style=\"color:white;\">";var_dump($_POST['addAmmoCrate']);echo"</pre>";

    $ADD = $_POST['addAmmoCrate'];

   if(!isset($ADD['description'])) {
        $ADD['description'] = "";
      }

      $sql =
      "INSERT INTO ar_ammoboxes
      (name,amount,description,type
        ) VALUES (
          '".mysqli_real_escape_string($UPLINK,$ADD['name'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['amount'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['description'])."'
          ,'".mysqli_real_escape_string($UPLINK,$ADD['type'])."'
          );";
      $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));

      header("location: ammocrate.php?ref=updated");
      exit();

    }

  else {

    $ADD = array();
    $ADD['name'] = '';
    $ADD['amount'] = '';
    $ADD['description'] = '';

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

    <form id="addAmmoCrate" name="addAmmoCrate" method="post" action="add_ammocrate.php?ref=form">
    <div class="main-row">

      <div class="row">
        <h1>Add new AmmoCrate</h1>
      </div>
      <div class="row">
        <hr/>
      </div>

      <div class="row">
        <label>Name</label>
      </div>
      <div class="row">
        <input
          type="text" class="textinput" style="max-width: 15rem;"
          max="49" required="required" value="<?=$ADD['name']?>"
          name="addAmmoCrate[name]" placeholder="20 round magazine"
        />
      </div>

      <div class="row">
        <label>Amount</label>
      </div>
      <div class="row">
        <input
          type="text" class="textinput" style="max-width: 15rem;"
          max="24" required="required" value="<?=$ADD['amount']?>"
          name="addAmmoCrate[amount]" placeholder="50"
        />
      </div>

      <div class="row">
        <label>Type</label>
      </div>
      <div class="row">
        <select name="addAmmoCrate[type]" style="max-width: 15rem;">
          <option value="Magazine">Magazine</option>
          <option value="explosives">Explosives</option>
          </select>
      </div>
 
      <div class="row">
        <label>Description</label>
      </div>
      <div class="row">
        <textarea name="addAmmoCrate[description]" placeholder="optional description"></textarea>
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
