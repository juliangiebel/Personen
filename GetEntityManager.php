<?php
/**
 * Created by PhpStorm.
 * User: giebelj
 * Date: 22.05.18
 * Time: 11:02
 */
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

$config = Setup::createAnnotationMetadataConfiguration(array("src/AppBundle/Entity/", true));

