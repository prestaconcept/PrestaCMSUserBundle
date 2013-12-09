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

use FOS\UserBundle\Model\UserInterface;
use Sonata\UserBundle\Controller\ChangePasswordController as SonataChangePasswordController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ChangePasswordController extends SonataChangePasswordController
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
     * {@inherit}
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->container->get('templating')->renderResponse(
            $view,
            array_merge($parameters, $this->getBaseViewParams()),
            $response
        );
    }

    /**
     * Change user password
     */
    public function changePasswordAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('fos_user.change_password.form');
        $formHandler = $this->container->get('fos_user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'change_password.flash.success');

            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        return $this->render(
            'FOSUserBundle:ChangePassword:changePassword.html.twig',
            array('form' => $form->createView())
        );
    }
}
