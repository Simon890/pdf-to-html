<?php
/**
 * Created by PhpStorm.
 * User: tonchik™
 * Date: 16.09.2015
 * Time: 13:00
 */

namespace TonchikTm\PdfToHtml;

/**
 * This is base class with common properties and methods.
 *
 * @property string $outputDir
 * @property array $options
 * @property array $defaultOptions
 */
class Base
{
    private $outputDir = '';
    private $options = [];

    /**
     * Get all options or one option by key.
     * @param string|null $key
     * @return array|null
     */
    public function getOptions($key=null)
    {
        if ($key) {
            return isset($this->options[$key]) ? $this->options[$key] : null;
        } else {
            return $this->options;
        }
    }

    /**
     * Set options as array or pair key-value.
     * @param $key
     * @param string|null $value
     */
    public function setOptions($key, $value=null)
    {
        if (is_array($key)) {
            $this->options = array_merge($this->options, $key);
        } elseif (is_string($key)) {
            $this->options[$key] = $value;
        }
    }

    /**
     * Get output dir.
     * @return string
     */
    public function getOutputDir()
    {
        return $this->outputDir;
    }

    /**
     * Set output dir.
     * @param string $dir
     * @return $this
     */
    public function setOutputDir($dir)
    {
        $this->setOptions('outputDir', $dir);
        $this->outputDir = $dir;
        return $this;
    }

    /**
     * Clear all files that has been generated by pdftohtml.
     * Make sure directory ONLY contain generated files from pdftohtml,
     * because it remove all contents under preserved output directory
     * @param bool|false $removeSelf
     * @return $this
     */
    public function clearOutputDir($removeSelf=false)
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->getOutputDir(), \FilesystemIterator::SKIP_DOTS));
        foreach($files as $file) {
            $path = (string)$file;
            $basename = basename($path);
            if($basename != '..') {
                if(is_file($path) && file_exists($path))
                    unlink($path);
                elseif(is_dir($path) && file_exists($path))
                    rmdir($path);
            }
        }
        if ($removeSelf) rmdir($this->getOutputDir());
        return $this;
    }
}