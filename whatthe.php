<?php
  echo "<br/><div id='wtf' name='wtf' width=100% align='center'>";

  echo "<img src='../photo.jpg' alt='WTF?' height='200' width='200' onclick='changeImage()'/>";

  echo "</div><br/>";
?>

<script language="javascript">
  function changeImage() {
    document.getElementById("wtf").innerHTML="<iframe width='420' height='315' align='middle' src='https://www.youtube.com/embed/66g1_oTEWhIqE' frameborder='0' allow='autoplay; encrypted-media' allowfullscreen></iframe>";
  }
</script>
