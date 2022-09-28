<?php

declare(strict_types=1);

namespace App\Admin\Book;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\Book\Author;
use App\Entity\Book\Category;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Author|null getSubject()
 */
class AuthorAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

//    protected LocaleProvider $localeProvider;
//
//    protected LanguageRepository $languageRepository;
//
//    private EntityManagerInterface $em;
//
//    #[Required]
//    public function initDependencies(
//        EntityManagerInterface $em,
//        LocaleProvider $localeProvider,
//        LanguageRepository $languageRepository,
//    ): void {
//        $this->em                 = $em;
//        $this->localeProvider     = $localeProvider;
//        $this->languageRepository = $languageRepository;
//    }

//    protected function configureDefaultSortValues(array &$sortValues): void
//    {
//        $sortValues = [
//            '_sort_order' => 'ASC',
//            '_sort_by'    => 'code',
//        ];
//    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('firstName', null, ['label' => 'BOOK_AUTHOR_ENTITY.LABEL.FIRST_NAME']);
        $filter->add('lastName', null, ['label' => 'BOOK_AUTHOR_ENTITY.LABEL.LAST_NAME']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'firstName', 'BOOK_AUTHOR_ENTITY.LABEL.FIRST_NAME');
        $this->configureListFieldText($list, 'lastName', 'BOOK_AUTHOR_ENTITY.LABEL.LAST_NAME');

        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $this->configureFormFieldText(
            $form,
            'firstName',
            'BOOK_AUTHOR_ENTITY.LABEL.FIRST_NAME',
            null,
            false,
            ['constraints' => [new NotBlank()]]
        );

        $this->configureFormFieldText(
            $form,
            'lastName',
            'BOOK_AUTHOR_ENTITY.LABEL.LAST_NAME',
            null,
            false,
            ['constraints' => [new NotBlank()]]
        );
    }

}
