# Usage

Cloner adds a `+` action to supported settings indexes in the control panel. You must be signed in as an administrator, and Craft's `allowAdminChanges` setting must be enabled. The action is hidden in environments where administrative changes are disabled.

## Supported Configuration

Cloner supports:

- Asset Transforms
- Category Groups
- Entry Types
- Filesystems
- Global Sets
- Sections
- Sites
- Tag Groups
- User Groups
- Volumes

## Clone Configuration

Open the relevant settings index, select the `+` action beside the configuration you want to copy, and enter a distinct name. Cloner generates a handle from that name and saves the new configuration through Craft's project config. Open the copy afterwards to review the generated handle and any values that must be unique to its destination.

For example, when a new content area needs the same category fields and site settings as an existing taxonomy, open **Settings → Categories**, select the `+` action beside the source group, and name the copy. The resulting category group has its own identity and field layout but contains no categories.

If you only need another entry type and your Craft installation provides **Save as a new entry type**, you can use Craft's native action instead. Cloner remains useful when you are working from the settings indexes or cloning one of the other supported configuration types.

## What Gets Copied

Cloner copies configuration, not content. Category and tag groups do not include their categories or tags, global sets do not include their content values, volumes do not duplicate assets, and user groups do not assign any users. A cloned user group does retain the source group's permissions.

Sections retain their configured entry types by reference. Cloner does not create separate copies of those entry types. Channel and structure sections keep their per-site URI formats; a cloned Single receives a distinct URI so it does not conflict with the source.

Sites retain raw environment-aware language, base URL, and enabled values, but a cloned site is never made primary. Volumes keep their filesystem references and receive distinct top-level asset and transform paths based on the new handle. A volume that occupies an entire filesystem cannot be cloned safely onto that same filesystem, so Craft reports a validation error instead of creating overlapping asset indexes.

Review copied filesystem and volume settings before indexing assets, and review site URLs before enabling a new site in a public environment.
