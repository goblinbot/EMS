<?php
if(!isset($_SESSION)) {
  session_start();
}

  include_once($_SERVER["DOCUMENT_ROOT"] . "/EMS/_includes/config.global.php");
  include_once($_CONFIG["root"] . "/_includes/functions.global.php");
  include_once($_CONFIG["root"] . "/_includes/includes.php");

  loginRequired();

  $_MODULES["current"]["module"] = "medbay";
  $_MODULES["current"]["page"] = "medbay";

  include_once($_CONFIG["root"] . "/header.php");
?>
<div class="main item">
  <div class="container">

    <h1>Medbay portal</h1>

    <div class="alert-dialog">
      <h2><strong><i class="fa fa-info-circle"></i>&nbsp;Currently in development.</strong></h2>
      <hr/>
    </div>

    <!-- echo "<div class=\"alert-dialog\">"
    .   "<h2><strong><i class=\"fa fa-unlink\"></i></strong> The page you just attempted to load, did not have access to the global settings.</h2>"
    .     "<p>Please contact sys.admin <strong>Maati Infor Danam</strong> if this keeps happening.</p>"
    .   "<hr/>"
    . "</div>"; -->




  </div>
</div>
<?php
  include_once($_CONFIG["root"] . "/footer.php");
