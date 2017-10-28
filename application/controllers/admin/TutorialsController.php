<?php
/**
 * This tutorial controller is the first step to have user generated tutorials.
 * Current project stance is only to serve pregenerated tutorials bay LimeSurvey Company.
 * @TODO: Make this user editable
 */
class TutorialsController extends Survey_Common_Action
{
	

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}


    public function serveprebuilt(){
        $ajax = Yii::app()->request->getParam('ajax',false);
        if($ajax == false){
            $this->getController()->redirect(['/admin']);
        }
        $tutorialname = Yii::app()->request->getParam('tutorialname','');
        $model = Tutorials::model();
        $prebuiltObject = $model->getPrebuilt($tutorialname);
        return Yii::app()->getController()->renderPartial(
            '/admin/super/_renderJson',
            array(
                'data' => [
                    'tutorial'=> $prebuiltObject,
                ]
            ),
            false,
            false
        );
    }

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function view($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function create()
	{
		$model=new Tutorials;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tutorials']))
		{
			$model->attributes=$_POST['Tutorials'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->tid));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function update($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tutorials']))
		{
			$model->attributes=$_POST['Tutorials'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->tid));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function delete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function index()
	{
		$dataProvider=new CActiveDataProvider('Tutorials');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function admin()
	{
		$model=new Tutorials('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tutorials']))
			$model->attributes=$_GET['Tutorials'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tutorials the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Tutorials::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tutorials $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tutorials-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}