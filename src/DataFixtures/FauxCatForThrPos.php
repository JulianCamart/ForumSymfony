<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\FixtureInterface;

use App\Entity\CatTable;
use App\Entity\ForumTable;
use App\Entity\ThreadTable;
use App\Entity\PostTable;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class FauxCatForThrPos extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $nbp = 0;
        $nbt = 0;
        $fid = 0;
        $SuperAdminGrade = ['ROLE_SUPER_ADMIN'];
        $AdminGrade = ['ROLE_ADMIN'];
        $ModoGrade = ['ROLE_MODERATOR'];
        $UserGrade = ['ROLE_USER'];

        $fauxSuperadmin = new User();
        $fauxSuperadmin->setUsername("SuperAdmin");
        $hash = $this->passwordEncoder->encodePassword($fauxSuperadmin, "root");
        $fauxSuperadmin->setPassword($hash)
                ->setEmail("SuperAdmin@forum.fr")
                ->setRole($SuperAdminGrade)
                ->setVerifEmail(true)
                ->setMemberSinceTime(new \DateTime());
        $manager->persist($fauxSuperadmin);

        $fauxadmin = new User();
        $fauxadmin->setUsername("Admin");
        $hash = $this->passwordEncoder->encodePassword($fauxadmin, "root");
        $fauxadmin->setPassword($hash)
                ->setEmail("Admin@forum.fr")
                ->setRole($AdminGrade)
                ->setMemberSinceTime($faker->dateTimeBetween('-11 months', '-10 months'))
                ->setVerifEmail(true);
        $manager->persist($fauxadmin);

        $fauxmodo = new User();
        $fauxmodo->setUsername("Modo");
        $hash = $this->passwordEncoder->encodePassword($fauxmodo, "root");
        $fauxmodo->setPassword($hash)
                ->setEmail("Modo@forum.fr")
                ->setRole($ModoGrade)
                ->setMemberSinceTime($faker->dateTimeBetween('-11 months', '-10 months'))
                ->setVerifEmail(true);
        $manager->persist($fauxmodo);

        $fauxuser = new User();
        $fauxuser->setUsername("User");
        $hash = $this->passwordEncoder->encodePassword($fauxuser, "root");
        $fauxuser->setPassword($hash)
                ->setEmail("User@forum.fr")
                ->setRole($UserGrade)
                ->setMemberSinceTime($faker->dateTimeBetween('-11 months', '-10 months'))
                ->setVerifEmail(true);
        $manager->persist($fauxuser);

        // On gere les categories et insere
        for($c = 1; $c <= 5; $c++){
            $fauxcat = new CatTable();
                if ($c == 1){
                    $cat = 'Générale';
                }
                else if ($c == 2){
                    $cat = 'Informatique';
                }
                else if ($c == 3){
                    $cat = 'Sport';
                }
                else if ($c == 4){
                    $cat = 'Cuisine';
                }
                else if ($c == 5){
                    $cat = 'Vip-Admin';
                }
            $fauxcat->setCatName($cat);

        ////// On créer un utilisateur admin pour les Threads
            $manager->persist($fauxcat);




            // On gere les Forums
            for($f = 1; $f <= mt_rand(1, 2); $f++){

                $nbpInForum = 0;
                $fid ++ ;  //FID++


                $fauxforum = new ForumTable();

                // On insére les Forums
                $fauxforum->setCategory($fauxcat)
                        ->setForumName($faker->word(mt_rand (2,4)))
                        ->setForumDescription($faker->sentence(mt_rand (1,3)));


                $manager->persist($fauxforum); //OK

                // On gere les Threads
                for($t = 1; $t <= mt_rand(2,4); $t++){

                    $nbt++ ; //NBT++


                    $fauxthread = new ThreadTable();

                     // On insére les Thread
                    $fauxthread->setForum($fauxforum) // FID
                                ->setThreadName($faker->sentence(mt_rand (4,6)))
                                ->setThreadAuthor($fauxadmin)
                                ->setThreadText($faker->text(mt_rand(200,400)))
                                ->setThreadTime($faker->dateTimeBetween('-6 months', 'now'));

                    $manager->persist($fauxthread); //OK



                        for($p = 1; $p <= mt_rand(1,5); $p++){

                            $nbp++;  //NBP++

                            // On créer un utlisateur pour chaque post

                            $fauxuser = new User();
                            $fauxuser->setUsername($faker->Username)
                                ->setEmail($faker->email)
                                ->setRole($UserGrade)
                                ->setMemberSinceTime($faker->dateTimeBetween('-10 months', '-6 months'))
                                ->setVerifEmail(true);

                            $hash = $this->passwordEncoder->encodePassword($fauxuser, $faker->password);
                            $fauxuser->setPassword($hash);


                            $manager->persist($fauxuser) ;

                            // On gere les posts
                            $fauxpost = new PostTable();
                            $fauxpost->setThread($fauxthread)  //NBT
                                ->setPostAuthor($fauxuser)
                                ->setPostText($faker->text(mt_rand(300,500)))
                                ->setPostTime($faker->dateTimeBetween('-6 months', 'now'));



                        $manager->persist($fauxpost) ; //OK
                    }
                }
            }
        }

        $manager->flush();
    }
}



