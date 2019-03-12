# EMS
*Eos Management System, by Thijs Boerma*

An inventory/armoury/warehouse project for the Sci-fi larp Eos, created for the purpose of teach myself CSS Grids, and fleshing out the use of our in-game armoury.

EMS is currently WIP.

*online mirror:*
http://www.gubat.nl/ems/

### To Install:
1. Edit _includes/config.global.php to include your MySQL DB Connection parameters and install folder name.
2. Save the edited file as _includes/config.global.php
3. Run the install.php script (assuming ems is a subfolder at the root of your site): http://www.example.com/ems/install/install.php
4. rename install/install.php to install/install.php.bak to prevent abuse

## Basic functionality
- Account registration / authentication.
- Easy navigation
- Creating an inventory of equipment/ammunition
- Checking equipment in/out
- Putting gear on 'requires maintenance'
- Keeping count of ammunition/grenades/misc

## Technical stuff
EMS is a web application, and requires a decent browser (i.e. Chrome, Firefox, the latest version of Edge) for an optimal experience.

EMS is built in PHP, and runs on the following:
- PHP 7.0 or higher (compatible with 5.3+)
- jQuery (included)
- MySQLi  (database)

EMS *is* going to use JSON/AJAX calls, but none are used so far.

### Current roadmap
- [module]    - create/edit/remove ammo & gear entries.
- [tech]      - Create an install for creating your own database on the spot.
- [security]  - Captcha for registration.
- [module]    - Phase out 'Medbay' due to other projects doing the same.

### Current 'Functional wishlist'
- [translate] - English comments
- [module]    - Add 'My Notes' module for keeping records.
- [tech]      - QR/Barcode integration for scanning objects.
- [module]    - User management
- [security]  - Password reset functionality
