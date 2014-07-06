<?php
/* User Model
 *
 */
class Pattern extends AppModel {
    public $name = 'Pattern';
    public $hasMany = array('PatternElement');

    public function getPatterns() {

        $result = $this->find('all', array(
            'conditions' => array(
            ),
        ));
        return $result;
    }
}
