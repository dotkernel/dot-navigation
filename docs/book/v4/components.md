# Components

A menu, or navigation container, is a class implementing the \RecursiveIterator interface. It has a hierarchical structure, with nodes called pages(see the `Page` class) that may have children. It is basically a tree

A Page extends the NavigationContainer class. The NavigationContainer is the top most node which represents the entire menu. The children of this node are Page instances that defines each navigation item.

A page has a reference to its parent, and can have options and attributes. There are no limitation on what is accepted as options or attributes.

Options can be any piece of information that describes a page. Some predefined options exists, in order for the navigation module to work seamlessly with other dot modules.

Attributes are key value pairs that defines the menu item. They are usually inserted as html attributes when parsing the menu, but of course, this is implementation specific.

A `NavigationService` class, is the service that handles all defined menu container. It can fetch the container from its provider, check if a page is active or not and get the page's generated URI.
