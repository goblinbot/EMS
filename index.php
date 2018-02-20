<?php
  // globals
  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");

  $_MODULES["current"]["module"] = "home";

  include_once($_CONFIG["root"] . "/header.php");

  if(!isset($_SESSION)) {
    session_start();
  }



  if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    unset($_SESSION['ems_login']);
    header('location: '.$_CONFIG["header"].'/');
    exit();
  }



  if (isset($_POST["ems_pass"]) && isset($_POST["ems_user"]) && $_POST["ems_user"] != "" && $_POST["ems_pass"] != "") {

    /*escape de keycode voordat we hem gaan gebruiken. $submitKeycode is vanaf nu de waarde waar we op checken. */
    $ems_user = trim($_POST["ems_user"]);
    unset($_POST["ems_user"]);

    $ems_pass = trim($_POST["ems_pass"]);
    unset($_POST["ems_pass"]);


      $query  =
      "SELECT username, fullname, faction, approved, password
        FROM ems_users
        WHERE username = '".mysqli_escape_string($UPLINK,$ems_user)."'
        LIMIT 1";
      $result = $UPLINK->query($query);

      $numRows = mysqli_num_rows($result);

      if($numRows == 1) {
        $row = mysqli_fetch_assoc($result);

        if(isset($row['approved']) && $row['username'] == true) {

          if(password_verify($ems_pass,$row['password'])){
            $_SESSION['ems_login']['user'] = mysqli_escape_string($UPLINK,$row['username']);
            $_SESSION['ems_login']['fullname'] = mysqli_escape_string($UPLINK,$row['fullname']);
            $_SESSION['ems_login']['faction'] = mysqli_escape_string($UPLINK,$row['faction']);

            header('location: '.$_CONFIG["header"].'/');
            exit();
          } else {
            header('location: '.$_CONFIG["header"].'/?error=wrongpass');
            exit();
          }

        } else {
          header('location: '.$_CONFIG["header"].'/?error=approved');
          exit();
        }

      } else {
        header('location: '.$_CONFIG["header"].'/?error=nouser');
        exit();
      }

      /*$row = mysqli_fetch_array($result);

      if ($result->num_rows == 1) {

        if(isset($row['approved']) && $row['username'] == true) {
          $_SESSION['ems_login']['user'] = mysqli_escape_string($UPLINK,$row['username']);
          $_SESSION['ems_login']['fullname'] = mysqli_escape_string($UPLINK,$row['fullname']);
          $_SESSION['ems_login']['faction'] = mysqli_escape_string($UPLINK,$row['faction']);

          header('location: '.$_CONFIG["header"].'/');
          exit();
        } else {
          header('location: '.$_CONFIG["header"].'/?error=approved');
          exit();
        }

      }*/
    $result->close();
  }






?>
<div class="main item">
  <?php

    if(isset($_GET["error"]) && $_GET["error"] != "") {
      if($_GET["error"] == "noconfig") {
        echo "<div class=\"alert-dialog\">"
        .   "<h2><strong><i class=\"fa fa-unlink\"></i></strong> The page you just attempted to load, did not have access to the global settings.</h2>"
        .     "<p>Please contact sys.admin <strong>Maati Infor Danam</strong> if this keeps happening.</p>"
        .   "<hr/>"
        . "</div>";
      } else if($_GET["error"] == "noAuth") {
        echo "<div class=\"alert-dialog\">"
        .   "<h2><strong><i class=\"fa fa-warning\"></i></strong> Insufficient user rights. Access denied.</h2>"
        .     "<p>Either you aren't logged in, or you aren't part of the proper usergroups.</p>"
        .     "<p>Contact sys.admin <strong>Maati Infor Danam</strong> for more information.</p>"
        .   "<hr/>"
        . "</div>";
      } else if($_GET["error"] == "approved") {
        echo "<div class=\"alert-dialog\">"
        .   "<h2><strong><i class=\"fa fa-info-circle\"></i></strong> Account hasn't been approved yet</h2>"
        .     "<p>&nbsp;</p>"
        .     "<p>Contact sys.admin <strong>Maati Infor Danam</strong> for more information.</p>"
        .   "<hr/>"
        . "</div>";
      } else if($_GET["error"] == "wrongpass") {
        echo "<div class=\"alert-dialog\">"
        .   "<h2><strong><i class=\"fa fa-info-circle\"></i></strong> Incorrect passcode.</h2>"
        .   "<hr/>"
        . "</div>";
      } else if($_GET["error"] == "nouser") {
        echo "<div class=\"alert-dialog\">"
        .   "<h2><strong><i class=\"fa fa-info-circle\"></i></strong> User doesn't exist.</h2>"
        .   "<hr/>"
        . "</div>";
      }

    }

    if(isset($_GET["registersucces"]) && $_GET["registersucces"] != "") {
      echo "<div class=\"alert-dialog\">"
      .   "<h2><strong>Succesfully registered. Account is now pending approval.</h2>"
      .     "<p>Contact sys.admin <strong>Maati Infor Danam</strong> for more information.</p>"
      .     "<hr/>"
      . "</div>";
    }

    if(isset($_SESSION["ems_login"]) && $_SESSION["ems_login"] != ""){
      ?>

      <div class="container">

        <div class="main-row">

          <div class="row text-center">
            <h1 class="text-center">Welcome, <?=$_SESSION["ems_login"]["fullname"]?></h1>
          </div>

          <div class="row text-center">
            <img src="<?=$_CONFIG["header"]?>/_img/logo_B.png" alt="logo"/>
          </div>

          <div class="row text-center">
            <h3>Powered by MMI</h3>
          </div>

          <div class="row text-center">

            <div class="static-motto">Man Machine</div>
            <div class="motto">
              <ul>
                  <li>Interactive</li>
                  <li>Initiative</li>
                  <li>Interface</li>
                  <li>Infolink</li>
              </ul>
            </div>

          </div>

        </div>

      </div>

      <?php
    } else {
      ?>
      <div class="container">

        <div class="form-block">
          <form class="form" method="post" action="<?=$_CONFIG["header"]?>/index.php" name="ems_login">
            <label>Username</label>
            <input type="text" class="input" name="ems_user" value="" required placeholder="Username">
            <label>Password</label>
            <input type="password" class="input" name="ems_pass" value="" required placeholder="******">
            <input type="submit" class="button buttonblock" value="Login"/>
          </form>

          <br/>

          <p><a href="<?=$_CONFIG["header"]?>/register.php" alt="Register link">I do not have an account yet.</a></p>
          <br/>
          <p style="font-size: 88%">Due to the recent galaxy-wide email security issues, automated password recovery has been disabled. Contact your sys.admin for more info.</p>

        </div>

      </div>
      <?php
    }
  ?>

</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
