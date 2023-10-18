<?php

//EJERCICIO 2

function generate_ratings($rating) {
    $movie_rating = '';
    for ($i = 0; $i < $rating; $i++) {
        $movie_rating .= '<img src="star.png" height="20px" alt="star"/>';
    }
    return $movie_rating;
}


function get_director($director_id) {

    global $db;

    $query = 'SELECT 
            people_fullname 
       FROM
           people
       WHERE
           people_id = ' . $director_id;
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    $row = mysqli_fetch_assoc($result);
    extract($row);

    return $people_fullname;
}

function get_leadactor($leadactor_id) {

    global $db;

    $query = 'SELECT
            people_fullname
        FROM
            people 
        WHERE
            people_id = ' . $leadactor_id;
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    $row = mysqli_fetch_assoc($result);
    extract($row);

    return $people_fullname;
}

function get_movietype($type_id) {

    global $db;

    $query = 'SELECT 
            movietype_label
       FROM
           movietype
       WHERE
           movietype_id = ' . $type_id;
    $result = mysqli_query($db, $query) or die(mysqli_error($db));

    $row = mysqli_fetch_assoc($result);
    extract($row);

    return $movietype_label;
}


function calculate_differences($takings, $cost) {

    $difference = $takings - $cost;

    if ($difference < 0) {     
        $color = 'red';
        $difference = '$' . abs($difference) . ' million';
    } elseif ($difference > 0) {
        $color ='green';
        $difference = '$' . $difference . ' million';
    } else {
        $color = 'blue';
        $difference = 'broke even';
    }

    return '<span style="color:' . $color . ';">' . $difference . '</span>';
}


$db = mysqli_connect('localhost', 'root', 'root') or 
    die ('Unable to connect. Check your connection parameters.');
mysqli_select_db($db, 'moviesite') or die(mysql_error($db));


$query = 'SELECT
        movie_name, movie_year, movie_director, movie_leadactor,
        movie_type, movie_running_time, movie_cost, movie_takings
    FROM
        movie
    WHERE
        movie_id = '.$_GET['movie_id'];

$result = mysqli_query($db, $query) or die(mysqli_error($db));
$row = mysqli_fetch_assoc($result);
$movie_name         = $row['movie_name'];
$movie_director     = get_director($row['movie_director']);
$movie_leadactor    = get_leadactor($row['movie_leadactor']);
$movie_year         = $row['movie_year'];
$movie_running_time = $row['movie_running_time'] .' mins';
$movie_takings      = $row['movie_takings'] . ' million';
$movie_cost         = $row['movie_cost'] . ' million';
$movie_health       = calculate_differences($row['movie_takings'],
                          $row['movie_cost']);

$query = 'SELECT
        AVG(review_rating) as average_rating
    FROM
        reviews
    WHERE
        review_movie_id = '. $_GET['movie_id'];

$result = mysqli_query($db, $query) or die(mysqli_error($db));
$row = mysqli_fetch_assoc($result);
$average_rating = number_format($row['average_rating'], 1);

echo <<<ENDHTML
<html>
 <head>
  <title>Details and Reviews for: $movie_name</title>
  <style>
    .odd-row {
        background-color: #f2f2f2;
    }

    .even-row {
        background-color: #ffffff; 
    }
 </style>
 </head>
 <body>
  <div style="text-align: center;">
   <h2>$movie_name</h2>
   <h3><em>Details</em></h3>
   <table cellpadding="2" cellspacing="2"
    style="width: 70%; margin-left: auto; margin-right: auto;">
    <tr>
     <td><strong>Title</strong></strong></td>
     <td>$movie_name</td>
     <td><strong>Release Year</strong></strong></td>
     <td>$movie_year</td>
    </tr><tr>
     <td><strong>Movie Director</strong></td>
     <td>$movie_director</td>
     <td><strong>Cost</strong></td>
     <td>$movie_cost<td/>
    </tr><tr>
     <td><strong>Lead Actor</strong></td>
     <td>$movie_leadactor</td>
     <td><strong>Takings</strong></td>
     <td>$movie_takings<td/>
    </tr><tr>
     <td><strong>Running Time</strong></td>
     <td>$movie_running_time</td>
     <td><strong>Health</strong></td>
     <td>$movie_health<td/>
    </tr><tr>
     <td><strong>Average Rating</strong></td>
     <td>$average_rating</td>
     <td></td>
     <td><td/>
    </tr>
   </table>
ENDHTML;

$query = 'SELECT
        review_movie_id, review_date, reviewer_name, review_comment,
        review_rating
    FROM
        reviews
    WHERE
        review_movie_id = ' . $_GET['movie_id'] . '
    ORDER BY
        review_date DESC';

$result = mysqli_query($db, $query) or die(mysqli_error($db));

//EJERCICIO 3
echo <<<ENDHTML

   <h3><em>Reviews</em></h3>
   <table cellpadding="2" cellspacing="2"
    style="width: 90%; margin-left: auto; margin-right: auto;">
    <tr>
     <th style="width: 7em;"><a href="?movie_id={$_GET['movie_id']}&column=date">Date</a></th>
     <th style="width: 10em;"><a href="?movie_id={$_GET['movie_id']}&column=reviewer">Reviewer</a></th>
     <th><a href="?movie_id={$_GET['movie_id']}&column=comment">Comments</a></th>
     <th style="width: 5em;"><a href="?movie_id={$_GET['movie_id']}&column=rating">Rating</a></th>
    </tr>
ENDHTML;

$orderColumn = 'review_date';

if (isset($_GET['column'])) {
    switch ($_GET['column']) {
        case 'date':
            $orderColumn = 'review_date';
            break;
        case 'reviewer':
            $orderColumn = 'reviewer_name';
            break;
        case 'comment':
            $orderColumn = 'review_comment';
            break;
        case 'rating':
            $orderColumn = 'review_rating';
            break;
        default:
            $orderColumn = 'review_date'; 
            break;
    }
}

$query = 'SELECT
    review_movie_id, review_date, reviewer_name, review_comment,
    review_rating
FROM
    reviews
WHERE
    review_movie_id = ' . $_GET['movie_id'] . '
ORDER BY
    '.$orderColumn.' DESC';

$result = mysqli_query($db, $query) or die(mysqli_error($db));


//EJERCICIO 4
$rowNumber = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['review_date'];
    $name = $row['reviewer_name'];
    $comment = $row['review_comment'];
    $rating = generate_ratings($row['review_rating']);

    $rowClass = $rowNumber % 2 == 0 ? 'even-row' : 'odd-row';

    echo '<tr class="' .$rowClass. '">';
    echo '<td style="vertical-align:top; text-align: center;">' . $date . '</td>';
    echo '<td style="vertical-align:top;">' . $name . '</td>';
    echo '<td style="vertical-align:top;">' . $comment . '</td>';
    echo '<td style="vertical-align:top;">' . $rating . '</td>';
    echo '</tr>';

    $rowNumber++;
}
echo '</table>';

echo <<<ENDHTML
  </div>
 </body>
</html>
ENDHTML;
?>
