<input type="hidden" id="url_if_change_dairy" value="schema/compare?dairy_id">
<?php
  if (isset($dairy))
    include "_compare_current.php";
  elseif(empty($dairies))
    echo "<h2>Primero debe crear el tambo y los controles lecheros</h3>";
  else
    include "_compare_select.php";
?>