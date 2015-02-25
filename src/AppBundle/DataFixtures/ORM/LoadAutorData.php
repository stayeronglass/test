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

        $format = 'Y-m-d H:i:s';

        $entity_0 = new Author();
        $entity_0->setName('Аверченко, Аркадий Тимофеевич');
        $entity_0->setDescription('Заимствование волнообразно. Наш современник стал особенно чутко относиться к слову, однако познание текста интегрирует эпизодический брахикаталектический стих, потому что сюжет и фабула различаются. Эпитет, как бы это ни казалось парадоксальным, неизменяем. ');
        $entity_0->setBirthday(\DateTime::createFromFormat($format,'2015-02-03 13:37:51'));

        $entity_0->setCreatedAt(new \DateTime());
        $entity_0->setUpdatedAt(new \DateTime());

        $manager->persist($entity_0);
        $manager->flush()

        ;$entity_1 = new Author();
        $entity_1->setName('Борис Акунин');
        $entity_1->setDescription('Поток сознания, соприкоснувшись в чем-то со своим главным антагонистом в постструктурной поэтике, существенно представляет собой резкий эпитет, и это является некими межсловесными отношениями другого типа, природу которых еще предстоит конкретизировать далее.');
        $entity_1->setBirthday(\DateTime::createFromFormat($format,'2014-12-03 13:38:39'));
        $entity_1->setBirthday(\DateTime::createFromFormat($format,'2014-12-03 13:38:39'));

        $entity_1->setCreatedAt(new \DateTime());
        $entity_1->setUpdatedAt(new \DateTime());

        $manager->persist($entity_1);
        $manager->flush();

        $entity_2 = new Author();
        $entity_2->setName('Вадим Баян');
        $entity_2->setDescription('Мужская рифма, как справедливо считает И.Гальперин, диссонирует символ, потому что в стихах и в прозе автор рассказывает нам об одном и том же. Эстетическое воздействие недоступно отталкивает резкий композиционный анализ.');
        $entity_2->setBirthday(\DateTime::createFromFormat($format,'2015-02-01 13:39:04'));

        $entity_2->setCreatedAt(new \DateTime());
        $entity_2->setUpdatedAt(new \DateTime());

        $manager->persist($entity_2);
        $manager->flush();

        $entity_3 = new Author();
        $entity_3->setName('Демьян Бедный');
        $entity_3->setDescription('Ритмическая организованность таких стихов не всегда очевидна при чтении "про себя", но синхрония нивелирует глубокий акцент, также необходимо сказать о сочетании метода апроприации художественных стилей прошлого с авангардистскими стратегиями.');
        $entity_3->setBirthday(\DateTime::createFromFormat($format,'2015-02-16 13:39:35'));

        $entity_3->setCreatedAt(new \DateTime());
        $entity_3->setUpdatedAt(new \DateTime());

        $manager->persist($entity_3);
        $manager->flush();

        $entity_4 = new Author();
        $entity_4->setName('Астахова, Наталья Васильевна');
        $entity_4->setDescription('Символ приводит деструктивный холодный цинизм. Ложная цитата, основываясь на парадоксальном совмещении исключающих друг друга принципов характерности и поэтичности, нивелирует диалогический акцент. Лексика просветляет строфоид.');
        $entity_4->setBirthday(\DateTime::createFromFormat($format,'2015-01-26 13:39:55'));

        $entity_4->setCreatedAt(new \DateTime());
        $entity_4->setUpdatedAt(new \DateTime());

        $manager->persist($entity_4);
        $manager->flush();

        $entity_5 = new Author();
        $entity_5->setName('Замятин, Евгений Иванович');
        $entity_5->setDescription('характерности и поэтичности, случайно. Олицетворение аллитерирует акцент. Гекзаметр, несмотря на внешние воздействия, приводит диалогический контрапункт. Такое понимание синтагмы восходит к Ф.де Соссюру, при этом первое полустишие последовательно притягивает деструктивный ритм. Нарративная семиотика текстологически выбирает экзистенциальный лирический субъект.');
        $entity_5->setBirthday(\DateTime::createFromFormat($format,'2015-02-11 13:40:43'));

        $entity_5->setCreatedAt(new \DateTime());
        $entity_5->setUpdatedAt(new \DateTime());

        $manager->persist($entity_5);
        $manager->flush();

        $entity_6 = new Author();
        $entity_6->setName('Донцова, Дарья');
        $entity_6->setDescription('Стиль приводит орнаментальный сказ, что нельзя сказать о нередко манерных эпитетах. Различное расположение, основываясь на парадоксальном совмещении исключающих друг друга принципов ');

        $entity_6->setCreatedAt(new \DateTime());
        $entity_6->setUpdatedAt(new \DateTime());

        $entity_6->setBirthday(\DateTime::createFromFormat($format,'2014-11-12 13:41:06'));
        $manager->persist($entity_6);
        $manager->flush();

        $entity_7 = new Author();
        $entity_7->setName('Королёв, Сергей Александрович');
        $entity_7->setDescription('Полифонический роман абсурдно выбирает глубокий подтекст. Субъективное восприятие, соприкоснувшись в чем-то со своим главным антагонистом в постструктурной поэтике, нивелирует музыкальный цикл, но не рифмами. Развивая эту тему, полифонический роман неустойчив. Декодирование нивелирует резкий стих, но не рифмами.');
        $entity_7->setBirthday(\DateTime::createFromFormat($format,'2015-02-25 13:41:41'));

        $entity_7->setCreatedAt(new \DateTime());
        $entity_7->setUpdatedAt(new \DateTime());

        $manager->persist($entity_7);
        $manager->flush();

        $entity_8 = new Author();
        $entity_8->setName('Хармс, Даниил Иванович');
        $entity_8->setDescription('Как мы уже знаем, слово интегрирует генезис свободного стиха, также необходимо сказать о сочетании метода апроприации художественных стилей прошлого с авангардистскими стратегиями. Пастиш неумеренно выбирает резкий дактиль. В заключении добавлю, филиация вызывает цикл. Мифопоэтическое пространство последовательно выбирает сюжетный холодный цинизм. Развивая эту тему, женское окончание волнообразно');
        $entity_8->setBirthday(\DateTime::createFromFormat($format,'2015-02-25 13:42:22'));

        $entity_8->setCreatedAt(new \DateTime());
        $entity_8->setUpdatedAt(new \DateTime());

        $manager->persist($entity_8);
        $manager->flush();

        $entity_9 = new Author();
        $entity_9->setName('Бруно Ясенский');
        $entity_9->setDescription('Талант Капниста по-настоящему раскрылся в комедии «Ябеда», здесь метонимия просветляет реформаторский пафос. Метаязык полидисперсен. Образ интегрирует пастиш.');
        $entity_9->setBirthday(\DateTime::createFromFormat($format,'2015-02-06 13:42:43'));

        $entity_9->setCreatedAt(new \DateTime());
        $entity_9->setUpdatedAt(new \DateTime());

        $manager->persist($entity_9);
        $manager->flush();
    }
}
