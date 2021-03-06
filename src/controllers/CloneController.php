<?php
namespace verbb\cloner\controllers;

use verbb\cloner\Cloner;

use Craft;
use craft\element\Entry;
use craft\helpers\StringHelper;
use craft\models\EntryType;
use craft\models\Section;
use craft\web\Controller;

class CloneController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionEntryType()
    {
        $request = Craft::$app->getRequest();

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $handle = $request->getParam('handle');

        $oldEntryType = Craft::$app->getSections()->getEntryTypeById($id);

        $entryType = Cloner::$plugin->getEntryTypes()->setupClonedEntryType($oldEntryType, $name, $handle);

        if (!Craft::$app->getSections()->saveEntryType($entryType)) {
            $error = Craft::t('cloner', 'Couldn’t clone entry type - {i}.', [ 'i' => json_encode($entryType->getErrors()) ]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asErrorJson($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Entry type cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionSection()
    {
        $request = Craft::$app->getRequest();

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $handle = $request->getParam('handle');

        $oldSection = Craft::$app->getSections()->getSectionById($id);

        $section = Cloner::$plugin->getSections()->setupClonedSection($oldSection, $name, $handle);

        if (!Craft::$app->getSections()->saveSection($section)) {
            $error = Craft::t('cloner', 'Couldn’t clone section - {i}.', [ 'i' => json_encode($section->getErrors()) ]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asErrorJson($error);
        }

        // Split off the default entry type
        $oldDefaultEntryType = $oldSection->getEntryTypes()[0];
        $newDefaultEntryType = $section->getEntryTypes()[0];

        // Because a new section will already have a default entry type, we want to treat that a little different
        // Instead, we just want to copy the field layout from the old section to the new one - not create a new one.
        $defaultEntryType = Cloner::$plugin->getEntryTypes()->setupDefaultEntryType($oldDefaultEntryType, $newDefaultEntryType);

        if (!Craft::$app->getSections()->saveEntryType($defaultEntryType)) {
            $error = Craft::t('cloner', 'Couldn’t section’s default entry type - {i}.', [ 'i' => json_encode($defaultEntryType->getErrors()) ]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asErrorJson($error);
        }

        foreach ($oldSection->getEntryTypes() as $key => $oldEntryType) {
            // We want to skip the default entry type - already done!
            if ($key === 0) {
                continue;
            }

            $newEntryName = $oldEntryType->name;
            $newEntryHandle = $oldEntryType->handle;

            $entryType = Cloner::$plugin->getEntryTypes()->setupClonedEntryType($oldEntryType, $newEntryName, $newEntryHandle);
            $entryType->sectionId = $section->id;

            if (!Craft::$app->getSections()->saveEntryType($entryType)) {
                $error = Craft::t('cloner', 'Couldn’t section’s entry type - {i}.', [ 'i' => json_encode($entryType->getErrors()) ]);
                Craft::$app->getSession()->setError($error);
                Cloner::error($error);

                return $this->asErrorJson($error);
            }
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Section cloned successfully.'));
        
        return $this->asJson(['success' => true]);
    }

    public function actionVolume()
    {
        $request = Craft::$app->getRequest();

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $handle = $request->getParam('handle');

        $oldVolume = Craft::$app->getVolumes()->getVolumeById($id);

        $volume = Cloner::$plugin->getVolumes()->setupClonedVolume($oldVolume, $name, $handle);

        if (!Craft::$app->getVolumes()->saveVolume($volume)) {
            $error = Craft::t('cloner', 'Couldn’t clone volume - {i}.', [ 'i' => json_encode($volume->getErrors()) ]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asErrorJson($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Volume cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionTransform()
    {
        $request = Craft::$app->getRequest();

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $handle = $request->getParam('handle');

        // NOTE: the ID will be the handle, because that's how the URL is setup for transforms
        $oldTransform = Craft::$app->getAssetTransforms()->getTransformByHandle($id);

        $transform = Cloner::$plugin->getAssetTransforms()->setupClonedTransform($oldTransform, $name, $handle);

        if (!Craft::$app->getAssetTransforms()->saveTransform($transform)) {
            $error = Craft::t('cloner', 'Couldn’t clone transform - {i}.', [ 'i' => json_encode($transform->getErrors()) ]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asErrorJson($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Transform cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionCategoryGroup()
    {
        $request = Craft::$app->getRequest();

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $handle = $request->getParam('handle');

        $oldCategoryGroup = Craft::$app->getCategories()->getGroupById($id);

        $categoryGroup = Cloner::$plugin->getCategoryGroups()->setupClonedCategoryGroup($oldCategoryGroup, $name, $handle);

        if (!Craft::$app->getCategories()->saveGroup($categoryGroup)) {
            $error = Craft::t('cloner', 'Couldn’t clone category group - {i}.', [ 'i' => json_encode($categoryGroup->getErrors()) ]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asErrorJson($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Category group cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionTagGroup()
    {
        $request = Craft::$app->getRequest();

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $handle = $request->getParam('handle');

        $oldTagGroup = Craft::$app->getTags()->getTagGroupById($id);

        $tagGroup = Cloner::$plugin->getTagGroups()->setupClonedTagGroup($oldTagGroup, $name, $handle);

        if (!Craft::$app->getTags()->saveTagGroup($tagGroup)) {
            $error = Craft::t('cloner', 'Couldn’t clone tag group - {i}.', [ 'i' => json_encode($tagGroup->getErrors()) ]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asErrorJson($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Tag group cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionGlobalSet()
    {
        $request = Craft::$app->getRequest();

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $handle = $request->getParam('handle');

        $oldGlobalSet = Craft::$app->getGlobals()->getSetById($id);

        $globalSet = Cloner::$plugin->getGlobalSets()->setupClonedGlobalSet($oldGlobalSet, $name, $handle);

        if (!Craft::$app->getGlobals()->saveSet($globalSet)) {
            $error = Craft::t('cloner', 'Couldn’t clone global set - {i}.', [ 'i' => json_encode($globalSet->getErrors()) ]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asErrorJson($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Global set cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionUserGroup()
    {
        $request = Craft::$app->getRequest();

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $handle = $request->getParam('handle');

        $oldUserGroup = Craft::$app->getUserGroups()->getGroupById($id);

        $userGroup = Cloner::$plugin->getUserGroups()->setupClonedUserGroup($oldUserGroup, $name, $handle);

        if (!Craft::$app->getUserGroups()->saveGroup($userGroup)) {
            $error = Craft::t('cloner', 'Couldn’t clone user group - {i}.', [ 'i' => json_encode($userGroup->getErrors()) ]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asErrorJson($error);
        }

        Cloner::$plugin->getUserGroups()->setupPermissions($oldUserGroup, $userGroup);

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'User group cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionSite()
    {
        $request = Craft::$app->getRequest();

        $id = $request->getParam('id');
        $name = $request->getParam('name');
        $handle = $request->getParam('handle');

        $oldSite = Craft::$app->getSites()->getSiteById($id);

        $site = Cloner::$plugin->getSites()->setupClonedSite($oldSite, $name, $handle);

        if (!Craft::$app->getSites()->saveSite($site)) {
            $error = Craft::t('cloner', 'Couldn’t clone site - {i}.', [ 'i' => json_encode($site->getErrors()) ]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asErrorJson($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Site cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

}