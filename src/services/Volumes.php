<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use Craft;
use craft\helpers\App;
use craft\helpers\StringHelper;
use craft\models\Volume;

class Volumes extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/volume';
    public static string $id = 'volumes';
    public static string $matchedRoute = 'volumes/volume-index';
    public static string $title = 'Volume';


    // Public Methods
    // =========================================================================

    public function setupClonedVolume(Volume $oldVolume, string $name, string $handle): Volume
    {
        $volume = clone $oldVolume;
        $volume->id = null;
        $volume->uid = null;
        $volume->fieldLayoutId = null;
        $volume->name = $name;
        $volume->handle = $handle;

        // Preserve environment-aware filesystem handles while giving each clone its own top-level paths.
        $fsHandle = (string)$oldVolume->getFsHandle(false);
        $transformFsHandle = $oldVolume->getTransformFsHandle(false);
        $subpath = StringHelper::toKebabCase($handle);

        $volume->setFsHandle($fsHandle);
        $volume->setSubpath($this->_uniqueSubpath($fsHandle, $subpath));
        $volume->setTransformFsHandle($transformFsHandle);
        $volume->setTransformSubpath($this->_uniqueSubpath($transformFsHandle ?: $fsHandle, $subpath . '-transforms'));

        $fieldLayout = $this->getFieldLayout($oldVolume->getFieldLayout());
        $volume->setFieldLayout($fieldLayout);

        return $volume;
    }

    // Private Methods
    // =========================================================================

    private function _uniqueSubpath(string $fsHandle, string $preferredSubpath): string
    {
        $parsedFsHandle = App::parseEnv($fsHandle) ?? $fsHandle;
        $usedTopFolders = [];

        foreach (Craft::$app->getVolumes()->getAllVolumes() as $volume) {
            $paths = [[
                $volume->getFsHandle(false),
                $volume->getSubpath(false, false),
            ]];

            $transformFsHandle = $volume->getTransformFsHandle(false);
            $transformSubpath = $volume->getTransformSubpath(false, false);

            if ($transformFsHandle || $transformSubpath !== '') {
                $paths[] = [
                    $transformFsHandle ?: $volume->getFsHandle(false),
                    $transformSubpath,
                ];
            }

            foreach ($paths as [$existingFsHandle, $existingSubpath]) {
                $parsedExistingFsHandle = App::parseEnv($existingFsHandle) ?? $existingFsHandle;

                if ($existingFsHandle !== $fsHandle && $parsedExistingFsHandle !== $parsedFsHandle) {
                    continue;
                }

                $parsedSubpath = App::parseEnv($existingSubpath) ?? $existingSubpath;
                $topFolder = explode('/', trim($parsedSubpath, '/'))[0] ?? '';

                // A volume rooted at the filesystem already contains every possible child path.
                if ($topFolder === '') {
                    return '';
                }

                $usedTopFolders[$topFolder] = true;
            }
        }

        $subpath = $preferredSubpath;
        $suffix = 2;

        while (isset($usedTopFolders[$subpath])) {
            $subpath = "$preferredSubpath-$suffix";
            $suffix++;
        }

        return $subpath;
    }

}
