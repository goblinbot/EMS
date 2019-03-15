<?php
function ar_initUsergroups() {
  global $_CONFIG, $UPLINK;

  $userArr = array();

  // haalt de gebruikersgroepstoewijzingen op
  $sql = "SELECT id,group_id_user_id FROM user_koppeling ORDER BY name ASC";
  $result = $UPLINK->query($sql);

  // loopt door de results heen
  if(mysqli_num_rows($result) > 0) {

    $userArr['magazine'] = array();

    while($row = mysqli_fetch_assoc($result)){

      if(isset($row['type']) && $row['type'] != "") {
        foreach($row AS $KEY => $VALUE) {

          // if($VALUE == NULL) {
          //   $VALUE = "";
          // }

          if($KEY != "description") {
            $VALUE = strtolower($VALUE);
          }

          // VOLLEDIGE DATA ->CATEGORIE ->UNIEKE ID ->ALLE WAARDES
          $userArr[strtolower($row['type'])][$row['id']][$KEY] = EMS_echo($VALUE);

        }//foreach
      }*/// if != ""

    } //while results
  } // if results hoger dan 0

  return $userArr;
}

/*function ar_initWeapons($PARAM = null) {
  global $_CONFIG, $UPLINK;

  $weaponArr = array();

  // haalt de inventory op
  if(isset($PARAM) && $PARAM == "onloan") {
    $WHERE = "WHERE loan_status = 'true' AND status <> 'broken'  AND status <> 'inactive' ";
  } else if(isset($PARAM) && $PARAM == "free") {
    $WHERE = "WHERE loan_status = 'false' AND status <> 'broken' AND status <> 'inactive' ";
  } else if(isset($PARAM) && $PARAM == "broken") {
    $WHERE = "WHERE status = 'broken' OR status = 'inactive' ";
  } else {
    $WHERE = "WHERE status <> 'broken' AND status <> 'inactive' ";
  }
  $sql = "SELECT id,label,type,skilltype,ammotype,status,barcode,model,description,loan_status,OC_owner,foto FROM ar_weapons ".$WHERE." ORDER BY label ASC";
  $result = $UPLINK->query($sql);

  // loopt door de results heen
  if(mysqli_num_rows($result) > 0) {

    $weaponArr['ranged'] = array();
    $weaponArr['melee'] = array();

    while($row = mysqli_fetch_assoc($result)){

      if(isset($row['skilltype']) && $row['skilltype'] != "") {
        foreach($row AS $KEY => $VALUE) {

          // if($VALUE == NULL) {
          //   $VALUE = "";
          // }

          if($row['status'] == 'inactive' || $row['status'] == 'broken') {
            $row['skilltype'] = 'inactive';
          } else {
            $row['skilltype'] = strtolower($row['skilltype']);
          }


          // VOLLEDIGE DATA ->CATEGORIE ->UNIEKE ID ->ALLE WAARDES
          $weaponArr[$row['skilltype']][$row['id']][$KEY] = EMS_echo($VALUE);

        }//foreach
      }// if != ""

    } //while results
  } // if results hoger dan 0


  return $weaponArr;
}*/


// get loans
/*function ar_getLoans($PARAMS = null) {

  global $UPLINK, $_CONFIG;

  $loanArr = array();

  // $loanArr["PARAMS"] = $PARAMS;

  if(isset($PARAMS) && $PARAMS == "out") {
    $WHERE = "WHERE lo.loan_status = 'out' ";
  } else if(isset($PARAMS) && $PARAMS == "done") {
    $WHERE = "WHERE lo.loan_status = 'done' ";
  } else {
    $WHERE = "";
  }

  $sql = "SELECT lo.id, we.barcode, we.label, we.model, we.type, we.status, lo.loaned_to, lo.loan_status, lo.loan_date, lo.return_date, lo.description
    FROM ar_loans AS lo
    INNER JOIN ar_weapons AS we
    ON lo.weapon_id = we.id
    ".$WHERE."
    ORDER BY id DESC";
  $result = $UPLINK->query($sql);

  if(mysqli_num_rows($result) > 0) {

    while($row = mysqli_fetch_assoc($result)){
      foreach($row AS $KEY => $VALUE) {

        // VOLLEDIGE DATA ->UNIEKE ID ->ALLE WAARDES
        $loanArr[$row['id']][$KEY] = EMS_echo($VALUE);

      }//foreach
    }

  }

  return $loanArr;

}*/
