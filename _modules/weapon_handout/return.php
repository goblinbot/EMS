<?php
if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  include_once($_CONFIG["root"] . "/_includes/date.php");
  loginRequired();

  $_MODULES["current"]["module"] = "gear exchange";
  $_MODULES["current"]["page"] = "Return";

  include_once($_CONFIG["root"] . "/header.php");


  if(isset($_POST['returnGear']) && $_POST['returnGear'] != "") {

    if(!isset($_POST['returnGear']['description'])) {
      $_POST['returnGear']['description'] = "";
    }

    if($_POST['returnGear']['barcode'] != "" && $_POST['returnGear']['return_date'] != "") {

      $sql = "SELECT id FROM ar_weapons WHERE barcode = '".mysqli_real_escape_string($UPLINK,$_POST['returnGear']['barcode'])."' AND loan_status = 'true' LIMIT 1";
      $result = $UPLINK->query($sql);

      if(mysqli_num_rows($result) > 0) {

        $result = mysqli_fetch_assoc($result)['id'];

        $WEAPON_ID = $result;

        $sql = "SELECT id FROM ar_loans_weapon WHERE weapon_id = '".$result."' AND loan_status = 'out' ORDER BY id DESC LIMIT 1";
        $result = $UPLINK->query($sql);

        if(mysqli_num_rows($result) > 0) {

          $result = mysqli_fetch_assoc($result)['id'];

          // zet de loan op voltooid
          $sql = "UPDATE ar_loans_weapon
                  SET return_date = '".mysqli_real_escape_string($UPLINK,$_POST['returnGear']['return_date'])."'
                    , description = '".mysqli_real_escape_string($UPLINK,$_POST['returnGear']['description'])."'
                    , loan_status = 'done'
                    WHERE id = '".mysqli_real_escape_string($UPLINK,$result)."'
                    LIMIT 1";
          $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));


          // zet wapen op beschikbaar
          $sql = "UPDATE ar_weapons SET loan_status = 'false', status = 'In armoury' WHERE id = '".mysqli_real_escape_string($UPLINK,$WEAPON_ID)."' LIMIT 1";
          $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));

          header("location: history.php?ref=updated");
          exit();


        } else {
          $ERROR = "<h3>Invalid input. Weapon is not deployed.</h3>";
        }

      } else {
        $ERROR = "<h3>Invalid input. Weapon does not exist.</h3>";
      }

    } else {
      $ERROR = "<h3>Invalid input. Data missing.</h3>";
    }

  }

  $loanArr = ar_getLoans('out');

  if(isset($_POST['returnGear']['barcode']) && $_POST['returnGear']['barcode'] != "") {
    $barcode = $_POST['returnGear']['barcode'];
  } else if(isset($_GET["co"]) && $_GET["co"] != "") {
    $barcode = $_GET["co"];
  } else {
    $barcode = "";
  }

  if(isset($_GET["key"]) && $_GET["key"] != "") {

    if(isset($loanArr[$_GET["key"]]["description"]) && $loanArr[$_GET["key"]]["description"] != "") {
      $description = $loanArr[$_GET["key"]]["description"];
    } else {
      $description = "";
    }
  } else {
    $description = "";
  }

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

    <h1>Return gear</h1>

    <p>Deployed gear can be returned by filling in the code.</p>

    <div class="main-row">

      <form id="returnGear" name="returnGear" action="return.php?ref=form&amp;co=<?=$barcode?>" method="post">

        <div class="row">
          <label>Code</label>
        </div>

        <div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="returnGear[barcode]" value="<?=$barcode?>" placeholder="A451E" required="required" max="10" autofocus/>
        </div>

        <div class="row">
          <label>Return date</label>
        </div>

        <div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="returnGear[return_date]" placeholder="" value="<?=$DateTime?>" required="required" />
        </div>

        <div class="row">
          <label>Details</label>
        </div>

        <div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="returnGear[description]" placeholder="Optional comment" value="<?=$description?>" />
        </div>

        <div class="row"></div>

        <div class="row">
          <!-- <a class="button button-default" onclick="$('#returnGear').submit();">Return gear to inventory.</a> -->
          <input type="submit" name="returnGear[submit]" value="Return Gear" class="button button-default">
          <a href="weap_currently_deployed.php?ref=cancelreturn" class="button button-default">Cancel</a>
        </div>


      </form>

    </div>

  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
