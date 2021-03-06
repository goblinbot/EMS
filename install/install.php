

<?php
 include_once($_SERVER['DOCUMENT_ROOT'] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
 
 if(!isset($_CONFIG)) die('Server not configured. Make sure you have _includes/config.global.php setup properly. An example config file can be found at _includes/config.global.php.example');

// check if the connection to $UPLINK exists!!
if(isset($UPLINK) && $UPLINK != "") {

  if(mysqli_ping($UPLINK)) {


      /*======================== create all the necessary database tables. ========================*/

      // Create the 'ammoboxes' table. This table keeps track of, for example, the amounts of magazines or grenades you still have left.
      $sql = "CREATE TABLE IF NOT EXISTS `ar_ammoboxes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `amount` int(11) NOT NULL DEFAULT '0',
        `description` text,
        `type` varchar(20) NOT NULL DEFAULT 'magazine',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2400;";
      $res = $UPLINK->query($sql)or trigger_error(mysqli_error($UPLINK));

      /*===========================================================================================*/

      /* Create the 'weapons' table.
      This table contains the weapons and other gear that can have an unique serial number.
      Items in this table can later be deployed. */
      $sql = "CREATE TABLE IF NOT EXISTS `ar_weapons` (
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
      $res = $UPLINK->query($sql)or trigger_error(mysqli_error($UPLINK));

      /*===========================================================================================*/

      // Create the 'loans' table. You can compare this table to a 'sales history field.'
      $sql = "CREATE TABLE IF NOT EXISTS `ar_loans_weapon` (
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
      $res = $UPLINK->query($sql)or trigger_error(mysqli_error($UPLINK));

      /*===========================================================================================*/

      // create the USERS field. Registration can be done on the frontend.
      $sql = "CREATE TABLE IF NOT EXISTS `users` (
        `id` smallint(6) NOT NULL AUTO_INCREMENT,
        `username` varchar(24) NOT NULL,
        `fullname` varchar(100) NOT NULL,
        `faction` varchar(20) NOT NULL,
        `password` varchar(255) NOT NULL,
        `approved` varchar(10) NOT NULL DEFAULT 'false',
        `registerdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1571 ;";
      $res = $UPLINK->query($sql)or trigger_error(mysqli_error($UPLINK));

      /*===========================================================================================*/

      // Create the USERGROUPS. An user can belong to multiple user groups.
      $sql = "CREATE TABLE IF NOT EXISTS `usergroups` (
        `group_id` smallint(6) NOT NULL AUTO_INCREMENT,
        `title` varchar(24) NOT NULL,
        PRIMARY KEY (`group_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=310 ;";
      $res = $UPLINK->query($sql)or trigger_error(mysqli_error($UPLINK));

      // insert the default usergroups here, if the table is empty.
      $sql = "SELECT * FROM usergroups LIMIT 1";
      $res = $UPLINK->query($sql)or trigger_error(mysqli_error($UPLINK));

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
        $res = $UPLINK->query($sql)or trigger_error(mysqli_error($UPLINK));
      }

      /*===========================================================================================*/

      // Create the link between users and usergroups.
      $sql = "CREATE TABLE IF NOT EXISTS `user_koppeling` (
          `id` smallint(6) NOT NULL AUTO_INCREMENT,
          `group_id` smallint(6) DEFAULT NULL,
          `user_id` smallint(6) NOT NULL,
          PRIMARY KEY (`id`),
          KEY `group_id` (`group_id`),
          KEY `group_id_2` (`group_id`),
          KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=55";
      $res = $UPLINK->query($sql)or trigger_error(mysqli_error($UPLINK));

      // end!
      unset($sql);
      unset($res);
    echo "Install complete. Table structure has been created. <br> <strong> !!!WARNING!!! </strong>:Please rename install/install.php to prevent abuse.";
  } else {
    
    // uplink exists, but no connection.
    echo "No connection to database. Check if your settings are correct, and try again."; die();

  }

} else {

  // $UPLINK isn't even valid at this point. Error.


}// end
