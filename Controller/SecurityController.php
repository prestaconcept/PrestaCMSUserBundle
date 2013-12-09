<?php
/**
 * This file is part of the PrestaCMSUserBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSUserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as FOSSecurityController;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class SecurityController extends FOSSecurityController
{
    /**
     * @return WebsiteManager
     */
    protected function getWebsiteManager()
    {
        return $this->container->get('presta_cms.manager.website');
    }

    /**
     * @return ThemeManager
     */
    protected function getThemeManager()
    {
        return $this->container->get('presta_cms.manager.theme');
    }

    /**
     * @return SeoPageInterface
     */
    protected function getSeoManager()
    {
        return $this->container->get('sonata.seo.page');
    }

    /**
     * Returns all the necessary view params needed to render the theme layout
     *
     * @return array
     */
    protected function getBaseViewParams()
    {
        $website    = $this->getWebsiteManager()->getCurrentWebsite();
        $theme      = $this->getThemeManager()->getTheme($website->getTheme(), $website);

        return array(
            'base_template'     => $theme->getTemplate(),
            'website'           => $website,
            'websiteManager'    => $this->getWebsiteManager(),
            'theme'             => $theme
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function renderLogin(array $data)
    {
        return parent::renderLogin(array_merge($data, $this->getBaseViewParams()));
    }
}
