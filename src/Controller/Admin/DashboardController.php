<?php

namespace App\Controller\Admin;

use App\Entity\Client\Issue;
use App\Entity\Client\UserMeasure;
use App\Entity\Commerce\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Core\User;
use App\Entity\Calendar\CalendarUser;
use App\Entity\Core\Business;
use App\Entity\Calendar\Meeting;
use App\Entity\Cms\Post;
use App\Entity\Cms\PostEducation;
use App\Entity\Cms\PostRecipes;
use App\Entity\Cms\PostCategory;
use App\Form\BusinessType;

class DashboardController extends AbstractDashboardController
{
    private $parameters;
    public function __construct(ParameterBagInterface $parameters) {
        $this->parameters = $parameters;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $user = $this->getUser();
        if($business = $user->getYourBusiness()){
            //return $this->loadHomeBusiness($business,$request);
        }

        return $this->render('admin/home.html.twig');
    }

    private function loadHomeBusiness(Business $business,Request $request)
    {
        $form = $this->createForm(BusinessType::class,$business);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($business);            
            $this->em->flush();
        }

        return $this->render('admin/homeBusiness.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Nutricion Api');
    }

    public function configureMenuItems(): iterable
    {
        $user = $this->getUser();

        $meetingMenu = MenuItem::linkToCrud('Citas', 'fa-regular fa-handshake', Meeting::class); 
        if ($user->isAdministrationBusiness()) {
            return [
                $meetingMenu,
                MenuItem::linkToLogout('Cerrar Session', 'fa fa-exit')
            ];
        }

        $menu = [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
        ];
        $clients = [
            MenuItem::linkToCrud('Usuarios', 'fa-solid fa-circle-user', User::class),
            MenuItem::linkToCrud('Dietas', 'fa-solid fa-comment-dots', CalendarUser::class),
            $meetingMenu,
            MenuItem::linkToCrud('Medidas', 'fa-solid fa-scale-balanced', UserMeasure::class),
        ];
        $content = [
            MenuItem::linkToCrud('Preguntas Frecuentes', 'fa-solid fa-folder', Post::class),
            MenuItem::linkToCrud('Educación nutricional', 'fa-solid fa-folder', PostEducation::class),
            MenuItem::linkToCrud('Recetas', 'fa-solid fa-folder', PostRecipes::class),
        ];

        if ($user->isUserAdmin()) {
            // links to the 'index' action of the Category CRUD controller
            $menu[] = MenuItem::linkToCrud('Empresas', 'fa-solid fa-briefcase', Business::class);
            $menu[] = MenuItem::linkToCrud('Categorias', 'fa-solid fa-signs-post', PostCategory::class);
        }
        if($user->isBossBusiness()){
            $business = $this->getUser()->getYourBusiness() ?? $this->getUser()->getBusiness();
            if($business){
                $menu[] = MenuItem::linkToCrud('Mi Comercio', 'fa-solid fa-briefcase', Business::class)
                    ->setAction('detail')
                    ->setEntityId($business->getId());
            }
            $clients[] = MenuItem::linkToCrud('Productos','fa fa-cutlery', Product::class);
        }

        /*if ($user->isUserAdmin()) {
            
        }*/

        $menu[] = MenuItem::subMenu('Clientes', 'fa-solid fa-users')->setSubItems($clients);
        $menu[] = MenuItem::subMenu('Contenido', 'fa-regular fa-clipboard')->setSubItems($content);

        $menu[] = MenuItem::linkToCrud('Incidencias','fa fa-info-circle', Issue::class);

        $menu[] = MenuItem::linkToLogout('Cerrar Sessión', 'fa fa-exit');
        return $menu;
    }

    public function configureAssets(): Assets
    {
        $assets = parent::configureAssets();
        if ($this->parameters->get('app.environment') != 'dev') {
            $assets
                ->addJsFile('js/fixProd.js')
            ;
        }

        return $assets;
    }
}

