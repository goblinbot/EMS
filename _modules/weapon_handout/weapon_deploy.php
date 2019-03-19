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

            $sql = "INSERT INTO ar_loans_weapon
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
            $sql = "UPDATE ar_weapons SET loan_status = 'true', status = 'Deployed' WHERE id = '".mysqli_real_escape_string($UPLINK,$WEAPON_ID)."' LIMIT 1";
            $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));
            //Setup ammocrate loan
            if(isset($POSTDATA['abid']) && $POSTDATA['abid'] != "") {

                $sql = "INSERT INTO ar_loans_ammoboxes
                        (ammobox_id, loaned_to, loan_status, loan_date, qty,assoc_weapon_loan
                        ) VALUES (
                            '".mysqli_real_escape_string($UPLINK,$POSTDATA['abid'])."'
                            ,'".mysqli_real_escape_string($UPLINK,$POSTDATA['loaned_to'])."'
                            ,'out'
                            ,'".mysqli_real_escape_string($UPLINK,$POSTDATA['loan_date'])."'
                            ,'".mysqli_real_escape_string($UPLINK,$POSTDATA['qty'])."'
                            ,'".mysqli_real_escape_string($UPLINK,$WEAPON_ID)."'
                            );";
                      $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));
                      // decrease available inventory
                      $sql = "UPDATE ar_ammoboxes SET amount = amount - '".mysqli_real_escape_string($UPLINK,$POSTDATA['qty'])."' WHERE id = '".mysqli_real_escape_string($UPLINK,$POSTDATA['abid'])."' LIMIT 1";
                      $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));
                  }



            header("location: weap_currently_deployed.php?ref=updated");
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
  if($CharGenUPLINK == false){}
    else{$characters = ar_initECCCharacters();}
  $ammoAvail = ar_getAmmoBoxLoanable();
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
     <form id="deployGear" name="deployGear" action="weapon_deploy.php?ref=form&amp;co=<?=$barcode?>" method="post">

      <div class="deploy-forms">
        <div class="main-row">
        <h1>Deploy Weapon</h1>
        <div class="row">
          <label>Code</label>
        </div>

        <div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="deployGear[barcode]" value="<?=$barcode?>" placeholder="A451E" required="required" max="10" autofocus/>
        </div>

        <div class="row">
          <label>Who?</label>
        </div>
        <?php
        if($CharGenUPLINK==false)
        {
          echo "<div class=\"row\">
          No db connection made. You cannot select a character";
        }  else {
            echo "<div class=\"row\">
            <select name=\"deployGear[loaned_to]\" style=\"max-width: 15rem;\" required>
            <option value=\"\">None</option>";
            foreach($characters as $character) {
            echo "<option value=\"{$character['characterID']}\">{$character['character_name']}</option>";
            }
            echo "</select> </div>";
          }
          ?>

        <div class="row">
          <label>When?</label>
        </div>

        <div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="deployGear[loan_date]" placeholder="" value="<?=$DateTime?>" required="required" />
        </div>

        <div class="row">
          <label>Details</label>
        </div>

        <div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="deployGear[description]" placeholder="Optional details" value="" />
        </div>
        </div>
        <div class="main-row">
          <input type="checkbox" name="choice-ammo" id="choice-ammo">
          <label for="choice-ammo">Deploy ammo?</label>
          <div class="reveal-if-active">
              <h1> Ammo Deployment Form</h1>
              <div class="row rowcopy">
                <label>Select Ammo Type</label>
                <select name="deployGear[abid]" class="require-if-active" data-require-pair="#choice-ammo">
                <option value="">None</option>
                  <?php
                    foreach($ammoAvail as $ammochoice) {
                    echo "<option value=\"{$ammochoice['abid']}\">{$ammochoice['inv_item_name']}</option>";
                    };
                  ?>
                </select>
              <label>Amount</label>&nbsp;
              <input type="number" class="numbers" style="max-width: 5rem;" class="require-if-active" data-require-pair="#choice-ammo" value="<?=$ADD['abid']?>" name="deployGear[qty]" placeholder="0" min="1" />
              <div class="actions">
                  <span class="copy">+</span>
                  <span class="remove">-</span>
              </div>
            </div>

        </div>

    </div>
</div>
                </div>
        <div class="row deploy-button-row">
          <!-- <a class="button button-default" onclick="$('#deployGear').submit();">Return gear to inventory.</a> -->
          <input type="submit" name="deployGear[submit]" value="Confirm Deployment" class="button button-default">
          <a href="weap_currently_deployed.php?ref=canceldeploy" class="button button-default">Cancel</a>
        </div>
      </div>
    </div>

    </form>


  </div>
</div>
<script>
    jQuery("body").on("click", ".copy", function(){
        var copy1 = jQuery(".rowcopy:first").clone(true);
        jQuery(copy1).appendTo(".reveal-if-active");
    })

    jQuery("body").on("click", ".remove", function(){
        jQuery(this).closest(".rowcopy").remove();
    })
</script>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
