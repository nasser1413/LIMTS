<?php
  /* This file contains all of the constants used to access and manipulate
   *  the database.
   *
   * Created by: Justin Szaday on Wed. Mar 25, 2014
   */

    // Connection information (host, uname, pwd)
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "scheduler";

    // Day abbreviations as given by the Registrar
    $day_abbreviations = array(
        "U" => 1,
        "M" => 2,
        "T" => 3,
        "W" => 4,
        "R" => 5,
        "F" => 6,
        "S" => 7 );

    // Meeting time date regex format (XX X:XX[ap]-X:XX[ap])
    $date_regex = "/([A-Z]+) *(\d+:\d+[ap])-(\d+:\d+[ap])/";

    // Section Column information
    define("USER_ID", "UserID");
    define("USER_USERNAME", "Username");
    define("USER_FIRSTNAME", "FirstName");
    define("USER_LASTNAME", "LastName");
    define("USER_PASSWORD", "Password");
    define("USER_SALT", "Salt");
    define("SESSION_VALID", "Valid");
    define("SESSION_MESSAGE", "Message");


    // Section Column information
    define("SECTION_DBID", 0);
    define("SECTION_ID", 1);
    define("SECTION_ROOMS", 2);
    define("SECTION_SEM", 3);
    define("SECTION_CLASS", 4);
    define("SECTION_PROF", 5);
    define("SECTION_TIMES", 6);
    define("SECTION_TYPE", 7);
    define("SECTION_MCAP", 8);
    define("SECTION_CRHR", 9);
    define("SECTION_TLC", 10);

    // Section Column information
    define("SECTION_DBID", 0);
    define("SECTION_ID", 1);
    define("SECTION_ROOMS", 2);
    define("SECTION_SEM", 3);
    define("SECTION_CLASS", 4);
    define("SECTION_PROF", 5);
    define("SECTION_TIMES", 6);
    define("SECTION_TYPE", 7);
    define("SECTION_MCAP", 8);
    define("SECTION_CRHR", 9);

    // Section Types
    define("SECTION_TYPE_NORMAL", 1);
    define("SECTION_TYPE_TBA", 2);
    define("SECTION_TYPE_ODD", 3);
    define("SECTION_TYPE_EVEN", 4);
    define("SECTION_TYPE_ONLINE", 5);

    // Room Column information
    define("ROOM_ID", 0);
    define("ROOM_BLDG", 1);
    define("ROOM_NMBR", 2);
    define("ROOM_CAP", 3);

    // Professor Information
    define("PROFESSOR_ID", 0);
    define("PROFESSOR_NAME", 1);
    define("PROFESSOR_MAXHRS", 2);
    define("PROFESSOR_TYPE", 3);
    define("PROFESSOR_VID", 4);

    // Professor Type Information
    define("PROFESSORTYPE_ID", 0);
    define("PROFESSORTYPE_NAME", 1);
    define("PROFESSORTYPE_CRHR", 2);

    // Class Information
    define("CLASS_ID", 0);
    define("CLASS_NAME", 1);
    define("CLASS_CREDITHOURS", 2);
    define("CLASS_CONTACTHOURS", 3);
    define("CLASS_TITLE", 4);

    // Semester Information
    define("SEMESTER_ID", 0);
    define("SEMESTER_NAME", 1);
    define("SEMESTER_TYPE", 2);
    define("SEMESTER_START", 3);
    define("SEMESTER_END", 4);

    // Building Information
    define("BUILDING_ID", 0);
    define("BUILDING_DESC", 1);
    define("BUILDING_ABRV", 2);

    // Semester Types
    define("EVERY_WEEK", 1);
    define("EVEN_WEEKS", 2);
    define("ODD_WEEKS", 3);
?>
