<?php

class XGallery extends XObject
{

    protected static $table = 'galleries';

    function pictures()
    {
        $query = $this->db()->select('picture_id AS id')
                            ->from('gallery_pictures')
                            ->where('gallery_id', $this->id);
        return XObject::load_objects('XPicture', $query);
    }

    function add_picture(XPicture $pic)
    {
        $this->db()->insert('gallery_pictures', array(
                                                     'gallery_id' => $this->id,
                                                     'picture_id' => $pic->id,
                                                ));
    }

    function remove_picture(XPicture $pic)
    {
        $this->db()->delete('gallery_pictures', array(
                                                     'gallery_id' => $this->id,
                                                     'picture_id' => $pic->id,
                                                ));
    }

}
