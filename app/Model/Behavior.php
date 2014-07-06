<?php
/* User Model
 *
 */
class Behavior extends AppModel {

    public $name = 'Behavior';
    public $belongsTo = array('Method','Label');

    public function getBehaviorElement($method_id) {

        $result = $this->find('all', array(
             'conditions' => array(
                'Behavior.method_id' => $method_id,
            ),
             'order' => 'Behavior.order ASC',
             "recursive" => 1,
        ));
        return $result;
    }
}
