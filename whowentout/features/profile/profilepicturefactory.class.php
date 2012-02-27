<?php

class ProfilePictureFactory
{
    /* @var $database Database */
    private $database;

    /* @var $image_repository ImageRepository */
    private $image_repository;
    
    function __construct(Database $database, ImageRepository $image_repository)
    {
        $this->database = $database;
        $this->image_repository = $image_repository;
    }

    /**
     * @return ProfilePicture
     */
    function build($user)
    {
        benchmark::start('ProfilePictureFactory::build');
        $profile_picture = new ProfilePicture($this->database, $this->image_repository, $user);
        benchmark::end('ProfilePictureFactory::build');
        return $profile_picture;
    }
    
}
