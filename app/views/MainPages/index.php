<?php
/*
ვიუში უკვე გვხვდება html ტეგები რაც იმას ნიშნავს გადავედით ვიზუალზე.
$data ვარიებლით ხდება ვიუზე გადაცემული მონაცემების ამოღება როგორც ბოდის დასაწყისშია ნაჩვენები.
აქ შეგვიძლია კიდევ სხვა კლასებთან მიმართვა და გამოძახება, მაგრამ სასურველია ვიუში უკვე მზა დაა ვქონდეს და უბრალოდ html ავაწყოთ.
*/
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>main page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
    <?php
    echo $data['user_class'] 
    ?>
    <hr>
    <h1>Main Page</h1>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>
