<?php

namespace App\Command\ExecuteOneTime;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Payment\Tariff;
use App\Entity\Place\State;
use App\Entity\Place\Country;

class EcommerceCommand extends Command
{
    protected static $defaultName = 'app:ecommerce-start';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Fill states data in the database.')
            ->setHelp('This command fills the database with data for states in Spain.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importTariff($input,$output);
        $this->importCountry($input,$output);

        return Command::SUCCESS;
    }

    private function importTariff(InputInterface $input, OutputInterface $output)
    {
        $repository = $this->entityManager->getRepository(Tariff::class);
        $entity = $repository->findOneBy(['isDefault' => true]);

        if (!$entity) {
            $entity = new Tariff();
            $entity->setName('UNIQUE');
            $entity->setTitle('Tarifa gratis');
            $entity->setDescription('Tarifa gratis');
            $entity->setIsActive(false);
            $entity->setIsDefault(true);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
        $output->writeln('Tariff default create.');
    }

    private function importCountry(InputInterface $input, OutputInterface $output)
    {
        $this->importSpain();
        $output->writeln('Spain states data has been filled in the database.');

        $this->importPortugal();
        $output->writeln('Portugal states data has been filled in the database.');

        $this->importAndorra();
        $output->writeln('Andorra states data has been filled in the database.');
    }

    private function importSpain()
    {
        $countryRepository = $this->entityManager->getRepository(Country::class);
        $country = $countryRepository->findOneBy(['lang' => 'es']);

        if (!$country) {
            $country = new Country();
            $country->setName('España');
            $country->setLang('es');

            $this->entityManager->persist($country);
            $this->entityManager->flush();
        }else{
            return;
        }

        $statesData = [
            ['name' => 'Álava', 'postal_code' => '010XX'],
            ['name' => 'Albacete', 'postal_code' => '020XX'],
            ['name' => 'Alicante', 'postal_code' => '030XX'],
            ['name' => 'Almería', 'postal_code' => '040XX'],
            ['name' => 'Asturias', 'postal_code' => '330XX'],
            ['name' => 'Ávila', 'postal_code' => '050XX'],
            ['name' => 'Badajoz', 'postal_code' => '060XX'],
            ['name' => 'Barcelona', 'postal_code' => '080XX'],
            ['name' => 'Burgos', 'postal_code' => '090XX'],
            ['name' => 'Cáceres', 'postal_code' => '100XX'],
            ['name' => 'Cádiz', 'postal_code' => '110XX'],
            ['name' => 'Cantabria', 'postal_code' => '390XX'],
            ['name' => 'Castellón', 'postal_code' => '120XX'],
            ['name' => 'Ciudad Real', 'postal_code' => '130XX'],
            ['name' => 'Córdoba', 'postal_code' => '140XX'],
            ['name' => 'Cuenca', 'postal_code' => '160XX'],
            ['name' => 'Gerona', 'postal_code' => '170XX'],
            ['name' => 'Granada', 'postal_code' => '180XX'],
            ['name' => 'Guadalajara', 'postal_code' => '190XX'],
            ['name' => 'Guipúzcoa', 'postal_code' => '200XX'],
            ['name' => 'Huelva', 'postal_code' => '210XX'],
            ['name' => 'Huesca', 'postal_code' => '220XX'],
            ['name' => 'Islas Baleares', 'postal_code' => '070XX'],
            ['name' => 'Jaén', 'postal_code' => '230XX'],
            ['name' => 'La Coruña', 'postal_code' => '150XX'],
            ['name' => 'La Rioja', 'postal_code' => '260XX'],
            ['name' => 'Las Palmas', 'postal_code' => '350XX'],
            ['name' => 'León', 'postal_code' => '240XX'],
            ['name' => 'Lérida', 'postal_code' => '250XX'],
            ['name' => 'Lugo', 'postal_code' => '270XX'],
            ['name' => 'Madrid', 'postal_code' => '280XX'],
            ['name' => 'Málaga', 'postal_code' => '290XX'],
            ['name' => 'Murcia', 'postal_code' => '300XX'],
            ['name' => 'Navarra', 'postal_code' => '310XX'],
            ['name' => 'Orense', 'postal_code' => '320XX'],
            ['name' => 'Palencia', 'postal_code' => '340XX'],
            ['name' => 'Pontevedra', 'postal_code' => '360XX'],
            ['name' => 'Salamanca', 'postal_code' => '370XX'],
            ['name' => 'Santa Cruz de Tenerife', 'postal_code' => '380XX'],
            ['name' => 'Segovia', 'postal_code' => '400XX'],
            ['name' => 'Sevilla', 'postal_code' => '410XX'],
            ['name' => 'Soria', 'postal_code' => '420XX'],
            ['name' => 'Tarragona', 'postal_code' => '430XX'],
            ['name' => 'Teruel', 'postal_code' => '440XX'],
            ['name' => 'Toledo', 'postal_code' => '450XX'],
            ['name' => 'Valencia', 'postal_code' => '460XX'],
            ['name' => 'Valladolid', 'postal_code' => '470XX'],
            ['name' => 'Vizcaya', 'postal_code' => '480XX'],
            ['name' => 'Zamora', 'postal_code' => '490XX'],
            ['name' => 'Zaragoza', 'postal_code' => '500XX'],
        ];

        foreach ($statesData as $stateData) {
            $state = new State();
            $state->setName($stateData['name']);
            $state->setCp($stateData['postal_code']);
            $state->setCountry($country);

            $this->entityManager->persist($state);
        }

        $this->entityManager->flush();

    }

    private function importPortugal()
    {
        $countryRepository = $this->entityManager->getRepository(Country::class);
        $country = $countryRepository->findOneBy(['lang' => 'pt']);

        if (!$country) {
            $country = new Country();
            $country->setName('Portugal');
            $country->setLang('pt');

            $this->entityManager->persist($country);
            $this->entityManager->flush();
        }else{
            return;
        }

        $statesData = [
            ['name' => 'Aveiro', 'postal_code' => '38XXX'],
            ['name' => 'Beja', 'postal_code' => '77XXX'],
            ['name' => 'Braga', 'postal_code' => '47XXX'],
            ['name' => 'Braganza', 'postal_code' => '53XXX'],
            ['name' => 'Castelo Branco', 'postal_code' => '60XXX'],
            ['name' => 'Coimbra', 'postal_code' => '30XXX'],
            ['name' => 'Évora', 'postal_code' => '70XXX'],
            ['name' => 'Faro', 'postal_code' => '80XXX'],
            ['name' => 'Guarda', 'postal_code' => '63XXX'],
            ['name' => 'Leiria', 'postal_code' => '24XXX'],
            ['name' => 'Lisboa', 'postal_code' => '10XXX'],
            ['name' => 'Portalegre', 'postal_code' => '73XXX'],
            ['name' => 'Porto', 'postal_code' => '40XXX'],
            ['name' => 'Santarém', 'postal_code' => '20XXX'],
            ['name' => 'Setúbal', 'postal_code' => '29XXX'],
            ['name' => 'Viana do Castelo', 'postal_code' => '49XXX'],
            ['name' => 'Vila Real', 'postal_code' => '50XXX'],
            ['name' => 'Viseu', 'postal_code' => '35XXX'],
        ];

        foreach ($statesData as $stateData) {
            $state = new State();
            $state->setName($stateData['name']);
            $state->setCp($stateData['postal_code']);
            $state->setCountry($country);

            $this->entityManager->persist($state);
        }

        $this->entityManager->flush();
    }

    private function importAndorra()
    {
        $countryRepository = $this->entityManager->getRepository(Country::class);
        $country = $countryRepository->findOneBy(['lang' => 'ad']);

        if (!$country) {
            $country = new Country();
            $country->setName('Andorra');
            $country->setLang('ad');

            $this->entityManager->persist($country);
            $this->entityManager->flush();
        }else{
            return;
        }

        $statesData = [
            ['name' => 'Andorra la Vella', 'postal_code' => 'AD500'],
            ['name' => 'Canillo', 'postal_code' => 'AD100'],
            ['name' => 'Encamp', 'postal_code' => 'AD200'],
            ['name' => 'Escaldes-Engordany', 'postal_code' => 'AD700'],
            ['name' => 'La Massana', 'postal_code' => 'AD400'],
            ['name' => 'Ordino', 'postal_code' => 'AD300'],
            ['name' => 'Sant Julià de Lòria', 'postal_code' => 'AD600'],
        ];

        foreach ($statesData as $stateData) {
            $state = new State();
            $state->setName($stateData['name']);
            $state->setCp($stateData['postal_code']);
            $state->setCountry($country);

            $this->entityManager->persist($state);
        }

        $this->entityManager->flush();
    }
    
}
