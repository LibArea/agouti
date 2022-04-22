<?php

namespace App\Models;

use DB;

class RssModel extends \Hleb\Scheme\App\Models\MainModel
{
    // Все посты для Sitemap
    public static function getPostsSitemap()
    {
        $sql = "SELECT post_id, post_slug, post_tl, post_is_deleted, post_draft
                    FROM posts 
                      WHERE post_is_deleted != 1 AND post_tl = 0 AND post_draft != 1";

        return  DB::run($sql)->fetchAll();
    }

    // Все пространства для Sitemap
    public static function getTopicsSitemap()
    {
        $sql = "SELECT facet_slug FROM facets";

        return  DB::run($sql)->fetchAll();
    }

    // Посты по id пространства для rss
    public static function getPostsFeed($facet_slug)
    {
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_feature,
                    post_translation,
                    post_draft,
                    post_date,
                    post_published,
                    post_user_id,
                    post_votes,
                    post_answers_count,
                    post_comments_count,
                    post_content,
                    post_content_img,
                    post_thumb_img,
                    post_merged_id,
                    post_closed,
                    post_tl,
                    post_lo,
                    post_top,
                    post_url_domain,
                    post_is_deleted,
                    rel.*,
                    id, login, avatar
                        FROM posts
                        LEFT JOIN
                        (
                            SELECT 
                                MAX(facet_id), 
                                MAX(facet_slug), 
                                MAX(facet_title),
                                MAX(relation_facet_id), 
                                relation_post_id,
                                GROUP_CONCAT(facet_slug, '@', facet_title SEPARATOR '@') AS facet_list
                                FROM facets      
                                LEFT JOIN facets_posts_relation 
                                    on facet_id = relation_facet_id
                                GROUP BY relation_post_id  
                        ) AS rel
                            ON rel.relation_post_id = post_id 
                            INNER JOIN users ON id = post_user_id
                                WHERE facet_list LIKE :qa
                                AND post_is_deleted = 0 AND post_tl = 0 AND post_draft = 0 
                                ORDER BY post_top DESC, post_date DESC LIMIT 1000";

        return DB::run($sql, ['qa' => "%" . $facet_slug . "%"])->fetchAll();
    }


    public static function getTopicSlug($facet_slug)
    {
        $sql = "SELECT 
                    facet_id,
                    facet_title,
                    facet_description,
                    facet_slug,
                    facet_img
                        FROM facets WHERE facet_slug = :facet_slug";

        return DB::run($sql, ['facet_slug' => $facet_slug])->fetch();
    }
}
