<?php
/**
 * TopController
 *
 * @author        Takanori Kobashi kobashi@akane.waseda.jp
 * @since         1.0.0
 * @version       1.0.0
 * @copyright
 */
App::uses('AppController', 'Controller');
class ElementController extends AppController
{
    public $name = 'Element';
    public $uses = array(
        'Label',
        'Relation',
        'Attribute',
        'Method',
        'Countermeasure',
        'Pattern',
        'Project',
        'SecurityRequirement'
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
     * edit
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function edit($label_id = null)
    {
         //不正アクセス
        if (!isset($label_id)) {
            throw new BadRequestException();
        }

        //要素の取得
        $elemet = $this->Label->getElement($label_id);


        //データ構造の加工
        $attribute_count = count($elemet['Attribute']);
        for($i = 0; $i < $attribute_count; $i++) {
            $elemet['Attribute']['id'][] = $elemet['Attribute'][$i]['id'];
            $elemet['Attribute']['type'][] = $elemet['Attribute'][$i]['type'];
            $elemet['Attribute']['name'][] = $elemet['Attribute'][$i]['name'];
        }

        //データ構造の加工
        $method_count = count($elemet['Method']);
        for($i = 0; $i < $method_count; $i++) {
            $elemet['Method']['id'][] = $elemet['Method'][$i]['id'];
            $elemet['Method']['type'][] = $elemet['Method'][$i]['type'];
            $elemet['Method']['name'][] = $elemet['Method'][$i]['name'];
        }


        //データ構造の加工
        $relation_count = count($elemet['Relation']);
        for($i = 0; $i < $relation_count; $i++) {

            //現在存在していれば
            if($this->Label->checkElement($elemet['Relation'][$i]['label_relation_id'])) {
                $elemet['Relation']['id'][] = $elemet['Relation'][$i]['id'];
                $elemet['Relation']['label_relation_id'][] = $elemet['Relation'][$i]['label_relation_id'];
            }
        }

        $this->set('label_id', $label_id);
        $this->set('elemet', $elemet);
        $this->set('attribute_count', $attribute_count);
        $this->set('method_count', $method_count);
        $this->set('relation_count', $relation_count);

        if (!empty($this->request->data['editElement'])) {


            $request_data = $this->request->data;


            // トランザクション処理
            $this->Label->begin();

            $data['Label']['id'] = $label_id;
            $data['Label']['interface'] = $request_data['Label']['interface'];
            $data['Label']['name'] = $request_data['Label']['name'];
            $data['Label']['project_id'] = $this->Session->read('Project.id');

            if (!$this->Label->save($data['Label'],false,array('id','interface','name','project_id'))) {
                $this->Label->rollback();
                throw new InternalErrorException();
            }

            $this->Label->commit();

            if(!empty($request_data['Attribute'])) {
                for($i = 0; $i < count($request_data['Attribute']['type']); $i++) {

                    if(!empty($request_data['Attribute']['name'][$i]))  {

                        //初期化
                        $data = array();

                        // // トランザクション処理
                        $this->Attribute->create();
                        $this->Attribute->begin();

                        if(!empty($request_data['Attribute']['id'][$i])) {
                            $data['Attribute']['id'] = $request_data['Attribute']['id'][$i];
                        }
                        $data['Attribute']['type'] = $request_data['Attribute']['type'][$i];
                        $data['Attribute']['name'] = $request_data['Attribute']['name'][$i];
                        $data['Attribute']['label_id'] = $label_id;

                        if (!$this->Attribute->save($data['Attribute'],false,array('id','type','name','label_id'))) {
                            $this->Attribute->rollback();
                            throw new InternalErrorException();
                        }
                        $this->Attribute->commit();
                    }
                }
            }

            if(!empty($request_data['Method'])) {
                for($i = 0; $i < count($request_data['Method']['type']); $i++) {


                        //初期化
                    $data = array();

                        // // トランザクション処理
                    $this->Method->create();
                    $this->Method->begin();

                    if(!empty($request_data['Method']['id'][$i])) {
                        $data['Method']['id'] = $request_data['Method']['id'][$i];
                    }
                    $data['Method']['type'] = $request_data['Method']['type'][$i];
                    $data['Method']['name'] = $request_data['Method']['name'][$i];
                    $data['Method']['label_id'] = $label_id;

                    if (!$this->Method->save($data['Method'],false,array('id','type','name','label_id'))) {
                        $this->Method->rollback();
                        throw new InternalErrorException();
                    }
                    $this->Method->commit();
                }
            }

            if(!empty($request_data['Relation'])) {
                for($i = 0; $i < count($request_data['Relation']['label_relation_id']); $i++) {
                    if(!empty($request_data['Relation']['label_relation_id'][$i]))  {

                         //初期化
                        $data = array();

                        //トランザクション処理
                        $this->Relation->create();
                        $this->Relation->begin();

                        if(!empty($request_data['Relation']['id'][$i])) {
                            $data['Relation']['id'] = $request_data['Relation']['id'][$i];
                        }
                        $data['Relation']['label_id'] = $label_id;
                        $data['Relation']['label_relation_id'] = $request_data['Relation']['label_relation_id'][$i];

                        if (!$this->Relation->save($data['Relation'],false,array('id','label_id','label_relation_id'))) {
                            $this->Relation->rollback();
                            throw new InternalErrorException();
                        }
                        $this->Relation->commit();
                    }
                }
            }



            $this->redirect(array('controller' => 'Top', 'action' => 'index'));

            return;

        }

        $this->request->data = $elemet;

    }

    /**
     * edit
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function add()
    {

        //エレメントの追加
        if (!empty($this->request->data['addElement'])) {

            $request_data = $this->request->data;

            // // トランザクション処理
            $this->Label->begin();

            $data['Label']['interface'] = $request_data['Label']['interface'];
            $data['Label']['name'] = $request_data['Label']['name'];
            $data['Label']['project_id'] = $this->Session->read('Project.id');

            if (!$this->Label->save($data['Label'],false,array('interface','name','project_id'))) {
                $this->Label->rollback();
                throw new InternalErrorException();
            }
            $label_id = $this->Label->id;

            $this->Label->commit();


            for($i = 0; $i < count($request_data['Attribute']['type']); $i++) {

                if(!empty($request_data['Attribute']['name'][$i]))  {

                    //初期化
                    $data = array();

                    // // トランザクション処理
                    $this->Attribute->create();
                    $this->Attribute->begin();
                    
                    $data['Attribute']['type'] = $request_data['Attribute']['type'][$i];
                    $data['Attribute']['name'] = $request_data['Attribute']['name'][$i];
                    $data['Attribute']['label_id'] = $label_id;

                    if (!$this->Attribute->save($data['Attribute'],false,array('type','name','label_id'))) {
                        $this->Attribute->rollback();
                        throw new InternalErrorException();
                    }
                    $this->Attribute->commit();
                }
            }

            for($i = 0; $i < count($request_data['Method']['type']); $i++) {

                    //初期化
                $data = array();

                    // // トランザクション処理
                $this->Method->create();
                $this->Method->begin();

                $data['Method']['type'] = $request_data['Method']['type'][$i];
                $data['Method']['name'] = $request_data['Method']['name'][$i];
                $data['Method']['label_id'] = $label_id;

                if (!$this->Method->save($data['Method'],false,array('type','name','label_id'))) {
                    $this->Method->rollback();
                    throw new InternalErrorException();
                }
                $this->Method->commit();
            }
            if(!empty($request_data['Relation']))  {

                for($i = 0; $i < count($request_data['Relation']['id']); $i++) {

                    if(!empty($request_data['Relation']['id'][$i]))  {

                        //初期化
                        $data = array();

                        // // トランザクション処理
                        $this->Relation->create();
                        $this->Relation->begin();
                        
                        $data['Relation']['label_id'] = $label_id;
                        $data['Relation']['label_relation_id'] = $request_data['Relation']['id'][$i];

                        if (!$this->Relation->save($data['Relation'],false,array('label_id','label_relation_id'))) {
                            $this->Relation->rollback();
                            throw new InternalErrorException();
                        }
                        $this->Relation->commit();
                    }
                }
            }
            $this->redirect(array('controller' => 'Top', 'action' => 'index'));
            return;
        }
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function delete($id = null)
    {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }
        // トランザクション処理始め
        $this->Label->begin();

        if (!$this->Label->delete($id)) {
            $this->Label->rollback();
            throw new BadRequestException();
        }

        $this->Label->commit();

        $this->Session->setFlash('You successfully delete element.', 'default', array('class' => 'alert alert-success'));
        $this->redirect(array('controller' => 'Top', 'action' => 'index'));
    }

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function relation_delete($id = null)
    {
        //不正アクセス
        if (!isset($id)) {
            throw new BadRequestException();
        }

        // トランザクション処理始め
        $this->Relation->begin();

        if (!$this->Relation->delete($id)) {
            $this->Relation->rollback();
            throw new BadRequestException();
        }

        $this->Relation->commit();

        $this->Session->setFlash('You successfully delete Relation.', 'default', array('class' => 'alert alert-success'));
        $this->redirect(array('controller' => 'Top', 'action' => 'index'));
    }


    /**
     * index
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function target()
    {
        //対策のリスト
        $countermeasures = $this->Countermeasure->getCountermeasures();
        for($i = 0; $i < count($countermeasures); $i++) {
            $countermeasure_list[$countermeasures[$i]['Countermeasure']['id']] = $countermeasures[$i]['Countermeasure']['name'];
        }

        $this->set('countermeasures', $countermeasures);
        $this->set('countermeasure_list', $countermeasure_list);
        

        //TargetFunctionと対策をセット
        if (!empty($this->request->data['addTargetFunction'])) {

            $request_data = $this->request->data;


            for($i = 0; $i < count($request_data['Countermeasure']); $i++) {

                // トランザクション処理
                $this->SecurityRequirement->create();
                $this->SecurityRequirement->begin();

                $data['SecurityRequirement']['method_id'] = $request_data['Label']['Method'];
                $data['SecurityRequirement']['countermeasure_id'] = $request_data['Countermeasure'][$i];
                $data['SecurityRequirement']['project_id'] = $this->Session->read('Project.id');
                
                if (!$this->SecurityRequirement->save($data['SecurityRequirement'],false,array('method_id','countermeasure_id','project_id'))) {
                    $this->SecurityRequirement->rollback();
                    throw new InternalErrorException();
                }
                $this->SecurityRequirement->commit();
            }
            
            $this->Session->setFlash('You successfully Set Target Function.', 'default', array('class' => 'alert alert-success'));
            $this->redirect(array('controller' => 'Top', 'action' => 'index'));
        }
    }

    /**
     * index
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function target_list()
    {
        //プロジェクトIDの取得   
        $project_id = $this->Session->read('Project.id');

        //要素の取得
        $security_requirements = $this->SecurityRequirement->getSecurityRequirements($project_id);

        $target_methods = array();

        //データの加工
        for($i = 0; $i < count($security_requirements); $i++) {
            if (!in_array($security_requirements[$i]['Method']['id'], $target_methods)) {
                $target_methods[] = $security_requirements[$i]['Method']['id'];
                $target_methods['id'][] = $security_requirements[$i]['Method']['id'];
                $target_methods['name'][] = $security_requirements[$i]['Method']['name'];
            }
        }
        $this->set('target_methods', $target_methods);
    }


    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function import()
    {
        $filename = 'xml/target.xml';

        if (file_exists($filename)) {
            echo "$filename が存在します";
            $movies = simplexml_load_file($filename);

            foreach ($movies->movie->characters->character as $character) {
               echo $character->name, ' played by ', $character->actor, PHP_EOL;
           }

       } else {
        echo "$filename は存在しません";
    }
}

    /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function testcasedata($method_id = null)
    {
        $attribute = $this->request->query['attribute'];

        //不正アクセス
        if (!isset($method_id)) {
            throw new BadRequestException();
        }

        for($i = 0; $i < count($attribute); $i++) {

            //初期化
            $data = array();
            $attribute_detail = array();

            $attribute_detail = explode(".", $attribute[$i]);


            $attribute_id = $this->Attribute->getAttributeIdByName($attribute_detail[1]);

            if(empty($attribute_id['Attribute']['id'])) {

                // トランザクション処理
                $this->Attribute->create();
                $this->Attribute->begin();

                $data['Attribute']['type'] = $attribute_detail[2];
                $data['Attribute']['name'] = $attribute_detail[1];
                $data['Attribute']['label_id'] = $attribute_detail[0];

                if (!$this->Attribute->save($data['Attribute'],false,array('type','name','label_id'))) {
                    $this->Attribute->rollback();
                    throw new InternalErrorException();
                }
                $this->Attribute->commit();
            }
        }
        $this->redirect(array('controller' => 'Element', 'action' => 'testcase', $method_id));
    }

     /**
     * delete
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    public function testcase($method_id = null)
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


        //テストの実行
        if (!empty($this->request->data['executeTest'])) {

            $this->layout = false;


            $label = $this->Method->getLabel($method_id);
            $this->set('label', $label);

            // $countermeasures = $this->request->data['Countermeasure'];
            // $this->set('countermeasures', $countermeasures);

            $attributes = $this->request->data['Attribute']['name'];

            $attribute_ids = array_keys($attributes);
            for($i = 0; $i < count($attribute_ids); $i++) {
              $attribute_name = $this->Attribute->getAttributeName($attribute_ids[$i]);
              $attributes[$attribute_ids[$i]]['name'] = $attribute_name['Label']['name'];
              $attributes[$attribute_ids[$i]]['attribute_name'] = $attribute_name['Attribute']['name'];
            }
            $this->set('attributes', $attributes);

            $this->render('testscript');
            $str = strtolower($this->render('testscript'));
            // ヘッダー指定


            $file = "test.txt";
            header("Content-type: text/plain");
            header("Content-Disposition: attachment; filename=$file");

            // readfile($str);
            return;

         }
    }
}
