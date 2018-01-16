<?php
if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  loginRequired();

  $_MODULES["current"]["module"] = "gear exchange";
  $_MODULES["current"]["page"] = "Deploy";



  if(isset($_POST['deployGear']['barcode']) && $_POST['deployGear']['barcode'] != "") {
    $barcode = $_POST['deployGear']['barcode'];
  } else if(isset($_GET["co"]) && $_GET["co"] != "") {
    $barcode = $_GET["co"];
  } else {
    $barcode = "";
  }

  if(isset($_POST['deployGear']) && $_POST['deployGear'] != "") {
    $POSTDATA = $_POST['deployGear'];

    if(isset($POSTDATA['barcode']) && $POSTDATA['barcode'] != "") {

      $sql = "SELECT id FROM ar_weapons WHERE barcode = '".mysqli_real_escape_string($UPLINK,$POSTDATA['barcode'])."' AND loan_status = 'false' LIMIT 1";
      $result = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));

      if(mysqli_num_rows($result) > 0) {

        $WEAPON_ID = (mysqli_fetch_assoc($result)['id']);

        if(!isset($POSTDATA['description'])) {
          $POSTDATA['description'] = "";
        }


        if(isset($POSTDATA['loaned_to']) && $POSTDATA['loaned_to'] != "") {
          if(isset($POSTDATA['loan_date']) && $POSTDATA['loan_date'] != "") {

            $sql =
            "INSERT INTO ar_loans
            (weapon_id, loaned_to, loan_status, loan_date, description
              ) VALUES (
                '".mysqli_real_escape_string($UPLINK,$WEAPON_ID)."'
                ,'".mysqli_real_escape_string($UPLINK,$POSTDATA['loaned_to'])."'
                ,'out'
                ,'".mysqli_real_escape_string($UPLINK,$POSTDATA['loan_date'])."'
                ,'".mysqli_real_escape_string($UPLINK,$POSTDATA['description'])."'
              );";
            $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));

            // zet wapen op onbeschikbaar
            $sql = "UPDATE ar_weapons SET loan_status = 'true', status = 'In armoury' WHERE id = '".mysqli_real_escape_string($UPLINK,$WEAPON_ID)."' LIMIT 1";
            $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));

            header("location: currently_deployed.php?ref=updated");
            exit();

          } else {
            $ERROR = "<h3>Data missing. It's only human to make these kind of mistakes.</h3>";
          }
        } else {
          $ERROR = "<h3>Data missing. It's only human to make these kind of mistakes.</h3>";
        }

      } else {
        $ERROR = "<h3>Code '".mysqli_real_escape_string($UPLINK,$POSTDATA['barcode'])."' is invalid, or is already deployed.</h3>";
      }


    } else {
      $ERROR = '<h3>Code missing.</h3>';
    }

  }

  // $weaponArr = ar_initWeapons();
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

    <h1>Deploy Weapon</h1>

    <p>.</p>

    <div class="main-row">

      <form id="deployGear" name="deployGear" action="deploy.php?ref=form&amp;co=<?=$barcode?>" method="post">

        <div class="row">
          <label>Code</label>
        </div>

        <div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="deployGear[barcode]" value="<?=$barcode?>" placeholder="A451E" required="required" max="10" />
        </div>

        <div class="row">
          <label>Who?</label>
        </div>

        <div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="deployGear[loaned_to]" placeholder="Mayumi Magalit Subal" value="" required="required" max="50" />
        </div>

        <div class="row">
          <label>When?</label>
        </div>

        <div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="deployGear[loan_date]" placeholder="31-01-240NT 23:59" value="" required="required" />
        </div>

        <div class="row">
          <label>Details</label>
        </div>

        <div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="deployGear[description]" placeholder="Optional details" value="" />
        </div>

        <div class="row"></div>

        <div class="row">
          <!-- <a class="button button-default" onclick="$('#deployGear').submit();">Return gear to inventory.</a> -->
          <input type="submit" name="deployGear[submit]" value="Confirm deployment." class="button button-default">
        </div>


      </form>

    </div>

  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
