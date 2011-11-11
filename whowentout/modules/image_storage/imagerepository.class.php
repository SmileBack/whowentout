<?php

require_once APPPATH . 'third_party/wideimage/wideimage.php';

class ImageRepository
{

    /* @var $storage FileRepository */
    private $storage;

    function __construct(FileRepository $storage)
    {
        $this->storage = $storage;
    }

    function url($key, $variation = 'source')
    {
        $filename = $this->filename($key, $variation);
        return $this->storage->url($filename) . '?version=' . $this->get_version($key);
    }

    function exists($key)
    {
        return $this->storage->exists($this->filename($key, 'source'));
    }

    function create_from_upload($key, $field_name)
    {
        $img = WideImage::loadFromUpload($field_name);
        $this->create_from_image($key, $img);
    }

    function create_from_filepath($key, $filepath)
    {
        $img = $this->load_image_from_filepath($filepath);
        $this->create_from_image($key, $img);
    }

    function load_image($key, $variation = 'source')
    {
        $url = $this->storage->url($this->filename($key, $variation));
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
        $variations = array('source', 'thumb', 'normal');
        foreach ($variations as $cur_variation) {
            $this->storage->delete($this->filename($key, $cur_variation));
        }
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
        $metadata = $this->storage->load_metadata($filename);
        return is_array($metadata) && isset($metadata['version'])
                ? $metadata['version']
                : 1;
    }

    private function set_version($key, $version)
    {
        $filename = $this->filename($key, 'source');

        $metadata = $this->storage->load_metadata($filename);
        $metadata['version'] = $version;
        $this->storage->save_metadata($filename, $metadata);
    }

    private function save_image_variation($key, $variation, WideImage_Image $img)
    {
        $temp_image_filepath = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
        $img->saveToFile($temp_image_filepath);
        $this->storage->create("$key.$variation.jpg", $temp_image_filepath);
    }

}
