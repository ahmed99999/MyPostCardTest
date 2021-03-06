<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <title>MyPostTest</title>
</head>
<body>
    <?php require( 'templates/index.php' ); ?>

    <div class="row " >
        <div class="col-md-1">
            <a class="btn btn-primary" href="prices.php">Prices</a>
        </div>
        <div class="col-md-9">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">thumbnail</th>
                    <th scope="col">Title</th>
                    <th scope="col">Price</th>
                    <th scope="col">#</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($thumbnails as $thumbnail) :?>
                    <tr>
                        <th> <?php echo '<img src="data:image/'. $thumbnail["thumbnailImage"]()["type"] .';base64,'.$thumbnail["thumbnailImage"]()["image"]().'"/>'?> </th>
                        <td><?php echo $thumbnail['title'];?></td>
                        <td class="price" id=<?= $thumbnail["id"]?>><?php echo $thumbnail['price']." €";?></td>
                        <td class="backGray">
                            <form method="POST" action="templates/index.php">
                                <input type="hidden" name="thumbnail_url" value="<?=$thumbnail['thumb_url']?>">
                                <button class="btn btn-primary" type="submit">Get PDF</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-2 center">
            <div class="input-group">
                <select class="custom-select" id="inputGroupSelect04">
                    <option selected disabled>Choose Price Oprion</option>
                    <option value="price">Original Price</option>
                    <option value="price_foldingcard">Price Foldingcard</option>
                    <option value="price_audiocard">Price Audiocard</option>
                </select>
            </div>
        </div>
    </div>
    
    <script src="index.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>