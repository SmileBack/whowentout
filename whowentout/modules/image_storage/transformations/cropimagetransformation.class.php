<?php

class CropImageTransformation extends ImageTransformation
{
    function transform(WideImage_Image $image)
    {
        return $image->crop($this->options['x'], $this->options['y'], $this->options['width'], $this->options['height']);
    }
}
