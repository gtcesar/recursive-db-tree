<?php

/**
 * RecursiveDBTree
 *
 * @version    v0.0.2
 * @autor      Augusto César da Costa Marques
 * @copyright  Copyright (c) 2021 Augusto César da Costa Marques
 * @license    MIT License
 */
namespace Gtcesar\RecursiveDBTree;

use Adianti\Widget\Base\TElement;
use Adianti\Widget\Base\TStyle;
use Adianti\Database\TCriteria;
use Adianti\Widget\Wrapper\AdiantiDatabaseWidgetTrait;

/**
 * This class represents a recursive database tree component.
 * It is designed to handle hierarchical data structures efficiently.
 * Developers can easily implement hierarchical data structures with intuitive tree navigation and manipulation capabilities.
 * This component is based on the VanillaTree JavaScript library.
 */
class RecursiveDBTree extends TElement
{
    /**
     * @var array $items The items of the tree
     */
    protected $treeItems;

    /**
     * @var string $tagName The HTML tag name of the main element
     */
    private $tagName;

    /**
     * @var mixed $itemAction The action to be executed when an item in the tree is selected
     */
    private $selectedItemAction;

    /**
     * @var bool $isCollapsed Indicates if the tree is collapsed (all nodes are closed)
     */
    private $isCollapsed;

    /**
     * @var string $keyField The table field to be used as a key in the tree
     */
    private $keyField;

    /**
     * @var string $labelField The table value to be used as label
     */
    private $labelField;

    /**
     * @var string $parentKeyField The table field used as a parent reference
     */
    private $parentKeyField;

    /**
     * @var array|null $contextMenuOptions Context menu options for the tree nodes
     */
    protected $contextMenuOptions;

    /**
     * Class Constructor
     *
     * @param string $tagName The HTML tag name of the main element
     * @param string $databaseName The database name
     * @param string $modelName The model class name
     * @param string $keyField The table field to be used as a key in the tree
     * @param string $parentKeyField The table field used as a parent reference
     * @param string $labelField The table value to be used as label
     * @param string|null $orderColumn The column to order the fields (optional)
     * @param TCriteria|null $criteria The criteria (TCriteria object) to filter the model (optional)
     */
    public function __construct($tagName, $databaseName, $modelName, $keyField, $parentKeyField, $labelField, $orderColumn = null, TCriteria $criteria = null)
    {
        parent::__construct('main');
        $this->id = 'tree_' . uniqid();
        $this->tagName = $tagName;
        $this->keyField = $keyField;
        $this->parentKeyField = $parentKeyField;
        $this->labelField = $labelField;
        $this->isCollapsed = false;
        $this->treeItems = AdiantiDatabaseWidgetTrait::getObjectsFromModel($databaseName, $modelName, $keyField, $orderColumn, $criteria);
    }

    /**
     * Collapse the Tree
     * This method sets the tree to be collapsed, i.e., all nodes are closed.
     */
    public function collapse()
    {
        $this->isCollapsed = true;
    }

    /**
     * Set item action
     *
     * @param mixed $action The item action
     * This method sets an action to be executed when an item in the tree is selected.
     * The action can be a URL or an instance of TAction.
     */
    public function setItemAction($action)
    {
        $this->selectedItemAction = $action;
    }

    /**
     * Add context menu option
     *
     * @param string $label The label of the context menu option
     * @param mixed $action The action to be executed when the context menu option is selected
     */
    public function addContextMenuOption($label, $action)
    {
        $this->contextMenuOptions[] = [$label, $action];
    }

    /**
     * Shows the plugin at the screen
     * This method renders the RecursiveDBTree component on the screen.
     * It generates the necessary HTML and JavaScript to display the tree.
     */
    public function show()
    {
        // Include the JavaScript library
        $include = new TElement('script');
        $include->src = 'vendor/gtcesar/recursive-db-tree/src/js/vanillatree.min.js';
        $include->type = 'text/javascript';
        parent::add($include);

        // Check if the tree is collapsed
        $collapsed = $this->isCollapsed ? 'true' : 'false';
        $const = $this->id;

        // Create the script element
        $script = new TElement('script');
        $script->type = 'text/javascript';

        // Create the main element to hold the tree
        $output = new TElement($this->tagName);
        parent::add($output);

        // Add the JavaScript code to initialize the tree
        $script->add(
            '
            $(document).ready(function(){ const ' .
                $this->tagName .
                '  = document.querySelector(' .
                "'{$this->tagName}'" .
                ');
            '
        );

        if ($this->contextMenuOptions) {
            $script->add(
                '
                const ' . $const . ' = new VanillaTree(' . $this->tagName . ', {
                    contextmenu: [
                '
            );
            $menuItems = [];
            foreach ($this->contextMenuOptions as $index => $option) {
                $menuLabel  = $option[0];
                $menuAction = $option[1]->serialize(FALSE);

                $menu = "{label: '{$menuLabel}',";
                $menu .= "action: function(id) { __adianti_ajax_exec('" . $menuAction . "&key=' + id); }}";

                $menuItems[] = $menu;
            }

            $script->add(implode(',', $menuItems));
            $script->add(']
                });');
        } else {
            $script->add(
                '
                const ' . $const . ' = new VanillaTree(' . $this->tagName . ');
            ');
        }

        // Iterate over the items and add them to the tree
        foreach ($this->treeItems as $item) {
            $key = $item->{$this->keyField};
            $label = $item->{$this->labelField};
            $parentKey = $item->{$this->parentKeyField};

            // Build the tree node
            $tree = "{$const}.add({label: '{$label}',";
            if ($parentKey != null) {
                $tree .= "parent: '{$parentKey}',";
            }
            $tree .= "id: '{$key}',";
            $tree .= "opened: {$collapsed}});";
                    // Add the tree node to the script
            $script->add($tree);
        }

        // Add action event listener to the tree
        if ($this->selectedItemAction) {
            $itemAction = $this->selectedItemAction->serialize(FALSE);

            $script->add(
                '
                ' . $this->tagName . '.addEventListener("vtree-select", function(evt) {
                    __adianti_ajax_exec("' . $itemAction . '&key=" + evt.detail.id);
                });
            ');
        }

        // Close the script tag
        $script->add('
            });
        ');

        // Add the script to the main element
        parent::add($script);

        // Import the CSS file for the tree
        TStyle::importFromFile('vendor/gtcesar/recursive-db-tree/src/css/vanillatree.min.css');

        // Render the main element
        parent::show();
    }
}

?>

