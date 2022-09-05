<?php
  /**
   * View used to display a list of Product models in a table format
   * 
   * @param Array $data An array of Product models to display
   */

use app\Helpers;

?>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Type</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $index => $product) { ?>
                <tr>
                    <td><?= ($index + 1) ?></td>
                    <td><?= Helpers::sanitiseString($product->id) ?></td>
                    <td><?= Helpers::sanitiseString($product->name) ?></td>
                    <td><?= Helpers::sanitiseString($product->description) ?></td>
                    <td><?= Helpers::sanitiseString($product->type) ?></td>
                    <td><?= Helpers::sanitiseString($product->suppliers) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>