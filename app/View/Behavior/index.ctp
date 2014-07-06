<?php 
echo $this->Html->script(array('lodash.min'));
echo $this->Html->script(array('raphael-min')); 
echo $this->Html->script(array('sequence-diagram-min'));
?>

<script>
	$(document).ready(function(){

		var diagram = Diagram.parse($('#language').val());
		console.log(diagram);

		diagram.drawSVG('diagram', {theme: 'simple'});
	});
</script>

<h1>Make a payment</h1>
<a class="btn btn-info col-md-2" id="edit" href="/<?php echo $base_dir;?>/behavior/edit/<?php echo $method_id;?>" style="margin: 10px;">Edit</a>
<a class="btn btn-danger col-md-2" href="" onclick="return confirm('Are You Sure ?');" style="margin: 10px;">Delete</a>

<?php $count = 0; ?>

<textarea id="language" style="display: none;" >

	<?php for($i = 0; $i < count($behaviors); $i++): ?>
		<?php if(!empty($behaviors[$i+1])): ?>

			<?php if($behaviors[$i]['Behavior']['type'] == 3): ?>
				<?php for($j = 1; $j < count($behaviors); $j++): ?>
					<?php if(!empty($behaviors[$i+$j])): ?>
						<?php if($behaviors[$i+$j]['Behavior']['type'] == 5): ?>
							<?php echo h($behaviors[$i]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i]['Behavior']['type']]."＞";?>-><?php echo h($behaviors[$i+$j]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i+$j]['Behavior']['type']]."＞";?>:Use "access_control" process
							<?php for($t = 0; $t < count($behaviors); $t++): ?>
								<?php if(!empty($behaviors[$i-$t])): ?>
									<?php echo h($behaviors[$i+$j-$t]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i+$j-$t]['Behavior']['type']]."＞";?>--><?php echo h($behaviors[$i-$t]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i-$t]['Behavior']['type']]."＞";?>:
									<?php if($t == 0): ?>
										Note left of <?php echo h($behaviors[$i-$t]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i-$t]['Behavior']['type']]."＞";?>: ["access_control" process return false]
									<?php endif; ?>
								<?php endif; ?>
							<?php endfor; ?>
						<?php else: ?>
							<?php if($count == 0): ?>
								Note right of <?php echo h($behaviors[$i]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i]['Behavior']['type']]."＞";?>: ["access_control" process return true]
								<?php $count ++; ?>
							<?php endif; ?>
							<?php echo h($behaviors[$i]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i]['Behavior']['type']]."＞";?>-><?php echo h($behaviors[$i+$j]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i+$j]['Behavior']['type']]."＞";?>:Access Data
							<?php echo h($behaviors[$i+$j]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i+$j]['Behavior']['type']]."＞";?>--><?php echo h($behaviors[$i]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i]['Behavior']['type']]."＞";?>:
						<?php endif; ?>
					<?php endif; ?>
				<?php endfor; ?>

			<?php elseif(($behaviors[$i]['Behavior']['type'] != 4) && ($behaviors[$i]['Behavior']['type'] != 5)): ?>
				<?php echo h($behaviors[$i]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i]['Behavior']['type']]."＞";?>-><?php echo h($behaviors[$i+1]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i+1]['Behavior']['type']]."＞";?>:
			<?php endif; ?>


		<?php endif; ?>
	<?php endfor; ?>


	<?php for($i = count($behaviors) -1 ; $i > 0; $i--): ?>
		<?php if(!empty($behaviors[$i-1])): ?>
			<?php if(($behaviors[$i]['Behavior']['type'] != 4) && ($behaviors[$i]['Behavior']['type'] != 5)): ?>
				<?php echo h($behaviors[$i]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i]['Behavior']['type']]."＞";?>--><?php echo h($behaviors[$i-1]['Label']['name']);?>\n<?php echo "＜".$BEHAVIOR[$behaviors[$i-1]['Behavior']['type']]."＞";?>:
			<?php endif; ?>
		<?php endif; ?>
	<?php endfor; ?>

</textarea>


<div id="diagram"></div>


