<?php
function ar_initUsergroups() {
  global $_CONFIG, $UPLINK;

  $userArr = array();

  // haalt de gebruikersgroepstoewijzingen op
  $sql = "SELECT user_koppeling.id as userid ,user_koppeling.group_id as groupid,t1.id,t1.fullname as fullname FROM user_koppeling right join users t1 on t1.id = user_koppeling.user_id ORDER by user_koppeling.id ASC";
  $result = $UPLINK->query($sql);

  // loopt door de results heen
  if(mysqli_num_rows($result) > 0) {

    $userArr = array();

    /* while ($row = mysqli_fetch_assoc($result)) {
      echo $row["userid"];
      echo $row["fullname"];
      echo $row["groupid"];
      echo $row["id"];
  }*/
     while($row = mysqli_fetch_assoc($result)){


      if(isset($row['group_id']) && $row['group_id'] != "") {
        foreach($row AS $KEY => $VALUE) {
          //$VALUE = strtolower($VALUE);
          // VOLLEDIGE DATA ->CATEGORIE ->UNIEKE ID ->ALLE WAARDES
          $userArr[$row['groupid']][$row['id']][$row['userid']][$row['fullname']][$KEY] = EMS_echo($VALUE);

        }//foreach
      } /// if != ""

    } //while results 
  } // if results hoger dan 0

  return $userArr;
}
?>