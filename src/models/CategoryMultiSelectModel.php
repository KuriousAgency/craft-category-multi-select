<?php
/**
 * Category Multi Select plugin for Craft CMS 3.x
 *
 * Craft CMS category multi select field type 
 *
 * @link      https://kurious.agency
 * @copyright Copyright (c) 2018 Kurious Agency
 */

namespace kuriousagency\categorymultiselect\models;

use kuriousagency\categorymultiselect\CategoryMultiSelect;

use Craft;
use craft\base\Model;

/**
 * CategoryMultiSelectModel Model
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Kurious Agency
 * @package   CategoryMultiSelect
 * @since     1.0.0
 */
class CategoryMultiSelectModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some model attribute
     *
     * @var string
     */
    // public $someAttribute = 'Some Default';

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            // ['someAttribute', 'string'],
            // ['someAttribute', 'default', 'value' => 'Some Default'],
        ];
    }
}
