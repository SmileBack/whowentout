<?php

class S3StorageDriver extends StorageDriver
{

    private $s3;

    function __construct($config)
    {
        parent::__construct($config);

        $ci =& get_instance();
        $this->amazon_public_key = $ci->config->item('amazon_public_key');
        $this->amazon_secret_key = $ci->config->item('amazon_secret_key');
    }

    function save($destFilename, $sourceFilepath)
    {
        $response = $this->s3()->create_object($this->config['bucket'], $destFilename, array(
                                                                                            'fileUpload' => $sourceFilepath,
                                                                                            'acl' => AmazonS3::ACL_PUBLIC,
                                                                                       ));
    }

    function getText($filename)
    {
        return file_get_contents($this->url($filename));
    }

    function saveText($destFilename, $text)
    {
        $this->s3()->create_object($this->config['bucket'], $destFilename, array(
                                                                                'body' => $text,
                                                                                'acl' => AmazonS3::ACL_PUBLIC,
                                                                           ));
    }

    function exists($filename)
    {
        return $this->s3()->if_object_exists($this->config['bucket'], $filename);
    }

    function delete($filename)
    {
        $response = $this->s3()->delete_object($this->config['bucket'], $filename);
    }

    function url($filename)
    {
        return $this->s3()->get_object_url($this->config['bucket'], $filename);
    }

    /**
     * @return AmazonS3
     */
    function s3()
    {
        if ($this->s3 == NULL) {
            require_once APPPATH . 'third_party/aws/sdk.class.php';
            $this->s3 = new AmazonS3($this->amazon_public_key, $this->amazon_secret_key);
            $this->s3()->use_ssl = false;
        }
        return $this->s3;
    }

}
