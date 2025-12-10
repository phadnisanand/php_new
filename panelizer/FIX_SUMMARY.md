# Fix Summary: Block UUID Preservation in Layout Builder Migration

## Problem

When migrating from Panelizer to Layout Builder, blocks were getting different IDs when inserting the same node in different language translations. This caused layout inconsistencies and translation issues.

## Root Cause

In `src/LayoutBuilderMigration.php`, the `toSection()` method was processing blocks to convert them from Panels format to Layout Builder format. However, it was:

1. Removing the `uuid` from the block configuration
2. Passing the block array to `SectionComponent::fromArray()` WITHOUT preserving the original UUID
3. This caused `SectionComponent::fromArray()` to generate a NEW UUID each time the block was processed
4. When the same node was accessed in different languages, each language variant would get new UUIDs for the same blocks

## Solution

Modified the `toSection()` method in `src/LayoutBuilderMigration.php` to:

1. **Preserve the original UUID** before removing it from the configuration array
2. **Restore the UUID** after cleaning up the configuration
3. **Pass the preserved UUID** to `SectionComponent::fromArray()` to ensure block consistency across all language variants

### Code Changes

**File:** `src/LayoutBuilderMigration.php` (lines 233-255)

**Before:**

```php
$block['configuration'] = $block;
// Remove keys which are not actually part of the block configuration.
unset(
  $block['configuration']['provider'],
  $block['configuration']['region'],
  $block['configuration']['uuid'],
  $block['configuration']['weight']
);
$block += [
  'additional' => [],
];
return SectionComponent::fromArray($block);
```

**After:**

```php
// Preserve the original UUID to maintain consistency across languages.
// Each block in the Panels display has a unique UUID. When migrating to
// Layout Builder, we must preserve this UUID so that blocks maintain
// consistent identifiers across all translations of a node.
// Without this preservation, SectionComponent::fromArray() would generate
// new UUIDs each time, causing blocks in different language variants to
// have different IDs, which leads to issues with layout consistency.
$uuid = $block['uuid'];
$block['configuration'] = $block;
// Remove keys which are not actually part of the block configuration.
unset(
  $block['configuration']['provider'],
  $block['configuration']['region'],
  $block['configuration']['uuid'],
  $block['configuration']['weight']
);
$block += [
  'additional' => [],
];
// Restore the UUID to ensure it remains constant in all translations.
$block['uuid'] = $uuid;
return SectionComponent::fromArray($block);
```

## Impact

- **Fixes:** Block ID inconsistencies across language translations
- **Improves:** Layout Builder migration reliability for multilingual sites
- **Ensures:** Consistent block identifiers across all language variants of a node
- **Maintains:** Backward compatibility - no API changes

## Additional Enhancements

### 1. Block Content Entity Validation (Lines 219-239)

Added validation for block_content references during migration to ensure non-translatable blocks maintain consistency across language variants:

- Validates that referenced block_content entities exist
- Logs warnings for missing blocks without breaking the migration
- Ensures block references are preserved correctly for multilingual content

### 2. Enhanced Documentation (Lines 145-161)

Improved docblock for `doProcessEntity()` method to clarify:

- How Layout Builder sections are language-aware
- How non-translatable blocks are handled across language variants
- The correct behavior for block references in multilingual nodes

### 3. Exception Documentation (Lines 197-200)

Updated `toSection()` method docblock to document potential exceptions that may be thrown during migration.

### 4. URL Alias Preservation (Lines 145-228)

Added preservation and restoration of URL aliases during entity migration:

- **Before save:** Extracts all URL aliases for the entity in all languages
- **After save:** Verifies aliases still exist, restores them if removed
- **Multilingual support:** Handles aliases for each language variant separately
- **Module awareness:** Only processes aliases if path_alias module is enabled
- **Graceful degradation:** Continues migration even if alias restoration fails

This prevents URL aliases from being removed during the Panelizer to Layout Builder migration, maintaining existing SEO and user-facing URL structures.

## Testing Recommendations

1. Test migration of panelized nodes with URL aliases
2. Verify URL aliases are preserved for all language variants
3. Test non-translatable block_content entities in multilingual nodes
4. Verify missing block references are logged but don't break migration
5. Test that blocks render correctly in all languages after migration
6. Confirm entity URLs remain accessible after migration
7. Run the existing LayoutBuilderMigrationTest suite to ensure no regressions

## Language and Translation Handling

### Current Behavior

- Each language variant of a node maintains its own Layout Builder sections
- Non-translatable block references are preserved as-is across all language variants
- Block UUIDs remain consistent due to the UUID preservation fix
- Missing block references are logged as warnings but don't interrupt migration
- URL aliases are preserved for all language variants during migration

### Best Practices

1. Ensure all referenced block_content entities exist before migration
2. Backup existing URL aliases before running migration (as a precaution)
3. Test translations after migration to verify block rendering
4. Check the Drupal logs for migration warnings about missing blocks
5. For non-translatable blocks, consider the language context of content displayed
6. Verify that entity URLs remain accessible after migration completes
7. If URLs change, set up redirects to preserve SEO value and user experience
8. Test all language variants to ensure both layout and URL aliases work correctly
