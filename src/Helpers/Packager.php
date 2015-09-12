<?php namespace Arcanedev\Workbench\Helpers;

use GuzzleHttp\Client;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use ZipArchive;

/**
 * Class     Packager
 *
 * @package  Arcanedev\Workbench\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Packager
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The filesystem handler.
     *
     * @var object
     */
    protected $files;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new instance.
     *
     * @param  Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Setting custom formatting for the progress bar.
     *
     * @param  object  $bar
     *
     * @return object
     */
    public function barSetup($bar)
    {
        // the finished part of the bar
        $bar->setBarCharacter('<comment>=</comment>');

        // the unfinished part of the bar
        $bar->setEmptyBarCharacter('-');

        // the progress character
        $bar->setProgressCharacter('>');

        // the 'layout' of the bar
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% ');

        return $bar;
    }

    /**
     * Open haystack, find and replace needles, save haystack.
     *
     * @param  string  $oldFile  -  The haystack
     * @param  mixed   $search   -  String or array to look for (the needles)
     * @param  mixed   $replace  -  What to replace the needles for?
     * @param  string  $newFile  -  Where to save, defaults to $oldFile
     */
    public function replaceAndSave($oldFile, $search, $replace, $newFile = null)
    {
        $newFile   = ($newFile === null) ? $oldFile : $newFile ;
        $file      = $this->files->get($oldFile);
        $replacing = str_replace($search, $replace, $file);

        $this->files->put($newFile, $replacing);
    }

    /**
     * Check if the package already exists.
     *
     * @param  string  $path    -  Path to the package directory
     * @param  string  $vendor  -  The vendor
     * @param  string  $name    -  Name of the package
     */
    public function checkExistingPackage($path, $vendor, $name)
    {
        if (is_dir($path . $vendor . '/' . $name)){
            throw new RuntimeException('Package already exists');
        }
    }

    /**
     * Create a directory if it doesn't exist.
     *
     * @param  string  $path  -  Path of the directory to make
     */
    public function makeDir($path)
    {
        if ( ! is_dir($path)) {
            mkdir($path);
        }
    }

    /**
     * Generate a random temporary filename for the package zipfile.
     *
     * @return string
     */
    public function makeFilename()
    {
        return getcwd() . DS . 'package' . md5(time() . uniqid()) . '.zip';
    }

    /**
     * Download the temporary Zip to the given file.
     *
     * @param  string  $zipFile
     * @param  string  $source
     *
     * @return self
     */
    public function download($zipFile, $source)
    {
        $response = (new Client)->get($source);
        file_put_contents($zipFile, $response->getBody());

        return $this;
    }

    /**
     * Extract the zip file into the given directory.
     *
     * @param  string  $zipFile
     * @param  string  $directory
     *
     * @return self
     */
    public function extract($zipFile, $directory)
    {
        $archive = new ZipArchive;

        $archive->open($zipFile);
        $archive->extractTo($directory);
        $archive->close();

        return $this;
    }

    /**
     * Clean-up the Zip file.
     *
     * @param  string  $zipFile
     *
     * @return self
     */
    public function cleanUp($zipFile)
    {
        @chmod($zipFile, 0777);
        @unlink($zipFile);

        return $this;
    }
}
