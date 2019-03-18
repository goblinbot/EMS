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
  $_MODULES["current"]["page"] = "AmmoReturn";

  include_once($_CONFIG["root"] . "/header.php");
  
  
  if(isset($_POST['returnGear']) && $_POST['returnGear'] != ""){
    $POSTDATA = $_POST['returnGear'];
    $sql = "SELECT id,qty FROM ar_loans_ammoboxes WHERE id = '".mysqli_real_escape_string($UPLINK,$POSTDATA['loan_id'])."'";
    $result = $UPLINK->query($sql);
    $quantity = (mysqli_fetch_assoc($result)['qty']);
    if ($quantity < $POSTDATA['qty']){
      $ERROR = "<h3>Quantity to check exceeds actual checked out quantity. Please try again.</h3>";
      } else {
        $sql = "UPDATE ar_ammoboxes SET amount = (amount + '".mysqli_real_escape_string($UPLINK,$POSTDATA['qty'])."') WHERE id = '".mysqli_real_escape_string($UPLINK,$POSTDATA['ammobox_id'])."' LIMIT 1;";
        $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));

        if($POSTDATA['qty'] == $quantity){
            //add the qty back to the ar_ammoboxes item
            $sql = "UPDATE ar_loans_ammoboxes SET qty = 0
                    , return_date = '".mysqli_real_escape_string($UPLINK,$POSTDATA['return_date'])."'
                    , loan_status = 'done'
                    WHERE id = '".mysqli_real_escape_string($UPLINK,$POSTDATA['loan_id'])."' LIMIT 1;";
                    $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));
        }
        if($POSTDATA['qty'] < $quantity ){
              //add the qty back to the ar_ammoboxes item
              $sql = "UPDATE ar_loans_ammoboxes SET qty = (qty - '".mysqli_real_escape_string($UPLINK,$POSTDATA['qty'])."') 
                      WHERE id = '".mysqli_real_escape_string($UPLINK,$POSTDATA['loan_id'])."' LIMIT 1;";
              $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));	
        }
        header("location: ammoboxes_currently_deployed.php?ref=updated");
        exit(); 
      }     
    } 
    

  $loanArr = ar_getAmmoBoxLoans('out');

  if(isset($_POST['returnGear']['loan_id']) && $_POST['returnGear']['loan_id'] != "") {
    $POSTDATA = $_POST['returnGear'];
    $barcode = $_POST['returnGear']['loan_id'];
  } else if(isset($_GET["co"]) && $_GET["co"] != "") {
    $barcode = $_GET["co"];
  } else {
    $barcode = "";
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

    <h1>Return AmmoCrates</h1>

    <p>Deployed ammoboxes can be returned by filling in the code.</p>
    
    <div class="main-row">

      <form id="returnGear" name="returnGear" action="ammobox_return.php?ref=form&amp;co=<?=$barcode?>&amp;key=<?=$_GET["key"]?>" method="post">

        <div class="row">
          <label>AmmoBox Loan ID</label>
    </div><div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="returnGear[loan_id]" value="<?=$_GET["key"]?>" required="required" max="10" autofocus/>        </div>
          <div class="row">
          <label>AmmoBox Type ID</label>
    </div><div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="returnGear[ammobox_id]" value="<?=$_GET["co"]?>" required="required" max="10" autofocus/>        </div>
          <div class="row">
          <label>Return date</label>
          </div><div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="returnGear[return_date]" placeholder="" value="<?=$DateTime?>" required="required" />
    </div>

          <div class="row">
          <label>Quantity</label>
          </div><div class="row">
        <input type="number" class="numbers" style="max-width: 5rem;" required name="returnGear[qty]" placeholder="0" value="<?=$_GET["qty"]?>" min="1"/>
        </div>

        <div class="row"></div>

        <div class="row">
          <input type="submit" name="returnGear[submit]" value="Return Ammobox" class="button button-default">
          <a href="ammoboxes_currently_deployed.php?ref=cancelreturn" class="button button-default">Cancel</a>
        </div>


      </form>

    </div>

  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
