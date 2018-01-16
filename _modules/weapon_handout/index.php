<?php
if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");
  loginRequired();

  $_MODULES["current"]["module"] = "gear exchange";
  $_MODULES["current"]["page"] = "gear exchange";

  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">
  <div class="container">

  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
