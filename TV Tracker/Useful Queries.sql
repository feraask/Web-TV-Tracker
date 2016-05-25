Total Shows: 89787

#TOP 10 Queries
SELECT title.id AS id, title.title AS title, title.series_years AS series_years, ratings.info AS rating
FROM title, movie_info_idx AS ratings, movie_info_idx AS num_votes
WHERE title.id = ratings.movie_id AND title.id = num_votes.movie_id
AND title.kind_id = 2 AND ratings.info_type_id = 101  AND num_votes.info_type_id = 100 
AND num_votes.info > 5000 AND title.title <> 'Band of Brothers' AND title.title <> 'Dekalog'
ORDER BY ratings.info DESC
LIMIT 10

# Gets genres and genre counts
SELECT movie_info.info AS genre, COUNT(movie_info.info) AS genre_count 
FROM `movie_info`,`title` 
WHERE 	movie_info.info_type_id = 3 AND 
	movie_info.movie_id = title.id AND 
        title.kind_id = 2
GROUP BY movie_info.info


# genre and genre count for users
SELECT movie_info.info AS genre, COUNT(movie_info.info) AS genre_count 
FROM `movie_info`,`title`,`z_10` 
WHERE 	movie_info.info_type_id = 3 	AND 
		movie_info.movie_id = title.id 	AND 
        title.kind_id = 2				AND
        z_10.movie_id = title.id
GROUP BY movie_info.info

# items in user list
SELECT COUNT(z_10.movie_id) FROM `z_10` WHERE 1

#Rating distance for shows not in user list
SELECT title.id, title.title, SQRT(POW((10 - ratings.info), 2)) AS rating_distance
FROM title, movie_info_idx AS ratings WHERE title.kind_id = 2 AND
ratings.movie_id = title.id AND ratings.info_type_id = 101 AND ratings.info >= 5 AND
title.id NOT IN
(
	SELECT movie_id FROM z_10
)
ORDER BY rating_distance DESC

#Mega recommendation query!
SELECT ratings_dist.movie_id AS movie_id, ratings_dist.ratings_distance AS ratings_distance, POW((1 - num_genres_in_top/num_genres_in_show)*10, 2) AS genre_distance, SQRT(ratings_distance+POW((1 - num_genres_in_top/num_genres_in_show)*10, 2)) AS euclid_dist
FROM
(
	SELECT title.id AS movie_id, POW((10-ratings.info), 2) AS ratings_distance
	FROM title, movie_info_idx AS ratings
	WHERE ratings.info >= 6 AND title.id = ratings.movie_id AND ratings.info_type_id = 101 
	AND title.id NOT IN
	        (
	        	SELECT movie_id as id FROM z_10        
	        )
) AS ratings_dist

INNER JOIN

(
	SELECT movie_id, num_genres_in_top
	FROM
	(
		SELECT genre.movie_id AS movie_id, COUNT(genre.info) AS num_genres_in_top
		FROM title, movie_info AS genre
		WHERE title.id = genre.movie_id AND genre.info_type_id = 3 AND title.kind_id = 2 AND title.id NOT IN
		(
			SELECT movie_id FROM z_10
		)
		AND genre.info IN
		(
		 	SELECT genre FROM
		 	(
				SELECT user_genre_freq.genre_freq/genre_freq.frequency as freq_relation, genre_freq.genre as genre FROM genre_freq,
		        	(
		        		SELECT movie_info.info AS genre, COUNT(movie_info.info)/13 AS genre_freq
		             	FROM movie_info, title, z_10 
		             	WHERE movie_info.info_type_id = 3 AND movie_info.movie_id = title.id AND title.kind_id = 2 AND z_10.movie_id = title.id
		             	GROUP BY movie_info.info
		             ) AS user_genre_freq
		        WHERE user_genre_freq.genre = genre_freq.genre
		        ORDER BY freq_relation DESC
		        LIMIT 5
		    ) AS top_genres
		)
		GROUP BY genre.movie_id
	) AS top_genres_2
) AS num_genres_in_top
ON num_genres_in_top.movie_id = ratings_dist.movie_id

INNER JOIN

(
	SELECT genre.movie_id AS num_genre_id, COUNT(genre.info) AS num_genres_in_show
    FROM title, movie_info AS genre
    WHERE title.id = genre.movie_id AND genre.info_type_id = 3 AND title.kind_id = 2 AND title.id NOT IN
    (
    	SELECT movie_id FROM z_10
    )
    GROUP BY genre.movie_id
) AS num_genres_in_show

ON num_genres_in_show.num_genre_id = ratings_dist.movie_id

LIMIT 0,10