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
  if($CharGenUPLINK == false){}
    else{$characters = ar_initECCCharacters();}
  
  
  if(isset($_POST['deployGear']) && $_POST['deployGear'] != ""){
    $POSTDATA = $_POST['deployGear'];
    $sql = "SELECT id,amount FROM ar_ammoboxes WHERE id = '".mysqli_real_escape_string($UPLINK,$POSTDATA['ammobox_id'])."'";
    $result = $UPLINK->query($sql);
    $quantity = (mysqli_fetch_assoc($result)['amount']);
    if ($quantity < $POSTDATA['qty']){
      $ERROR = "<h3>Quantity to deploy exceeds available quantity. Please try again.</h3>";
    }
    else{
        $sql = "INSERT INTO ar_loans_ammoboxes
                (ammobox_id, loaned_to, loan_status, loan_date, qty) 
                VALUES ('".mysqli_real_escape_string($UPLINK,$POSTDATA['ammobox_id'])."'
                ,'".mysqli_real_escape_string($UPLINK,$POSTDATA['loaned_to'])."'
                ,'out'
                ,'".mysqli_real_escape_string($UPLINK,$POSTDATA['loan_date'])."'
                ,'".mysqli_real_escape_string($UPLINK,$POSTDATA['qty'])."'
                );";
        $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));
        // decrease available inventory
        $sql = "UPDATE ar_ammoboxes SET amount = amount - '".mysqli_real_escape_string($UPLINK,$POSTDATA['qty'])."' WHERE id = '".mysqli_real_escape_string($UPLINK,$POSTDATA['ammobox_id'])."' LIMIT 1";
        $update = $UPLINK->query($sql) or trigger_error(mysqli_error($UPLINK));
        }
        header("location: ammoboxes_currently_deployed.php?ref=updated");
        exit(); 
      }
           
     
    

  $loanArr = ar_getAmmoBoxLoans('out');
  $ammoArr = ar_initAmmoBox();
  
  if(isset($_POST['deployGear']['loan_id']) && $_POST['deployGear']['loan_id'] != "") {
    $POSTDATA = $_POST['deployGear'];
    $barcode = $_POST['deployGear']['loan_id'];
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

    <h1>Deploy AmmoCrates</h1>

    <p>Inventoried AmmoCrates can be deployed by filling in this form.</p>
    
    <div class="main-row">

      <form id="deployGear" name="deployGear" action="ammobox_deploy.php?ref=form&amp;co=<?=$barcode?>&amp;key=<?=$_GET["key"]?>" method="post">

           <div class="row">
          <label>AmmoBox Type ID</label>
          </div>
          <div class="row">
          <input type="hidden" class="hidden" style="max-width:25rem;" name="deployGear[ammobox_id]" value="<?=$_GET["key"]?>" required="required" max="10" autofocus/>        
          <?=$_GET["key"]?>
          </div>
          <div class="row">
          <label>AmmoBox Description</label>
          </div>
          <div class="row">
          <?=$_GET['co']?>
          </div>
          <div class = "row">
          <label>AmmoBox Variant</label>
          </div>
          <div class="row">
          <?=$_GET['var']?>
          </div>
          <div class="row">
          <label>Deploy date</label>
          </div><div class="row">
          <input type="text" class="textinput" style="max-width:25rem;" name="deployGear[loan_date]" placeholder="" value="<?=$DateTime?>" required="required" />
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
          <label>Quantity</label>
          </div><div class="row">
        <input type="number" class="numbers" style="max-width: 5rem;" required name="deployGear[qty]" placeholder="0" max="<?=$_GET["max"]?>" min="1"/>
        </div>

        <div class="row"></div>

        <div class="row">
          <input type="submit" name="deployGear[submit]" value="Deploy Ammobox" class="button button-default">
          <a href="ammoboxes_currently_deployed.php?ref=cancelreturn" class="button button-default">Cancel</a>
        </div>


      </form>

    </div>

  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
