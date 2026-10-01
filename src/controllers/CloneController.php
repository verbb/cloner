<?php
namespace verbb\cloner\controllers;

use verbb\cloner\Cloner;

use Craft;
use craft\helpers\Json;
use craft\web\Controller;

use yii\web\BadRequestHttpException;
use yii\web\Response;

use Throwable;

class CloneController extends Controller
{
    // Public Methods
    // =========================================================================

    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->requireCpRequest();
        $this->requirePostRequest();
        $this->requireAdmin();

        return true;
    }

    public function actionEntryType(): Response
    {
        $id = (int)$this->request->getRequiredBodyParam('id');
        $name = (string)$this->request->getRequiredBodyParam('name');
        $handle = (string)$this->request->getRequiredBodyParam('handle');

        $oldEntryType = $this->_requireSource(Craft::$app->getEntries()->getEntryTypeById($id), 'entry type');

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
        $id = (int)$this->request->getRequiredBodyParam('id');
        $name = (string)$this->request->getRequiredBodyParam('name');
        $handle = (string)$this->request->getRequiredBodyParam('handle');

        $oldSection = $this->_requireSource(Craft::$app->getEntries()->getSectionById($id), 'section');

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
        $id = (int)$this->request->getRequiredBodyParam('id');
        $name = (string)$this->request->getRequiredBodyParam('name');
        $handle = (string)$this->request->getRequiredBodyParam('handle');

        $oldVolume = $this->_requireSource(Craft::$app->getVolumes()->getVolumeById($id), 'volume');

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
        $oldHandle = (string)$this->request->getRequiredBodyParam('id');
        $name = (string)$this->request->getRequiredBodyParam('name');
        $handle = (string)$this->request->getRequiredBodyParam('handle');

        $oldTransform = $this->_requireSource(Craft::$app->getImageTransforms()->getTransformByHandle($oldHandle), 'transform');

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
        $id = (int)$this->request->getRequiredBodyParam('id');
        $name = (string)$this->request->getRequiredBodyParam('name');
        $handle = (string)$this->request->getRequiredBodyParam('handle');

        $oldCategoryGroup = $this->_requireSource(Craft::$app->getCategories()->getGroupById($id), 'category group');

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
        $id = (int)$this->request->getRequiredBodyParam('id');
        $name = (string)$this->request->getRequiredBodyParam('name');
        $handle = (string)$this->request->getRequiredBodyParam('handle');

        $oldTagGroup = $this->_requireSource(Craft::$app->getTags()->getTagGroupById($id), 'tag group');

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
        $id = (int)$this->request->getRequiredBodyParam('id');
        $name = (string)$this->request->getRequiredBodyParam('name');
        $handle = (string)$this->request->getRequiredBodyParam('handle');

        $oldGlobalSet = $this->_requireSource(Craft::$app->getGlobals()->getSetById($id), 'global set');

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
        $id = (int)$this->request->getRequiredBodyParam('id');
        $name = (string)$this->request->getRequiredBodyParam('name');
        $handle = (string)$this->request->getRequiredBodyParam('handle');

        $oldUserGroup = $this->_requireSource(Craft::$app->getUserGroups()->getGroupById($id), 'user group');

        $userGroup = Cloner::$plugin->getUserGroups()->setupClonedUserGroup($oldUserGroup, $name, $handle);

        if (!Craft::$app->getUserGroups()->saveGroup($userGroup)) {
            $error = Craft::t('cloner', 'Couldn’t clone user group - {i}.', ['i' => Json::encode($userGroup->getErrors())]);
            Craft::$app->getSession()->setError($error);
            Cloner::error($error);

            return $this->asFailure($error);
        }

        try {
            $permissionsSaved = Cloner::$plugin->getUserGroups()->setupPermissions($oldUserGroup, $userGroup);
        } catch (Throwable $e) {
            Cloner::error($e->getMessage());
            $permissionsSaved = false;
        }

        if (!$permissionsSaved) {
            try {
                Craft::$app->getUserGroups()->deleteGroup($userGroup);
            } catch (Throwable $e) {
                Cloner::error($e->getMessage());
            }

            $error = Craft::t('cloner', 'Couldn’t copy permissions to the cloned user group.');
            Craft::$app->getSession()->setError($error);

            return $this->asFailure($error);
        }

        Craft::$app->getSession()->setNotice(Craft::t('cloner', 'User group cloned successfully.'));

        return $this->asJson(['success' => true]);
    }

    public function actionSite(): Response
    {
        $id = (int)$this->request->getRequiredBodyParam('id');
        $name = (string)$this->request->getRequiredBodyParam('name');
        $handle = (string)$this->request->getRequiredBodyParam('handle');

        $oldSite = $this->_requireSource(Craft::$app->getSites()->getSiteById($id), 'site');

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
        $oldHandle = (string)$this->request->getRequiredBodyParam('id');
        $name = (string)$this->request->getRequiredBodyParam('name');
        $handle = (string)$this->request->getRequiredBodyParam('handle');

        $oldFilesystem = $this->_requireSource(Craft::$app->getFs()->getFilesystemByHandle($oldHandle), 'filesystem');

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

    // Private Methods
    // =========================================================================

    private function _requireSource(mixed $source, string $type): mixed
    {
        if (!$source) {
            throw new BadRequestHttpException(Craft::t('cloner', 'Invalid {type}.', [
                'type' => $type,
            ]));
        }

        return $source;
    }

}
