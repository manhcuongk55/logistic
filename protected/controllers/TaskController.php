<?php
class TaskController extends Controller {
	public function actionIndex(){
		$this->layout = "test";
		$this->render("test");
	}

	public function actionBackground_task(){
		BackgroundTask::handle();
	}

	public function actionTest_background_task(){
		BackgroundTask::runInBackground("application.components.task.delay",array(
			"name" => "tung"
		));
	}

	public function actionTest_no_background_task(){
		BackgroundTask::run("application.components.task.delay",array(
			"name" => "tung"
		));
	}
}
