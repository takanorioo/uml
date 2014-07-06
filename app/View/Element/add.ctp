
<script>

	//属性の追加
	$(function() {
		var option = '<option value="">----</option>';
		<?php for($i = 0; $i < count($TYPE); $i++): ?>
		option  += '<option value=<?php echo $i;?>><?php echo h($TYPE[$i]);?></option>'
	<?php endfor; ?>

	var count = 1;
	$("#add_attribute").click(function(){
		$(".add_attribute").append('<tr><td><select name="data[Attribute][type]['+count+']" class="form-control">'+option+'</select></td><td><input name="data[Attribute][name]['+count+']" id="" class="form-control" placeholder="AttributeName" type="text"/></td></tr>');
		count ++;
	});
});

	//操作の追加
	$(function() {
		var option = '<option value="">----</option>';
		<?php for($i = 0; $i < count($RETURNVALUE); $i++): ?>
		option  += '<option value=<?php echo h($RETURNVALUE[$i]);?>><?php echo h($RETURNVALUE[$i]);?></option>'
	<?php endfor; ?>

	var count = 1;
	$("#add_method").click(function(){
		$(".add_method").append('<tr><td><select name="data[Method][type]['+count+']" class="form-control">'+option+'</select></td><td><input name="data[Method][name]['+count+']" id="" class="form-control" placeholder="MethodName" type="text"/></td></tr>');
		count ++;
	});

});

	//リレーションの追加
	$(function() {
		var option_relation = '<option value="">----</option>';
		<?php for($i = 0; $i < count($option_relation); $i++): ?>
		option_relation  += '<option value=<?php echo h($option_relation[$i]['id']);?>><?php echo h($option_relation[$i]['name']);?></option>'
	<?php endfor; ?>

	var count = 1;
	$("#add_relation").click(function(){
		$(".add_relation").append('<tr><td><select name="data[Relation][id]['+count+']"class="form-control">'+option_relation+'</select></td></tr>');
		count ++;
	});
});

</script>

<div>
	<div>
		<div class="row">
			<div style="padding-top: 20px;">
				<p style="font-size: 40px;margin-left: 10px;">Add Element</p>
			</div>
		</div>
		<?php echo $this->Form->create('Label'); ?>
		<div class="row">
			<div class="col-md-8 well">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>StereoType</th>
							<th>Name</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $this->Form->input('Label.interface', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'StereoType', 'error'=>false)); ?></td>
							<td><?php echo $this->Form->input('Label.name', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'Element Name', 'error'=>false)); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 well element">
				<p style="text-align: right;">
					<input id="add_attribute" type="button" class ="btn btn-success" value="+" />
				</p>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Type</th>
							<th>AttributeName</th>
						</tr>
					</thead>
					<tbody class="add_attribute">
						<tr>
							<td><?php echo $this->Form->input('Attribute.type.0', array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $TYPE, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?>
							</td>
							<td><?php echo $this->Form->input('Attribute.name.0', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'AttributeName', 'error'=>false)); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-4 well element">
				<p style="text-align: right;">
					<input id="add_method" type="button" class ="btn btn-success" value="+" />
				</p>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Return</th>
							<th>MethodName</th>
						</tr>
					</thead>
					<tbody class="add_method">
						<tr>
							<td><?php echo $this->Form->input('Method.type.0', array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $RETURNVALUE, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?>
							</td>
							<td><?php echo $this->Form->input('Method.name.0', array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'MethodName', 'error'=>false)); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-4 well element">
				<p style="text-align: right;">
					<input id="add_relation" type="button" class ="btn btn-success" value="+" />
				</p>
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Relation</th>
						</tr>
					</thead>
					<tbody class="add_relation">
						<tr>
							<?php if(isset($relation)): ?>
							<td><?php echo $this->Form->input('Relation.id.0', array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $relation, 'empty' => '----', 'class' => 'form-control', 'error'=>false)); ?>
							</td>
							<?php endif; ?>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<p style="text-align: center;">
					<?php
					echo $this->Form->submit('Add Element', array('name' => 'confirm', 'div' => false, 'class' => 'btn btn-primary'));
					?>
				</p>
				<input type="hidden" name="token" value="<?php echo session_id();?>">
				<input type="hidden" name="addElement" value="addElement">
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
