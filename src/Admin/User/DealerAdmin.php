<?php

declare(strict_types=1);

namespace App\Admin\User;

use App\Admin\AbstractAdmin;
use App\Admin\Traits\ConfigureAdminFullTrait;
use App\Entity\User\Dealer;
use App\Repository\User\DealerRepository;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @method Dealer|null getSubject()
 */
class DealerAdmin extends AbstractAdmin
{
    use ConfigureAdminFullTrait;

    private DealerRepository $dealerRepository;

    #[Required]
    public function initDependencies(DealerRepository $dealerRepository): void
    {
        $this->dealerRepository = $dealerRepository;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        parent::configureDatagridFilters($filter);
        $this->configureFilterFieldText($filter, 'title', 'DEALER_ENTITY.LABEL.TITLE');
        $this->configureFilterFieldText($filter, 'slug', 'DEALER_ENTITY.LABEL.SLUG');
        $this->configureFilterFieldText($filter, 'email', 'DEALER_ENTITY.LABEL.EMAIL');
        $this->configureFilterFieldActive($filter);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $this->configureListFieldText(
            $list,
            'title',
            'DEALER_ENTITY.LABEL.TITLE',
            [
                'identifier' => true,
                'route'      => ['name' => 'edit'],
            ]
        );

        $list->add(
            'slug',
            'url',
            [
                'label'        => 'DEALER_ENTITY.LABEL.SLUG',
                'help'         => 'DEALER_ENTITY.HELP.SLUG',
                'header_style' => 'width:170px;',
                'attributes'   => ['target' => '_blank'],
                'template'     => 'admin/CRUD/dealer/list__slug_entity.twig',
            ]
        );

        $this->configureListFieldText(
            $list,
            'email',
            'DEALER_ENTITY.LABEL.EMAIL',
        );

        $this->configureListFieldActive($list);
        $this->configureListFieldChildrenActions($list);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->with('DEALER_ENTITY.SECTION.MAIN');

        $this->configureFormFieldActive($form);

        $this->configureFormFieldText(
            $form,
            'title',
            'DEALER_ENTITY.LABEL.TITLE',
            'DEALER_ENTITY.HELP.TITLE',
            true
        );

        $this->configureFormFieldVichImage(
            $form,
            'imageFile',
            'DEALER_ENTITY.LABEL.IMAGE',
            'DEALER_ENTITY.HELP.IMAGE',
            !($this->getSubject() && $this->getSubject()->getImage())
        );

        $form->add(
            'slug',
            TextType::class,
            [
                'label'       => 'DEALER_ENTITY.LABEL.SLUG',
                'help'        => 'DEALER_ENTITY.HELP.SLUG',
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                    new Callback(
                        function (?string $value, ExecutionContext $context) {
                            if (null !== $value) {
                                $dealer = $this->dealerRepository->getOneBySlug($value, $this->getSubject()?->getId());

                                if ($dealer instanceof Dealer) {
                                    $context
                                        ->buildViolation('DEALER_ENTITY.ERROR.SLUG_EXISTS')
                                        ->setTranslationDomain($this->getTranslationDomain())
                                        ->addViolation();
                                }
                            }
                        }
                    ),
                ],
            ]
        );

        $this->configureFormFieldText(
            $form,
            'email',
            'DEALER_ENTITY.LABEL.EMAIL',
            'DEALER_ENTITY.HELP.EMAIL',
            true
        );

        $this->configureFormFieldText(
            $form,
            'countryName',
            'DEALER_ENTITY.LABEL.COUNTRY',
            'DEALER_ENTITY.HELP.COUNTRY'
        );

        $this->configureFormFieldText(
            $form,
            'regionName',
            'DEALER_ENTITY.LABEL.REGION',
            'DEALER_ENTITY.HELP.REGION'
        );

        $this->configureFormFieldText(
            $form,
            'cityName',
            'DEALER_ENTITY.LABEL.CITY',
            'DEALER_ENTITY.HELP.CITY'
        );

        $this->configureFormFieldText(
            $form,
            'addressLine1',
            'DEALER_ENTITY.LABEL.ADDRESS_LINE_1',
            'DEALER_ENTITY.HELP.ADDRESS_LINE_1'
        );

        $this->configureFormFieldText(
            $form,
            'addressLine2',
            'DEALER_ENTITY.LABEL.ADDRESS_LINE_2',
            'DEALER_ENTITY.HELP.ADDRESS_LINE_2'
        );

        $this->configureFormFieldText(
            $form,
            'postalCode',
            'DEALER_ENTITY.LABEL.POSTAL_CODE',
            'DEALER_ENTITY.HELP.POSTAL_CODE'
        );

        $form->end();
    }
}
