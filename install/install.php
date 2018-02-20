<?php

// check if the connection to $UPLINK exists!!
if(isset($UPLINK) && $UPLINK != "") {

  if(mysqli_ping($UPLINK)) {


      /*======================== create all the necessary database tables. ========================*/

      // Create the 'ammoboxes' table. This table keeps track of, for example, the amounts of magazines or grenades you still have left.
      $sql = "CREATE TABLE IF NOT EXISTS `ems_ar_ammoboxes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `amount` int(11) NOT NULL DEFAULT '0',
        `description` text,
        `type` varchar(20) NOT NULL DEFAULT 'magazine',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2400;";
      $res = $_WCMS_DBi->query($sql)or trigger_error(mysqli_error($_WCMS_DBi));

      /*===========================================================================================*/

      /* Create the 'weapons' table.
      This table contains the weapons and other gear that can have an unique serial number.
      Items in this table can later be deployed. */
      $sql = "CREATE TABLE IF NOT EXISTS `ems_ar_weapons` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `label` varchar(50) NOT NULL,
        `type` varchar(25) NOT NULL,
        `skilltype` varchar(10) DEFAULT 'ranged',
        `ammotype` varchar(20) NOT NULL DEFAULT 'bullet',
        `status` varchar(20) NOT NULL DEFAULT 'in armoury',
        `barcode` varchar(10) NOT NULL,
        `model` varchar(25) DEFAULT NULL,
        `description` text,
        `loan_status` varchar(6) NOT NULL DEFAULT 'false',
        `OC_owner` varchar(25) NOT NULL,
        `foto` varchar(50) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4510";
      $res = $_WCMS_DBi->query($sql)or trigger_error(mysqli_error($_WCMS_DBi));

      /*===========================================================================================*/

      // Create the 'loans' table. You can compare this table to a 'sales history field.'
      $sql = "CREATE TABLE IF NOT EXISTS `ems_ar_loans` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `weapon_id` int(11) NOT NULL,
        `loaned_to` varchar(50) NOT NULL,
        `loan_status` varchar(20) DEFAULT 'out',
        `loan_date` varchar(40) NOT NULL,
        `return_date` varchar(40) DEFAULT NULL,
        `description` text,
        PRIMARY KEY (`id`),
        KEY `weapon_id` (`weapon_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8100";
      $res = $_WCMS_DBi->query($sql)or trigger_error(mysqli_error($_WCMS_DBi));

      /*===========================================================================================*/

      // create the USERS field. Registration can be done on the frontend.
      $sql = "CREATE TABLE IF NOT EXISTS `ems_users` (
        `id` smallint(6) NOT NULL AUTO_INCREMENT,
        `username` varchar(24) NOT NULL,
        `fullname` varchar(100) NOT NULL,
        `faction` varchar(20) NOT NULL,
        `password` varchar(255) NOT NULL,
        `approved` varchar(10) NOT NULL DEFAULT 'false',
        `registerdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1571 ;";
      $res = $_WCMS_DBi->query($sql)or trigger_error(mysqli_error($_WCMS_DBi));

      /*===========================================================================================*/

      // Create the USERGROUPS. An user can belong to multiple user groups.
      $sql = "CREATE TABLE IF NOT EXISTS `ems_usergroups` (
        `group_id` smallint(6) NOT NULL AUTO_INCREMENT,
        `title` varchar(24) NOT NULL,
        PRIMARY KEY (`group_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=310 ;";
      $res = $_WCMS_DBi->query($sql)or trigger_error(mysqli_error($_WCMS_DBi));

      // insert the default usergroups here, if the table is empty.
      $sql = "SELECT * FROM usergroups LIMIT 1";
      $res = $_WCMS_DBi->query($sql)or trigger_error(mysqli_error($_WCMS_DBi));

      if (mysqli_num_rows($res) < 1) {
        $sql = "INSERT INTO `usergroups` (`group_id`, `title`) VALUES
          (311, 'guest'),
          (312, 'admin'),
          (313, 'military'),
          (314, 'medbay'),
          (315, 'finance'),
          (316, 'conclave'),
          (317, 'clergy'),
          (318, 'armoury'),
          (319, 'HR'),
          (320, 'customs'),
          (321, 'engineering'),
          (322, 'science'),
          (323, 'IT'),
          (324, 'officer'),
          (325, 'pilot'),
          (326, 'eosinitiative'),
          (327, '808'),
          (328, '716');";
        $res = $_WCMS_DBi->query($sql)or trigger_error(mysqli_error($_WCMS_DBi));
      }

      /*===========================================================================================*/

      // Create the link between users and usergroups.
      $sql = "CREATE TABLE IF NOT EXISTS `ems_user_koppeling` (
          `id` smallint(6) NOT NULL AUTO_INCREMENT,
          `group_id` smallint(6) DEFAULT NULL,
          `user_id` smallint(6) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `group_id` (`group_id`),
          KEY `group_id_2` (`group_id`),
          KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=55";
      $res = $_WCMS_DBi->query($sql)or trigger_error(mysqli_error($_WCMS_DBi));

      // end!
      unset($sql);
      unset($res);

  } else {

    // uplink exists, but no connection.
    echo "No connection to database. Check if your settings are correct, and try again."; die();

  }

} else {

  // $UPLINK isn't even valid at this point. Error.


}// end
