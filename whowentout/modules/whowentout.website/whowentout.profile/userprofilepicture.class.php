<?php

class UserProfilePicture
{

    /* @var $user XUser */
    private $user;

    function __construct(XUser $user)
    {
        $this->user = $user;
    }

    function img($size = 'normal')
    {
        return img($this->url($size));
    }

    function url($size = 'normal')
    {
        return images()->url($this->user->id, $size);
    }

    function refresh_image($preset)
    {
        images()->refresh($this->user->id, $preset);
    }

    function set_to_upload()
    {
        $this->refresh_image('upload');

        $logger = new UserEventLogger();
        $logger->log($this->user, college()->get_time(), 'user_upload_pic');
    }

    function set_to_facebook()
    {
        images()->delete($this->user->id, 'upload');
        images()->delete($this->user->id, 'source');
        images()->refresh($this->user->id, 'facebook');

        $logger = new UserEventLogger();
        $logger->log($this->user, college()->get_time(), 'user_use_facebook_pic');
    }

    /**
     * @return WideImage
     */
    function get_image_handle($preset)
    {
        return images()->get($this->user->id, $preset);
    }

    function has_image()
    {
        return images()->exists($this->user->id, 'source');
    }

    function crop($x, $y, $width, $height)
    {
        $this->user->pic_x = $x;
        $this->user->pic_y = $y;
        $this->user->pic_width = $width;
        $this->user->pic_height = $height;

        $this->refresh_image('normal');
        $this->refresh_image('thumb');
    }

    function get_crop_box()
    {
        return (object)array(
            'x' => $this->user->pic_x,
            'y' => $this->user->pic_y,
            'width' => $this->user->pic_width,
            'height' => $this->user->pic_height,
        );
    }

}
