<?php

/**
 * RecursiveDBTree
 *
 * @version    v0.0.1
 * @author     Augusto César da Costa Marques
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
    protected $items;
    private $tagname;
    private $itemAction;
    private $collapsed;
    private $key;
    private $label;
    private $parentkey;

    /**
     * Class Constructor
     * @param string $tagname       The HTML tag name of the main element
     * @param string $database      The database name
     * @param string $model         The model class name
     * @param string $key           The table field to be used as a key in the tree
     * @param string $parentkey     The table field used as a parent reference
     * @param string $label         The table value to be used as label
     * @param string $ordercolumn   The column to order the fields (optional)
     * @param TCriteria $criteria   The criteria (TCriteria object) to filter the model (optional)
     */
    public function __construct($tagname, $database, $model, $key, $parentkey, $label, $ordercolumn = null, TCriteria $criteria = null)
    {
        parent::__construct('main');
        $this->id = 'tree_' . uniqid();
        $this->tagname = $tagname;
        $this->key = $key;
        $this->parentkey = $parentkey;
        $this->label = $label;
        $this->collapsed = false;
        $this->items = AdiantiDatabaseWidgetTrait::getObjectsFromModel($database, $model, $key, $ordercolumn, $criteria);
    }

    /**
     * Collapse the Tree
     * This method sets the tree to be collapsed, i.e., all nodes are closed.
     */
    public function collapse()
    {
        $this->collapsed = true;
    }

    /**
     * Set item action
     * @param mixed $action   The item action
     * This method sets an action to be executed when an item in the tree is selected.
     * The action can be a URL or an instance of TAction.
     */
    public function setItemAction($action)
    {
        $this->itemAction = $action;
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
        $collapsed = $this->collapsed ? 'true' : 'false';
        $constante = $this->id;

        // Create the script element
        $script = new TElement('script');
        $script->type = 'text/javascript';

        // Create the main element to hold the tree
        $output = new TElement($this->tagname);
        parent::add($output);

        // Add the JavaScript code to initialize the tree
        $script->add(
            '
            $(document).ready(function(){ const ' .
            $this->tagname .
            '  = document.querySelector(' .
            "'{$this->tagname}'" .
            ');
                const ' . $constante . ' = new VanillaTree(' .
            "{$this->tagname}" .
            ');
        '
        );

        // Iterate over the items and add them to the tree
        foreach ($this->items as $item) {

            $key = $item->{$this->key};
            $label = $item->{$this->label};
            $parentkey = $item->{$this->parentkey};

            // Build the tree node
            $tree = "{$constante}.add({label: '{$label}',";

            if ($parentkey != null) {
                $tree .= "parent: '{$parentkey}',";
            }
            $tree .= "id: '{$key}',";
            $tree .= "opened: {$collapsed}});";

            // Add the tree node to the script
            $script->add($tree);
        }

        // Add action event listener to the tree
        if ($this->itemAction) {
            $string_action = $this->itemAction->serialize(FALSE);
            $script->add('
            ' . $this->tagname . '.addEventListener("vtree-select", function(evt) {
                __adianti_ajax_exec("' . $string_action . '&key=" + evt.detail.id);
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