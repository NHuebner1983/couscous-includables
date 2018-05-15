<?php

/**
 * This script is automatically ran AFTER Couscous regenerates the documentation.
 */

require_once 'helpers.php';

Tell_Standalone_File::copy(BACK, FRONT);
Tell_Standalone_File::copy(__DIR__ . '/index.php', FRONT . 'index.php');
Tell_Standalone_File::remove(FRONT . '.gitignore');
