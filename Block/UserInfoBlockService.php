<?php
/**
 * This file is part of the PrestaCMSUserBundle.
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSUserBundle\Block;

use Presta\CMSCoreBundle\Block\BaseBlockService;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class UserInfoBlockService extends BaseBlockService
{
    /**
     * @var string
     */
    protected $template = 'PrestaCMSUserBundle:Block:block_user_info.html.twig';
}
