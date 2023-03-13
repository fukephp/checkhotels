@php
    for($x=1; $x<=$star_rating; $x++) {
        echo '<i class="fas fa-star"></i>';
    }
    if (strpos($star_rating,'.')) {
        echo '<i class="fas fa-star-half-alt"></i>';
        $x++;
    }
    while ($x <= 5) {
        echo '<i class="far fa-star"></i>';
        $x++;
    }
@endphp