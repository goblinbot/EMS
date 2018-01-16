
  <div class="empty-1 item"></div>

  <div class="empty-2 item">

    <?php
      //clock
      echo "<p id=\"clock\" class=\"text-center\">".date("H:i",time())." ECT</p>";
    ?>

  </div>

  <div class="footer item"></div>

</div>
  <?=EMSincludeJS()?>
  <script type="text/javascript">
    $(document).ready(function(){
      setTimeout(function(){
        $('body').removeClass('notransition');
      },400);

      checkGridSupport();
    });
  </script>
</body>
</html>
