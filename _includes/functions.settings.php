<?php
function ar_initUsergroups() {
  global $_CONFIG, $UPLINK;

  $userArr = array();

  // haalt de gebruikersgroepstoewijzingen op
  $sql = "SELECT id,group_id,user_id FROM user_koppeling ORDER BY id ASC";
  $result = $UPLINK->query($sql);

  // loopt door de results heen
  if(mysqli_num_rows($result) > 0) {

    $userArr = array();

    while($row = mysqli_fetch_assoc($result)){

      if(isset($row['group_id']) && $row['group_id'] != "") {
        foreach($row AS $KEY => $VALUE) {
          $VALUE = strtolower($VALUE);
          // VOLLEDIGE DATA ->CATEGORIE ->UNIEKE ID ->ALLE WAARDES
          $userArr[strtolower($row['group_id'])][$row['id']][$KEY] = EMS_echo($VALUE);

        }//foreach
      } /// if != ""

    } //while results
  } // if results hoger dan 0

  return $userArr;
}
