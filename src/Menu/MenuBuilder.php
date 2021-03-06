<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuBuilder
{
    private FactoryInterface $factory;
    private string $username;
    private TranslatorInterface $translator;

    /**
     * @param FactoryInterface      $factory
     * @param TokenStorageInterface $tokenStorage
     * @param TranslatorInterface   $translator
     */
    public function __construct(FactoryInterface $factory, TokenStorageInterface $tokenStorage, TranslatorInterface $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;

        if ($tokenStorage->getToken() && $tokenStorage->getToken()->getUser()) {
            $this->username = $tokenStorage->getToken()->getUser()->getUsername();
        }
    }

    public function createUserMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'list-unstyled');

        $this->addProfileMenu($menu);
        $menu->addChild('hr', ['label' => '<hr>', 'labelAttributes' => ['class' => 'normal'], 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.logout'), ['label' => '<span class="icon-off"></span>' . $this->translator->trans('menu.logout'), 'route' => 'logout', 'extras' => ['safe_label' => true]]);

        return $menu;
    }

    public function createProfileMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-tabs nav-stacked');

        $this->addProfileMenu($menu);

        return $menu;
    }

    private function addProfileMenu($menu)
    {
        $menu->addChild($this->translator->trans('menu.profile'), ['label' => '<span class="icon-vcard"></span>' . $this->translator->trans('menu.profile'), 'route' => 'fos_user_profile_show', 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.settings'), ['label' => '<span class="icon-tools"></span>' . $this->translator->trans('menu.settings'), 'route' => 'fos_user_profile_edit', 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.change_password'), ['label' => '<span class="icon-key"></span>' . $this->translator->trans('menu.change_password'), 'route' => 'fos_user_change_password', 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.configure_2fa'), ['label' => '<span class="icon-mobile"></span>' . $this->translator->trans('menu.configure_2fa'), 'route' => 'user_2fa_configure', 'routeParameters' => ['name' => $this->username], 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.my_packages'), ['label' => '<span class="icon-box"></span>' . $this->translator->trans('menu.my_packages'), 'route' => 'user_packages', 'routeParameters' => ['name' => $this->username], 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.my_favorites'), ['label' => '<span class="icon-leaf"></span>' . $this->translator->trans('menu.my_favorites'), 'route' => 'user_favorites', 'routeParameters' => ['name' => $this->username], 'extras' => ['safe_label' => true]]);
    }
}
