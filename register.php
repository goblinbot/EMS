<?php

  if(isset($_SESSION["ems_login"]) && $_SESSION["ems_login"] != ""){

    unset($_SESSION["ems_login"]);
    header("location: /ems/");
    exit();

  }

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");

  $_MODULES["current"]["module"] = "home";
  $_MODULES["current"]["page"] = "home";

  include_once($_CONFIG["root"] . "/header.php");

?>
<div class="main item">
  <div class="container">

  <?php

    if(isset($_POST["ems_register"]) && $_POST["ems_register"] != "") {

      $VALIDATED = true;
      $STATUS = "";

      $USERDATA = $_POST["ems_register"];

      if(isset($USERDATA["user"]) && $USERDATA["user"] != "" && huizingfilter($USERDATA["user"]) == "clear") {

        // bevat de naam admin?
        if(strpos(strtolower($USERDATA["user"]),'admin') !== false) {
          $VALIDATED = false;
          $STATUS = "Very funny.";
        } else {

          if(strlen($USERDATA["user"]) > 23 ) {

            $VALIDATED = false;
            echo "<h1>Manipulation detected.. systems offline.</h1>";
            exit();

          } else {
            // bestaat hij al?
            $sql = $UPLINK->query('SELECT username FROM users WHERE username = "'.mysqli_real_escape_string($UPLINK,$USERDATA["user"]).'"');

            if(mysqli_num_rows($sql) > 0) {
              $VALIDATED = false;
              $STATUS = "Username is already taken";
            }
          }

        }

        // ga voort.
        if(isset($USERDATA["fullname"]) && $USERDATA["fullname"] != "" && huizingfilter($USERDATA["fullname"]) == "clear" && $VALIDATED == true) {

          if(isset($USERDATA["faction"]) && $USERDATA["faction"] != "" && huizingfilter($USERDATA["faction"]) == "clear" && $VALIDATED == true) {

            if( (strtolower($USERDATA["faction"]) == "aquila"
            ||strtolower($USERDATA["faction"]) == "dugo"
            ||strtolower($USERDATA["faction"]) == "ekanesh"
            ||strtolower($USERDATA["faction"]) == "sona"
            ||strtolower($USERDATA["faction"]) == "pendzal"
            ||strtolower($USERDATA["faction"]) == "other" ) && $VALIDATED == true) {

              if($VALIDATED == true && isset($USERDATA["pass"]) && $USERDATA["pass"] != "" && isset($USERDATA["pass_confirm"]) && $USERDATA["pass_confirm"] != "") {

                if($VALIDATED == true
                && $USERDATA["pass_confirm"] == $USERDATA["pass"]
                && huizingfilter($USERDATA["pass_confirm"]) == "clear"
                && huizingfilter($USERDATA["pass"]) == "clear") {

                  $options = array("cost"=>4);
                  $USERDATA["pass"] = password_hash( $USERDATA["pass"], PASSWORD_BCRYPT,$options );

                  // alles gucci!
                  $sql = "INSERT INTO `users` (`username`, `fullname`, `faction`, `password` )
                  VALUES ( '".mysqli_real_escape_string($UPLINK,$USERDATA["user"])."'
                    , '".mysqli_real_escape_string($UPLINK,$USERDATA["fullname"])."'
                    , '".mysqli_real_escape_string($UPLINK,$USERDATA["faction"])."'
                    , '".mysqli_real_escape_string($UPLINK,$USERDATA["pass"])."'
                  );";

                  $UPLINK->query($sql);
                  $VALIDATED = "succes";

                } else {
                  $VALIDATED = false;
                  if($STATUS == "") {
                    $STATUS = "Error: Passwords do not match, please try again.";
                  }
                }

              } else {
                $VALIDATED = false;
                if($STATUS == "") {
                  $STATUS = "Error: Password(s) invalid. No additional info is available at this time.";
                }
              }

            } else {
              $VALIDATED = false;
              echo "<h1>Manipulation detected.. systems offline.</h1>";
              exit();
            }
          } else {
            $VALIDATED = false;
            echo "<h1>Manipulation detected.. systems offline.</h1>";
            exit();
          }
        } else {
          $VALIDATED = false;
          if($STATUS == "") {
            $STATUS = "Error: full name is invalid, or blank.";
          }

        }
      } else {
        $VALIDATED = false;
        if($STATUS == "") {
          $STATUS = "Error: Username is invalid, or blank.";
        }
      }

    }

    if(isset($STATUS) && $STATUS != "") {
      echo "<div class=\"alert-dialog\">"
      .   "<h2><strong><i class=\"fa fa-info\"></i></strong>&nbsp;".$STATUS."</h2>"
      .   "<hr/>"
      . "</div>";
    }


    if(isset($VALIDATED) && $VALIDATED == "succes") {

      header("location: /ems/?registersucces");
      exit();

    } else {

      ?>
      <div class="form-block">
        <form class="form" method="post" action="<?=$_CONFIG["header"]?>/register.php" name="ems_register">

          <label for="user">Desired username</label>
          <input type="text" class="input" name="ems_register[user]" value="" required placeholder="Username" max="23">

          <label for="fullname">Full name</label>
          <input id="fullnamefield" type="text" class="input" name="ems_register[fullname]" value="" required placeholder="Fullus Titlus" max="99">

          <label for="faction">Faction</label>
          <select id="factionfield" class="select" name="ems_register[faction]" onchange="MaiPlaceholder();">
            <option value="Aquila">Aquila</option>
            <option value="Dugo">Dugo</option>
            <option value="Ekanesh">Ekanesh</option>
            <option value="Pendzal">Pendzal</option>
            <option value="Sona">Sona</option>
            <option value="Other">Other</option>
          </select>

          <label for="pass">Password</label>
          <input type="password" class="input" name="ems_register[pass]" value="" required placeholder="******" max="99">
          <label for="pass_confirm">Password, again.</label>
          <input type="password" class="input" name="ems_register[pass_confirm]" value="" required placeholder="******" max="99">

          <input type="submit" class="button buttonblock" value="register"/>

        </form>

      </div>

      <script type="text/javascript">
        function MaiPlaceholder() {

        var faction = $('#factionfield').val();

          switch(faction) {
            case 'Aquila': default:
              $('#fullnamefield').attr('placeholder','Marcus Lucius');
              break;
            case 'Dugo':
              $('#fullnamefield').attr('placeholder','Nama Vel Tulang');
              break;
            case 'Ekanesh':
              $('#fullnamefield').attr('placeholder','Julius Spiritualus');
              break;
            case 'Pendzal':
              $('#fullnamefield').attr('placeholder','Vladimir Ivanslav');
              break;
            case 'Sona':
              $('#fullnamefield').attr('placeholder','Amin Yashed');
              break;
            case 'Other':
              $('#fullnamefield').attr('placeholder','John Computersmith');
              break;
          }
        }
      </script>
    <?php
    }
  ?>
  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
