<?php

// Constants
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
define('BACK', __DIR__ . '/../.couscous/generated/');
define('FRONT', __DIR__ . '/../../');

/**
 * Standalone File Helper
 * ---
 * @author     Tell Konkle <tellkonkle@gmail.com>
 * @copyright  (c) 2011-2018 Tell Konkle. All rights reserved.
 */
class Tell_Standalone_File
{
    /**
     * Recursively copy a file or directory.
     * ---
     * @param   string  Source file or directory.
     * @param   string  Destination path of file or directory.
     * @return  bool    TRUE if copied successfully.
     */
    public static function copy($from, $to)
    {
        // Must be a file or directory
        if ( ! file_exists($from)) {
            throw new RuntimeException('Cannot copy a non-existent path: ' . $from);
        }

        // Copy a file
        if (is_file($from)) {
            // Create blank destination file if needed (so directories exist)
            self::mkfile($to);

            // Failed to copy file
            if ( ! @copy($from, $to)) {
                throw new RuntimeException('Failed to copy file to: ' . $to);
            }
        // Copy a directory
        } else {
            // Create destination directory if needed
            self::mkdir($to);

            // (object) Instance of Directory
            $dir = dir($from);

            // (string) Normalize directory names
            $from = rtrim($from, '/\\') . DS;
            $to   = rtrim($to,   '/\\') . DS;

            // [recursion] Loop through and copy all files and sub-directories
            while (FALSE !== $path = $dir->read()) {
                if ('.' !== $path && '..' !== $path) {
                    static::copy($from . $path, $to . $path);
                }
            }
        }

        // Successful if no exceptions are thrown
        return TRUE;
    }

    /**
     * Recursively make a directory.
     * ---
     * @param   string|array|object  Traversable instance or string/array of directories.
     * @return  bool                 TRUE if created successfully.
     */
    public static function mkdir($path)
    {
        // (array) Convert Traversable object to array
        if ($path instanceof Traversable) {
            $path = iterator_to_array($path, FALSE);
        }

        // Loop through each directory to create (convert string to array if needed)
        foreach ((array) $path as $p) {
            // Directory doesn't exist, create it now
            if ( ! is_dir($p)) {
                // [recursion] Create parent directory if needed
                static::mkdir(dirname($p));

                // Failed to create directory
                if ( ! @mkdir($p)) {
                    throw new RuntimeException('Failed to create directory: ' . $p);
                }
            }
        }

        // Successful if no exceptions thrown
        return TRUE;
    }

    /**
     * Make a file and recursively make directory it should be placed in (if applicable).
     * ---
     * @param   string|array|object  Traversable instance or string/array of file paths.
     * @param   string               File contents. Replaces existing file contents.
     * @return  bool                 TRUE if created successfully.
     */
    public static function mkfile($path, $contents = NULL)
    {
        // (array) Convert Traversable object to array
        if ($path instanceof Traversable) {
            $path = iterator_to_array($path, FALSE);
        }

        // Loop through each path to create (convert string to array if needed)
        foreach ((array) $path as $p) {
            // File doesn't exist, create it now
            if ( ! is_file($p)) {
                // [recursion] Create parent directory if needed
                static::mkdir(dirname($p));

                // Failed to create file
                if (FALSE === @file_put_contents($p, $contents)) {
                    throw new RuntimeException('Failed to create file: ' . $p);
                }
            // File already exists but cannot be updated
            } elseif (is_file($p) && $contents && ! @file_put_contents($p, $contents)) {
                throw new RuntimeException('Failed to update file: ' . $p);
            }
        }

        // Successful if no exceptions thrown
        return TRUE;
    }

    /**
     * Delete a file or a directory.
     * ---
     * @param   string|array|object  Traversable instance or string/array of paths.
     * @return  bool                 TRUE if deleted successfully.
     */
    public static function remove($path)
    {
        // (array) Convert Traversable object to array
        if ($path instanceof Traversable) {
            $path = iterator_to_array($path, FALSE);
        }

        // Loop through each path to delete (convert string to array if needed)
        foreach ((array) $path as $p) {
            // Failed to delete file
            if (is_file($p) && ! @unlink($p)) {
                throw new RuntimeException('Cannot remove file: ' . $p);
            // Delete a directory
            } elseif (is_dir($p)) {
                // Try loosening file permissions before deleting
                @chmod($p, 0777);

                // (string) Normalize path
                $p = rtrim($p, '/\\');

                // [recursion] Must first delete all files in directory
                foreach (glob($p . '/{,.}[!.,!..]*', GLOB_MARK|GLOB_BRACE) as $sp) {
                    static::remove($sp);
                }

                // Failed to delete directory
                if ( ! @rmdir($p)) {
                    throw new RuntimeException('Cannot remove directory: ' . $p);
                }
            }
        }

        // Successful if no exceptions thrown
        return TRUE;
    }
}