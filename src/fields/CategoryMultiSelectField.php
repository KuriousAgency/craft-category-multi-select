<?php
/**
 * Category Multi Select plugin for Craft CMS 3.x
 *
 * Craft CMS category multi select field type 
 *
 * @link      https://kurious.agency
 * @copyright Copyright (c) 2018 Kurious Agency
 */

namespace kuriousagency\categorymultiselect\fields;

use kuriousagency\categorymultiselect\CategoryMultiSelect;
use kuriousagency\categorymultiselect\assetbundles\categorymultiselectfieldfield\CategoryMultiSelectFieldFieldAsset;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\Db;
use yii\db\Schema;
use craft\helpers\Json;

use craft\fields\BaseRelationField;
use craft\elements\Category;
use craft\helpers\ArrayHelper;
use craft\helpers\ElementHelper;

/**
 * CategoryMultiSelectField Field
 *
 * Whenever someone creates a new field in Craft, they must specify what
 * type of field it is. The system comes with a handful of field types baked in,
 * and we’ve made it extremely easy for plugins to add new ones.
 *
 * https://craftcms.com/docs/plugins/field-types
 *
 * @author    Kurious Agency
 * @package   CategoryMultiSelect
 * @since     1.0.0
 */
class CategoryMultiSelectField extends BaseRelationField
{
    
    public $someAttribute;
    
    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('app', 'Category Multi Select');
    }

    /**
     * @inheritdoc
     */
    protected static function elementType(): string
    {
        return Category::class;
    }

    /**
     * @inheritdoc
     */
    public static function defaultSelectionLabel(): string
    {
        return Craft::t('app', 'Add a category');
    }

    // Properties
    // =========================================================================

    /**
     * @var int|null Branch limit
     */
    public $branchLimit;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->allowLimit = false;
        $this->allowMultipleSources = false;
        $this->settingsTemplate = 'category-multi-select/_components/fields/CategoryMultiSelectField_settings';
        $this->inputTemplate = 'category-multi-select/_components/fields/CategoryMultiSelectField_input';
        // $this->inputJsClass = 'Craft.CategorySelectInput';
        $this->sortable = false;
    }

    /**
     * @inheritdoc
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
		// Craft::dd($value);

		if (is_array($value)) {
            /** @var Category[] $categories */
            $categories = Category::find()
                ->siteId($this->targetSiteId($element))
                ->id(array_values(array_filter($value)))
                ->anyStatus()
                ->all();

            // Fill in any gaps
            $categoriesService = Craft::$app->getCategories();
            $categoriesService->fillGapsInCategories($categories);

            // Enforce the branch limit
            if ($this->branchLimit) {
                $categoriesService->applyBranchLimitToCategories($categories, $this->branchLimit);
            }

            $value = ArrayHelper::getColumn($categories, 'id');
        }

        return parent::normalizeValue($value, $element);
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        // Make sure the field is set to a valid category group
        if ($this->source) {
            $source = ElementHelper::findSource(static::elementType(), $this->source, 'field');
        }

        if (empty($source)) {
            return '<p class="error">' . Craft::t('app', 'This field is not set to a valid category group.') . '</p>';
        }

        return parent::getInputHtml($value, $element);
    }

    public function getCategoriesByGroup()
    {

        $groupParts = explode(":",$this->source);

		$query = Category::find();
		$query->groupId($groupParts[1]);

        return $query->all();

    }

	public function getCategoryStructure()
	{

		$categoriesArray = [];
		$parentId = 0;
		$level = 1;
		
		$categories = $this->getCategoriesByGroup();
		
		foreach($categories as $category) {
			
			if($category->level == 1) {
				$parentId = $category->id;
				if($category->hasDescendants) {
					$categoriesArray[$parentId] = [
						'title' => $category->title,
						'children' => [],
					];
				}
			} elseif($category->level == 2 && $parentId > 0) {
				$categoriesArray[$parentId]['children'][$category->id] = $category->title;
            }
            
            if($category->level > $level)

            $level = $category->level > $level ? $category->level : $level;

		}

		// Craft::dd($categoriesArray);

        return [
            'categoriesArray' => $categoriesArray,
            'maxLevel' => $level,
        ];

    }

    /**
     * @inheritdoc
     */
    protected function inputTemplateVariables($value = null, ElementInterface $element = null): array
    {
        $variables = parent::inputTemplateVariables($value, $element);
        $variables['branchLimit'] = $this->branchLimit;
        $variables['categories'] = $this->getCategoryStructure()['categoriesArray'];
        $variables['maxLevel'] = $this->getCategoryStructure()['maxLevel'];

        return $variables;
    }
}

