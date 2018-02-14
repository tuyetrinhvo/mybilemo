<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProduct extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $product1 = new Product();
        $product1->setName('iPhone 6 32 Go');
        $product1->setDescription('Smartphone 4G, Fonction GPS, Système exploitation : iOS 9');
        $product1->setBrand('Apple');
        $product1->setPrice(399);
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('iPhone 7 32 Go');
        $product2->setDescription('Smartphone 4G, Fonction GPS, Système exploitation : iOS 9');
        $product2->setBrand('Apple');
        $product2->setPrice(639);
        $manager->persist($product2);

        $product3 = new Product();
        $product3->setName('Galaxy A8 32 Go');
        $product3->setDescription('Smartphone 4G, Fonction GPS, Système exploitation : Android 7.1 Nougat');
        $product3->setBrand('Samsung');
        $product3->setPrice(499);
        $manager->persist($product3);

        $product4 = new Product();
        $product4->setName('Galaxy J7 2017 16 Go');
        $product4->setDescription('Smartphone 4G, Fonction GPS, GPRS, Système exploitation Android 7.0 Nougat');
        $product4->setBrand('Samsung');
        $product4->setPrice(279);
        $manager->persist($product4);

        $product5 = new Product();
        $product5->setName('Xperia XA1 DUAL SIM 32 Go');
        $product5->setDescription('Smartphone 4G, Fonction GPS, Système exploitation Android 7.0 Nougat');
        $product5->setBrand('Sony');
        $product5->setPrice(229);
        $manager->persist($product5);

        $product6 = new Product();
        $product6->setName('HuaWei mate 10 Pro');
        $product6->setDescription('Smartphone 4G+, Fonction GPS, Système Exploitation Android 8.0 Oreo');
        $product6->setBrand('HuaWei');
        $product6->setPrice(799);
        $manager->persist($product6);

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }

}