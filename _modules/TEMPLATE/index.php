<?php
  if(!isset($_SESSION)) {
    session_start();
  }

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");

  loginRequired();

  $_MODULES["current"]["module"] = "home";
  $_MODULES["current"]["page"] = "home";
  $_MODULES["current"]["parent"] = "none";

  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">
  <div class="container">

    <div class="alert-dialog">
      <h2><strong><i class="fa fa-info-circle"></i>&nbsp;Currently in development.</strong></h2>
      <hr/>
    </div>




  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
