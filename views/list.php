<?php
  /**
   * View used to display a list of Product models in a table format
   * 
   * @param Array $data An array of Product models to display
   */
use app\Helpers;
?>

<div class="table-responsive">
    <table class="table table-striped mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Type</th>
                <th scope="col">Supplier</th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach ($data as $index => $product) { ?>
            <tr>
                <td><?= ($index + 1) ?></td>
                <td><?= Helpers::sanitiseString($product->id) ?></td>
                <td><?= Helpers::sanitiseString($product->name) ?></td>
                <td><?= Helpers::sanitiseString($product->description) ?></td>
                <td><?= Helpers::sanitiseString($product->type) ?></td>
                <td><?= Helpers::sanitiseString($product->suppliers) ?></td>
            <?php } ?>
            </tr>
        </tbody>
    </table>
</div>