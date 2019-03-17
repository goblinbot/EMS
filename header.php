<?php
  if(!isset($_CONFIG) || !isset($_MODULES["modules"])) {
    header("location: /ems/index.php?error=noconfig");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>EMS | Eos Management System</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <?=EMSincludeCSS()?>
</head>
<body class="notransition">
<div class="maincontent">

  <div class="logo item">
    <img src="<?=$_CONFIG["header"]?>/_img/logo.png" alt="logo"/>
  </div>

  <div class="topbar item">
    <h1><?=$_CONFIG["params"]["title"]?>&nbsp;v<?=$_CONFIG["params"]["version"]?></h1>
  </div>

  <div class="account item">
    <a href="<?=$_CONFIG["header"]?>/_modules/users/?ref=topmenu" class="block" title="User">
      <p><i class="fa fa-user-circle-o"></i></p>
    </a>
  </div>

  <!-- VERTICALE BALK  -->
  <?php
    echo init_modules();
