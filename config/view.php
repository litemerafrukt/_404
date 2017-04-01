<?php
/**
 * Config-file for view container.
 *
 */
return [

    // Paths to look for views, without ending slash.
    "path" => [
        ANAX_APP_PATH . "/templates",
        ANAX_INSTALL_PATH . "/templates",
    ],

    // File suffix for template files
    //"suffix" => ".tpl.php",
    "suffix" => ".php",

];
