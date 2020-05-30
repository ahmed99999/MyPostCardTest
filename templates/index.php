<?php

include('TCPDF-master/tcpdf.php');

function makeRequest( string $url ): array {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    return [
        'get' => function() use ( $curl ) {
            $data = curl_exec($curl);
            curl_close( $curl );
            return $data ;
        },
        'post' => function( array $body ) use ( $curl ) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query( $body ));
            $data = curl_exec($curl);
            curl_close ( $curl );
            return $data;
        }
    ];
}

function imageCreateFrom( string $type, $sourceImage ){
    $imageTypes = [
        "png" => function() use ( $sourceImage ) { return imagecreatefrompng ( $sourceImage );},
        "gif" => function() use ( $sourceImage ) { return imagecreatefromgif ( $sourceImage );},
        "jpg" => function() use ( $sourceImage ) { return imagecreatefromjpeg( $sourceImage );},
    ];
    return $imageTypes[ $type ]();
}

function getBase64Image( $functionName, $sourceImage ){
    ob_start();
    call_user_func ( $functionName, $sourceImage );
    $output = ob_get_contents();
    ob_end_clean();
    return base64_encode($output);
}

function imageFormat ( string $type, $sourceImage ){
    $imageFormats = [
        "png" => function() use ( $sourceImage ) { return getBase64Image ( "imagepng",  $sourceImage );},
        "gif" => function() use ( $sourceImage ) { return getBase64Image ( "imagegif",  $sourceImage );},
        "jpg" => function() use ( $sourceImage ) { return getBase64Image ( "imagejpeg", $sourceImage );}
    ];
    return $imageFormats[ $type ]();
}

function getPercentage( $width, $height, $oldWidth, $oldHeight ){
    if ( isset( $width ) ) return (( $width * 100 ) / $oldWidth );
    elseif ( isset( $height ) ) return (( $height * 100 ) / $oldHeight );
    else return 100;
}

function getResizedImage( string $url, $width, $height ){
    $sourceImageType = pathinfo( $url, PATHINFO_EXTENSION );
    list( $oldWidth, $oldHeight ) = getimagesize( $url );
    $percentage = getPercentage( $width, $height, $oldWidth, $oldHeight );
    $height = isset( $height ) ? $height : ( $oldHeight * $percentage ) / 100;
    $width  = isset( $width ) ? $width : ( $oldWidth * $percentage ) / 100;
    $thumb  = imagecreatetruecolor( $width, $height );
    $sourceImage = imageCreateFrom( $sourceImageType, $url );
    imagecopyresized($thumb, $sourceImage, 0, 0, 0, 0, $width, $height, $oldWidth, $oldHeight);
    return [
        "type" => $sourceImageType,
        "image" => function() use( $sourceImageType, $thumb ) { return imageFormat( $sourceImageType, $thumb );}
    ];
}

function makeThambnailPDF( string $thumbnail_url ){
    $sourceImageType = strtoupper ( pathinfo( $thumbnail_url, PATHINFO_EXTENSION ) );
    $pdf = new TCPDF( 'p', 'mm', 'A4' );
    $pdf->setPrintHeader(false);    
    $pdf->setPrintFooter(false);
    $pdf->addPage();
    $dimentions = resizeimagePdf( $thumbnail_url );
    $pdf->Image( $thumbnail_url, 0, 0 , $dimentions["width"], $dimentions["height"], $sourceImageType , $thumbnail_url, '', true, 150, '', false, false, 1, false, false, false);
    return $pdf->Output( 'title.pdf', 'I');
}

function mapToThumbnail( $thumbnail ){
    $thumbnail_url = $thumbnail["thumb_url"];
    $title = $thumbnail["title"];
    return [
        "thumbnailImage" => function() use ( $thumbnail_url ) { return getResizedImage( $thumbnail_url, 200, null );},
        "price" => $thumbnail["price"],
        "title" => $title,
        "thumb_url" => $thumbnail_url,
        "id" => $thumbnail["id"]
    ];
}

function pixelsToMM($val) {
    $DPI = 96;
    $MM_IN_INCH = 25.4;
    return $val * $MM_IN_INCH / $DPI;
}

function getCenterDimentionsA4( $url ){
    list( $width, $height ) = getimagesize( $url );
    $A4_HEIGHT = 297;
    $A4_WIDTH = 210;
    return [
        "centerWidth" => ($A4_HEIGHT - $width ) / 2,
        "centerHeight" => ($A4_HEIGHT - $height) / 2
    ];
}

function resizeimagePdf($imgFilename) {
    $MAX_WIDTH = 800;
    $MAX_HEIGHT = 500;
    list($width, $height) = getimagesize($imgFilename);

    $widthScale = $MAX_WIDTH / $width;
    $heightScale = $MAX_HEIGHT / $height;

    $scale = min($widthScale, $heightScale);

    return [
        "width" => round( pixelsToMM($scale * $width) ),
        "height" => round( pixelsToMM($scale * $height) )
    ];
}

function mapToProduct( $product ){
    $productOptions = $product["product_options"];
    return array_map( function( $productOption ) use ( $product ){
        $productOption["price"] = (float)$productOption["price"] + (float)$product["price"];
        return $productOption;
    }, $productOptions );
}

function getThumbnails() {
    $thumbnailsURL = "https://appdsapi-6aa0.kxcdn.com/content.php?lang=de&json=1&search_text=berlin&currencyiso=EUR";
    $data = json_decode ( makeRequest( $thumbnailsURL )["get"](), true );
    // shortening the list to 25 thumbnails
    $thumbnails = array_slice( $data["content"], 0, 25 );
    return array_map( 'mapToThumbnail', $thumbnails );
}

function getCards(){
    $cardsURL = "https://www.mypostcard.com/mobile/product_prices.php?json=1&type=get_postcard_products&currencyiso=EUR";
    $data = json_decode( makeRequest( $cardsURL )["get"](), true );
    $products = array_map ( "mapToProduct", $data["products"]); 
    $productOptions = [];
    foreach ($products as $product) {
        $productOptions = array_merge( $productOptions, $product );
    }
    return $productOptions;
}

$products = getCards();
$thumbnails = getThumbnails();

if ( isset($_POST["thumbnail_url"])){
    makeThambnailPDF( $_POST["thumbnail_url"] );
}