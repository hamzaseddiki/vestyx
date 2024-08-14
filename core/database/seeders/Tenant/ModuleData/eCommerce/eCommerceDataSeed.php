<?php

namespace Database\Seeders\Tenant\ModuleData\eCommerce;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\DeliveryOption;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\SubCategory;
use Modules\Badge\Entities\Badge;
use Modules\Campaign\Entities\Campaign;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductChildCategory;
use Modules\Product\Entities\ProductShippingReturnPolicy;
use Modules\Product\Entities\ProductSubCategory;

class eCommerceDataSeed
{
    public static function execute()
    {
        if(!Schema::hasTable('statuses')){
            Schema::create('statuses', function (Blueprint $table) {
                $table->id();
                $table->string("name");
                $table->softDeletes();
            });
        }

        self::seedStatus();
        self::seedCategories();
        self::seedSubCategories();
        self::seedChildCategories();
        self::seedColors();
        self::seedSize();
        self::seedTags();
        self::seedUnit();
        self::seedCountries();
        self::seedStates();
        self::seedBadge();
        self::seedProduct();
        self::seedDeliveryOption();
        self::seedProductCategory();
        self::seedProductSubCategories();
        self::seedProductChildCategories();
        self::seedProductTags();
        self::seedProductGalleries();
        self::seedProductInventories();
        self::seedProductInventoryDetails();
        self::seedProductUOM();
        self::seedProductCreatedBy();
        self::seedProductDeliveryOption();
        self::seedProductReturnPolicies();
        self::seedCampaign();
        self::seedCampaignProducts();

        if(!Schema::hasTable('product_reviews'))
        {
            Schema::create('product_reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->integer('rating')->nullable();
                $table->longText('review_text')->nullable();
                $table->timestamps();

                $table->foreign("product_id")->references("id")->on("products")->cascadeOnDelete();
                $table->foreign("user_id")->references("id")->on("users")->cascadeOnDelete();
            });
        }

    }

    private static function seedStatus()
    {
        DB::statement("INSERT INTO `statuses` (`id`, `name`, `deleted_at`) VALUES (1,'active',NULL), (2,'inactive',NULL)");
    }

    private static function seedCategories()
    {
        $object = new JsonDataModifier('product','product-category');
        $data = $object->getColumnData([
            'id',
            'name',
            'slug',
            'status_id',
            'image_id',
            'description',
        ]);

        Category::insert($data);
    }

    private static function seedSubCategories()
    {
        $object = new JsonDataModifier('product','product-subcategory');
        $data = $object->getColumnData([
            'id',
            'name',
            'description',
            'slug',
            'status_id',
            'image_id',
            'category_id',
        ]);

        SubCategory::insert($data);
    }

    private static function seedChildCategories()
    {

        $object = new JsonDataModifier('product','product-childcategory');
        $data = $object->getColumnData([
            'id',
            'name',
            'category_id',
            'sub_category_id',
            'slug',
            'description',
            'status_id',
            'image_id',
        ]);

        ChildCategory::insert($data);
    }

    private static function seedColors()
    {

        $object = new JsonDataModifier('product','colors');
        $data = $object->getColumnData([
            'name',
            'slug',
            'color_code',

        ]);

        Color::insert($data);
    }

    private static function seedSize()
    {
        $object = new JsonDataModifier('product','sizes');
        $data = $object->getColumnData([
            'name',
            'slug',
            'size_code',

        ]);

        Size::insert($data);
    }

    private static function seedTags()
    {
        DB::statement("INSERT INTO `tags` (`id`, `tag_text`, `created_at`, `updated_at`, `deleted_at`)
    VALUES
        (1,'{\"en_US\":\"saree\",\"ar\":\"\\\u0644\\\u0648\\\u0646 \\\u062a\\\u064a \\\u0634\\\u064a\\\u0631\\\u062a\"}','2022-12-19 07:06:58','2023-01-03 14:00:11',NULL),
        (2,'{\"en_US\":\"kameez\",\"ar\":\"\\\u0644\\\u0648\\\u0646 \\\u062a\\\u064a \\\u0634\\\u064a\\\u0631\\\u062a\"}','2022-12-19 07:07:04','2023-01-03 14:00:08',NULL),
        (3,'{\"en_US\":\"casual t shirt\",\"ar\":\"\\\u0644\\\u0648\\\u0646 \\\u062a\\\u064a \\\u0634\\\u064a\\\u0631\\\u062a\"}','2022-12-19 07:07:09','2023-01-03 14:00:04',NULL),
        (4,'{\"en_US\":\"sun glasses\",\"ar\":\"\\\u0644\\\u0648\\\u0646 \\\u062a\\\u064a \\\u0634\\\u064a\\\u0631\\\u062a\"}','2022-12-19 07:07:15','2023-01-03 14:00:01',NULL),
        (5,'{\"en_US\":\"best dress for kid\",\"ar\":\"\\\u0644\\\u0648\\\u0646 \\\u062a\\\u064a \\\u0634\\\u064a\\\u0631\\\u062a\"}','2022-12-19 07:07:20','2023-01-03 13:59:58',NULL),
        (6,'{\"en_US\":\"denim shirt\",\"ar\":\"\\\u0644\\\u0648\\\u0646 \\\u062a\\\u064a \\\u0634\\\u064a\\\u0631\\\u062a\"}','2022-12-19 07:07:26','2023-01-03 13:59:55',NULL),
        (7,'{\"en_US\":\"stylish hat\",\"ar\":\"\\\u0644\\\u0648\\\u0646 \\\u062a\\\u064a \\\u0634\\\u064a\\\u0631\\\u062a\"}','2022-12-19 07:07:31','2023-01-03 13:59:53',NULL),
        (8,'{\"en_US\":\"color t shirt\",\"ar\":\"\\\u0644\\\u0648\\\u0646 \\\u062a\\\u064a \\\u0634\\\u064a\\\u0631\\\u062a\"}','2022-12-19 07:07:43','2023-01-03 13:59:49',NULL)");
    }

    private static function seedUnit()
    {
        DB::statement("INSERT INTO `units` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`)
    VALUES
        (1,'Pair','2022-12-19 07:07:57','2022-12-19 07:07:57',NULL),
        (2,'Piece','2022-12-19 07:08:01','2022-12-19 07:08:01',NULL),
        (3,'g','2022-12-19 07:08:07','2022-12-19 07:08:07',NULL),
        (4,'Ltr','2022-12-19 07:08:12','2022-12-19 07:08:12',NULL),
        (5,'Dozen','2022-12-19 07:08:17','2022-12-19 07:08:17',NULL),
        (6,'Lb','2022-12-19 07:08:22','2022-12-19 07:08:22',NULL),
        (7,'Kg','2022-12-19 07:08:26','2022-12-19 07:08:26',NULL)");
    }

    private static function seedCountries()
    {
        DB::statement("INSERT INTO `countries` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
        (1, 'Bangladesh', 'publish', '2022-08-22 06:35:32', '2022-08-22 06:35:32'),
        (2, 'USA', 'publish', '2022-08-22 06:35:38', '2022-08-22 06:35:38'),
        (3, 'Turkey', 'publish', '2022-08-22 06:35:43', '2022-08-22 06:35:43'),
        (4, 'Russia', 'publish', '2022-08-22 06:35:48', '2022-08-22 06:35:48'),
        (5, 'China', 'publish', '2022-08-22 06:35:52', '2022-08-22 06:35:52'),
        (6, 'England', 'publish', '2022-08-22 06:35:59', '2022-08-22 06:35:59'),
        (7, 'Saudi Arabia', 'publish', '2022-08-22 06:41:29', '2022-08-22 06:41:29')");
    }

    private static function seedStates()
    {
        DB::statement("INSERT INTO `states` (`id`, `name`, `country_id`, `status`, `created_at`, `updated_at`) VALUES
        (1, 'Dhaka', 1, 'publish', '2022-08-22 06:36:11', '2022-08-22 06:36:11'),
        (2, 'Chandpur', 1, 'publish', '2022-08-22 06:36:15', '2022-08-22 06:36:15'),
        (3, 'Noakhali', 1, 'publish', '2022-08-22 06:36:21', '2022-08-22 06:36:21'),
        (4, 'Bhola', 1, 'publish', '2022-08-22 06:36:27', '2022-08-22 06:36:27'),
        (5, 'Barishal', 1, 'publish', '2022-08-22 06:36:32', '2022-08-22 06:36:32'),
        (6, 'Nework', 2, 'publish', '2022-08-22 06:36:43', '2022-08-22 06:36:43'),
        (7, 'Chicago', 2, 'publish', '2022-08-22 06:36:54', '2022-08-22 06:36:54'),
        (8, 'Las Vegas', 2, 'publish', '2022-08-22 06:37:05', '2022-08-22 06:37:05'),
        (9, 'Ankara', 3, 'publish', '2022-08-22 06:37:12', '2022-08-22 06:37:12'),
        (10, 'Istanbul', 3, 'publish', '2022-08-22 06:37:19', '2022-08-22 06:37:19'),
        (11, 'Izmir', 3, 'publish', '2022-08-22 06:37:26', '2022-08-22 06:37:26'),
        (12, 'Moscow', 4, 'publish', '2022-08-22 06:37:34', '2022-08-22 06:37:34'),
        (13, 'Lalingard', 4, 'publish', '2022-08-22 06:37:44', '2022-08-22 06:37:44'),
        (14, 'Siberia', 4, 'publish', '2022-08-22 06:37:55', '2022-08-22 06:37:55'),
        (15, 'Shanghai', 5, 'publish', '2022-08-22 06:38:04', '2022-08-22 06:38:04'),
        (16, 'Anuhai', 5, 'publish', '2022-08-22 06:38:13', '2022-08-22 06:38:13'),
        (17, 'Hong Kong', 5, 'publish', '2022-08-22 06:38:29', '2022-08-22 06:38:29'),
        (18, 'London', 6, 'publish', '2022-08-22 06:38:37', '2022-08-22 06:38:37'),
        (19, 'Madina', 7, 'publish', '2022-08-22 06:41:44', '2022-08-22 06:41:44')");
    }

    private static function seedDeliveryOption()
    {
        $object = new JsonDataModifier('product','delivery-option');
        $data = $object->getColumnData([
            'title',
            'sub_title',
            'icon',
        ]);

        DeliveryOption::insert($data);
    }

    private static function seedBadge()
    {
        $object = new JsonDataModifier('product','badge');
        $data = $object->getColumnData([
            'name',
            'image',
            'for',
            'sale_count',
            'status',
            'type',
        ]);

        Badge::insert($data);

    }

    private static function seedProduct()
    {
        $object = new JsonDataModifier('product','product');
        $data = $object->getColumnData([
            'id',
            'name',
            'summary',
            'description',
            'slug',
            'image_id',
            'price',
            'sale_price',
            'cost',
            'badge_id',
            'brand_id',
            'status_id',
            'product_type',
            'sold_count',
            'min_purchase',
            'max_purchase',
            'is_refundable',
            'is_in_house',
            'is_inventory_warn_able',
        ]);

        Product::insert($data);
    }

    private static function seedProductCategory()
    {
        DB::statement("INSERT INTO `product_categories` (`id`, `product_id`, `category_id`)
VALUES
	(1,1,3),
	(5,7,6),
	(6,8,5),
	(7,9,8),
	(8,10,5),
	(9,11,5),
	(10,12,5),
	(11,13,5),
	(15,17,5),
	(16,18,10),
	(17,19,10),
	(18,20,10),
	(19,21,10)");
    }


    private static function seedProductSubCategories()
    {
        DB::statement("INSERT INTO `product_sub_categories` (`id`, `product_id`, `sub_category_id`)
VALUES
	(1,1,1),
	(5,7,4),
	(6,8,6),
	(7,9,9),
	(8,10,6),
	(9,11,6),
	(10,12,6),
	(11,13,6),
	(15,17,6),
	(16,18,11),
	(17,19,11),
	(18,20,11),
	(19,21,11)");
    }

    private static function seedProductChildCategories()
    {
        DB::statement("INSERT INTO `product_child_categories` (`id`, `product_id`, `child_category_id`)
VALUES
	(68,1,2),
	(76,13,3),
	(77,12,3),
	(78,11,3),
	(79,10,3),
	(81,7,4),
	(82,9,5),
	(83,8,3),
	(84,17,3),
	(101,21,7),
	(102,20,7),
	(103,19,7),
	(104,18,7)");
    }


    private static function seedProductTags()
    {
        DB::statement("INSERT INTO `product_tags` (`tag_name`, `product_id`)
        VALUES
            ('shirt',1),
            ('tshirt',1),
            ('shirt',13),
            ('tshirt',13),
            ('shirt',12),
            ('tshirt',12),
            ('shirt',11),
            ('tshirt',11),
            ('shirt',10),
            ('tshirt',10),
            ('shirt',7),
            ('tshirt',7),
            ('shirt',9),
            ('tshirt',9),
            ('shirt',8),
            ('tshirt',8),
            ('shirt',17),
            ('tshirt',17),
            ('shirt',18),
            ('tshirt',19),
            ('shirt',20),
            ('tshirt',21)");
    }

    private static function seedProductGalleries()
    {
        DB::statement("INSERT INTO `product_galleries` (`id`, `product_id`, `image_id`)
        VALUES
            (114,1,282),
            (115,1,280),
            (116,1,279),
            (133,13,294),
            (134,13,293),
            (135,12,298),
            (136,12,297),
            (137,12,295),
            (138,11,302),
            (139,11,301),
            (140,11,300),
            (141,10,305),
            (142,10,304),
            (143,10,303),
            (147,7,286),
            (148,7,285),
            (149,7,284),
            (150,9,309),
            (151,9,307),
            (152,9,306),
            (153,8,267),
            (154,8,255),
            (155,17,290),
            (156,17,289),
            (157,17,288)");
    }

    private static function seedProductInventories()
    {
        DB::statement("INSERT INTO `product_inventories` (`id`, `product_id`, `sku`, `stock_count`, `sold_count`)
VALUES
	(1,1,'shirt-9999',98,2),
	(5,7,'dress-9999-1-1',96,4),
	(6,8,'dress-9999-1-1-1',160,3),
	(7,9,'headphone',89,11),
	(8,10,'bag-20',88,12),
	(9,11,'indian-loger',153,7),
	(10,12,'indian-loger-1',159,1),
	(11,13,'indian-loger-1-1',115,5),
	(15,17,'indian-loger-1-1-1-1-1-1',115,NULL),
	(16,18,'wash-1202',180,NULL),
	(17,19,'wash-1202-1',150,NULL),
	(18,20,'wash-1202-1-1',200,NULL),
	(19,21,'wash-1202-1-1-1fhsad',100,NULL)");
    }


    private static function seedProductInventoryDetails()
    {
        DB::statement("INSERT INTO `product_inventory_details` (`id`, `product_inventory_id`, `product_id`, `color`, `size`, `hash`, `additional_price`, `add_cost`, `image`, `stock_count`, `sold_count`)
VALUES
	(13,6,8,'1','3','',5.00,3.00,NULL,80,0),
	(14,6,8,'3','4','',10.00,5.00,NULL,80,0)");
    }


    private static function seedProductUOM(): void
    {
        DB::statement("INSERT INTO `product_uom` (`id`, `product_id`, `unit_id`, `quantity`)
        VALUES
            (1,1,6,2.00),
            (3,7,6,2.00),
            (4,8,6,2.00),
            (5,9,2,1.00),
            (6,10,2,1.00),
            (7,11,6,2.00),
            (8,12,6,2.00),
            (9,13,6,2.00),
            (13,17,6,2.00),
            (14,18,2,1.00),
            (15,19,6,2.00),
            (16,20,6,2.00),
            (17,21,6,2.00)");
    }

    private static function seedProductCreatedBy()
    {
        DB::statement("INSERT INTO `product_created_by` (`id`, `product_id`, `created_by_id`, `guard_name`, `updated_by`, `updated_by_guard`, `deleted_by`, `deleted_by_guard`)
        VALUES
            (1,1,1,'admin',1,'admin',NULL,NULL),
            (7,7,1,'admin',1,'admin',NULL,NULL),
            (8,8,1,'admin',1,'admin',NULL,NULL),
            (9,9,1,'admin',1,'admin',NULL,NULL),
            (10,10,1,'admin',1,'admin',NULL,NULL),
            (11,11,1,'admin',1,'admin',NULL,NULL),
            (12,12,1,'admin',1,'admin',NULL,NULL),
            (13,13,1,'admin',1,'admin',NULL,NULL),
            (14,17,1,'admin',1,'admin',NULL,NULL),
            (15,18,1,'admin',1,'admin',NULL,NULL),
            (16,19,1,'admin',1,'admin',NULL,NULL),
            (17,20,1,'admin',1,'admin',NULL,NULL),
            (18,21,1,'admin',1,'admin',NULL,NULL)");
    }

    private static function seedProductDeliveryOption()
    {
        DB::statement("INSERT INTO `product_delivery_options` (`id`, `product_id`, `delivery_option_id`)
        VALUES
            (105,1,1),
            (119,13,1),
            (120,13,2),
            (121,12,1),
            (122,12,2),
            (123,11,1),
            (124,11,2),
            (125,10,1),
            (127,7,1),
            (128,9,1),
            (129,8,1),
            (130,8,2),
            (131,17,1),
            (132,17,2)");
    }


    private static function seedProductReturnPolicies()
    {
        $object = new JsonDataModifier('product','return-policy');
        $data = $object->getColumnData([
            'id',
            'shipping_return_description',
            'product_id',
        ]);

        ProductShippingReturnPolicy::insert($data);
    }

    private static function seedCampaign()
    {
        $object = new JsonDataModifier('product','campaign');
        $data = $object->getColumnData([
            'title',
            'subtitle',
            'image',
            'start_date',
            'end_date',
            'status',
            'vendor_id',
            'admin_id',
            'type',
        ]);

        Campaign::insert($data);
    }

    private static function seedCampaignProducts()
    {
        DB::statement("INSERT INTO `campaign_products` (`id`, `product_id`, `campaign_id`, `campaign_price`, `units_for_sale`, `start_date`, `end_date`, `created_at`, `updated_at`)
        VALUES
            (19,7,1,40.00,30,'2022-12-19 00:00:00','2022-12-31 00:00:00',NULL,NULL),
            (20,8,1,25.00,30,'2022-12-19 00:00:00','2022-12-31 00:00:00',NULL,NULL),
            (21,9,1,120.00,30,'2022-12-19 00:00:00','2022-12-31 00:00:00',NULL,NULL),
            (22,10,1,130.00,30,'2022-12-19 00:00:00','2022-12-31 00:00:00',NULL,NULL)");
    }



}
