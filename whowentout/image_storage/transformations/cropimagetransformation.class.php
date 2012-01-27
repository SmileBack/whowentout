<?php

class CropImageTransformation extends ImageTransformation
{
    function transform(WideImage_Image $image)
    {
        if ($this->crop_out_of_bounds($image))
            throw new CropOutOfBoundsException("Can't crop an image outside of its bounds.");

        return $image->crop($this->options['x'], $this->options['y'], $this->options['width'], $this->options['height']);
    }

    private function crop_out_of_bounds(WideImage_Image $image)
    {
        return $this->options['x'] < 0 || $this->options['y'] < 0
                    || $this->options['width'] == 0 || $this->options['height'] == 0
                    || $this->options['x'] + $this->options['width'] > $image->getWidth()
                    || $this->options['y'] + $this->options['height'] > $image->getHeight();
    }

}
