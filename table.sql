--
-- Manning Custom Theme — site customization globals.
--
-- Run by OpenEMR's SQLUpgradeService when the module is installed/upgraded. Each block enforces a
-- core OpenEMR global that this theme requires. We use:
--   #IfNotRow2D globals gl_name <name> gl_value <value>   -- run only if the global is NOT already
--                                                             set to the desired value (absent OR
--                                                             a different value)
--   REPLACE INTO globals (...)                            -- works whether or not a row exists; many
--                                                             of these have no globals row by default
--                                                             (they fall back to globals.inc.php until
--                                                             explicitly set). gl_index 0 is the
--                                                             scalar-global slot.
-- This means the theme owns these settings: a fresh install gets them, and a later module upgrade
-- re-applies them if they have drifted from the values the theme needs.
--

-- Login page layout: use the vertical box layout the theme is designed around.
#IfNotRow2D globals gl_name login_page_layout gl_value login/layouts/vertical_box.html.twig
START TRANSACTION;
REPLACE INTO `globals` (`gl_name`, `gl_index`, `gl_value`) VALUES ('login_page_layout', 0, 'login/layouts/vertical_box.html.twig');
COMMIT;
#EndIf

-- Primary (login) logo width: full width of its container so the bundled logo fills the box.
#IfNotRow2D globals gl_name primary_logo_width gl_value w-100
START TRANSACTION;
REPLACE INTO `globals` (`gl_name`, `gl_index`, `gl_value`) VALUES ('primary_logo_width', 0, 'w-100');
COMMIT;
#EndIf

-- Application name shown throughout the UI.
#IfNotRow2D globals gl_name openemr_name gl_value Dr. Jill Manning Assessments
START TRANSACTION;
REPLACE INTO `globals` (`gl_name`, `gl_index`, `gl_value`) VALUES ('openemr_name', 0, 'Dr. Jill Manning Assessments');
COMMIT;
#EndIf

-- Online support link (login page / help).
#IfNotRow2D globals gl_name online_support_link gl_value https://drjillmanning.com/
START TRANSACTION;
REPLACE INTO `globals` (`gl_name`, `gl_index`, `gl_value`) VALUES ('online_support_link', 0, 'https://drjillmanning.com/');
COMMIT;
#EndIf

-- Support phone number (login page / help).
#IfNotRow2D globals gl_name support_phone_number gl_value 720.209.9510
START TRANSACTION;
REPLACE INTO `globals` (`gl_name`, `gl_index`, `gl_value`) VALUES ('support_phone_number', 0, '720.209.9510');
COMMIT;
#EndIf

-- Do not show the acknowledgements block on the login page.
#IfNotRow2D globals gl_name display_acknowledgements gl_value 0
START TRANSACTION;
REPLACE INTO `globals` (`gl_name`, `gl_index`, `gl_value`) VALUES ('display_acknowledgements', 0, '0');
COMMIT;
#EndIf

-- Default color theme: cobalt blue.
#IfNotRow2D globals gl_name css_header gl_value style_cobalt_blue.css
START TRANSACTION;
REPLACE INTO `globals` (`gl_name`, `gl_index`, `gl_value`) VALUES ('css_header', 0, 'style_cobalt_blue.css');
COMMIT;
#EndIf

-- Do not require the user's email at sign-in.
#IfNotRow2D globals gl_name enforce_signin_email gl_value 0
START TRANSACTION;
REPLACE INTO `globals` (`gl_name`, `gl_index`, `gl_value`) VALUES ('enforce_signin_email', 0, '0');
COMMIT;
#EndIf

-- Password expiration: 365 days.
#IfNotRow2D globals gl_name password_expiration_days gl_value 365
START TRANSACTION;
REPLACE INTO `globals` (`gl_name`, `gl_index`, `gl_value`) VALUES ('password_expiration_days', 0, '365');
COMMIT;
#EndIf
