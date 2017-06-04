Escape Hither SecurityManagerBundle
===============================

Step 1: Download the Bundle
---------------------------
The Bundle is actually in a private Repository.
In your Composer.json add:
```json
{
  //....
  "repositories": [{
    "type": "composer",
    "url": "https://packages.escapehither.com"
  }]

}
```
Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require escapehither/security-manager-bundle dev-master
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

             new EscapeHither\CrudManagerBundle\StarterKitCrudBundle(),
             new EscapeHither\SecurityManagerBundle\StarterKitSecurityManagerBundle(),
             new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
             new Knp\Bundle\MenuBundle\KnpMenuBundle(),
             new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
             new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),
        );

        // ...
    }

    // ...
}
```

7. Import config file in `app/config/config.yml` for default filter set configuration:

    ```yaml
    imports:
       - { resource: "@EscapeHitherSecurityManagerBundle/Resources/config/services.yml" }
       - { resource: "@EscapeHitherSecurityManagerBundle/Resources/config/config.yml" }
    ```

8. Import routing files in `app/config/routing.yml`:

    ```yaml
    escape_hither_security_manager:
        resource: "@EscapeHitherSecurityManagerBundle/Resources/config/routing.yml"
        prefix:   /
    ```

8. Configuration reference:

    ```yaml
    escape_hither_security_manager:
        user_provider:
            class : AppBundle\Entity\User
    ```
8. Import security files in `app/config/security.yml`:
    ```yaml
    providers:
                our_users:
                    entity: { class: AppBundle\Entity\User, property: email }
    firewalls:
    #........
        login:
                    pattern:  ^/api/login
                    stateless: true
                    anonymous: true
                    form_login:
                        check_path:               /api/login_check
                        success_handler:          lexik_jwt_authentication.handler.authentication_success
                        failure_handler:          lexik_jwt_authentication.handler.authentication_failure
                        require_previous_session: false
        api:
                    pattern:   ^/api
                    stateless: true
                    guard:
                        authenticators:
                            - lexik_jwt_authentication.jwt_token_authenticator
                            #- escapehither.security_jwt_token_authenticator #my authenticator
        main:
                    anonymous: ~
                    # activate different ways to authenticate

                    # http_basic: ~
                    # http://symfony.com/doc/current/security.html#a-configuring-how-your-users-will-authenticate

                    # form_login: ~
                    # http://symfony.com/doc/current/cookbook/security/form_login_setup.html
                    guard:
                        entry_point: escapehither.security_login_form_authenticator
                        authenticators:
                            - escapehither.security_login_form_authenticator
                    logout:
                        path: /logout
    encoders:
        EscapeHither\SecurityManagerBundle\Entity\UserAccountInterface: bcrypt
    ```

 Add encoder for jwt.
mkdir var/jwt
openssl genrsa -out var/jwt/private.pem -aes256 4096
openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem

