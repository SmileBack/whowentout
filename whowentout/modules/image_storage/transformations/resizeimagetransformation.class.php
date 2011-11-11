<?php

class ResizeImageTransformation extends ImageTransformation
{
    
    function transform(WideImage_Image $img)
    {
        return $img->resize($this->options['width'], $this->options['height']);
    }

}
