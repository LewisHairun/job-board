<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\City;
use App\Entity\JobBranch;
use App\Entity\Recruiter;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private array $citiesData = [];
    private array $jobBranchesData = [];

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadCities($manager);
        $this->loadJobBranches($manager);
    }

    public function loadCities(ObjectManager $manager): void
    {
        $cities = ["Tananarive", "Fianarantsoa", "Antsirabe", "Majunga", "Tamatave", "Tuléar", "Diégo-Suarez"];

        foreach ($cities as $city) {
            $entity = new City;
            $entity->setName($city);
            $manager->persist($entity);

            $this->citiesData[] = $entity;
        }

        $manager->flush();
    }

    public function loadJobBranches(ObjectManager $manager): void
    {
        $jobBranches = ["Développement web", "Développement mobile", "Assurance et qualité de logiciel", 
                        "Comptabilité", "Ressources humaines", "Commerciale", "Formation français"
                    ];

        foreach ($jobBranches as $jobBranch) {
            $entity = new JobBranch;
            $entity->setName($jobBranch);
            $manager->persist($entity);

            $this->jobBranchesData[] = $entity;
        }

        $manager->flush();            
    }

    // public function loadRecruiters(ObjectManager $manager): void
    // {
    //     for ($i=0; $i < 5; $i++) { 
    //         $faker = Factory::create('fr_FR');
    //         $recruiter = new Recruiter;
    //         $firstname = $faker->firstname;
    //         $lastname = $faker->lastname;
    //         $email = $lastname . $firstname . "@gmail.com";
    //         $password = $this->passwordHasher->hashPassword($recruiter, "password");

    //         $recruiter->setFirstname($firstname);
    //         $recruiter->setFirstname($lastname);
    //         $recruiter->setEmail($email);
    //         $recruiter->setPicture(null);
    //         $recruiter->setPassword($password);
    //         $recruiter->setRole(["ROLE_RECRUITER"]);
    //     }
    // }
}
