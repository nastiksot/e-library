<?php
declare(strict_types=1);

namespace App\Form\Type;

use App\Service\Manager\AuthorManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class BookType extends AbstractEntityType
{

    protected AuthorManager $authorManager;

    public function __construct(AuthorManager $authorManager)
    {
        $this->authorManager = $authorManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'constraints' => new NotBlank()
                ]
            )
            ->add(
                'description',
                TextareaType::class
            )
            ->add(
                'authors',
                ChoiceType::class,
                [
                    'multiple' => true,
                    'choices'  => $this->getAuthorsChoices(),
                ]
            );
    }

    public function getAuthorsChoices(): array
    {
        $choices = [];
        $authors = $this->authorManager->all();
        foreach ($authors as $author) {
            $choices[$author['first_name'] . ' ' . $author['last_name']] = (int)$author['id'];
        }

        return $choices;
    }
}
