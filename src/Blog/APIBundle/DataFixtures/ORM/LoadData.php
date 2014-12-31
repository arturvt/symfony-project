<?php

namespace Blog\APIBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;
use Faker;

/**
 * Class LoadData
 *  Loads all fixture data
 *
 * @package Blog\APIBundle\DataFixtures\ORM
 */
class LoadData implements FixtureInterface
{
    public function load(ObjectManager $objectManager)
    {
        $faker = Faker\Factory::create();
        Fixtures::load(
            __DIR__.'/data.yml',
            $objectManager,
            array('providers' =>
                array(
                    $this,
                    new BlogDataProvider($faker)
                )
            )
        );
    }
}
