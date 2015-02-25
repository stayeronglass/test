<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Author as Author;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity_0 = new Author();
        $entity_0->setId('1');
        $entity_0->setName('Аверченко, Аркадий Тимофеевич');
        $entity_0->setBirthday('2015-02-03 13:37:51');
        $manager->persist($entity_0);
        $manager->flush()

        ;$entity_1 = new Author();
        $entity_1->setId('2');
        $entity_1->setName('Борис Акунин');
        $entity_1->setBirthday('2014-12-03 13:38:39');
        $manager->persist($entity_1);
        $manager->flush();

        $entity_2 = new Author();
        $entity_2->setId('3');
        $entity_2->setName('Вадим Баян');
        $entity_2->setBirthday('2015-02-01 13:39:04');
        $manager->persist($entity_2);
        $manager->flush();

        $entity_3 = new Author();
        $entity_3->setId('4');
        $entity_3->setName('Демьян Бедный');
        $entity_3->setBirthday('2015-02-16 13:39:35');
        $manager->persist($entity_3);
        $manager->flush();

        $entity_4 = new Author();
        $entity_4->setId('5');
        $entity_4->setName('Астахова, Наталья Васильевна');
        $entity_4->setBirthday('2015-01-26 13:39:55');
        $manager->persist($entity_4);
        $manager->flush();

        $entity_5 = new Author();
        $entity_5->setId('6');
        $entity_5->setName('Замятин, Евгений Иванович');
        $entity_5->setBirthday('2015-02-11 13:40:43');
        $manager->persist($entity_5);
        $manager->flush();

        $entity_6 = new Author();
        $entity_6->setId('7');
        $entity_6->setName('Донцова, Дарья');
        $entity_6->setBirthday('2014-11-12 13:41:06');
        $manager->persist($entity_6);
        $manager->flush();

        $entity_7 = new Author();
        $entity_7->setId('8');
        $entity_7->setName('Королёв, Сергей Александрович');
        $entity_7->setBirthday('2015-02-25 13:41:41');
        $manager->persist($entity_7);
        $manager->flush();

        $entity_8 = new Author();
        $entity_8->setId('9');
        $entity_8->setName('Хармс, Даниил Иванович');
        $entity_8->setBirthday('2015-02-25 13:42:22');
        $manager->persist($entity_8);
        $manager->flush();

        $entity_9 = new Author();
        $entity_9->setId('10');
        $entity_9->setName('Бруно Ясенский');
        $entity_9->setBirthday('2015-02-06 13:42:43');
        $manager->persist($entity_9);
        $manager->flush();
    }
}
