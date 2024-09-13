<?php

namespace App\Command\ExecuteOneTime;

use App\Entity\Cms\PostCategory;
use App\Entity\Cms\Post;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class CategoryCommand extends Command
{

    protected static $defaultName = 'app:category-start';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Fill category data in the database.')
            ->setHelp('This command category the database with data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importCategoryQuestion($input,$output);
        $this->importCategoryEducation($input,$output);
        $this->importCategoryRecipes($input,$output);
        return Command::SUCCESS;
    }

    private function importCategoryQuestion(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->entityManager->getRepository(PostCategory::class);
        $entity = $repository->findOneBy(['type' => Post::POST_QUESTION]);

        if (!$entity) {
            
            $entity = new PostCategory();
            $entity->setType(Post::POST_QUESTION);
            $entity->setName('Preguntas Frecuentes');
            $entity->setDescription('');

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
        $output->writeln('Preguntas create.');
    }

    private function importCategoryEducation(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->entityManager->getRepository(PostCategory::class);
        $entity = $repository->findOneBy(['type' => Post::POST_EDUCTATION]);

        if (!$entity) {
            
            $entity = new PostCategory();
            $entity->setType(Post::POST_EDUCTATION);
            $entity->setName('Educación nutricional');
            $entity->setDescription('');

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
        $output->writeln('Educacion create.');
    }

    private function importCategoryRecipes(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->entityManager->getRepository(PostCategory::class);
        $entity = $repository->findOneBy(['type' => Post::POST_RECIPES]);

        if (!$entity) {
            
            $entity = new PostCategory();
            $entity->setType(Post::POST_RECIPES);
            $entity->setName('Recetas');
            $entity->setDescription('');

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
        $output->writeln('Recetas create.');
    }
    
}
