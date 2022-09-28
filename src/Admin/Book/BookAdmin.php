<?php

declare(strict_types=1);

namespace App\Admin\Book;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\Book\Author;
use App\Entity\Book\Book;
use App\Entity\Book\Category;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method Book|null getSubject()
 */
class BookAdmin extends AbstractAdmin
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
        $filter->add('name', null, ['label' => 'BOOK_ENTITY.LABEL.NAME']);
        $filter->add('description', null, ['label' => 'BOOK_ENTITY.LABEL.DESCRIPTION']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText($list, 'id', 'ID');
        $this->configureListFieldText($list, 'name', 'BOOK_ENTITY.LABEL.NAME');
        $this->configureListFieldText($list, 'description', 'BOOK_ENTITY.LABEL.DESCRIPTION');

        $this->configureListFieldActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $this->configureFormFieldText(
            $form,
            'name',
            'BOOK_ENTITY.LABEL.NAME',
            null,
            false,
            ['constraints' => [new NotBlank()]]
        );

        $this->configureFormFieldText(
            $form,
            'description',
            'BOOK_ENTITY.LABEL.DESCRIPTION',
            null,
            false,
            ['constraints' => [new NotBlank()]]
        );
    }

}
