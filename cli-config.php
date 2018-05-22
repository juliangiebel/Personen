<?php
/**
 * Created by PhpStorm.
 * User: giebelj
 * Date: 22.05.18
 * Time: 10:12
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;


require "GetEntityManager.php";
// replace with file to your own project bootstrap
//require_once 'bootstrap.php';

// replace with mechanism to retrieve EntityManager in your app
// $entityManager = GetEntityManager();

return ConsoleRunner::createHelperSet($entityManager);