<?php

namespace App\Command\ExecuteOneTime;

use App\Entity\Commerce\Product;
use App\Entity\Commerce\ListProduct;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProductCommand extends Command
{

    protected static $defaultName = 'app:product-start';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Fill product data in the database.')
            ->setHelp('This command product the database with data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importListProduct($input,$output,ListProduct::TYPE_PROTEINS);
        $this->importListProduct($input,$output,ListProduct::TYPE_FATS);
        $this->importListProduct($input,$output,ListProduct::TYPE_HYDRATES);
        return Command::SUCCESS;
    }

    private function importListProduct(InputInterface $input, OutputInterface $output,$type)
    {
        $repository = $this->entityManager->getRepository(ListProduct::class);
        $entity = $repository->findOneBy(['type' => $type]);

        if (!$entity) {
            
            $entity = new ListProduct();
            $entity->setType($type);
            switch ($type) {
                case ListProduct::TYPE_PROTEINS:
                    $entity->setName('PROTEÍNAS');
                    break;
                case ListProduct::TYPE_FATS:
                    $entity->setName('GRASAS');
                    break;
                case ListProduct::TYPE_HYDRATES:
                    $entity->setName('CARBOHIDRATOS');
                    break;
            }

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        $output->writeln('Listas create.');

        if($entity->getProducts()->count()){
            $output->writeln('products create.');
            return;
        }

        $products = [];

        switch ($type) {
            case ListProduct::TYPE_PROTEINS:
                $products = $this->getProteinsProduct();
                break;
            case ListProduct::TYPE_FATS:
                $products = $this->getFatsProduct();
                break;
            case ListProduct::TYPE_HYDRATES:
                $products = $this->getHidratesProduct();
                break;
        }

        foreach ($products as $name => $values) {
            $product = new Product();
            $product->setName($name);
            $product->setListProduct($entity);
            $product->setKcal($values[1]);
            $product->setGrams($values[0]);
            $this->entityManager->persist($product);
        }

        if (count($products)) {
            $this->entityManager->flush();
        }
    }

    public function getProteinsProduct()
    {
        return array(
            "Pechuga de pollo" => array(0 => 81, 1 => 100),
            "Solomillo de pollo" => array(0 => 81, 1 => 100),
            "Contramuslo de pollo deshuesado sin piel" => array(0 => 63, 1 => 100),
            "Alitas de pollo" => array(0 => 49, 1 => 100),
            "Hamburguesa de pollo" => array(0 => 56, 1 => 100),
            "Ternera" => array(0 => 57, 1 => 100),
            "Hamburguesa de ternera" => array(0 => 48, 1 => 100),
            "Carne picada de ternera" => array(0 => 57, 1 => 100),
            "Hígado de ternera/cerdo" => array(0 => 60, 1 => 100),
            "Lomo de cerdo" => array(0 => 70, 1 => 100),
            "Merluza" => array(0 => 40, 1 => 100),
            "Bacalao" => array(0 => 40, 1 => 100),
            "Rape" => array(0 => 40, 1 => 100),
            "Lubina" => array(0 => 40, 1 => 100),
            "Lenguado" => array(0 => 40, 1 => 100),
            "Jamón serrano" => array(0 => 29, 1 => 100),
            "Huevo" => array(0 => 71, 1 => 100),
            "Tofu" => array(0 => 77, 1 => 100),
            "Cecina" => array(0 => 24, 1 => 100),
            "Seitán" => array(0 => 54, 1 => 100),
            "Queso fresco entero (tipo burgos)" => array(0 => 58, 1 => 100),
            "Salmón" => array(0 => 43, 1 => 100),
            "Atún" => array(0 => 54, 1 => 100),
            "Pez espada" => array(0 => 57, 1 => 100),
            "Caballa" => array(0 => 57, 1 => 100),
            "Calamar" => array(0 => 40, 1 => 100),
            "Gamba" => array(0 => 40, 1 => 100),
            "Mejillón" => array(0 => 40, 1 => 100),
            "Pulpo" => array(0 => 40, 1 => 100),
            "Sepia" => array(0 => 40, 1 => 100),
            "Gulas" => array(0 => 87, 1 => 100),
            "Fiambre de pollo" => array(0 => 76, 1 => 100),
            "Fiambre de pavo" => array(0 => 76, 1 => 100),
            "Atún al natural en lata" => array(0 => 57, 1 => 100),
            "Clara de huevo" => array(0 => 200, 1 => 100),
            "Queso cottage" => array(0 => 45, 1 => 100),
            "Queso fresco desnatado/batido 0%" => array(0 => 59, 1 => 100),
            "Soja texturizada" => array(0 => 35, 1 => 100),
            "Bebida vegetal" => array(0 => 40, 1 => 100),
            "Yogur natural desnatado" => array(0 => 40, 1 => 100),
            "Leche desnatada" => array(0 => 40, 1 => 100),
        );
        
    }

    public function getFatsProduct()
    {
        return array(
            "Aceite de oliva virgen extra (AOVE)" => array(0 => 10, 1 => 100),
            "Queso curado" => array(0 => 30, 1 => 100),
            "Frutos secos" => array(0 => 15, 1 => 100),
            "Crema de frutos secos" => array(0 => 16, 1 => 100),
            "Aguacate" => array(0 => 40, 1 => 100),
            "Aceituna" => array(0 => 30, 1 => 100),
            "Coco" => array(0 => 13, 1 => 100),
            "Cacao puro desgrasado en polvo" => array(0 => 12, 1 => 100),
            "Chocolate 85%" => array(0 => 10, 1 => 100),
            "Mantequilla" => array(0 => 12, 1 => 100),
            "Pipas de calabaza" => array(0 => 20, 1 => 100),
            "Semillas de lino" => array(0 => 12, 1 => 100),
            "Semillas de chía" => array(0 => 20, 1 => 100)
        );
        
    }

    public function getHidratesProduct()
    {
        return array(
            "Patata" => array(0 => 150, 1 => 100),
            "Boniato" => array(0 => 100, 1 => 100),
            "Pasta" => array(0 => 30, 1 => 100),
            "Arroz" => array(0 => 30, 1 => 100),
            "Quinoa" => array(0 => 30, 1 => 100),
            "Cuscús" => array(0 => 30, 1 => 100),
            "Pan blanco/integral" => array(0 => 40, 1 => 100),
            "Copos de avena" => array(0 => 30, 1 => 100),
            "Harina de trigo" => array(0 => 30, 1 => 100),
            "Harina de avena" => array(0 => 30, 1 => 100),
            "Harina de maíz" => array(0 => 30, 1 => 100),
            "Maíz dulce" => array(0 => 120, 1 => 100),
            "Yuca" => array(0 => 60, 1 => 100),
            "Tortilla para fajita" => array(0 => 40, 1 => 100),
            "Tortitas de arroz" => array(0 => 28, 1 => 100),
            "Tortitas de maíz" => array(0 => 28, 1 => 100),
            "Pan tostado" => array(0 => 35, 1 => 100),
            "Legumbres de bote" => array(0 => 120, 1 => 100),
            "Legumbres (pesadas en seco)" => array(0 => 30, 1 => 100),
            "Gnocchi de patata" => array(0 => 65, 1 => 100),
            "Pasta de harina de lenteja roja" => array(0 => 30, 1 => 100),
            "Noodles de arroz" => array(0 => 30, 1 => 100),
            "Fruta" => array(0 => 150, 1 => 100)
        );
        
    }
    
}
