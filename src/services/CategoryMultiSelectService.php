<?php
/**
 * Category Multi Select plugin for Craft CMS 3.x
 *
 * Craft CMS category multi select field type 
 *
 * @link      https://kurious.agency
 * @copyright Copyright (c) 2018 Kurious Agency
 */

namespace kuriousagency\categorymultiselect\services;

use kuriousagency\categorymultiselect\CategoryMultiSelect;

use Craft;
use craft\base\Component;
use craft\elements\Category;

/**
 * CategoryMultiSelectService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Kurious Agency
 * @package   CategoryMultiSelect
 * @since     1.0.0
 */
class CategoryMultiSelectService extends Component
{
    // Public Methods
    // =========================================================================

    

    public function someFunction(): array
    {
        return true;
    }

}
