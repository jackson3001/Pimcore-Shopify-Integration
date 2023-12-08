<?php

namespace ShopifyBundle\Services;

use Pimcore\Model\DataObject;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Filesystem\Filesystem;

class ShopifyService
{
    public const SHOPIFY = 'Shopify';
    /**
     * @param object $object
     * @return mixed
     */
    public function createProduct($object)
    {
        $shopifyCredentials = DataObject\ShopifyStore::getByName(self::SHOPIFY, 1);
            if(!is_null($shopifyCredentials) && !empty($object)){
                // Prepare request body
                $body = [
                  "product" => [
                        "id" => 1072481064, 
                        "title" => "Burton Custom Freestyle 151",
                        "body_html" => "<strong>Good snowboard!</strong>",
                        "vendor" => "Burton",
                        "product_type" => "Snowboard",
                        "created_at" => "2023-11-07T12:36:01-05:00",
                        "handle" => "burton-custom-freestyle-151",
                        "updated_at" => "2023-11-07T12:38:01-05:00",
                        "published_at" => null,
                        "template_suffix" => null,
                        "published_scope" => "web",
                        "tags" => "",
                        "status" => "draft",
                        "admin_graphql_api_id" => "gid://shopify/Product/1072481064",
                        "variants" => [
                           [
                              "id" => 1070325049,
                              "product_id" => 1072481064,
                              "title" => "Default Title",
                              "price" => "0.00",
                              "sku" => "",
                              "position" => 1,
                              "inventory_policy" => "deny",
                              "compare_at_price" => null,
                              "fulfillment_service" => "manual",
                              "inventory_management" => null,
                              "option1" => "Default Title",
                              "option2" => null,
                              "option3" => null,
                              "created_at" => "2023-11-07T12:37:01-05:00",
                              "updated_at" => "2023-11-07T12:37:01-05:00",
                              "taxable" => true,
                              "barcode" => null,
                              "grams" => 0,
                              "image_id" => null,
                              "weight" => 0,
                              "weight_unit" => "lb",
                              "inventory_item_id" => 1070325049,
                              "inventory_quantity" => 0,
                              "old_inventory_quantity" => 0,
                              "presentment_prices" => [
                                 [
                                    "price" => [
                                       "amount" => "0.00",
                                       "currency_code" => "USD"
                                    ],
                                    "compare_at_price" => null
                                 ]
                              ],
                              "requires_shipping" => true,
                              "admin_graphql_api_id" => "gid://shopify/ProductVariant/1070325049"
                           ]
                        ],
                        "image" => null
                     ]
               ];
                //Add product to shopify platform
                try{
                    $client = HttpClient::create();
                    $storeEndpoint = $shopifyCredentials->getApiEndpoint();
                    $accessToken = $shopifyCredentials->getAccessToken();
                    $response = $client->request('POST', $storeEndpoint,[
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'X-Shopify-Access-Token' => $accessToken
                        ],
                        'body' => $body
                        
                    ]);
                }catch (\Exception $e) {
                    return $e;
                }

                // Adding a product Id in object brick
                if(!empty($response->toArray()['product']['id'])){
                    $id = $response->toArray()['product']['id'];
                    $channelBrick = new DataObject\Objectbrick\Data\Shopify($object);
                    $channelBrick->setShopifyId($id);
                    $object->getChannel()->setShopify($channelBrick);
                    $object->save();
                    // Adding a product Id in object brick
                    if (!file_exists('public/Shopify')) {
                        $filesystem = new Filesystem();
                        $filesystem->mkdir('Shopify/',0700);
                    }
                    file_put_contents('Shopify/'.$id.'.json', json_encode($response->toArray()));
                }
            }
    }
}