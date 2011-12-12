<?php

class ProfilePicture
{

    /* @var $database Database */
    private $database;

    /* @var $table DatabaseTable */
    private $table;
    
    /* @var $row DatabaseRow */
    private $row;
    
    /* @var $image_repository ImageRepository */
    private $image_repository;

    /* @var $user DatabaseRow */
    private $user;
    
    function __construct(Database $database, ImageRepository $image_repository, $user)
    {
        $this->database = $database;
        $this->image_repository = $image_repository;
        $this->user = $user;

        $this->table = $this->database->table('profile_pictures');
        $this->load_profile_picture_row();
    }

    function url($size)
    {
        return $this->image_repository->url($this->user->id, $size);
    }

    function set_to_upload($field_name)
    {
        $this->image_repository->create_from_upload($this->user->id, $field_name);

        $this->set_default_crop_box();
        $this->update_variations();
        $this->update_version();
    }

    function set_to_facebook()
    {
        $facebook_image_url = $this->get_facebook_image_url($this->user);
        $this->image_repository->create_from_filepath($this->user->id, $facebook_image_url);

        $this->set_default_crop_box();
        $this->update_variations();
        $this->update_version();
    }
    
    function delete()
    {
        $this->image_repository->delete($this->user->id);
    }

    function is_missing()
    {
        return !$this->image_repository->exists($this->user->id);
    }

    private function update_variations()
    {
        $box = $this->get_crop_box();
        $variations = $this->get_variations($box);
        $this->image_repository->update_variations($this->user->id, $variations);
    }

    private function update_version()
    {
        $this->row->version++;
        $this->row->save();
    }

    private function get_version()
    {
        return $this->row->version;
    }

    private function get_variations($box)
    {
        $variations = array(
            'thumb' => array(
                array('type' => 'crop', 'x' => $box->x, 'y' => $box->y, 'width' => $box->width, 'height' => $box->height),
                array('type' => 'resize', 'width' => 105, 'height' => 140),
            ),
            'normal' => array(
                array('type' => 'crop', 'x' => $box->x, 'y' => $box->y, 'width' => $box->width, 'height' => $box->height),
                array('type' => 'resize', 'width' => 150, 'height' => 200),
            ),
        );
        return $variations;
    }

    function crop($x, $y, $width, $height)
    {
        $this->set_crop_box(array(
                                 'x' => $x, 'y' => $y, 'width' => $width, 'height' => $height,
                            ));
        $this->update_variations();
        $this->update_version();
    }

    private function get_facebook_image_url($user)
    {
        $facebook_id = $user->facebook_id;
        return "https://graph.facebook.com/$facebook_id/picture?type=large";
    }

    function set_default_crop_box()
    {
        $image = $this->image_repository->load_image($this->user->id, 'source');
        $box = $this->get_default_crop_box($image);
        $this->set_crop_box($box);
    }

    function set_crop_box($crop_box)
    {
        $crop_box = (object)$crop_box;

        $this->row->crop_x = $crop_box->x;
        $this->row->crop_y = $crop_box->y;
        $this->row->crop_width = $crop_box->width;
        $this->row->crop_height = $crop_box->height;
        $this->row->save();
    }

    function get_crop_box()
    {
        return (object)array(
            'x' => $this->row->crop_x,
            'y' => $this->row->crop_y,
            'width' => $this->row->crop_width,
            'height' => $this->row->crop_height,
        );
    }

    private function get_default_crop_box(WideImage_Image $img)
    {
        $crop_box = new stdClass();

        $padding = 20;

        $img_width = $img->getWidth();
        $img_height = $img->getHeight();

        if ($img_width / $img_height > 3 / 4) {
            $crop_box->height = $img_height - $padding * 2;
            $crop_box->width = $crop_box->height * (3 / 4);

            $crop_box->x = $img_width / 2 - $crop_box->width / 2;
            $crop_box->y = $img_height / 2 - $crop_box->height / 2;
        }
        else {
            $crop_box->x = $padding;
            $crop_box->y = $padding;
            $crop_box->width = $img_width - $padding * 2;
            $crop_box->height = $crop_box->width * (4 / 3);
        }

        $crop_box->x = round($crop_box->x);
        $crop_box->y = round($crop_box->y);
        $crop_box->width = round($crop_box->width);
        $crop_box->height = round($crop_box->height);

        return $crop_box;
    }

    private function load_profile_picture_row()
    {
        $this->row = $this->table->where('user_id', $this->user->id)->first();
        if (!$this->row) {
            $this->row = $this->table->create_row(array(
                                                       'user_id' => $this->user->id,
                                                       'version' => 0,
                                                  ));
            $this->set_to_facebook();
        }
    }
    
}
