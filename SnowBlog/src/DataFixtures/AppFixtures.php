<?php

namespace App\DataFixtures;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Figure;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
   
    /**
     *
     * @var UserPasswordEncoderInterface
     * 
     */
    private $encoder;

    public function __construct(UserPAsswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $users = [];
        $categorys = [];
        $figures = [];
        $filesFigure = [
        'dbcdbae3ae508e8c9a46ba61d45d6136.jpeg',
        '21ce571edc427fb10aa74edac77ecf3c.jpeg',
        '2e26f5f98a5af418e1a33954d68aa326.jpeg',
        '05b0b8eabad2b5f2ec3269b51246ec8f.jpeg',
        '7bb010227fbca7126c793a0de3f15dcf.jpeg',
        '209ff159f6a206392fe90bdcc57dbc5b.jpeg',
        'aa1271b44f485696e4e1274aa50a9514.jpeg',
        'c158866b93282205720c314e11857a05.jpeg',
        'd6c44dc48699d85f5719a7907096e5fd.jpeg',
        'e0c75bf1081783b89b365900a33a505f.jpeg'
        ];
        $filesUser = [
        null,
        '61fe84016c123932eb8e110021e59e22.jpeg',
        '69b447d6660a20653dc87a2593d66f83.jpeg',
        '5855c4da811e0244f0e6d514efbf48c2.jpeg',
        '237376da972ce26a8c50b1249e375f66.jpeg',
        'd23ab3be5af47f784187410dfa9bdbc5.jpeg',
        'ead4f241120741ae169ecc589166f7fb.jpeg'
        ];
        $UrlImage = [
        'https://image.noelshack.com/fichiers/2019/48/3/1574861134-1200px-shakedown-2008-figure-4.jpg',
        'https://image.noelshack.com/fichiers/2019/48/3/1574861211-924-en-snow-comme-en-skate-une-figure-ou-article-normal-image-1.jpg',
        'https://image.noelshack.com/fichiers/2019/48/3/1574861282-snowboard-freestyle.jpg',
        'https://image.noelshack.com/fichiers/2019/48/3/1574861286-compilation-sport-extreme-30be78e0357a4fe69d13af5e7132552137a7872b.jpg',
        'https://image.noelshack.com/fichiers/2019/48/3/1574861291-japan-air.jpg'
        ];

        for($i = 0; $i < 6; $i++){
            $user = new User();
            $user ->setUsername($faker->userName)
                  ->setImage($faker->randomElement($filesUser))
                  ->setEmail($faker->email)
                  ->setPassword($this->encoder->encodePassword($user, 'password'))
                  ->setToken(rand())
                  ->setConfirm(true)
                  ->setDateCreated($faker->dateTime);
            
                  $manager->persist($user);
                  $users[] = $user;
            
        }

        for($j = 0; $j < 3; $j++){
            $category = new Category();
            $category ->setName($faker->tld);
            
                  $manager->persist($category);
                  $categorys[] = $category;
            
        }

        for($k = 0; $k < 32; $k++){
            $figure = new Figure();
            $figure ->setName($faker->domainWord)
                    ->setUser($faker->randomElement($users))
                    ->setImage($faker->randomElement($filesFigure))
                    ->setContent($faker->text)
                    ->setCategory($faker->randomElement($categorys))
                    ->setDateCreated($faker->dateTime);
            
                  $manager->persist($figure);
                  $figures[] = $figure;
            
        }

        for($l = 0; $l < 96; $l++){
            $media = new Media();
            $media ->setFigure($faker->randomElement($figures))
                    ->setUrl($faker->randomElement($UrlImage))
                    ->setType('image');
            
                  $manager->persist($media);
            
        }

        for($m = 0; $m < 365; $m++){
            $comment = new Comment();
            $comment ->setFigure($faker->randomElement($figures))
                     ->setUser($faker->randomElement($users))
                     ->setContent($faker->sentence)
                     ->setDateCreated($faker->dateTime);
            
                  $manager->persist($comment);
            
        }
        $manager->flush();
    }
}
