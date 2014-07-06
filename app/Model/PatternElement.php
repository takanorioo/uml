<?php
/* User Model
 *
 */
class PatternElement extends AppModel {
    public $name = 'PatternElement';
	public $belongsTo = array('Pattern');
}
