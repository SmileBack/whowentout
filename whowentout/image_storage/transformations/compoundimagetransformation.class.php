<?php

class CompoundImageTransformation extends ImageTransformation
{

    function transform(WideImage_Image $img)
    {
        $transformed_image = $img;
        foreach ($this->options as $transform_options) {
            $transformed_image = $this->apply_transformation($transformed_image, $transform_options);
        }
        return $transformed_image;
    }
    
    /**
     * @param WideImage_Image $img
     * @param array $options
     * @return WideImage_Image
     */
    private function apply_transformation(WideImage_Image $img, array $options)
    {
        $transformation = $this->load_transformation($options);
        return $transformation->transform($img);
    }

    /**
     * @param WideImage_Image $img
     * @param array $options
     * @return ImageTransformation
     */
    private function load_transformation(array $options)
    {
        $transformation = app()->class_loader()->init_subclass('ImageTransformation', $options['type'], $options);
        return $transformation;
    }

}