
<h1>Target Function List</h1>

 <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Target Function</th>
          <td>Behavior</td>
          <td>Security Requirements</td>
          <td>Security Design Requirements</td>
          <td>Delete</td>
        </tr>
      </thead>
      <tbody>
      	<?php for($i = 0; $i < count($target_methods['name']); $i++): ?>
        <tr>
          <td><?php echo $i + 1;?></td>
          <td><?php echo h($target_methods['name'][$i]);?></td>
          <td><a href="/<?php echo $base_dir;?>/behavior/index/<?php echo h($target_methods['id'][$i]);?>" class ="btn btn-primary">Bahavior</a></td>
          <td><a href="/<?php echo $base_dir;?>/security_requirements/table/<?php echo h($target_methods['id'][$i]);?>" class ="btn btn-info">Show Security Requirements</a></td>
          <td><a href="/<?php echo $base_dir;?>/security_design_requirements/table/<?php echo h($target_methods['id'][$i]);?>" class ="btn btn-primary">Show Security Design Requirements</a></td>
          <td><a href="/<?php echo $base_dir;?>/security_requirements/table/<?php echo h($target_methods['id'][$i]);?>" class ="btn btn-warning">Delete from Targe Function</a></td>
        </tr>
        <?php endfor; ?>

      </tbody>
    </table>
