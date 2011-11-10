<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function index()
    {
        $s3_storage = new FileRepository(array(
                                              'driver' => 's3',
                                              'amazon_public_key' => '0N83TDC3E416BETER2R2',
                                              'amazon_secret_key' => 'sKpMFrppw9X2KtuvUgJRyZo+O7yvYPluC4ttAwWK',
                                              'bucket' => 'whowasout_pics',
                                         ));

        $local_storage = new FileRepository(array(
                                                 'driver' => 'local',
                                                 'path' => 'teststorage',
                                                 'base_url' => base_url(),
                                            ));

        $local_storage->delete('test.png');
//        $local_storage->create('test.png', 'assets/images/empty_picture.png');


        //        $image_repository = new ImageRepository($local_storage);
        //
        //        $img = WideImage::loadFromFile('assets/images/empty_picture.png');
        //        $image_repository->create_from_image('test', $img);
    }

    function test($name)
    {
        $emails = array('ven' => 'vendiddy@gmail.com',
                        'berenholtzdan@gmail.com',
                        'ventxt' => '4438569502@txt.att.net');

        if (isset($emails[$name])) {
            $email = $emails[$name];
            job_call_async('send_email', $email, 'hello ' . rand(1, 100), 'hello here is a random number ' . rand(1, 100));
            print "<h3>sent email to $email</h3>";
        }
        else {
            print "<h3>no such email</h3>";
        }
    }

}
