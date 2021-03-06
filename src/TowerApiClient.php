<?php
/**
 * Tower SDK Client
 *
 * @license MIT
 * @author zhangv
 * @link http://docs.tower.im/
 */

namespace zhangv\tower;

use zhangv\tower\util\HttpClient;

class TowerApiClient{
	private $endPoint = 'https://api.tower.im/v1/';

	/** @var string 应用ID */
	private $clientId = null;
	/** @var string 密钥 */
	private $clientSecret = null;
	/** @var string 访问Token */
	private $accessToken = null;
	/** @var HttpClient Http客户端 */
	private $httpClient = null;
	/** @var TowerOAuth OAuth */
	private $towerOAuth = null;

	public function __construct($clientId, $clientSecret){
		$this->clientId     = $clientId;
		$this->clientSecret = $clientSecret;
		$this->httpClient = new HttpClient(5);
	}

	public function getTowerOAuth(){
		if(!$this->towerOAuth){
			$this->towerOAuth = new TowerOAuth($this->clientId,$this->clientSecret);
		}
		return $this->towerOAuth;
	}

	public function setHttpClient($httpClient){
		$this->httpClient = $httpClient;
	}

	public function authorizeURI($redirectURI){
		return $this->getTowerOAuth()->authorizeURI($redirectURI);
	}

	public function authorize($code,$redirectURI){
		return $this->getTowerOAuth()->authorize($code,$redirectURI);
	}

	public function revoke($token){
		return $this->getTowerOAuth()->revoke($token);
	}

	public function refresh($refreshToken){
		return $this->getTowerOAuth()->refresh($refreshToken);
	}

	public function password($username,$password){
		return $this->getTowerOAuth()->password($username,$password);
	}
	public function setEndPoint($endpoint){
		$this->endPoint = $endpoint;
	}

	/**
	 * 使用token进行访问
	 *
	 * @param       $uri
	 * @param array $data
	 * @param string  $method
	 * @throws \Exception
	 * @return mixed
	 */
	public function api($uri, $data = array(), $method = HttpClient::GET){
		$url = $this->endPoint . $uri;
		$headers = array('Content-Type: application/json','Authorization: Bearer ' . $this->accessToken);
		$content = null;
		if ($method == HttpClient::GET) {
			$content = $this->httpClient->get($url,$data,$headers);
		}elseif ($method == HttpClient::POST) {
			$content = $this->httpClient->post($url,$data,$headers);
		}elseif ($method == HttpClient::PUT) {
			$content = $this->httpClient->put($url,$data,$headers);
		}elseif ($method == HttpClient::DELETE) {
			$content = $this->httpClient->delete($url,$data,$headers);
		}elseif ($method == HttpClient::PATCH) {
			$content = $this->httpClient->patch($url,$data,$headers);
		}else throw new \Exception("Unknown method - $method");
		return json_decode($content);
	}

	public function setAccessToken($accessToken){
		$this->accessToken = $accessToken;
	}

	/**
	 * 获取团队信息
	 * @return mixed
	 */
	public function getTeams(){
		return $this->api("teams", array());
	}

	/**
	 * 获取团队项目列表
	 * @param $teamId
	 * @return mixed
	 */
	public function getTeamProjects($teamId){
		return $this->api("teams/$teamId/projects", array());
	}

	/**
	 * 获取项目任务清单列表
	 * @param $projectId
	 * @return mixed
	 */
	public function getProjectTodolists($projectId){
		return $this->api("projects/$projectId/todolists", array());
	}

	/**
	 * 创建任务清单
	 * @param $projectId
	 * @param $todolistName
	 * @return mixed
	 */
	public function createTodoList($projectId,$todolistName){
		$params = ['todolist' => ['name'=>$todolistName]];
		$params = json_encode($params);
		return $this->api("projects/$projectId/todolists", $params,HttpClient::POST);
	}

	/**
	 * 删除任务清单
	 * @param $todolistId
	 * @return mixed
	 */
	public function deleteTodoList($todolistId){
		return $this->api("todolists/$todolistId", [],HttpClient::DELETE);
	}

	/**
	 * 获取任务清单任务列表
	 * @param $todolistId
	 * @return mixed
	 */
	public function getTodolistTodos($todolistId){
		return $this->api("todolists/$todolistId/todos", array());
	}

	/**
	 * 设置任务截止时间
	 * @param $todoId
	 * @param $dueAt string Y-m-d
	 * @return mixed
	 */
	public function setTodoDue($todoId,$dueAt){
		$params = ['todos_due' => ['due_at'=>$dueAt]];
		$params = json_encode($params);
		return $this->api("todos/$todoId/due",$params,HttpClient::PATCH);
	}

	/**
	 * 设置任务完成
	 * @param $todoId
	 * @return mixed
	 */
	public function setTodoCompletion($todoId){
		return $this->api("todos/$todoId/completion",[],HttpClient::POST);
	}

	/**
	 * 指派任务
	 * @param $todoId
	 * @param $assigneeId
	 * @return mixed
	 */
	public function setTodoAssignment($todoId,$assigneeId){
		$params = ['todos_assignment' => ['assignee_id'=>$assigneeId]];
		$params = json_encode($params);
		return $this->api("todos/$todoId/assignment",$params,HttpClient::PATCH);
	}

	/**
	 * 创建任务
	 * @param $todolistId
	 * @param $content
	 * @param $desc
	 * @return mixed
	 */
	public function createTodo($todolistId, $content, $desc){
		$params = ['todo' => ['content'=>$content,'desc' => $desc]];
		$params = json_encode($params);
		return $this->api("todolists/$todolistId/todos", $params, HttpClient::POST);
	}

	/**
	 * 创建任务评论
	 * @param $todoId
	 * @param $comment
	 * @return mixed
	 */
	public function createTodoComment($todoId, $comment){
		$params = ['todos_comment' => ['content'=>$comment]];
		$params = json_encode($params);
		return $this->api("todos/$todoId/comments", $params, HttpClient::POST);
	}
}
