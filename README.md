<img src="https://img.shields.io/badge/Version-0.0.1-green"> <img src="https://img.shields.io/badge/License-MIT-success"> <img src="https://img.shields.io/badge/Adianti-7.x-blue"> <img src="https://img.shields.io/badge/PHP-7%20or%20higher-blueviolet">

## RecursiveDBTree plugin 

The RecursiveDBTree plugin is a versatile and efficient solution tailored for the Adianti Framework. It provides developers with a powerful toolset to manage hierarchical data structures seamlessly. This component facilitates intuitive tree navigation and manipulation within your web application.

## Key Features
- **Versatile:** Handles a wide range of hierarchical data structures.
- **Efficient:** Optimized for performance and resource utilization.
- **Intuitive Navigation:** Provides an intuitive interface for navigating through the tree structure.
- **Easy Manipulation:** Allows for easy manipulation of tree elements, including adding, editing, and deleting nodes.

## Based on VanillaTree Library
This plugin is built upon the foundation of the [VanillaTree](https://github.com/finom/vanillatree) JavaScript library. Leveraging the capabilities of VanillaTree, the RecursiveDBTree component enhances the Adianti Framework by empowering developers to implement dynamic and interactive tree views effortlessly.

## Installation
Run the following command:
`composer require gtcesar/recursive-db-tree`

## Usage Example - RecursiveDBTree
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
```php

use Gtcesar\RecursiveDBTree\RecursiveDBTree;

class SegmentForm extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // creates a panel
        $panel = new TPanelGroup('Segment');
       
        $segmentos = new RecursiveDBTree('segment', 'DATABASE', 'Segment', 'id', 'parent_segment_id', 'description', 'id asc');
        $segmentos->collapse();
        $segmentos->setItemAction(new TAction(array($this, 'onSelect')));
        
        $panel->add($segmentos);
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($panel);

        parent::add($vbox);
    }
    
    public function onSelect($param)
    {
        new TMessage('info', str_replace(',', '<br>', json_encode($param)));
    }    

}
```
## Result
<img src="https://github.com/gtcesar/recursive-db-tree/blob/main/images/RecursiveDBTree.png?raw=true">
