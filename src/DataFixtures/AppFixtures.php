<?php

namespace App\DataFixtures;

use App\Entity\Candidate;
use Faker\Factory;
use App\Entity\City;
use App\Entity\JobBranch;
use App\Entity\PositionType;
use App\Entity\Recruiter;
use App\Repository\PositionTypeRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private array $citiesData = [];
    private array $jobBranchesData = [];
    private array $recruitersData = [];
    private array $positionTypesData = [];
    private array $candidatesData = [];

    public function __construct(private UserPasswordHasherInterface $passwordHasher, 
                                private PositionTypeRepository $positionTypeRepository
                            )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadCitiesData($manager);
        $this->loadJobBranchesData($manager);
        $this->loadRecruitersData($manager);
        $this->loadPositionTypesData($manager);
        $this->loadCandidatesData($manager);
    }

    public function loadCitiesData(ObjectManager $manager): void
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

    public function loadJobBranchesData(ObjectManager $manager): void
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

    public function loadRecruitersData(ObjectManager $manager): void
    {
        for ($i=0; $i < 5; $i++) { 
            $faker = Factory::create('fr_FR');

            $recruiter = new Recruiter;
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();
            $email = strtolower($firstname) . "." . strtolower($lastname) . "@gmail.com";
            $password = $this->passwordHasher->hashPassword($recruiter, "password");

            $recruiter->setFirstname($firstname);
            $recruiter->setLastname($lastname);
            $recruiter->setEmail($email);
            $recruiter->setPicture(null);
            $recruiter->setPassword($password);
            $recruiter->setRoles(["ROLE_RECRUITER"]);
            $manager->persist($recruiter);

            $this->recruitersData[] = $recruiter;
        }

        $manager->flush();
    }

    public function loadPositionTypesData(ObjectManager $manager): void 
    {
        $positionTypes = ["Développeur Php", "Développeur Js", "Ingénieur assurance et qualité", "Commerciale", "Recruteur"];

        foreach ($positionTypes as $positionType) {
            $entity = new PositionType;
            $entity->setType($positionType);
            $manager->persist($entity);

            $this->positionTypesData[] = $entity;
        }

        $manager->flush();
    }

    public function loadCandidatesData(ObjectManager $manager): void
    {
        $degrees = ["DTS", "Licence", "Master"];

        for ($i=0; $i < 20; $i++) { 
            $faker = Factory::create('fr_FR');

            $candidate = new Candidate;
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();
            $email = strtolower($firstname) . "." . strtolower($lastname) . "@gmail.com";
            $password = $this->passwordHasher->hashPassword($candidate, "password");
            $isActivated = rand(0, 1) === 0 ? true : false;
            $positionType = $this->positionTypeRepository->findOneBy(["type" => $this->positionTypesData[array_rand($this->positionTypesData)]->getType()]);

            $candidate->setFirstname($firstname);
            $candidate->setLastname($lastname);
            $candidate->setEmail($email);
            $candidate->setPicture(null);
            $candidate->setPassword($password);
            $candidate->setDegree($degrees[array_rand($degrees)]);
            $candidate->setSalaryRange($faker->numberBetween(100000, 999999) / 100);
            $candidate->setRoles(["ROLE_CANDIDATE"]);
            $candidate->setIsActivated($isActivated);
            $candidate->setPositionType($positionType);
            $manager->persist($candidate);

            $this->candidatesData[] = $candidate;
        }

        $manager->flush();
    }
}
