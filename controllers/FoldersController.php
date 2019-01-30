<?php

namespace isalcedo\filemanager\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use isalcedo\filemanager\FilemanagerAsset;

/**
 * FoldersController implements the CRUD actions for Folders model.
 */
class FoldersController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Folders models.
     * @return mixed
     */
    public function actionIndex()
    {
        FilemanagerAsset::register($this->view);
        $model        = new $this->module->models['folders'];
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model'        => $model
        ]);
    }

    /**
     * Displays a single Folders model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        FilemanagerAsset::register($this->view);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Folders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        FilemanagerAsset::register($this->view);
        $model = new $this->module->models['folders'];

        if ($model->load(Yii::$app->request->post())) {
            $model->storage       = isset($this->module->storage['s3']) ? 'S3' : 'local';
            $model->parent_folder = trim($model->parent_folder, '/');
            $model->path          = trim($model->path, '/');

            if ($model->save()) {
                if ($this->module->public_path) {
                    if (!file_exists(Yii::getAlias('@webroot' . '/../..' . $this->module->public_path . $model->parent_folder . '/' . $model->path))) {
                        mkdir(Yii::getAlias('@webroot' . '/../..' . $this->module->public_path . $model->parent_folder . '/' . $model->path),
                            0755, true);
                    }
                } else {
                    if (!file_exists(Yii::getAlias('@webroot' . $this->module->public_path . $model->parent_folder . '/' . $model->path))) {
                        mkdir(Yii::getAlias('@webroot' . $this->module->public_path . $model->parent_folder . '/' . $model->path),
                            0755, true);
                    }
                }

                Yii::$app->session->setFlash('success', 'Folder successfully created.');

                return $this->redirect(['view', 'id' => $model->folder_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Folders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        FilemanagerAsset::register($this->view);
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->parent_folder = trim($model->parent_folder, '/');
            $model->path          = trim($model->path, '/');

            if ($model->save()) {
                if ($this->module->public_path) {
                    if (!file_exists(Yii::getAlias('@webroot' . '/../..' . $this->module->public_path . $model->parent_folder . '/' . $model->path))) {
                        mkdir(Yii::getAlias('@webroot' . '/../..' . $this->module->public_path . $model->parent_folder . '/' . $model->path),
                            0755, true);
                    }
                } else {
                    if (!file_exists(Yii::getAlias('@webroot' . $model->parent_folder . '/' . $model->path))) {
                        mkdir(Yii::getAlias('@webroot' . $model->parent_folder . '/' . $model->path), 0755, true);
                    }
                }
                Yii::$app->session->setFlash('success', 'Folder successfully updated.');

                return $this->redirect(['view', 'id' => $model->folder_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Folders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $baseRoute   = Yii::getAlias('@publicShared') . '/uploads/';
        $folderModel = $this->findModel($id);

        var_dump($folderModel);

        if ($folderModel->parent_folder !== '') {
            $completeRoute = $baseRoute . $folderModel->parent_folder . '/' . $folderModel->path . '/';
        } else {
            $completeRoute = $baseRoute . $folderModel->path . '/';
        }

        if ($this->isDirEmpty($completeRoute)) {
            rmdir($completeRoute);
            $folderModel->delete();
        } else {
            Yii::$app->getSession()->setFlash(
                'error',
                'El directorio no está vacíó. Posee otros directorios dentro o imágenes.'
            );
        }

        return $this->redirect(['index']);
    }

    private function isDirEmpty($dir)
    {
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                closedir($handle);

                return false;
            }
        }
        closedir($handle);

        return true;
    }

    /**
     * Finds the Folders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Folders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $folders = $this->module->models['folders'];

        if (($model = $folders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
