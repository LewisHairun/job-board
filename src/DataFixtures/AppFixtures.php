<?php

namespace App\DataFixtures;

use App\Entity\Candidate;
use App\Entity\CandidateJobOffer;
use Faker\Factory;
use App\Entity\City;
use App\Entity\GeneralTerm;
use App\Entity\JobBranch;
use App\Entity\JobOffer;
use App\Entity\LegalNotice;
use App\Entity\PositionType;
use App\Entity\Recruiter;
use App\Entity\Skill;
use App\Entity\User;
use App\Repository\CandidateRepository;
use App\Repository\CityRepository;
use App\Repository\JobBranchRepository;
use App\Repository\JobOfferRepository;
use App\Repository\PositionTypeRepository;
use App\Repository\RecruiterRepository;
use App\Repository\SkillRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private array $citiesData = [];
    private array $jobBranchesData = [];
    private array $recruitersData = [];
    private array $positionTypesData = [];
    private array $candidatesData = [];
    private array $jobOffersData = [];
    private array $skillsData = [];

    public function __construct(private UserPasswordHasherInterface $passwordHasher, 
                                private PositionTypeRepository $positionTypeRepository,
                                private CityRepository $cityRepository,
                                private SluggerInterface $slugger,
                                private JobBranchRepository $jobBranchRepository,
                                private RecruiterRepository $recruiterRepository,
                                private CandidateRepository $candidateRepository,
                                private JobOfferRepository $jobOfferRepository,
                                private SkillRepository $skillRepository
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
        $this->loadJobOffersData($manager);
        $this->loadCandidateJobOffersData($manager);
        $this->loadSkillsData($manager);
        $this->loadCandidateSkillsData($manager);
        $this->loadUserAdmin($manager);
        $this->loadLegalNotice($manager);
        $this->loadGeneralTerm($manager);
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
        $jobBranches = ["Développement web", "Développement mobile"];

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
        $positionTypes = ["Développeur Php", "Développeur Js", "Développeur React Native", "Développeur Flutter", "Développeur Java"];

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
            $positionType = $this->positionTypeRepository->findOneBy(["type" => $this->positionTypesData[array_rand($this->positionTypesData)]->getType()]);
            $registeredDate = (new \DateTimeImmutable())->modify("- " . rand(1, 30) . "days"); 
            
            $candidate->setFirstname($firstname);
            $candidate->setLastname($lastname);
            $candidate->setEmail($email);
            $candidate->setPicture(null);
            $candidate->setPassword($password);
            $candidate->setDegree($degrees[array_rand($degrees)]);
            $candidate->setRoles(["ROLE_CANDIDATE"]);
            $candidate->setIsActivated(rand(0, 1));
            $candidate->setRegisteredDate($registeredDate);
            $candidate->setPositionType($positionType);
            $manager->persist($candidate);

            $this->candidatesData[] = $candidate;
        }

        $manager->flush();
    }

    public function loadPost(string $position): string
    {
       $post = "";

       $post = match ($position) {
        'Développeur Php' => "Php",
        'Développeur Js' => "Js",
        'Développeur React Native' => "React Native",
        'Développeur Flutter' => "Flutter",
        'Développeur Java' => "Java",
       };

       return $post;
    }

    public function loadCoordinate(string $cityName): array
    {
        $coordinate = [];

        $coordinate = match($cityName) {
            "Tananarive" => [-18.91368, 47.53613],
            "Fianarantsoa" => [-21.45267, 47.08569],
            "Antsirabe" => [-19.86586, 47.03333],
            "Majunga" => [-15.71667, 46.31667],
            "Tamatave" => [-18.1492, 49.40234],
            "Tuléar" => [-23.35, 43.66667],
            "Diégo-Suarez" => [-12.2787, 49.29171]
        };

        return $coordinate;
    }

    public function loadJobOffersData(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            $faker = Factory::create('fr_FR');

            $jobOffer = new JobOffer;
            $jobBranch = $this->jobBranchRepository->findOneBy(["name" => $this->jobBranchesData[array_rand($this->jobBranchesData)]->getName()]);
            $positionType = $this->positionTypeRepository->findOneBy(["type" => $this->positionTypesData[array_rand($this->positionTypesData)]->getType()]);
            $title = "Poste de " . $this->loadPost($positionType->getType());
            $slug = strtolower($this->slugger->slug($title));
            $city = $this->cityRepository->findOneBy(["name" => $this->citiesData[array_rand($this->citiesData)]->getName()]);
            $latitude = $this->loadCoordinate($city->getName())[0];;
            $longitude = $this->loadCoordinate($city->getName())[1];
            $expiringDate = (new \DateTime())->modify("+ 30 days");
            $publicationDate = new \DateTimeImmutable();
            $recruiter = $this->recruiterRepository->findOneBy(["email" => $this->recruitersData[array_rand($this->recruitersData)]->getEmail()]);

            $jobOffer->setTitle($title);
            $jobOffer->setSlug($slug);
            $jobOffer->setDescription($faker->text(300));
            $jobOffer->setIsActivated(rand(0, 1));
            $jobOffer->setMinSalary($faker->numberBetween(50000, 70000) / 100);
            $jobOffer->setMaxSalary($faker->numberBetween(70000, 90000) / 100);
            $jobOffer->setLatitude($latitude);
            $jobOffer->setLongitude($longitude);
            $jobOffer->setExpiringDate($expiringDate);
            $jobOffer->setPublicationDate($publicationDate);
            $jobOffer->setCity($city);
            $jobOffer->setJobBranch($jobBranch);
            $jobOffer->setRecruiter($recruiter);
            $jobOffer->setPositionType($positionType);

            $manager->persist($jobOffer);

            $this->jobOffersData[] = $jobOffer;
        }

        $manager->flush();
    }

    public function loadCandidateJobOffersData(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            $candidateJobOffer = new CandidateJobOffer;
            $candidate = $this->candidateRepository->findOneBy(["email" => $this->candidatesData[array_rand($this->candidatesData)]->getEmail()]);
            $jobOffer = $this->jobOfferRepository->findOneBy(["title" => $this->jobOffersData[array_rand($this->jobOffersData)]->getTitle()]);
            $candidacyDate = new \DateTimeImmutable();

            $candidateJobOffer->setCandidate($candidate);
            $candidateJobOffer->setJobOffer($jobOffer);
            $candidateJobOffer->setCandidacyDate($candidacyDate);

            $manager->persist($candidateJobOffer);
        }

        $manager->flush();
    }

    public function loadSkillsData(ObjectManager $manager): void
    {
        $skills = ["Communication", "Capacité d'apprentissage et d'adaptation", "Techno web", "Techno mobile"];

        foreach ($skills as $skill) {
            $entity = new Skill;
            $entity->setName($skill);
            $manager->persist($entity);

            $this->skillsData[] = $entity;
        }

        $manager->flush();
    }

    public function loadCandidateSkillsData(ObjectManager $manager): void 
    {
        for ($i=0; $i < 10; $i++) { 
            $candidate = $this->candidateRepository->findOneBy(["email" => $this->candidatesData[array_rand($this->candidatesData)]->getEmail()]);
            $skill = $this->skillRepository->findOneBy(["name" => $this->skillsData[array_rand($this->skillsData)]->getName()]);

            $candidate->addSkill($skill);
            $manager->persist($candidate);
        }

        $manager->flush();
    }

    public function loadUserAdmin(ObjectManager $manager): void
    {
        $admin = new User;
        $password = $this->passwordHasher->hashPassword($admin, "admin");

        $admin->setFirstname("Admin");
        $admin->setLastname("Admin");
        $admin->setEmail("admin@gmail.com");
        $admin->setPassword($password);
        $admin->setRoles(["ROLE_ADMIN"]);

        $manager->persist($admin);
        $manager->flush();
    }

    public function loadLegalNotice(ObjectManager $manager): void
    {
        $legalNotice = new LegalNotice;
        $content = <<<EOD
            <div class="container">
                <h2 style="text-align:center;"<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>Mention legale</strong></h2>
                <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                <strong>Date d'effet</strong> : 05/10/23<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>
                
                1. Objet</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Les présentes Conditions Générales de Vente (ci-après les "CGV") régissent les relations contractuelles entre Job Board 
                (ci-après le "Site") et toute personne utilisant les services de recherche d'emploi proposés sur le Site (ci-après "l'Utilisateur"). 
                Toute utilisation du Site implique l'acceptation pleine et entière des CGV par l'Utilisateur.
                <br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>

                2. Services Proposés</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                Le Site propose des services de recherche d'emploi, y compris la publication d'offres d'emploi, 
                la consultation de profils de candidats, et d'autres fonctionnalités liées à la recherche d'emploi.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<strong> 3. Responsabilités</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Le Site ne peut garantir l'exactitude des informations publiées par les utilisateurs. L'Utilisateur est seul responsable du contenu des offres d'emploi publiées et des informations incluses dans son profil. Le Site ne peut être tenu responsable des dommages directs ou indirects causés par l'utilisation des services proposés.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>4. Tarifs et Paiement</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;L'utilisation de base du Site est gratuite pour les Utilisateurs. Cependant, des services premium peuvent être proposés moyennant des frais additionnels. Les tarifs et modalités de paiement des services premium sont indiqués sur le Site et peuvent être modifiés à tout moment.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>5. Protection des Données Personnelles</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Le Site collecte et traite les données personnelles des Utilisateurs conformément à sa Politique de Confidentialité. L'Utilisateur accepte cette collecte et ce traitement de ses données personnelles.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>6. Propriété Intellectuelle</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Le Site et son contenu sont protégés par les lois sur la propriété intellectuelle. Toute reproduction, distribution ou utilisation non autorisée du contenu du Site est strictement interdite.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>7. Modification des CGV</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Le Site se réserve le droit de modifier les présentes CGV à tout moment. Les Utilisateurs seront informés des modifications via le Site. L'utilisation continue du Site après modification des CGV constitue l'acceptation de ces modifications.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>8. Droit Applicable et Juridiction</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Les présentes CGV sont soumises au droit en vigueur. En cas de litige, les tribunaux compétents seront ceux du lieu du siège social de Job Board.<br>&nbsp; &nbsp; &nbsp;</p></div>
        EOD;

        $legalNotice->setContent($content);

        $manager->persist($legalNotice);
        $manager->flush();
    }

    public function loadGeneralTerm(ObjectManager $manager): void
    {
        $generalTerm = new GeneralTerm;
        $content = <<<EOD
            <div class="container">
                <h2 style="text-align:center;"<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>Conditions Générales de Vente de Job Board</strong></h2>
                <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    <strong>1. Identité de l'éditeur</strong>
                </p>
                <p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Le présent site est édité par la société 
                <strong>JobBoard</strong>, société par actions simplifiée au capital de 500 000, 
                immatriculée au registre du commerce et des sociétés de Tana sous le numéro 21541848215, 
                dont le siège social est situé Tana.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                &nbsp;<strong>2. Contact</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Pour toute question ou demande d'information, vous pouvez nous contacter :<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Par téléphone au 12484848<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Par e-mail à l'adresse contact@job-board.com</p><p><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<strong> 3. Hébergement</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Le présent site est hébergé par la société [Nom de l'hébergeur], dont le siège social est situé [adresse du siège social].<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>4. Utilisation du site</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;L'utilisation du présent site est soumise aux conditions générales d'utilisation disponibles sur le site.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>5. Propriété intellectuelle</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;L'ensemble des éléments composant le présent site, notamment la structure, les textes, les images, les sons, les vidéos, les logiciels, sont la propriété exclusive de la société [Nom de la société] ou de ses partenaires. Toute reproduction, représentation, diffusion ou exploitation, à titre commercial ou non, de l'ensemble ou d'une partie de ces éléments, sans l'autorisation expresse de la société [Nom de la société], est interdite et constitue une contrefaçon sanctionnée par les articles L.335-2 et suivants du Code de la propriété intellectuelle.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<strong> 6. Données personnelles</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Les informations recueillies sur le présent site font l'objet d'un traitement informatique destiné à la gestion des candidatures. Ce traitement est nécessaire à l'exécution des prestations proposées par la société [Nom de la société].<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Conformément à la loi « Informatique et libertés » du 6 janvier 1978, vous disposez d'un droit d'accès, de rectification et de suppression des informations vous concernant. Vous pouvez exercer ce droit en nous contactant par e-mail à l'adresse [adresse e-mail] ou par courrier à l'adresse [adresse postale].<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<strong> &nbsp; 7. Cookies</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Le présent site utilise des cookies. Les cookies sont des fichiers texte qui sont déposés sur votre ordinateur lorsque vous visitez un site internet. Ils permettent à un site internet de se souvenir de vos actions et de vos préférences (par exemple, votre langue, votre taille de police, vos couleurs préférées, etc.) au cours d'une session de navigation.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Vous pouvez refuser l'utilisation des cookies en modifiant les paramètres de votre navigateur. Toutefois, certaines fonctionnalités du présent site peuvent être altérées si vous désactivez les cookies.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>8. Liens hypertextes</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Le présent site peut contenir des liens hypertextes vers d'autres sites internet. La société [Nom de la société] n'exerce aucun contrôle sur ces sites et ne saurait être tenue responsable de leur contenu.<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong> &nbsp;9. Droit applicable</strong><br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Les présentes mentions légales sont régies par le droit français. En cas de litige, les tribunaux français seront seuls compétents.</p>
            </div>
        EOD;

        $generalTerm->setContent($content);

        $manager->persist($generalTerm);
        $manager->flush();
    }
}
