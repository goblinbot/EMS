<?php
// modules
$_MODULES = array();
$_MODULES["modules"] = array();
$_MODULES["current"] = array();

class moduleObj {

  var $title        = "title";
  // var $id           = '0';
  var $url          = "#";
  var $icon         = "fa fa-database";
  var $menu_level   = '1';
  var $parent       = 'none';
  // var $menuitems    = array();
  var $usergroups   = array();

   function __construct() {
      //  print "In moduleObj constructor\n";
   }
}

// teller voor de ID's, etc.
$module_counter = 0;
$parent_counter = "home";

// home /////////////////////////////////////////////////////////////////////////
$_MODULES["modules"]["home"] = array();
$module_counter = 0;
$parent_counter = "home";

$_MODULES["modules"]["home"][$module_counter] = new moduleObj();
$_MODULES["modules"]["home"][$module_counter]->title = 'Home';
  $_MODULES["modules"]["home"][$module_counter]->url = $_CONFIG["header"].'/';
  $_MODULES["modules"]["home"][$module_counter]->icon  = 'fa fa-home';
  $_MODULES["modules"]["home"][$module_counter]->menu_level = '0';

  $module_counter++;

// USERS /////////////////////////////////////////////////////////////////////////
$_MODULES["modules"]["users"] = array();
$module_counter = 0;
$parent_counter = "users";

$_MODULES["modules"]["users"][$module_counter] = new moduleObj();
$_MODULES["modules"]["users"][$module_counter]->title = 'Profile';
  $_MODULES["modules"]["users"][$module_counter]->menu_level = '-1';
  $_MODULES["modules"]["users"][$module_counter]->url = $_CONFIG["header"].'/_modules/user/';
  $_MODULES["modules"]["users"][$module_counter]->icon  = 'fa fa-user';

  $module_counter++;

// armoury module /////////////////////////////////////////////////////////////////
$_MODULES["modules"]["armoury"] = array();
$module_counter = 0;
$parent_counter = "armoury";

$_MODULES["modules"]["armoury"][$module_counter] = new moduleObj();
$_MODULES["modules"]["armoury"][$module_counter]->title = 'Armoury';
  $_MODULES["modules"]["armoury"][$module_counter]->url = $_CONFIG["header"].'/_modules/armoury/';
  $_MODULES["modules"]["armoury"][$module_counter]->icon  = 'fa fa-shield';
  $_MODULES["modules"]["armoury"][$module_counter]->menu_level = '0';

  $module_counter++;

  /////////////
  $_MODULES["modules"]["armoury"][$module_counter] = new moduleObj();
  $_MODULES["modules"]["armoury"][$module_counter]->title = 'Add Weapon';
  $_MODULES["modules"]["armoury"][$module_counter]->parent  = $parent_counter;
    $_MODULES["modules"]["armoury"][$module_counter]->url = $_CONFIG["header"].'/_modules/armoury/add_weapon.php';
    $_MODULES["modules"]["armoury"][$module_counter]->usergroups = ['armoury','finance'];

    $module_counter++;
  /////////////
  $_MODULES["modules"]["armoury"][$module_counter] = new moduleObj();
  $_MODULES["modules"]["armoury"][$module_counter]->title = 'AmmoCrate';
  $_MODULES["modules"]["armoury"][$module_counter]->parent  = $parent_counter;
    $_MODULES["modules"]["armoury"][$module_counter]->url = $_CONFIG["header"].'/_modules/armoury/ammocrate.php';
    $_MODULES["modules"]["armoury"][$module_counter]->usergroups = ['armoury','finance'];

    $module_counter++;


         /////////////
  $_MODULES["modules"]["armoury"][$module_counter] = new moduleObj();
  $_MODULES["modules"]["armoury"][$module_counter]->title = 'Add AmmoCrate';
  $_MODULES["modules"]["armoury"][$module_counter]->parent  = $parent_counter;
    $_MODULES["modules"]["armoury"][$module_counter]->url = $_CONFIG["header"].'/_modules/armoury/add_ammocrate.php';
    $_MODULES["modules"]["armoury"][$module_counter]->usergroups = ['armoury','finance'];

    $module_counter++;
    
  /////////////
  $_MODULES["modules"]["armoury"][$module_counter] = new moduleObj();
  $_MODULES["modules"]["armoury"][$module_counter]->title = 'Damage rep.';
  $_MODULES["modules"]["armoury"][$module_counter]->parent  = $parent_counter;
    $_MODULES["modules"]["armoury"][$module_counter]->url = $_CONFIG["header"].'/_modules/armoury/damaged.php';
    $_MODULES["modules"]["armoury"][$module_counter]->usergroups = ['armoury','finance'];

    $module_counter++;
// gear exchange module /////////////////////////////////////////////////////////////////////////
$_MODULES["modules"]["gearExchange"] = array();
$module_counter = 0;
$parent_counter = "Gear exchange";

