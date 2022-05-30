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
        UserFactory::createOne([
            'email' => 'alexblabla@gmail.com',
            'roles' => ['ROLE_AUTEUR'],
            'pseudo' => 'Alexblabla'
        ]);
        UserFactory::createMany(10);

        ArticleFactory::createOne([
            'title' => 'Nouveau plat',
            'categorie' => 'Plat',
            'description' => 'Eijnerfeuh ifiuefiefiufuifnvehufn iofjerifenfioe efeozjfoei',
            'creationDate' => new \DateTimeImmutable(),
            'imageFile' => 'imgs/cake.jpg',
            'idUser' =>  UserFactory::random(),
            'deleted' => false
        ]);

        ArticleFactory::createMany(20, function (){
            return [
                'idUser' =>  UserFactory::random(),
            ];
        });

        $manager->flush();
    }
}
