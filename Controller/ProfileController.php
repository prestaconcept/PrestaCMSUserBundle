<?php
/**
 * This file is part of the PrestaCMSUserBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSUserBundle\Controller;

use Presta\CMSCoreBundle\Model\ThemeManager;
use Presta\CMSCoreBundle\Model\WebsiteManager;
use Sonata\SeoBundle\Seo\SeoPageInterface;
use Sonata\UserBundle\Controller\ProfileController as SonataProfileController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Add PrestaCMS base view parameters to SonataUser Profile actions
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class ProfileController extends SonataProfileController
{
    /**
     * @return WebsiteManager
     */
    protected function getWebsiteManager()
    {
        return $this->get('presta_cms.manager.website');
    }

    /**
     * @return ThemeManager
     */
    protected function getThemeManager()
    {
        return $this->get('presta_cms.manager.theme');
    }

    /**
     * @return SeoPageInterface
     */
    protected function getSeoManager()
    {
        return $this->get('sonata.seo.page');
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
     * {@inherit}
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        return parent::render($view, array_merge($parameters, $this->getBaseViewParams()), $response);
    }
}
