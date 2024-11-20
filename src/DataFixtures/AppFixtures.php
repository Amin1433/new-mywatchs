<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Rack;
use App\Entity\Watch;
use App\Entity\Member;
use App\Entity\Galerie;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    

    private function membersGenerator()
    {
        yield ['olivier@localhost', '123456', 'Olivier', 'Collectionneur passionné de montres.', 'ROLE_USER'];
        yield ['slash@localhost', '123456', 'Slash', 'Amateur de montres modernes.', 'ROLE_USER'];
    }
    
    public function load(ObjectManager $manager): void
    {
        $this-> loadRessources($manager);
        
    }
    
    public function loadRessources(ObjectManager $manager)
    {
        foreach ($this->membersGenerator() as [$email, $password, $name, $description, $role]) {
            // Création du membre
            $member = new Member();
            $password = $this->hasher->hashPassword($member, $password);
            $member->setEmail($email);
            $member->setPassword($password);
            $member->setNom($name);
            $member->setDescription($description);
            $roles = array();
            $roles[] = $role;
            $member ->setRoles($roles);
            $manager->persist($member);
            
            // Création du rack pour ce membre
            $rack = new Rack();
            $rack->setNom("Présentoir de $name");
            $member->setRack($rack);
            $manager->persist($rack);
            
            // Création des montres
            $watch = new Watch();
            $watch->setName("Montre 1 de $name");
            $watch->setDescription("Description de la montre 1 de $name.");
            $watch->setRack($rack);
            $manager->persist($watch);
            
            $watch2 = new Watch();
            $watch2->setName("Montre 2 de $name");
            $watch2->setDescription("Description de la montre 2 de $name.");
            $watch2->setRack($rack); // Lier la montre au rack
            $manager->persist($watch2);
            
            // Création des galeries pour ce membre
            $galerie1 = new Galerie();
            $galerie1->setName("Galerie 1 de $name");
            $galerie1->setDescription("Description de la galerie 1 de $name.");
            $galerie1->setCreator($member);
            $galerie1->setPubliee(False);
            $galerie1->addWatch($watch);
            $galerie1->addWatch($watch2);
            
            $manager->persist($galerie1);
            
            $galerie2 = new Galerie();
            $galerie2->setName("Galerie 2 de $name");
            $galerie2->setDescription("Description de la galerie 2 de $name.");
            $galerie2->setCreator($member);
            $galerie2->setPubliee(True);
            $galerie2->addWatch($watch);
            $manager->persist($galerie2);
        }
        
        $manager->flush();
    }
    
}
