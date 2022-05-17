<?php

namespace App\DataFixtures;

use App\Factory\ArticleFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'alexadmin@gmail.com',
            'roles' => ['ROLE_ADMIN'],
            'pseudo' => 'alecs'
        ]);
        UserFactory::createOne([
            'email' => 'alex@gmail.com',
            'roles' => ['ROLE_USER'],
            'pseudo' => 'alecsadmin'
        ]);
        UserFactory::createMany(10);

        ArticleFactory::createOne([
            'title' => 'Test',
            'autheur' => 'admin',
            'creationDate' => new \DateTimeImmutable(),
            'description' => 'wooooooooooooooooooooow incroyable'
        ]);

        $manager->flush();
    }
}
