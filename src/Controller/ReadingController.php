<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\BookType;
use App\Form\Type\ReadingType;
use App\Service\Manager\AuthorManager;
use App\Service\Manager\BookManager;
use App\Service\Manager\ReadingManager;
use App\Service\Manager\UserManager;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function array_flip;

/**
 * @Route(path="/reading")
 */
class ReadingController extends AbstractController
{

    protected AuthorManager  $authorManager;
    protected BookManager    $bookManager;
    protected ReadingManager $readingManager;
    protected UserManager    $userManager;

    public function __construct(
        AuthorManager $authorManager,
        BookManager $bookManager,
        ReadingManager $readingManager,
        UserManager $userManager
    ) {
        $this->authorManager  = $authorManager;
        $this->bookManager    = $bookManager;
        $this->readingManager = $readingManager;
        $this->userManager    = $userManager;
    }


    /**
     * @Route(path="", name="reading.list")
     */
    public function index(Request $request): Response
    {
        $filter       = $request->query->all();
        $readings     = $this->readingManager->paginate($filter);
        $readingTypes = array_flip(ReadingType::READING_TYPE_CHOICES);

        return $this->render('default/reading/index.html.twig',
            [
                'readings'       => $readings,
                'readingTypes'   => $readingTypes,
                'authorManager'  => $this->authorManager,
                'bookManager'    => $this->bookManager,
                'readingManager' => $this->readingManager,
                'userManager'    => $this->userManager,
            ]
        );
    }
    
    /**
     * @Route(path="/add", name="reading.add")
     */
    public function add(Request $request): Response
    {
        $form = $this->readingManager->form(ReadingType::class);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->readingManager->handleForm($form, $request))
        ) {
            $data = $form->getData();
            $this->readingManager->create($data);

            return $this->redirectToRoute('reading.list');
        }

        return $this->render(
            'default/reading/add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/edit", name="reading.edit")
     */
    public function edit(Request $request, int $id): Response
    {
        $data = $this->readingManager->get($id);

        // convert dates
        $data['start_at'] = $data['start_at'] ? DateTime::createFromFormat('Y-m-d', $data['start_at']) : null;
        $data['end_at']   = $data['end_at'] ? DateTime::createFromFormat('Y-m-d', $data['end_at']) : null;

        // create form
        $form = $this->readingManager->form(ReadingType::class, $data ?? [], ['id' => $id]);
        if ($request->isMethod(Request::METHOD_POST) &&
            !($errors = $this->readingManager->handleForm($form, $request))
        ) {
            $data = $form->getData();
            $this->readingManager->update($id, $data);

            return $this->redirectToRoute('reading.list');
        }

        return $this->render(
            'default/reading/edit.html.twig',
            [
                'id'   => $id,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/{id}/delete", name="reading.delete")
     */
    public function delete(int $id): Response
    {
        $this->readingManager->delete($id);

        return $this->redirectToRoute('reading.list');
    }


}
