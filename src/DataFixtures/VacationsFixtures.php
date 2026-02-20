<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Vacation;
use App\Entity\VacationType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Component\DependencyInjection\Attribute\Target;

class VacationsFixtures extends Fixture {

    private Generator $faker;

    public function __construct(
        #[Target('vacations')] private readonly WorkflowInterface $vacationsWorkflow
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void {

        $vacationsTypes = [
            'Police secours',
            'Service judiciaire',
            'Compagnie de marche / LIC',
        ];

        foreach ($vacationsTypes as $typeName) {
            $type = new VacationType();
            $type->setName($typeName);
            $manager->persist($type);

            for ($i = 0; $i < 10; $i++) {
                $vacation = new Vacation();
                $vacation->setDate(DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-6 months')
                ));
                $vacation->setState(
                    $this->faker->randomElement(
                        $this->vacationsWorkflow->getDefinition()->getPlaces()
                    )
                );
                $vacation->setHours($this->faker->numberBetween(7, 12));
                $vacation->setType($type);
                $manager->persist($vacation);
            }
        }

        $manager->flush();
    }
}
