# Page options

Each page can have these options:

- `label`: the text of the menu item. Without it, `getLabel()` returns `Not defined`.
- `uri`: the link of the menu item.
- `route`: the route of the menu item. The navigation service uses it when there is no `uri`.
- `name`: an optional name. Error messages use it.
- `permission`: optional. If an authorization service is in the container, the page shows only when the user has this permission.
- `roles`: optional, used with `permission`. A string or a list of role names.

A page needs `uri` or `route`. Without one of them, `getHref()` throws `Dot\Navigation\Exception\RuntimeException`.

## The `route` option

| Key | Use |
| --- | --- |
| `route_name` | the name of the route. Required |
| `route_params` | the route parameters |
| `query_params` | the query parameters |
| `fragment_id` | the fragment of the URI |
| `options` | the router options |
| `ignore_params` | a list of request parameter names that the active check does not compare. Not used to make the URI |

See [Usage](usage.md) for the rule that makes a page active.
