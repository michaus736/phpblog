<div class="varinfo">
<?php
    
    echo "<br \>";echo "<br \>";echo "<br \>";echo "table names";echo "<br \>";
    echo json_encode($tableNames);
    echo "<br \>";echo "<br \>";echo "table content";echo "<br \>";
    echo json_encode($queryResult);
    echo "<br \>";echo "<br \>";echo "<br \>";echo "<br \>";
    

?>
</div>
<table border="1px">
    <thead>
        <?php foreach($tableNames as $thead){ ?>
        <th>
            <?=$thead?>
        </th>
        <?php } ?> 
    </thead>
    <?php if($queryResult!=0 && $queryResult !=[] && is_array($queryResult) && count(array_filter($queryResult,'is_array'))){ ?>
        <?php foreach($queryResult as $tr) {?>
        <tr>    
            <?php foreach($tr as $td){ ?>
            <td><?=$td?></td>
           <?php } ?>
        </tr>
        <?php } ?>



    <?php } elseif($queryResult!=0 && $queryResult !=[] && is_array($queryResult)){?>
        <tr>
            <?php foreach($queryResult as $td){?>
                <td>
                    <?=$td?>
                </td>
            <?php }?>
        </tr>

    <?php } 
    
    
    
    
    else{ ?>
        <tr>
        <?php foreach($tableNames as $td){ ?>
            <td>
                &nbsp;
            </td>
        <?php } ?>
        </tr>
    <?php }?>

</table>