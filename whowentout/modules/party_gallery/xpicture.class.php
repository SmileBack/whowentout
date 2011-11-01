<?php

class XPicture extends XObject
{

    protected static $table = 'pictures';

    static function createFromUpload($files_upload_key)
    {
        if (!isset($_FILES[$files_upload_key]))
            throw new Exception("Upload $files_upload_key doesn't exist");
        
        $pic = XPicture::create(array(
                                     'variations' => 'source',
                                ));

        $pic->refresh_variation('source', array(
                                               'files_upload_key' => $files_upload_key,
                                          ));

        return $pic;
    }

    function url($variation)
    {
        return $this->get_image_url($this->get_variation_filename($variation));
    }

    function refresh_variation($variation, $options = array())
    {
        $fn = "refresh_{$variation}_variation";
        $this->$fn($options);

        $variations = explode(',', $this->variations);
        $variations[] = $variation;

        sort($variations);
        $variations = array_unique($variations);
        $variations = implode(',', $variations);

        $this->variations = $variations;

        $this->save();
    }

    function refresh_source_variation($options = array())
    {
        $files_upload_key = 'pic';

        if (isset($_FILES[$files_upload_key])) {
            $this->load_wide_image();
            $img = WideImage::loadFromUpload($files_upload_key);

            $filename = $this->get_variation_filename('source');
            $temp_image_path = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
            $img->saveToFile($temp_image_path);
            $this->save_image($filename, $temp_image_path);

            $this->refresh_variation('thumb');
            $this->refresh_variation('large');
        }
    }

    function refresh_thumb_variation($options = array())
    {
        $this->load_wide_image();
        $img = WideImage::load($this->url('source'));
        $img = $img->resize(100, 100);

        $filename = $this->get_variation_filename('thumb');
        $temp_image_path = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
        $img->saveToFile($temp_image_path);
        $this->save_image($filename, $temp_image_path);
    }

    function refresh_large_variation($options = array())
    {
        $this->load_wide_image();
        $img = WideImage::load($this->url('source'));
        $img = $img->resize(500, 500);

        $filename = $this->get_variation_filename('large');
        $temp_image_path = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
        $img->saveToFile($temp_image_path);
        $this->save_image($filename, $temp_image_path);
    }

    private function get_variation_filename($variation)
    {
        return "{$this->id}.$variation.jpg";
    }

    private function get_image_url($filename)
    {
        $ci =& get_instance();
        return $ci->storage->url('gallery_pics', $filename);
    }

    function delete_variation($variation)
    {
        $this->delete_image($this->get_variation_filename($variation));
    }

    private function save_image($dest_filename, $source_filename)
    {
        $ci =& get_instance();
        $ci->storage->save('gallery_pics', $dest_filename, $source_filename);
    }

    private function delete_image($filename)
    {
        $ci =& get_instance();
        $ci->storage->delete('gallery_pics', $filename);
    }

    function delete()
    {
        $variations = explode(',', $this->variations);
        foreach ($variations as $v) {
            $this->delete_variation($v);
        }
        parent::delete();
    }

    private function load_wide_image()
    {
        require_once APPPATH . 'third_party/wideimage/WideImage.php';
    }

}
