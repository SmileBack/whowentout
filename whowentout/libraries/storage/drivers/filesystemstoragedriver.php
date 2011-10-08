<?php

class FilesystemStorageDriver extends StorageDriver
{

    function bucket()
    {
        return $this->config['bucket'];
    }

    function save($destFilename, $sourceFilepath)
    {
        copy($sourceFilepath, $this->filepath($destFilename));
    }

    function getText($filename)
    {
        return file_get_contents($this->filepath($filename));
    }

    function saveText($destFilename, $text)
    {
        file_put_contents($this->filepath($destFilename), $text, LOCK_EX);
    }

    function delete($filename)
    {
        @unlink($this->filepath($filename));
    }

    function exists($filename)
    {
        return file_exists($this->filepath($filename));
    }

    function url($filename)
    {
        return site_url($this->filepath($filename));
    }

    private function filepath($filename)
    {
        $bucket = $this->bucket();
        return "$bucket/$filename";
    }

}
