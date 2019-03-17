<?php
function ar_initAmmoBox() {
  global $_CONFIG, $UPLINK;

  $ammoArr = array();

  // haalt de inventory op
  $sql = "SELECT ar_ammoboxes.id as id,
            t1.type as type,
            ar_ammoboxes.ammobox_type as ammobox_type,
            t1.name as name,
            t1.capacity as capacity,
            v1.name as variant,
            ar_ammoboxes.description as description,
            ar_ammoboxes.amount as qty FROM ar_ammoboxes
            join ar_ammoboxes_types t1 on t1.id = ar_ammoboxes.ammobox_type
            join ar_ammoboxes_variants v1 on v1.id = ar_ammoboxes.variant
             ORDER BY name ASC";
  $result = $UPLINK->query($sql);

  // loopt door de results heen
  if(mysqli_num_rows($result) > 0) {

    $ammoArr['magazine'] = array();

    while($row = mysqli_fetch_assoc($result)){

      if(isset($row['type']) && $row['type'] != "") {
        foreach($row AS $KEY => $VALUE) {

          // if($VALUE == NULL) {
          //   $VALUE = "";
          // }

          /*if($KEY != "description") {
            $VALUE = strtolower($VALUE);
          }*/

          // VOLLEDIGE DATA ->CATEGORIE ->UNIEKE ID ->ALLE WAARDES
          $ammoArr[strtolower($row['type'])][$row['id']][$KEY] = EMS_echo($VALUE);

        }//foreach
      }// if != ""

    } //while results
  } // if results hoger dan 0

  return $ammoArr;
}
function ar_initAmmoBoxCategories() {
  global $_CONFIG, $UPLINK;

  $ammotypeArr = array();

  $sql = "SELECT DISTINCT type FROM ar_ammoboxes_types;";
  $result = $UPLINK->query($sql);
  $ammotypeArr = $result;

  return $ammotypeArr;
}

function ar_initAmmoBoxTypes() {
  global $_CONFIG, $UPLINK;

  $ammotypeArr = array();

  $sql = "SELECT ar_ammoboxes_types.id as typeid, ar_ammoboxes_types.name, ar_ammoboxes_types.capacity, ar_ammoboxes_types.type
  FROM ar_ammoboxes_types ORDER BY ar_ammoboxes_types.name";
  $result = $UPLINK->query($sql);
  $ammotypeArr = $result;

  return $ammotypeArr;
}

function ar_initAmmoBoxVariants() {
  global $_CONFIG, $UPLINK;

  $ammotypeArr = array();

  $sql = "SELECT * FROM ar_ammoboxes_variants ORDER BY name";
  $result = $UPLINK->query($sql);
  $ammotypeArr = $result;

  return $ammotypeArr;
}

function ar_initWeapons($PARAM = null) {
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
}


// get weapon loans
function ar_getLoans($PARAMS = null) {

  global $UPLINK, $_CONFIG;

  $loanArr = array();

  if(isset($PARAMS) && $PARAMS == "out") {
    $WHERE = "WHERE lo.loan_status = 'out' ";
  } else if(isset($PARAMS) && $PARAMS == "done") {
    $WHERE = "WHERE lo.loan_status = 'done' ";
  } else {
    $WHERE = "";
  }

  $sql = "SELECT we.barcode, we.label, we.model, we.type, we.status,lo.id,weapon_id,c1.character_name as loaned_to, lo.loan_status,lo.loan_date, lo.return_date,lo.description,c1.ICC_number,c1.card_id
FROM ar_loans_weapon as lo
  INNER JOIN ar_weapons AS we ON lo.weapon_id = we.id
  join joomla.ecc_characters c1 on c1.characterID = lo.loaned_to
       ".$WHERE."
      ORDER BY id DESC;";
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

}

// get ammoBox Loans

function ar_getAmmoBoxLoans($PARAMS = null) {

  global $UPLINK, $_CONFIG;

  $loanArr = array();

  if(isset($PARAMS) && $PARAMS == "out") {
    $WHERE = "WHERE lo.loan_status = 'out' ";
  } else if(isset($PARAMS) && $PARAMS == "done") {
    $WHERE = "WHERE lo.loan_status = 'done' ";
  } else {
    $WHERE = "";
  }

  $sql = "SELECT lo.id as loan_id,ab.id as abid, abt.type as abtype, abt.name as abtname, abv.name as abvariant, abt.capacity, c1.character_name as loaned_to,
  lo.loan_status,lo.loan_date, lo.return_date, lo.qty,c1.ICC_number,c1.card_id, concat(we.barcode, ' - ',we.label) as deployed_with
  FROM ar_loans_ammoboxes as lo
    JOIN ar_ammoboxes ab on lo.ammobox_id = ab.id
    join ar_ammoboxes_types as abt on abt.id = ab.ammobox_type
    join ar_ammoboxes_variants as abv on abv.id = ab.variant
    join joomla.ecc_characters c1 on c1.characterID = lo.loaned_to
    JOIN ar_weapons we on we.id = lo.assoc_weapon_loan
       ".$WHERE."
    ORDER BY ab.id desc;";
  $result = $UPLINK->query($sql);

  if(mysqli_num_rows($result) > 0) {

    while($row = mysqli_fetch_assoc($result)){
      foreach($row AS $KEY => $VALUE) {

        // VOLLEDIGE DATA ->UNIEKE ID ->ALLE WAARDES
        $loanArr[$row['loan_id']][$KEY] = EMS_echo($VALUE);

      }//foreach
    }

  }

  return $loanArr;

}
// get ammobox inventory for loans
function ar_getAmmoBoxLoanable($PARAMS = null) {

  global $UPLINK, $_CONFIG;

  $ammoInvLoan = array();

  $sql = "SELECT ab.id as abid, CONCAT(abt.name, ' - ', abv.name, '(', ab.amount, ' available)') as inv_item_name,
  abt.capacity, abt.type, ab.amount as abamount
  from ar_ammoboxes as ab
  join ar_ammoboxes_types abt on ab.ammobox_type = abt.id
  join ar_ammoboxes_variants abv on ab.variant = abv.id
  WHERE ab.amount > 0
  ORDER by inv_item_name asc";
  $result = $UPLINK->query($sql);

  if(mysqli_num_rows($result) > 0) {

    while($row = mysqli_fetch_assoc($result)){
      foreach($row AS $KEY => $VALUE) {

        // VOLLEDIGE DATA ->UNIEKE ID ->ALLE WAARDES
        $ammoInvLoan[$row['abid']][$KEY] = EMS_echo($VALUE);

      }//foreach
    }

  }

  return $ammoInvLoan;

}