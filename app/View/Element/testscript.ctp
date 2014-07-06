
-- classes

<?php for($i = 0; $i < count($elements); $i++): ?>
class <?php echo h($elements[$i]['Label']['name']);?>


<?php if(!empty($elements[$i]['Attribute'])): ?>
attributes
<?php if($elements[$i]['Label']['id'] == $label['Label']['id']): ?>
  result : Boolean
<?php endif; ?>
<?php for($j = 0; $j < count($elements[$i]['Attribute']); $j++): ?>
 <?php echo h($elements[$i]['Attribute'][$j]['name']);?> : <?php echo h($TYPE[$elements[$i]['Attribute'][$j]['type']]);?>

<?php endfor; ?>
<?php endif; ?>


<?php if(!empty($elements[$i]['Method'])): ?>
operations

<?php for($j = 0; $j < count($elements[$i]['Method']); $j++): ?>
 <?php echo h($elements[$i]['Method'][$j]['name']);?> : Boolean = true
<?php endfor; ?>

end
<?php endif; ?>

<?php endfor; ?>


-- associations

<?php for($i = 0; $i < count($elements); $i++): ?>
<?php for($j = 0; $j < count($elements[$i]['Relation']); $j++)  : ?>
association access_<?php echo h($elements[$i]['Relation'][$j]['id']);?> between
	<?php echo h($elements[$i]['Label']['name']);?>[1..*]
	<?php echo h($elements[$i]['Relation'][$j]['name']);?>[1..*]
end
<?php endfor; ?>
<?php endfor; ?>


-- OCL constraints



-- Test Case

-- Create instances
<?php for($i = 0; $i < count($elements); $i++): ?>
 !create <?php echo h($elements[$i]['Label']['name']);?> : <?php echo h($elements[$i]['Label']['name']);?>

<?php endfor; ?>


-- Insert associations

<?php for($i = 0; $i < count($elements); $i++): ?>
<?php for($j = 0; $j < count($elements[$i]['Relation']); $j++)  : ?>
 !insert (<?php echo h($elements[$i]['Label']['name']);?>, <?php echo h($elements[$i]['Relation'][$j]['name']);?>) into access_<?php echo h($elements[$i]['Relation'][$j]['id']);?>

<?php endfor; ?>
<?php endfor; ?>



-- Set Test Case

<?php  $attributes_key = array_keys($attributes); ?>
<?php for($i = 0; $i < count($attributes); $i++): ?>
 !set <?php echo h($attributes[$attributes_key[$i]]['name']);?>.<?php echo h($attributes[$attributes_key[$i]]['attribute_name']);?> :=<?php if(!empty($attributes[$attributes_key[$i]]['testcase'])): ?><?php if(is_numeric($attributes[$attributes_key[$i]]['testcase']) || $attributes[$attributes_key[$i]]['testcase'] == 'true' || $attributes[$attributes_key[$i]]['testcase'] == 'false'): ?><?php echo h($attributes[$attributes_key[$i]]['testcase']);?><?php else: ?>'<?php echo h($attributes[$attributes_key[$i]]['testcase']);?>'
<?php endif; ?>
<?php endif; ?>

<?php endfor; ?>


-- Execute Method

!set RBAC.result := RBAC.check_access_permission(User_Data, Role, Right)
