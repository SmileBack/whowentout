<?php

class BootWhoWentOutPlugin extends Plugin
{

    function on_fire_boot($e)
    {
        /* @var $fire FireApp */
        $fire = $e->f;

        $cache_storage = new FileRepository(array(
                                                 'driver' => 'local',
                                                 'path' => '../cache',
                                            ));
        $cache = new Cache(array(
                                'driver' => 'storage',
                                'storage' => $cache_storage,
                           ));
        $fire->register('cache', $cache);

        $serverchannel_config = $this->load_config('serverchannel', 'default');
        $serverchannel = new ServerChannel($serverchannel_config);
        $fire->register('serverchannel', $serverchannel);
        
        $image_respository_storage_config = $this->load_config('storage', 'pics');
        
        $image_repository_storage = new FileRepository($image_respository_storage_config);
        $image_repository = new ImageRepository($image_repository_storage);
        $fire->register('pics_image_repository', $image_repository);
    }

    private $CFG;
    private function load_config($item, $key)
    {
        if (!$this->CFG)
            $this->CFG = load_class('Config', 'core');

        $this->CFG->load($item);
        $config = $this->CFG->item($item);

        return $config[$key];
    }

}
