<?php
namespace verbb\cloner\controllers;

use verbb\cloner\Cloner;

use Craft;
use craft\elements\Entry;
use craft\helpers\Json;
use craft\web\Controller;

use yii\web\Response;

class CloneController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionEntryType(): Response
    {
        $id = (int)$this->request->getParam('id');
        $name = $this->request->getParam('name');
        $handle = $this->request->getParam('handle');

        $oldEntryType = Craft::$app->getEntries()->getEntryTypeById($id);

        $entryType = Cloner::$plugin->getEntryTypes()->setupClonedEntryType($oldEntryType, $name, $handle);

        if (!Craft::$app->getEntries()->saveEntryType($entryType)) {
            $error = Craft::t('cloner', 'Couldn’t clone entry type - {i}.', ['i' => Json::encode($entryType->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Entry type cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionSection(): Response
    {
        $id = (int)$this->request->getParam('id');
        $name = $this->request->getParam('name');
        $handle = $this->request->getParam('handle');

        $oldSection = Craft::$app->getEntries()->getSectionById($id);

        $section = Cloner::$plugin->getEntries()->setupClonedSection($oldSection, $name, $handle);

        if (!Craft::$app->getEntries()->saveSection($section)) {
            $error = Craft::t('cloner', 'Couldn’t clone section - {i}.', ['i' => Json::encode($section->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Section cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionVolume(): Response
    {
        $id = (int)$this->request->getParam('id');
        $name = $this->request->getParam('name');
        $handle = $this->request->getParam('handle');

        $oldVolume = Craft::$app->getVolumes()->getVolumeById($id);

        $volume = Cloner::$plugin->getVolumes()->setupClonedVolume($oldVolume, $name, $handle);

        if (!Craft::$app->getVolumes()->saveVolume($volume)) {
            $error = Craft::t('cloner', 'Couldn’t clone volume - {i}.', ['i' => Json::encode($volume->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Volume cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionTransform(): Response
    {
        $id = (int)$this->request->getParam('id');
        $name = $this->request->getParam('name');
        $handle = $this->request->getParam('handle');

        // NOTE: the ID will be the handle, because that's how the URL is set up for transforms
        $oldTransform = Craft::$app->getImageTransforms()->getTransformByHandle($id);

        $transform = Cloner::$plugin->getImageTransforms()->setupClonedTransform($oldTransform, $name, $handle);

        if (!Craft::$app->getImageTransforms()->saveTransform($transform)) {
            $error = Craft::t('cloner', 'Couldn’t clone transform - {i}.', ['i' => Json::encode($transform->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Transform cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionCategoryGroup(): Response
    {
        $id = (int)$this->request->getParam('id');
        $name = $this->request->getParam('name');
        $handle = $this->request->getParam('handle');

        $oldCategoryGroup = Craft::$app->getCategories()->getGroupById($id);

        $categoryGroup = Cloner::$plugin->getCategoryGroups()->setupClonedCategoryGroup($oldCategoryGroup, $name, $handle);

        if (!Craft::$app->getCategories()->saveGroup($categoryGroup)) {
            $error = Craft::t('cloner', 'Couldn’t clone category group - {i}.', ['i' => Json::encode($categoryGroup->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Category group cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionTagGroup(): Response
    {
        $id = (int)$this->request->getParam('id');
        $name = $this->request->getParam('name');
        $handle = $this->request->getParam('handle');

        $oldTagGroup = Craft::$app->getTags()->getTagGroupById($id);

        $tagGroup = Cloner::$plugin->getTagGroups()->setupClonedTagGroup($oldTagGroup, $name, $handle);

        if (!Craft::$app->getTags()->saveTagGroup($tagGroup)) {
            $error = Craft::t('cloner', 'Couldn’t clone tag group - {i}.', ['i' => Json::encode($tagGroup->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Tag group cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionGlobalSet(): Response
    {
        $id = (int)$this->request->getParam('id');
        $name = $this->request->getParam('name');
        $handle = $this->request->getParam('handle');

        $oldGlobalSet = Craft::$app->getGlobals()->getSetById($id);

        $globalSet = Cloner::$plugin->getGlobalSets()->setupClonedGlobalSet($oldGlobalSet, $name, $handle);

        if (!Craft::$app->getGlobals()->saveSet($globalSet)) {
            $error = Craft::t('cloner', 'Couldn’t clone global set - {i}.', ['i' => Json::encode($globalSet->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Global set cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionUserGroup(): Response
    {
        $id = (int)$this->request->getParam('id');
        $name = $this->request->getParam('name');
        $handle = $this->request->getParam('handle');

        $oldUserGroup = Craft::$app->getUserGroups()->getGroupById($id);

        $userGroup = Cloner::$plugin->getUserGroups()->setupClonedUserGroup($oldUserGroup, $name, $handle);

        if (!Craft::$app->getUserGroups()->saveGroup($userGroup)) {
            $error = Craft::t('cloner', 'Couldn’t clone user group - {i}.', ['i' => Json::encode($userGroup->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        Cloner::$plugin->getUserGroups()->setupPermissions($oldUserGroup, $userGroup);

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'User group cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionSite(): Response
    {
        $id = (int)$this->request->getParam('id');
        $name = $this->request->getParam('name');
        $handle = $this->request->getParam('handle');

        $oldSite = Craft::$app->getSites()->getSiteById($id);

        $site = Cloner::$plugin->getSites()->setupClonedSite($oldSite, $name, $handle);

        if (!Craft::$app->getSites()->saveSite($site)) {
            $error = Craft::t('cloner', 'Couldn’t clone site - {i}.', ['i' => Json::encode($site->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Site cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionFilesystem(): Response
    {
        $oldHandle = $this->request->getParam('id');
        $name = $this->request->getParam('name');
        $handle = $this->request->getParam('handle');

        $oldFilesystem = Craft::$app->getFs()->getFilesystemByHandle($oldHandle);

        $filesystem = Cloner::$plugin->getFilesystems()->setupClonedFilesystem($oldFilesystem, $name, $handle);

        if (!Craft::$app->getFs()->saveFilesystem($filesystem)) {
            $error = Craft::t('cloner', 'Couldn’t clone filesystem - {i}.', ['i' => Json::encode($filesystem->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'Filesystem cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

}