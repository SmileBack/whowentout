<?php

class ImageRepository
{

    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->library('storage');
    }

    /**
     *
     * @param WideImage $img
     * @param int $id
     * @param string $preset
     */
    function saveImage($img, $id, $preset)
    {
        $temp_image_path = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
        $img->saveToFile($temp_image_path);
        $filename = $this->filename($id, $preset);
        $this->ci->storage->save('pics', $filename, $temp_image_path);
        
        $user = user($id);
        $user->pic_version++;
        $user->save();
    }

    /**
     * @return WideImage
     */
    function get($id, $preset)
    {
        $this->load_wide_image();

        if (!$this->exists($id, $preset))
            return NULL;

        return WideImage::load( $this->url($id, $preset) );
    }
    
    function url($id, $preset)
    {
//        if ( ! $this->exists($id, $preset)) {
//            $this->refresh($id, $preset);
//        }
        $user = user($id);

        $filename = $this->filename($id, $preset);
        return $this->ci->storage->url('pics', $filename) . "?version=$user->pic_version";
    }

    function exists($id, $preset)
    {
        $filename = $this->filename($id, $preset);
        return $this->ci->storage->exists('pics', $filename);
    }

    function delete($id, $preset)
    {
        $filename = $this->filename($id, $preset);
        $this->ci->storage->delete('pics', $filename);
    }

    function filename($id, $preset)
    {
        return "$id.$preset.jpg";
    }

    function refresh($id, $preset)
    {
        $method = "refresh_$preset";
        $this->$method($id);
    }

    protected function load_wide_image()
    {
        require_once APPPATH . 'third_party/wideimage/WideImage.php';
    }

    protected function refresh_facebook($id)
    {
        $user = user($id);

        if (!$user->facebook_id)
            return;

        $this->load_wide_image();
        $facebook_pic_url = "https://graph.facebook.com/$user->facebook_id/picture?type=large&access_token=" . fb()->getAccessToken();
        $img = WideImage::loadFromFile($facebook_pic_url);
        $this->saveImage($img, $id, 'facebook');

        $this->refresh($id, 'source');
        $this->refresh($id, 'normal');
        $this->refresh($id, 'thumb');
    }

    protected function refresh_upload($id)
    {
        $user = user($id);

        $file = $_FILES['upload_pic'];
        $filepath = $file['tmp_name'];
        $filename = $file['name'];

        if (!$this->is_valid_image($filepath)) { //Invalid image
            return;
        }

        $this->load_wide_image();
        $img = WideImage::loadFromUpload('upload_pic');
        $this->saveImage($img, $id, 'upload');

        $this->refresh($id, 'source');
        $this->refresh($id, 'normal');
        $this->refresh($id, 'thumb');
    }

    function refresh_source($id)
    {
        if ($this->exists($id, 'upload')) {
            $img = $this->get($id, 'upload');
        }
        elseif ($this->exists($id, 'facebook')) {
            $img = $this->get($id, 'facebook');
        }
        else {
            $this->refresh($id, 'facebook');
            $img = $this->get($id, 'facebook');
        }
        $this->saveImage($img, $id, 'source');

        $this->set_default_crop_box($id);
    }

    protected function refresh_normal($id)
    {
        $this->load_wide_image();

        $user = user($id);
        $url = $this->url($id, 'source');
        $img = WideImage::load($url)
                ->crop($user->pic_x, $user->pic_y, $user->pic_width, $user->pic_height)
                ->resize(150, 200);
        $this->saveImage($img, $id, 'normal');
    }

    protected function refresh_thumb($id)
    {
        $this->load_wide_image();
        $user = user($id);
        $url = $this->url($id, 'source');
        $img = WideImage::load($url)
                ->crop($user->pic_x, $user->pic_y, $user->pic_width, $user->pic_height)
                ->resize(105, 140);
        $this->saveImage($img, $id, 'thumb');
    }

    function set_default_crop_box($id)
    {
        $user = user($id);

        $padding = 20;

        $img = $this->get($id, 'source');

        $img_width = $img->getWidth();
        $img_height = $img->getHeight();

        if ($img_width / $img_height > 3 / 4) {
            $user->pic_height = $img_height - $padding * 2;
            $user->pic_width = $user->pic_height * (3 / 4);

            $user->pic_x = $img_width / 2 - $user->pic_width / 2;
            $user->pic_y = $img_height / 2 - $user->pic_height / 2;
            $user->save();
        }
        else {
            $user->pic_x = $padding;
            $user->pic_y = $padding;
            $user->pic_width = $img_width - $padding * 2;
            $user->pic_height = $user->pic_width * (4 / 3);
            $user->save();
        }
    }

    private function is_valid_image($filepath)
    {
        return getimagesize($filepath) !== FALSE;
    }

}
