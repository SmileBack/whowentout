<?php

abstract class Package
{
    abstract function install();

    abstract function uninstall();

    abstract function is_installed();

    abstract function installer_version();

    abstract function installed_version();
}
