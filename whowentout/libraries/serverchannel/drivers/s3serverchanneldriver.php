<?php

class S3ServerChannelDriver extends ServerChannelDriver
{

    private $s3;

    public function push($channel, $data)
    {
        $config = (object)$this->config;
        $encoded_data = "json_$channel(" . json_encode($data) . ')';

        $this->s3()->create_object($this->config['bucket'], $channel, array(
                                                                 'body' => $encoded_data,
                                                                 'contentType' => 'application/javascript',
                                                                 'acl' => AmazonS3::ACL_PUBLIC,
                                                            ));
    }

    public function delete($id)
    {
        $this->s3()->delete_object($this->config['bucket'], $id);
    }

    public function url($id)
    {
        return $this->s3()->get_object_url($this->config['bucket'], $id);
    }

    /**
     * @return AmazonS3
     */
    function s3()
    {
        if ($this->s3 == NULL) {
            $this->s3 = new AmazonS3($this->config['amazon_public_key'], $this->config['amazon_secret_key']);
            $this->s3()->use_ssl = false;
        }
        return $this->s3;
    }
    
}
