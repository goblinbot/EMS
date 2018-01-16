<?php
if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");

  loginRequired();

  $_MODULES["current"]["module"] = "users";
  $_MODULES["current"]["page"] = "users";

  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">
  <div class="container">

    <h3>User control</h3>

    <p>&nbsp;</p>

    <p>
      <a href="<?=$_CONFIG["header"]?>?logout=true" class="button button-default"><i class="fa fa-power-off"></i>&nbsp;Logout</a>
    </p>




  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
