
<script>
	//属性の追加
	$(function() {
		var option = '<option value="">----</option>';

		<?php for($i = 0; $i < count($TYPE); $i++): ?>
		option  += '<option value=<?php echo $i;?>><?php echo h($TYPE[$i]);?></option>'
	<?php endfor; ?>

	var count = <?php echo $attribute_count;?>;
	$("#add_attribute").click(function(){
		$(".add_attribute").append('<tr><td><select name="data[Attribute][type]['+count+']" class="form-control">'+option+'</select></td><td><input name="data[Attribute][name]['+count+']" id="" class="form-control" placeholder="AttributeName" type="text"/></td></tr>');
		count ++;
	});
});

	//操作の追加
	$(function() {
		var option = '<option value="">----</option>';
		<?php for($i = 0; $i < count($RETURNVALUE); $i++): ?>
		option  += '<option value=<?php echo $i;?>><?php echo h($RETURNVALUE[$i]);?></option>'
	<?php endfor; ?>

	var count = <?php echo $method_count;?>;
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

	var count = <?php echo $relation_count;?>;
	$("#add_relation").click(function(){
		$(".add_relation").append('<tr><td><select name="data[Relation][label_relation_id]['+count+']" class="form-control">'+option_relation+'</select></td></tr>');
		count ++;
	});
});

</script>





<div>
	<div>
		<?php echo $this->Form->create('Label', array('id' => false)); ?>

		<div>
			<div class="row">
				<div class="col-md-12 well element">
					<p style="text-align: right;">
						<input id="add_attribute" type="button" class ="btn btn-success" value="+" />
					</p>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Type</th>
								<th>Element</th>
								<th>Order</th>
							</tr>
						</thead>
						<tbody class="add_attribute">
							<?php if(!empty($behaviors['Behavior']['id'])): ?>
								<?php for($i = 0; $i < count($behaviors['Behavior']['id']); $i++): ?>
									<tr>
										<?php echo $this->Form->hidden('Behavior.id.'.$i,  array('label' => false, 'div' => false, 'id' => false)); ?>
										<td><?php echo $this->Form->input('Behavior.type.'.$i, array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $BEHAVIOR, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?>
										</td>
										<td><?php echo $this->Form->input('Behavior.label_id.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'select', 'class' => 'form-control','options' => $relation,  'placeholder' => 'AttributeName', 'error'=>false)); ?>
										</td>
										<td><?php echo $this->Form->input('Behavior.order.'.$i, array('label' => false, 'div' => false, 'id' => '', 'type' => 'text', 'class' => 'form-control', 'placeholder' => 'AttributeName', 'error'=>false)); ?>
										</td>
									</tr>
								<?php endfor; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>

			<div class="col-md-12">
				<p style="text-align: center;">
					<?php
					echo $this->Form->submit('Edit Element', array('name' => 'confirm', 'div' => false, 'class' => 'btn btn-primary'));
					?>
				</p>
				<input type="hidden" name="token" value="<?php echo session_id();?>">
				<input type="hidden" name="editElement" value="editElement">
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
</div>


