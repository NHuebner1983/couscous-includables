<?php

/**
 * This script is automatically ran BEFORE Couscous regenerates the documentation.
 */
require_once 'helpers.php';

Tell_Standalone_File::remove(FRONT . 'css');
Tell_Standalone_File::remove(FRONT . 'fonts');
Tell_Standalone_File::remove(FRONT . 'intro');
Tell_Standalone_File::remove(FRONT . 'js');
Tell_Standalone_File::remove(FRONT . 'template');
Tell_Standalone_File::remove(FRONT . '.htaccess');
Tell_Standalone_File::remove(FRONT . 'index.php');
