<?php

abstract class FileRepositoryDriver extends Driver
{
    abstract function create($destination_filename, $source_filepath);

    abstract function delete($filename);

    abstract function exists($filename);

    abstract function url($filename);

}
