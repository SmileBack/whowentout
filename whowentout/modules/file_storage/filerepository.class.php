<?php

class FileRepository
{

    /* @var $driver FileRepositoryDriver */
    private $driver;

    protected $options = array();
    
    function __construct($options = array())
    {
        $this->options = $options;
        $this->driver = f()->class_loader()->init_subclass('FileRepositoryDriver', $this->options['driver'], $this->options);
    }

    function create($destination_filename, $source_filepath, $metadata = array())
    {
        $this->create_without_metadata($destination_filename, $source_filepath);
        $this->save_metadata($destination_filename, $metadata);
    }

    private function create_without_metadata($destination_filename, $source_filepath)
    {
        $this->driver->create($destination_filename, $source_filepath);
    }

    function delete($filename)
    {
        $this->driver->delete($filename);
        $this->delete_metadata($filename);
    }

    function exists($filename)
    {
        return $this->driver->exists($filename);
    }

    function url($filename)
    {
        return $this->driver->url($filename);
    }

    function get_file_names()
    {
        return $this->driver->get_file_names();
    }

    function create_from_text($destination_filename, $text)
    {
        $temp_filepath = tempnam(sys_get_temp_dir(), 'filerepository_temp_');
        file_put_contents($temp_filepath, $text);
        $this->create_without_metadata($destination_filename, $temp_filepath);
        @unlink($temp_filepath);
    }

    function create_from_data($destination_filename, $data)
    {
        $serialized_data = json_encode($data);
        $this->create_from_text($destination_filename, $serialized_data);
    }
    
    function load_metadata($filename)
    {
        
    }

    function save_metadata($filename, $metadata)
    {
        $this->create_from_data($this->metadata_filename($filename), $metadata);
    }
    
    function delete_metadata($filename)
    {
        $this->delete($this->metadata_filename($filename));
    }

    private function metadata_filename($filename)
    {
        return "$filename.meta";
    }

}
