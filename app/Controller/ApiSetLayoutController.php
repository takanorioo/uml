<?php
/**
 * ApiSetLayoutController
 *
 * @author        Takanori Kobashi kobashi@akane.waseda.jp
 * @since         1.0.0
 * @version       1.0.0
 * @copyright
 */
class ApiSetLayoutController extends AppController
{
    public $name = 'ApiSetLayout';
    public $uses = array('Label');
    public $autoRender= false;
    public $autoLayout = false;

    /**
     * beforeFilter
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function beforeFilter()
    {
        $this->Auth->allow();
        Configure::write('debug', 0);
        header("Content-Type: application/json; charset=utf-8");
        header("X-Content-Type-Options: nosniff");
    }

    /**
     * setlayout
     * @param:
     * @author: T.Kobashi
     * @since: 1.0.0
     */
    function setlayout ()
    {
        $position_x = $this->request->query['x'];
        $position_y = $this->request->query['y'];
        $id = $this->request->query['id'];

        for($i = 0; $i < count($id); $i++) {

            // トランザクション処理
            $this->Label->create();

            $data['Label']['id'] = $id[$i];
            $data['Label']['position_x'] = $position_x[$i];
            $data['Label']['position_y'] = $position_y[$i];

            if (!$this->Label->save($data['Label'],false,array('id','position_x','position_y'))) {
                $this->Label->rollback();
                throw new InternalErrorException();
            }
        }
    }
}
?>
