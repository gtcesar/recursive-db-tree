# RecursiveDBTree plugin 

[![Static Badge](https://img.shields.io/badge/Version-0.0.2-green)](https://github.com/gtcesar/recursive-db-tree)
[![Static Badge](https://img.shields.io/badge/License-MIT-success)](https://opensource.org/license/mit)
[![Adianti Framework](https://img.shields.io/badge/Adianti%20Framework-Adianti%20Solutions-blue.svg)](https://www.adianti.com.br/)
[![Static Badge](https://img.shields.io/packagist/php-v/rubix/ml.svg?style=flat&colorB=8892BF)](https://www.php.net/releases/7_4_0.php)
[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6QPL893GX55J2)

The RecursiveDBTree plugin is a versatile and efficient solution tailored for the Adianti Framework. It provides developers with a powerful toolset to manage hierarchical data structures seamlessly. This component facilitates intuitive tree navigation and manipulation within your web application.

## Key Features
- **Versatile:** Handles a wide range of hierarchical data structures.
- **Efficient:** Optimized for performance and resource utilization.
- **Intuitive Navigation:** Provides an intuitive interface for navigating through the tree structure.
- **Easy Manipulation:** Allows for easy manipulation of tree elements, including adding, editing, and deleting nodes.
- **Context Menu Options:** Offers context menu options for enhanced user interaction and management of tree nodes.

## Based on VanillaTree Library
> This plugin is built upon the foundation of the [VanillaTree](https://github.com/finom/vanillatree) JavaScript library. Leveraging the capabilities of VanillaTree, the RecursiveDBTree component enhances the Adianti Framework by empowering developers to implement dynamic and interactive tree views effortlessly.

## Adianti Framework Dependency
> This plugin is built specifically for Adianti Framework. Make sure your project is using Adianti for seamless integration and optimal performance.

## Installation
> Run the following command:
`composer require gtcesar/recursive-db-tree`

## Usage Example
### SQL
>Table definition for the segment table, including columns for id, parent_segment_id, and description.
Insert statements to add data to the segment table, populating it with some examples of segments and their relationships.

```sql
CREATE TABLE IF NOT EXISTS `segment` (
  `id` int(11) NOT NULL,
  `parent_segment_id` int(11) DEFAULT NULL,
  `description` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `segment` (`id`, `parent_segment_id`, `description`) VALUES
(1, NULL, 'Transportation'),
(2, 1, 'Executive'),
(3, 1, 'Fractional'),
(4, NULL, 'Information Technology'),
(5, 4, 'Software'),
(6, 4, 'Support and Maintenance'),
(7, NULL, 'Retail'),
(8, 7, 'Cleaning Supplies'),
(16, 8, 'Chemicals'),
(17, 16, 'Controlled');


```
### Model
> Create Model in app/model/Segment.php

```php

/**
 * Segment
 *
 * Represents a segment entity in the database.
 * Extends TRecord, the base class for database records in Adianti Framework.
 */
class Segment extends TRecord
{
    const TABLENAME = 'segment'; // Name of the database table
    const PRIMARYKEY = 'id'; // Primary key field name
    const IDPOLICY = 'serial'; // ID generation policy (max, serial)

    /**
     * Constructor method
     *
     * @param int|null $id The ID of the segment (optional)
     * @param bool $callObjectLoad Whether to call the parent's load method (default: TRUE)
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        // Call the parent constructor to initialize the object
        parent::__construct($id, $callObjectLoad);
        
        // Add attributes to the record
        parent::addAttribute('parent_segment_id'); // Parent segment ID
        parent::addAttribute('description'); // Segment description
    }
}

```

### Controller
> Create Controller in app/controller/SegmentTree.php

```php

use Gtcesar\RecursiveDBTree\RecursiveDBTree;

/**
 * SegmentTree
 *
 * This class represents a segment tree page.
 * It displays a hierarchical structure of segments using RecursiveDBTree component.
 * Users can interact with the segments by selecting, editing, deleting, or viewing them.
 */
class SegmentTree extends TPage
{
    /**
     * Class constructor
     * Creates the page and initializes its components
     */
    function __construct()
    {
        parent::__construct();
        
        // Create a panel
        $panel = new TPanelGroup('Segment');
       
        // Create a RecursiveDBTree instance to display segments
        $segment = new RecursiveDBTree('segment', 'app', 'Segment', 'id', 'parent_segment_id', 'description', 'id asc');
        $segment->collapse();
        
        // Set an action when selecting an item
        $segment->setItemAction(new TAction(array($this, 'onSelect')));
        
        // Add options to the context menu
        $segment->addContextMenuOption('Edit', new TAction([$this, 'onEdit']));
        $segment->addContextMenuOption('Delete', new TAction([$this, 'onDelete']));
        $segment->addContextMenuOption('View', new TAction([$this, 'onView']));
        
        $panel->add($segment);
        
        // Wrap the page content using a vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($panel);

        parent::add($vbox);
    }
    
    /**
     * Handle item selection
     * Displays information about the selected item
     */
    public function onSelect($param)
    {
        new TMessage('info', str_replace(',', '<br>', json_encode($param)));
    }    
    
    /**
     * Handle item editing
     * Displays information about the item being edited
     */
    public function onEdit($param)
    {
        new TMessage('info', str_replace(',', '<br>', json_encode($param)));
    }    

    /**
     * Handle item deletion
     * Displays information about the item being deleted
     */
    public function onDelete($param)
    {
        new TMessage('info', str_replace(',', '<br>', json_encode($param)));
    }    

    /**
     * Handle item viewing
     * Displays information about the item being viewed
     */
    public function onView($param)
    {
        new TMessage('info', str_replace(',', '<br>', json_encode($param)));
    }  
}

```

## Result
> The result of the example
<img src="https://github.com/gtcesar/recursive-db-tree/blob/main/images/img1.png?raw=true">
<img src="https://github.com/gtcesar/recursive-db-tree/blob/main/images/img2.png?raw=true">

## License

**MIT**

> Free Software, Hell Yeah!

## Get me a cup of coffee
>Ah, coffee! One of my passions that transcends the digital world. That unmistakable aroma, the rich flavor... it's as if each sip brings a new surge of energy. Coffee isn't just a beverage to me, it's an indispensable work companion. From foggy mornings to late-night bursts of inspiration, it's always by my side, ready to rejuvenate me and keep my mind sharp.

>It's amazing how a simple cup of coffee can work wonders for my performance. It helps me stay focused, concentrated, and creative, enabling me to tackle the challenges of the day with more vigor and determination. Without it, I admit I wouldn't be the same!

>So, yes, a cup of coffee is more than welcome here. It's the key to unlocking my full potential and ensuring that I can continue to offer the best of myself in every interaction.

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6QPL893GX55J2)
