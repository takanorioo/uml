<?php
/**
 * ProjectsController
 *
 * @author        Takanori Kobashi kobashi@akane.waseda.jp
 * @since         1.0.0
 * @version       1.0.0
 * @copyright
 */
App::uses('AppController', 'Controller');
class SecurityRequirementsController extends AppController
{
    public $name = 'SecurityRequirements';
    public $uses = array(
        'SecurityRequirement',
        'CountermeasureBind',
        );
    public $helpers = array('Html', 'Form');
    public $layout = 'base';

    /**
     * beforeFilter
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->deny();
    }

    /**
     * add
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function bind($method_id = null)
    {
        //プロジェクトIDの取得   
        $project_id = $this->Session->read('Project.id');

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        //Method情報を取得
        $method = $this->Method->getMethod($method_id);
        $this->set('method', $method);

        // SecurityRequirement
        $security_requirement = $this->SecurityRequirement->getSecurityRequirement($method_id);
        $this->set('security_requirement', $security_requirement);

         //TargetFunctionと対策をセット
        if (!empty($this->request->data['setBind'])) {

            $request_data = $this->request->data;


            for($i = 0; $i < count($request_data['CountermeasureElement']); $i++) {

                for($j = 0; $j < count($request_data['CountermeasureElement'][$i]['id']); $j++) {

                        // トランザクション処理
                    $this->CountermeasureBind->begin();
                    $this->CountermeasureBind->create();

                    $data['CountermeasureBind']['security_requirement_id'] = $request_data['CountermeasureElement'][$i]['security_requirement_id'];
                    $data['CountermeasureBind']['countermeasure_element_id'] = $request_data['CountermeasureElement'][$i]['countermeasure_element_id'];
                    $data['CountermeasureBind']['label_id'] = $request_data['CountermeasureElement'][$i]['id'][$j];

                    if (!$this->CountermeasureBind->save($data['CountermeasureBind'],false,array('security_requirement_id','countermeasure_element_id', 'label_id'))) {
                        $this->CountermeasureBind->rollback();
                        throw new InternalErrorException();
                    }
                    $this->CountermeasureBind->commit();
                }
            }

            $this->Session->setFlash('You successfully Set Target Function.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'SecurityRequirements', 'action' => 'table', $method_id));
        }

    }

    /**
     * index
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function delete($method_id = null)
    {
        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }
        // トランザクション処理始め
        $this->SecurityRequirement->begin();

        $conditions = array('SecurityRequirement.method_id' => $method_id);
        if (!$this->SecurityRequirement->deleteAll($conditions, false)) {
            $this->SecurityRequirement->rollback();
            throw new BadRequestException();
        }

        $this->SecurityRequirement->commit();

        $this->Session->setFlash('You successfully delete.', 'default', array('class' => 'alert alert-success'));
        $this->redirect($this->referer());
    }

     /**
     * index
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function delete_countermeasure($countermeasure_id = null)
    {
        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }
        // トランザクション処理始め
        $this->SecurityRequirement->begin();

        $conditions = array('SecurityRequirement.method_id' => $method_id);
        if (!$this->SecurityRequirement->deleteAll($conditions, false)) {
            $this->SecurityRequirement->rollback();
            throw new BadRequestException();
        }

        $this->SecurityRequirement->commit();

        $this->Session->setFlash('You successfully delete.', 'default', array('class' => 'alert alert-success'));
        $this->redirect($this->referer());
    }


    /**
     * add
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function table($method_id = null)
    {
        //プロジェクトIDの取得   
        $project_id = $this->Session->read('Project.id');

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        //Method情報を取得
        $method = $this->Method->getMethod($method_id);
        $this->set('method', $method);

        // SecurityRequirement
        $security_requirement = $this->SecurityRequirement->getSecurityRequirement($method_id);

        $security_requirement_count = pow (2, count($security_requirement));

        $conditions = "?";
        $this->set('conditions', $conditions);
        $this->set('security_requirement', $security_requirement);
        $this->set('security_requirement_count', $security_requirement_count);

    }

}