$_MODULES["modules"]["gearExchange"][$module_counter] = new moduleObj();
$_MODULES["modules"]["gearExchange"][$module_counter]->title = 'Gear exchange';
  $_MODULES["modules"]["gearExchange"][$module_counter]->url = $_CONFIG["header"].'/_modules/weapon_handout/currently_deployed.php';
  $_MODULES["modules"]["gearExchange"][$module_counter]->icon  = 'fa fa-retweet';
  $_MODULES["modules"]["gearExchange"][$module_counter]->menu_level = '0';

  $module_counter++;

    /////////////
    $_MODULES["modules"]["gearExchange"][$module_counter] = new moduleObj();
    $_MODULES["modules"]["gearExchange"][$module_counter]->title = 'Deployed';
    $_MODULES["modules"]["gearExchange"][$module_counter]->parent  = $parent_counter;
      $_MODULES["modules"]["gearExchange"][$module_counter]->url = $_CONFIG["header"].'/_modules/weapon_handout/currently_deployed.php';
      $_MODULES["modules"]["gearExchange"][$module_counter]->usergroups = ['armoury'];
  
      $module_counter++;

  /////////////
  $_MODULES["modules"]["gearExchange"][$module_counter] = new moduleObj();
  $_MODULES["modules"]["gearExchange"][$module_counter]->title = 'Deploy';
  $_MODULES["modules"]["gearExchange"][$module_counter]->parent  = $parent_counter;
    $_MODULES["modules"]["gearExchange"][$module_counter]->url = $_CONFIG["header"].'/_modules/weapon_handout/deploy.php';
    $_MODULES["modules"]["gearExchange"][$module_counter]->usergroups = ['armoury'];

    $module_counter++;

  /////////////
  $_MODULES["modules"]["gearExchange"][$module_counter] = new moduleObj();
  $_MODULES["modules"]["gearExchange"][$module_counter]->title = 'Return';
  $_MODULES["modules"]["gearExchange"][$module_counter]->parent  = $parent_counter;
    $_MODULES["modules"]["gearExchange"][$module_counter]->url = $_CONFIG["header"].'/_modules/weapon_handout/return.php';

    $module_counter++;

  //////////////
  $_MODULES["modules"]["gearExchange"][$module_counter] = new moduleObj();
  $_MODULES["modules"]["gearExchange"][$module_counter]->title = 'History';
  $_MODULES["modules"]["gearExchange"][$module_counter]->parent  = $parent_counter;
    $_MODULES["modules"]["gearExchange"][$module_counter]->url = $_CONFIG["header"].'/_modules/weapon_handout/history.php';
    $_MODULES["modules"]["gearExchange"][$module_counter]->usergroups = ['armoury'];

    $module_counter++;

// medbay exchange module /////////////////////////////////////////////////////////////////////////
// $_MODULES["modules"]["medbay"] = array();
// $module_counter = 0;
// $parent_counter = "medBay";

// $_MODULES["modules"]["medbay"][$module_counter] = new moduleObj();
// $_MODULES["modules"]["medbay"][$module_counter]->title = 'Medbay';
//   $_MODULES["modules"]["medbay"][$module_counter]->url = $_CONFIG["header"].'/_modules/medbay/';
//   $_MODULES["modules"]["medbay"][$module_counter]->icon  = 'fa fa-medkit';
//   $_MODULES["modules"]["medbay"][$module_counter]->menu_level = '0';
//   $_MODULES["modules"]["medbay"][$module_counter]->usergroups = ['medbay','finance'];

//   $module_counter++;

// ////////////////
// $_MODULES["modules"]["medbay"][$module_counter] = new moduleObj();
// $_MODULES["modules"]["medbay"][$module_counter]->title = 'Medical history';
//   $_MODULES["modules"]["medbay"][$module_counter]->url = $_CONFIG["header"].'/_modules/medbay/medicalhistory.php';
//   $_MODULES["modules"]["medbay"][$module_counter]->parent  = $parent_counter;
//   $_MODULES["modules"]["medbay"][$module_counter]->usergroups = ['medbay'];

//   $module_counter++;


///// Settings ////////////////////////
$_MODULES["modules"]["settings"] = array();
$module_counter = 0;
$parent_counter = "settings";

$_MODULES["modules"]["settings"][$module_counter] = new moduleObj();
$_MODULES["modules"]["settings"][$module_counter]->title = 'Settings';
  $_MODULES["modules"]["settings"][$module_counter]->url = $_CONFIG["header"].'/_modules/settings/';
  $_MODULES["modules"]["settings"][$module_counter]->icon  = 'fa fa-cog';
  $_MODULES["modules"]["settings"][$module_counter]->menu_level = '0';
  $_MODULES["modules"]["settings"][$module_counter]->usergroups = ['admin'];

  $module_counter++;

////////////////
$_MODULES["modules"]["settings"][$module_counter] = new moduleObj();
$_MODULES["modules"]["settings"][$module_counter]->title = 'Usergroups';
  $_MODULES["modules"]["settings"][$module_counter]->url = $_CONFIG["header"].'/_modules/settings/usergroups.php';
  $_MODULES["modules"]["settings"][$module_counter]->parent  = $parent_counter;
  $_MODULES["modules"]["settings"][$module_counter]->usergroups = ['admin'];

  $module_counter++;

//end
