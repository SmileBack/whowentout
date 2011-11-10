<?php

class S3FileRepository extends FileRepository
{

    private $options = array();

    function __construct($options)
    {
        $this->options = $options;
    }

    function create($destination_filename, $source_filepath)
    {
        $response = $this->s3()->create_object($this->options['bucket'], $destination_filename, array(
                                                                                                    'fileUpload' => $source_filepath,
                                                                                                    'acl' => AmazonS3::ACL_PUBLIC,
                                                                                               ));
    }

    function delete($filename)
    {
        $response = $this->s3()->delete_object($this->bucket(), $filename);
    }

    function exists($filename)
    {
        return $this->s3()->if_object_exists($this->bucket(), $filename);
    }

    function url($filename)
    {
        return $this->s3()->get_object_url($this->bucket(), $filename);
    }

    function get_file_names()
    {
        return $this->s3()->get_object_list($this->bucket());
    }
    
    private function bucket()
    {
        return $this->options['bucket'];
    }

    private $s3;
    /**
     * @return AmazonS3
     */
    private function s3()
    {
        if ($this->s3 == NULL) {
            require_once APPPATH . 'third_party/aws/sdk.class.php';
            $this->s3 = new AmazonS3($this->options['amazon_public_key'], $this->options['amazon_secret_key']);
            $this->s3()->use_ssl = false;
        }
        return $this->s3;
    }

}
