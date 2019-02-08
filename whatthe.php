<?php
  echo "<div id='wtf' name='wtf' width=100% align='center'>";

  echo "<img src='../photo.jpg' alt='WTF?' height='60' width='60' onclick='changeImage()'/>";

  echo "</div>";
?>

<script language="javascript">
  function changeImage() {
    document.getElementById("wtf").innerHTML="<iframe width='420' height='315' align='middle' src='https://www.youtube.com/embed/66g1_oTEWhIqE'></iframe>";
  }
</script>
