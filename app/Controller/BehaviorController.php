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
class BehaviorController extends AppController
{
    public $name = 'Behavior';
    public $uses = array('Behavior');
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
    public function index ($method_id = null)
    {
         //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

         //要素の取得
        $behaviors = $this->Behavior->getBehaviorElement($method_id);

        $this->set('behaviors', $behaviors);
        $this->set('method_id', $method_id);

    }

    /**
     * add
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function edit ($method_id = null)
    {
          //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        //要素の取得
        $behaviors = $this->Behavior->getBehaviorElement($method_id);

        $behaviors_count = count($behaviors);
        for($i = 0; $i < $behaviors_count; $i++) {
            $behaviors['Behavior']['id'][] = $behaviors[$i]['Behavior']['id'];
            $behaviors['Behavior']['type'][] = $behaviors[$i]['Behavior']['type'];
            $behaviors['Behavior']['label_id'][] = $behaviors[$i]['Behavior']['label_id'];
            $behaviors['Behavior']['order'][] = $behaviors[$i]['Behavior']['order'];
            $behaviors['Behavior']['name'][] = $behaviors[$i]['Label']['name'];
        }
        $this->set('behaviors', $behaviors);
        $this->request->data = $behaviors;


        // //データ構造の加工
        // $attribute_count = count($elemet['Attribute']);
        // for($i = 0; $i < $attribute_count; $i++) {
        //     $elemet['Attribute']['id'][] = $elemet['Attribute'][$i]['id'];
        //     $elemet['Attribute']['type'][] = $elemet['Attribute'][$i]['type'];
        //     $elemet['Attribute']['name'][] = $elemet['Attribute'][$i]['name'];
        // }

        // //データ構造の加工
        // $method_count = count($elemet['Method']);
        // for($i = 0; $i < $method_count; $i++) {
        //     $elemet['Method']['id'][] = $elemet['Method'][$i]['id'];
        //     $elemet['Method']['type'][] = $elemet['Method'][$i]['type'];
        //     $elemet['Method']['name'][] = $elemet['Method'][$i]['name'];
        // }

    
        // //データ構造の加工
        // $relation_count = count($elemet['Relation']);
        // for($i = 0; $i < $relation_count; $i++) {

        //     //現在存在していれば
        //     if($this->Label->checkElement($elemet['Relation'][$i]['label_relation_id'])) {
        //         $elemet['Relation']['id'][] = $elemet['Relation'][$i]['id'];
        //         $elemet['Relation']['label_relation_id'][] = $elemet['Relation'][$i]['label_relation_id'];
        //     }
        // }

        // $this->set('label_id', $label_id);
        // $this->set('elemet', $elemet);
        // $this->set('attribute_count', $attribute_count);
        // $this->set('method_count', $method_count);
        // $this->set('relation_count', $relation_count);
    }
}
