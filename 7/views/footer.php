<footer>
    <div>
        <?=date("Y-m-d H:i:s")?>
    </div>
    <div>
        <?php echo json_encode($_SESSION['user']) ?>
    </div>
</footer>
</html>