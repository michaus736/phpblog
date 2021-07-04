<h1>Notes View</h1>
<h3>
    Search: 
    <form action="index.php" method="POST">
        <input type="search" required>
        <input type="submit" value="search">
    </form>
</h3>
<?php if($queryResult == 0 || $queryResult == []){
    echo    "<div class='note'>
                There are no notes yet!
            </div>";
} else{
    if(is_array($queryResult) && count(array_filter($queryResult,'is_array'))){?>
        <div id="notes">
            <?php
                foreach($queryResult as $note){?>
                    <div class="note">
                        <?=$note['text']?>
                    </div>
                <?php } ?>
    
            


        </div>
    <?php } elseif($queryResult!=0 && $queryResult !=[] && is_array($queryResult)){?>
        <div id="notes">
            <div class="note">
                <?=$queryResult['text']?>
            </div>
        </div>

    <?php } 
} ?>



<form action="index.php" method="POST">
    <label>
    <h3>
        Add Note
    </h3>
    <textarea type="text" required name="noteToSend" required></textarea>
    </label>

    <input type="submit" value="send">
</form>