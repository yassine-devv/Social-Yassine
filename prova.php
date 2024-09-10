<!DOCTYPE html>
<html>
<body>

<form name="frm" action="a3 - Copy.php" method="post" enctype="multipart/form-data"> 
    <input type="submit" name="submitFile" style="display:none" id="submit">
    <input type="file"  name="uploadFile" onchange="document.getElementById('submit').click()">
</form>

</body>
</html>