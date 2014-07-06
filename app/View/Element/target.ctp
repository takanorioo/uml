
<script>

	//リレーションの追加
	$(function() {
		var option_countermeasure = '<option value="">----</option>';
		<?php for($i = 0; $i < count($countermeasures); $i++): ?>
		option_countermeasure  += '<option value=<?php echo h($countermeasures[$i]['Countermeasure']['id']);?>><?php echo h($countermeasures[$i]['Countermeasure']['name']);?></option>'
	<?php endfor; ?>

	var count = 1;
	$("#add_countermeasure").click(function(){
		$(".add_countermeasure").append('<tr><td><select name="data[Countermeasure]['+count+']"class="form-control">'+option_countermeasure+'</select></td></tr>');
		count ++;
	});
});

</script>


<h1>Set Target Function</h1>
<?php echo $this->Form->create('Label'); ?>
<div class="row"style = "padding-top:40px">
	<div class="col-md-4 well element">
		<table class="table table-hover" style="margin-top: 44px;">
			<thead>
				<tr>
					<th>MethodName</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $this->Form->input('Method', array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'class' => 'form-control', 'options' => $methods, 'empty' => '----', 'error'=>false)); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-4 well element">
		<p style="text-align: right;">
			<input id="add_countermeasure" type="button" class ="btn btn-success" value="+" />
		</p>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Countermeasure</th>
				</tr>
			</thead>
			<tbody class="add_countermeasure">
				<tr>
					<td><?php echo $this->Form->input('Countermeasure.0', array('label' => false, 'div' => false, 'id' => false, 'type' => 'select', 'options' => $countermeasure_list, 'class' => 'form-control', 'empty' => '----', 'error'=>false)); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="col-md-12">
	<p style="text-align: center;">
		<?php
		echo $this->Form->submit('Set Target Function', array('name' => 'addTargetFunction', 'div' => false, 'class' => 'btn btn-primary'));
		?>
	</p>
	<input type="hidden" name="token" value="<?php echo session_id();?>">
	<input type="hidden" name="addTargetFunction" value="addTargetFunction">
</div>
<?php echo $this->Form->end(); ?>


