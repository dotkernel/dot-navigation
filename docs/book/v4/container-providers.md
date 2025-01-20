# Container providers

Each menu can be created from different sources. The responsibility of creating a menu container from the source falls on a container provider.

Each provider must implement the interface `ProviderInterface` and be registered in the ProviderPluginManager.

We offer just one provider for now, `ArrayProvider`, that is able to fetch and create a menu container from a php array that is defined in the configuration file.
