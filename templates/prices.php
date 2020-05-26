<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <title>Greeting card with Envelope</title>
</head>
<body>
<?php require( '../index.php' ); ?>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                <th scope="col">Name</th>
                <th scope="col">Option Code</th>
                <th scope="col">Price</th>
                <th scope="col">Type</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product) :?>
                    <tr>
                        <td><?php echo $product['name'];?></td>
                        <td><?php echo $product['option_code'];?></td>
                        <td class="price" original=<?=$product['original']?> addon=<?=$product['price']?>><?php echo $product['price']." €";?></td>
                        <td><?php echo $product['assignedtype'] ;?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <select class="custom-select" id="inputGroupSelect04">
                <option selected disabled>Choose Price Oprion</option>
                <option value="original">Original Price</option>
                <option value="addon">Price + Add On</option>
            </select>
        </div>
    </div>
</div>

    <script>
        const selectPrice = document.querySelector( "#inputGroupSelect04");
        selectPrice.addEventListener( "change", function( event ) {
            const selectPriceValue = event.target.value;
            const prices = [...document.querySelectorAll( ".price")];
            prices.forEach( price => {
                const origin = price.getAttribute("original");
                const addon = price.getAttribute("addon");
                if ( event.target.value == "original" ) price.innerText = origin + " €";
                else price.innerText = addon + " €";
            });

        });
    </script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>