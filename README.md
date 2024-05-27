# RecursiveDBTree Component <img src="https://img.shields.io/badge/Versão-0.0.1-green"> <img src="https://img.shields.io/badge/Licença-MIT-success"> <img src="https://img.shields.io/badge/Adianti-7.x-blue"> <img src="https://img.shields.io/badge/PHP-7.x-blueviolet">

The RecursiveDBTree component is a versatile and efficient solution tailored for the Adianti Framework. It provides developers with a powerful toolset to manage hierarchical data structures seamlessly. This component facilitates intuitive tree navigation and manipulation within your web application.

## Key Features
- **Versatile:** Handles a wide range of hierarchical data structures.
- **Efficient:** Optimized for performance and resource utilization.
- **Intuitive Navigation:** Provides an intuitive interface for navigating through the tree structure.
- **Easy Manipulation:** Allows for easy manipulation of tree elements, including adding, editing, and deleting nodes.

## Based on VanillaTree Library
This component is built upon the foundation of the [VanillaTree](https://github.com/finom/vanillatree) JavaScript library. Leveraging the capabilities of VanillaTree, the RecursiveDBTree component enhances the Adianti Framework by empowering developers to implement dynamic and interactive tree views effortlessly.

## Installation
Run the following command:
`composer require gtcesar/recursive-db-tree`

## Usage Example - RecursiveDBTree
```sql
CREATE TABLE IF NOT EXISTS `segmento` (
  `id` int(11) NOT NULL,
  `segmento_id` int(11) DEFAULT NULL,
  `descricao` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `segmento` (`id`, `segmento_id`, `descricao`) VALUES
(1, NULL, 'Transporte'),
(2, 1, 'Executivo'),
(3, 1, 'Fracionado'),
(4, NULL, 'Informática'),
(5, 4, 'Software'),
(6, 4, 'Suporte e manutenção'),
(7, NULL, 'Varejo'),
(8, 7, 'Materiais de limpeza'),
(16, 8, 'Químicos'),
(17, 16, 'Controlados');

```
```php

use Gtcesar\RecursiveDBTree\RecursiveDBTree;

class SegmentoForm extends TPage
{
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // creates a panel
        $panel = new TPanelGroup('Segmentos');
       
        $segmentos = new RecursiveDBTree('segmento', 'DATABASE', 'Segmento', 'id', 'segmento_id', 'descricao', 'id asc');
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
## Resultado
<img src="https://github.com/gtcesar/recursive-db-tree/blob/main/images/RecursiveDBTree.png?raw=true">
