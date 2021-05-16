
<?php
//echo json_encode($_SESSION);
//echo json_encode($photos);
if($photos==false){ ?>
    <h4>Brak zdjęć</h4>
<?php }else{
?>
    <table>
    <tr>
        <th>Zdjęcie</th>
        <th>Autor</th>
        <th>Id postu</th>
        <th>data</th>
    </tr>

<?php foreach($photos as $photo){ ?>
    <tr>
        <td><img src="<?=$photo['newname']?>"></td>
        <td><?=$photo['userid']?></td>
        <td><?=$photo['postid']?></td>
        <td><?=$photo['date']?></td>
    </tr>
<?php    
} ?>    
    </table>


<?php 
}
?>