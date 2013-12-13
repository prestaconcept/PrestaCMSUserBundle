
## Installation social login with twitter, facebook and google plus

We use hwi/oauthbundle to handle oauth login inside this bundle.
You need to follow the step below to enable oauth integration

First add HWIOAuthBundle in your composer.json

```js
{
    "require": {
        "hwi/oauth-bundle": "0.3.*@dev"
    }
}
```

Then enable the bundles in app/Kernel.php
```php
public function registerBundles()
{
    $bundles = array(
        // ...
        new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
    );
}
```

Add hwi_oauth config inside config.yml

```yml
# app/config/config.yml
hwi_oauth:
    connect: ~
    # name of the firewall in which this bundle is active, this setting MUST be set
    firewall_name: main
    fosub:
        username_iterations: 30
        properties:
            # these properties will be used/redefined later in the custom FOSUBUserProvider service.
            twitter: twitterUid
            facebook: facebookUid
            google: gplusUid
    resource_owners:
        twitter:
            type:          twitter
            client_id:     %twitter.client_id%
            client_secret: %twitter.client_secret%
        facebook:
            type:          facebook
            client_id:     %facebook.client_id%
            client_secret: %facebook.client_secret%
            scope:         "email"
        google:
            type:          google
            client_id:     %google.client_id%
            client_secret: %google.client_secret%
            scope:         "https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile"
```

Import oauth.yml routing file before PrestaCMSUserBundle routing.yml file, just like below

```yml
# app/config/routing.yml
prestacms_user_oauth:
    resource: "@PrestaCMSUserBundle/Resources/config/routing/oauth.yml"
    prefix:  /user

prestacms_user:
    resource: "@PrestaCMSUserBundle/Resources/config/routing.xml"
    prefix: /user
```

configure the oauth firewall

```
# app/config/security.yml
security:
    encoders:
        Application\Sonata\UserBundle\Entity\User: sha512

    providers:
        fos_userbundle:
            id: fos_user.user_manager

    firewalls:
        main:
            pattern:      .*
            form_login:
                provider:       fos_userbundle
                login_path:     /user/login
                use_forward:    false
                check_path:     /user/login_check
                failure_path:   null
            oauth:
                resource_owners:
                    twitter: "/user/connect/check-twitter"
                    facebook: "/user/connect/check-facebook"
                    google: "/user/connect/check-google"
                login_path: /user/connect
                failure_path: /user/connect
                oauth_user_provider:
                    service: hwi_oauth.user.provider.fosub_bridge
            logout:
                path: /user/logout
            anonymous:    true

    access_control:
        # URL of FOSUserBundle which need to be available to anonymous users
        - { path: ^/user/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/login, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/user/connect, role: IS_AUTHENTICATED_ANONYMOUSLY }
```

You can find an example below to how to add a "Sign in with twitter" link in your login page

```
<a href="{{ path('hwi_oauth_service_redirect', {'service': 'twitter' }) }}" alt="Sign in with Twitter">Sign in with Twitter</a>
```

Useful link to setup some oauth provides
+ Twitter: [https://dev.twitter.com/docs/auth/using-oauth](https://dev.twitter.com/docs/auth/using-oauth)
+ Facebook: [https://developers.facebook.com/docs/facebook-login/](https://developers.facebook.com/docs/facebook-login/)
+ Google+: [https://developers.google.com/accounts/docs/OAuth2Login](https://developers.google.com/accounts/docs/OAuth2Login)
