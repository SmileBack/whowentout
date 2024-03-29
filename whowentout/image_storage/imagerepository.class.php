<?php

class ImageRepository
{

    /* @var $storage FileRepository */
    private $file_repository;

    function __construct(FileRepository $file_repository)
    {
        $this->file_repository = $file_repository;
    }

    function url($key, $variation = 'source')
    {
        $filename = $this->filename($key, $variation);
        return $this->file_repository->url($filename);// . '?version=' . $this->get_version($key); TODO: add back and cache
    }

    function exists($key)
    {
        return $this->file_repository->exists($this->filename($key, 'source'));
    }

    function create_from_upload($key, $field_name)
    {
        if (!$this->is_valid_image_upload($field_name))
            throw new InvalidImageException();
        
        $img = WideImage::loadFromUpload($field_name);
        $this->create_from_image($key, $img);
    }

    function create_from_filepath($key, $filepath)
    {
        if (!$this->is_valid_image($filepath))
            throw new InvalidImageException();
        
        $img = $this->load_image_from_filepath($filepath);
        $this->create_from_image($key, $img);
    }

    function load_image($key, $variation = 'source')
    {
        $url = $this->file_repository->url($this->filename($key, $variation));
        return $this->load_image_from_filepath($url);
    }

    /**
     *
     * @param WideImage $img
     * @param int $id
     * @param string $preset
     */
    function create_from_image($key, WideImage_Image $img)
    {
        $version = $this->get_version($key);

        $this->save_image_variation($key, 'source', $img);

        $this->set_version($key, $version + 1);
    }

    function update_variations($key, $variation_transformations)
    {
        $version = $this->get_version($key);

        $source = $this->load_image($key);
        foreach ($variation_transformations as $variation => $transformations) {
            $image = $this->apply_transformations($source, $transformations);
            $this->save_image_variation($key, $variation, $image);
        }

        $this->set_version($key, $version + 1);
    }

    function delete($key)
    {
        $variations = array('source', 'thumb', 'normal'); //todo: remove hard-coding by storing in metadata
        foreach ($variations as $cur_variation) {
            $this->file_repository->delete($this->filename($key, $cur_variation));
        }
    }

    private function is_valid_image_upload($field_name)
    {
        $upload_filepath = $this->get_upload_filepath($field_name);
        return $this->is_valid_image($upload_filepath);
    }

    private function is_valid_image($filepath)
    {
        return getimagesize($filepath) !== FALSE;
    }

    private function get_upload_filepath($field_name)
    {
        $file = $_FILES[$field_name];
        $filepath = $file['tmp_name'];
        return $filepath;
    }

    private function filename($key, $variation)
    {
        return "$key.$variation.jpg";
    }

    private $images = array();

    /**
     * @param  $filepath
     * @return WideImage_Image
     */
    private function load_image_from_filepath($filepath)
    {
        if (!isset($this->images[$filepath])) {
            $this->images[$filepath] = WideImage::loadFromFile($filepath);
        }
        return $this->images[$filepath];
    }

    private function apply_transformations(WideImage_Image $image, $transformation_options)
    {
        $compound_image_transformation = new CompoundImageTransformation($transformation_options);
        return $compound_image_transformation->transform($image);
    }

    private function get_version($key)
    {
        $filename = $this->filename($key, 'source');
        $metadata = $this->file_repository->load_metadata($filename);
        return is_array($metadata) && isset($metadata['version'])
                ? $metadata['version']
                : 1;
    }

    private function set_version($key, $version)
    {
        $filename = $this->filename($key, 'source');

        $metadata = $this->file_repository->load_metadata($filename);
        $metadata['version'] = $version;
        $this->file_repository->save_metadata($filename, $metadata);
    }

    private function save_image_variation($key, $variation, WideImage_Image $img)
    {
        $temp_image_filepath = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
        $img->saveToFile($temp_image_filepath);
        $this->file_repository->create("$key.$variation.jpg", $temp_image_filepath);
    }

}
