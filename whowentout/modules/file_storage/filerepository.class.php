<?php

abstract class FileRepository
{
    abstract function create($destination_filename, $source_filepath);
    abstract function delete($filename);
    abstract function exists($filename);
    abstract function url($filename);
    abstract function get_file_names();

    function create_from_text($destination_filename, $text)
    {
        $temp_filepath = tempnam(sys_get_temp_dir(), 'filerepository_temp_');
        file_put_contents($temp_filepath, $text);
        $this->create($destination_filename, $temp_filepath);
        @unlink($temp_filepath);
    }

    function create_from_data($destination_filename, $data)
    {
        $serialized_data = json_encode($data);
        $this->create_from_text($destination_filename, $serialized_data);
    }

}
