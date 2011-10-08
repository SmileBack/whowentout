<?php

abstract class StorageDriver extends Driver
{
    
    abstract function save($destFilename, $sourceFilepath);

    abstract function getText($filename);

    abstract function saveText($destFilename, $text);

    abstract function exists($filename);

    abstract function delete($filename);

    abstract function url($filename);

}
