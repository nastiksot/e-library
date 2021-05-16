<?php
declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Contracts\UserInterface;
use App\Entity\Reading;
use App\Service\Manager\BookManager;
use App\Service\Manager\UserManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReadingType extends AbstractEntityType
{

    protected BookManager $bookManager;
    protected UserManager $userManager;

    public const READING_TYPE_CHOICES = [
        'Subscription' => Reading::READING_TYPE_SUBSCRIPTION,
        'Reading Hall' => Reading::READING_TYPE_READING_ROOM,
    ];

    public function __construct(
        BookManager $bookManager,
        UserManager $userManager
    ) {
        $this->bookManager = $bookManager;
        $this->userManager = $userManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $bookChoices = ['' => ''] + $this->bookManager->choices('title');
        $userChoices = ['' => ''] + $this->userManager->choices(['first_name', 'last_name'],
                ['role' => UserInterface::ROLE_READER]);

        $readingTypeChoices = ['' => ''] + self::READING_TYPE_CHOICES;

        $builder
            ->add(
                'book_id',
                ChoiceType::class,
                [
                    'label'       => 'Book',
                    'choices'     => $bookChoices,
                    'constraints' => [new NotBlank()],
                ]
            )
            ->add(
                'user_id',
                ChoiceType::class,
                [
                    'label'       => 'Reader',
                    'choices'     => $userChoices,
                    'constraints' => [new NotBlank()],
                ]
            )
            ->add(
                'reading_type',
                ChoiceType::class,
                [
                    'choices'     => $readingTypeChoices,
                    'constraints' => [new NotBlank()],
                ]
            )
            ->add(
                'start_at',
                DateType::class,
                [
                    'widget'      => 'single_text',
                    'constraints' => [new NotBlank()],
                ]
            )
            ->add(
                'end_at',
                DateType::class,
                [
                    'widget'      => 'single_text',
                    'constraints' => [new NotBlank()],
                ]
            );
    }
}
