<?php
$db = mysqli_connect('localhost', 'root', 'root') or 
    die ('Unable to connect. Check your connection parameters.');

mysqli_select_db($db, 'moviesite') or die(mysqli_error($db));

$query = <<<ENDSQL
INSERT INTO reviews
    (review_movie_id, review_date, reviewer_name, review_comment,
        review_rating)
VALUES 

(1, "2023-10-10", "Ana López", "La trama de la película me mantuvo en vilo de principio a fin. Los actores hicieron un trabajo excepcional, ¡definitivamente una obra maestra!", 5),
(2, "2023-10-09", "Carlos Martínez", "Aunque la película tenía buenos efectos especiales, la historia era predecible y cliché. No cumplió con mis expectativas.", 3),
(3, "2023-10-08", "María Rodríguez", "¡Increíble! Los giros inesperados y la cinematografía impecable hicieron que esta película se convirtiera en una de mis favoritas. ¡Recomendadísima!", 5),
(1, "2023-10-07", "Luis García", "La película fue decepcionante. Los personajes eran planos y la trama carecía de profundidad. No entiendo por qué recibió tantas críticas positivas.", 1),
(2, "2023-10-06", "Laura Fernández", "Me encantó la dirección artística de la película. Cada escena era visualmente impactante. Sin embargo, la falta de desarrollo en algunos personajes fue una gran desventaja.", 2),
(3, "2023-10-05", "Javier Martínez", "Es difícil expresar con palabras lo mala que fue esta película. La trama era incoherente y los diálogos parecían escritos por un niño. Lamentable experiencia cinematográfica.", 1),
(1, "2023-10-04", "Isabel Díaz", "Buena película, pero no excelente. Algunas actuaciones fueron notables, pero el guion tenía agujeros en la trama que no se resolvieron. Estuvo bien para pasar el rato.", 3),
(2, "2023-10-03", "Francisco Ramos", "Una película promedio en todos los sentidos. Ni buena ni mala. La trama era predecible y los efectos especiales no sorprendieron. Pudieron haber hecho mucho más con esta historia.", 2),
(3, "2023-10-02", "Elena Sánchez", "Me aburrí durante toda la película. La historia no tenía ningún atractivo y las actuaciones fueron mediocres en el mejor de los casos. Definitivamente, no la recomendaría.", 1);
ENDSQL;
mysqli_query($db, $query) or die(mysqli_error($db));

echo 'Movie database successfully updated!';
?>
