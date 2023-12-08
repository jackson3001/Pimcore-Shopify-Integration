Check the Semantic Versioning page for info on how to version the new release: http://semver.org

Ensure your local repo is up-to-date

run composer require shopify/shopify-api

1 Edit the composer.json file and register the bundle
  namespace in the "autoload:psr-4" section and run composer dump-autoload:
  "ShopifyBundle\\": "bundles/ShopifyBundle/src/"

2 Edit the application configuration and make sure
  you have added the bundles/ShopifyBundle to the Pimcore bundle search paths:
   pimcore:
      bundles:
         search_paths:
             - bundles/ShopifyBundle

3 Edit the application config/bundles.php and make sure
  you have added the ShopifyBundle\ShopifyBundle to the bundles array like following:
    ShopifyBundle\ShopifyBundle::class => ['all' => true],

4 $ composer update

5 run $ bin/console shopify-bundle:generate-class
    this will create class definition in your system

6 run $ bin/console pimcore:deployment:classes-rebuild -d -c
    this will create class in your system

7 create ShopifyStore data object and fill all mendatory fields to configure your shopify account