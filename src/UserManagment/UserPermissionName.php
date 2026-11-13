<?php

declare(strict_types=1);

namespace Calexandar\Adminium\UserManagment;

enum UserPermissionName: string
{
    case MANAGE_SETTINGS = 'manage_settings';
    case MANAGE_SLIDER = 'manage_slider';
    case MANAGE_CATEGORIES = 'manage_categories';
    case MANAGE_GROUPS = 'manage_groups';
    case MANAGE_PRODUCTS = 'manage_products';
    case MANAGE_ARTICLES = 'manage_articles';
    case MANAGE_NEWS = 'manage_news';
    case MANAGE_MEDIA = 'manage_media';
    case MANAGE_PAGES = 'manage_pages';
    case MANAGE_FOOTER_LINKS = 'manage_footer_links';
    case MANAGE_EDITABLES = 'manage_editables';
    case MANAGE_USERS = 'manage_users';
    case MANAGE_BANNERS = 'manage_banners';
    case MANAGE_ACCORDIONS = 'manage_accordions';
    case MANAGE_MOTOS = 'manage_motos';
    case MANAGE_LOYALTY = 'manage_loyalty';
    case MANAGE_MOBILEAPP = 'manage_mobileapp';

    public function module(): string
    {
        return match ($this) {
            self::MANAGE_SETTINGS => 'settings',
            self::MANAGE_CATEGORIES => 'categories',
            self::MANAGE_GROUPS => 'groups',
            self::MANAGE_PRODUCTS => 'products',
            self::MANAGE_ARTICLES => 'articles',
            self::MANAGE_NEWS => 'news',
            self::MANAGE_MEDIA => 'media',
            self::MANAGE_PAGES => 'pages',
            self::MANAGE_USERS => 'users',
            self::MANAGE_SLIDER,
            self::MANAGE_FOOTER_LINKS,
            self::MANAGE_EDITABLES,
            self::MANAGE_BANNERS,
            self::MANAGE_ACCORDIONS,
            self::MANAGE_MOTOS,
            self::MANAGE_LOYALTY,
            self::MANAGE_MOBILEAPP => 'legacy',
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::MANAGE_SETTINGS => 'Manage Settings',
            self::MANAGE_SLIDER => 'Manage Slider',
            self::MANAGE_CATEGORIES => 'Manage Categories',
            self::MANAGE_GROUPS => 'Manage Groups',
            self::MANAGE_PRODUCTS => 'Manage Products',
            self::MANAGE_ARTICLES => 'Manage Articles',
            self::MANAGE_NEWS => 'Manage News',
            self::MANAGE_MEDIA => 'Manage Media',
            self::MANAGE_PAGES => 'Manage Pages',
            self::MANAGE_FOOTER_LINKS => 'Manage Footer Links',
            self::MANAGE_EDITABLES => 'Manage Editables',
            self::MANAGE_USERS => 'Manage Users',
            self::MANAGE_BANNERS => 'Manage Banners',
            self::MANAGE_ACCORDIONS => 'Manage Accordions',
            self::MANAGE_MOTOS => 'Manage Motos',
            self::MANAGE_LOYALTY => 'Manage Loyalty',
            self::MANAGE_MOBILEAPP => 'Manage Mobile App',
        };
    }
}
