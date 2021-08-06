<?php

namespace App\Models;

use DB;
use PDO;

class PostModel extends \MainModel
{
    // Добавляем пост
    public static function addPost($data)
    {
        $result = self::getSlug($data['post_slug']);
        if ($result) {
            $data['post_slug'] =  $data['post_slug'] . "-";
        }

        $params = [
            'post_title'        =>  $data['post_title'],
            'post_content'      =>  $data['post_content'],
            'post_content_img'  =>  $data['post_content_img'],
            'post_thumb_img'    =>  $data['post_thumb_img'],
            'post_related'      =>  $data['post_related'],
            'post_merged_id'    =>  $data['post_merged_id'],
            'post_tl'           =>  $data['post_tl'],
            'post_slug'         =>  $data['post_slug'],
            'post_type'         =>  $data['post_type'],
            'post_translation'  =>  $data['post_translation'],
            'post_draft'        =>  $data['post_draft'],
            'post_ip_int'       =>  $data['post_ip_int'],
            'post_published'    =>  $data['post_published'],
            'post_user_id'      =>  $data['post_user_id'],
            'post_space_id'     =>  $data['post_space_id'],
            'post_closed'       =>  $data['post_closed'],
            'post_top'          =>  $data['post_top'],
            'post_url'          =>  $data['post_url'],
            'post_url_domain'   =>  $data['post_url_domain'],
        ];

        $sql = "INSERT INTO posts(post_title, 
                                    post_content, 
                                    post_content_img,
                                    post_thumb_img,
                                    post_related,
                                    post_merged_id,
                                    post_tl,
                                    post_slug,
                                    post_type,
                                    post_translation,
                                    post_draft,
                                    post_ip_int,
                                    post_published,
                                    post_user_id,
                                    post_space_id,
                                    post_closed,
                                    post_top,
                                    post_url,
                                    post_url_domain)
                                    
                                VALUES(:post_title, 
                                    :post_content, 
                                    :post_content_img,
                                    :post_thumb_img,
                                    :post_related,
                                    :post_merged_id,
                                    :post_tl,
                                    :post_slug,
                                    :post_type,
                                    :post_translation,
                                    :post_draft,
                                    :post_ip_int,
                                    :post_published,
                                    :post_user_id,
                                    :post_space_id,
                                    :post_closed,
                                    :post_top,
                                    :post_url,
                                    :post_url_domain)";

        DB::run($sql, $params);

        $sql_last_id =  DB::run("SELECT LAST_INSERT_ID() as last_id")->fetch(PDO::FETCH_ASSOC);

        return $sql_last_id['last_id'];
    }

    public static function getSlug($slug)
    {
        $sql = "SELECT 
                    post_slug 
                        FROM posts WHERE post_slug = :slug";

        return DB::run($sql, ['slug' => $slug])->fetch(PDO::FETCH_ASSOC);
    }

    // Полная версия поста  
    public static function getPostSlug($slug, $user_id, $trust_level)
    {
        // Ограничение по TL
        if ($user_id == 0) {
            $trust_level = 0;
        }

        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_type,
                    post_translation,
                    post_draft,
                    post_space_id,
                    post_date,
                    post_published,
                    post_user_id,
                    post_votes,
                    post_answers_count,
                    post_comments_count,
                    post_content,
                    post_content_img,
                    post_thumb_img,
                    post_closed,
                    post_tl,
                    post_lo,
                    post_top,
                    post_url,
                    post_modified,
                    post_related,
                    post_merged_id,
                    post_url_domain,
                    post_hits_count,
                    post_is_deleted,
                    id,
                    login,
                    avatar,
                    my_post,
                    space_id, 
                    space_slug, 
                    space_name,
                    space_img,
                    space_short_text,
                    votes_post_item_id,
                    votes_post_user_id
                        FROM posts
                        LEFT JOIN users ON id = post_user_id
                        LEFT JOIN spaces ON space_id = post_space_id
                        LEFT JOIN votes_post ON votes_post_item_id = post_id AND votes_post_user_id = :user_id
                            WHERE post_slug = :slug AND post_tl <= :trust_level";

        return DB::run($sql, ['slug' => $slug, 'user_id' => $user_id, 'trust_level' => $trust_level])->fetch(PDO::FETCH_ASSOC);
    }

    // Получаем пост по id
    public static function getPostId($post_id)
    {
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_type,
                    post_translation,
                    post_draft,
                    post_space_id,
                    post_date,
                    post_published,
                    post_user_id,
                    post_votes,
                    post_answers_count,
                    post_comments_count,
                    post_content,
                    post_content_img,
                    post_thumb_img,
                    post_closed,
                    post_tl,
                    post_lo,
                    post_top,
                    post_url,
                    post_related,
                    post_merged_id,
                    post_url_domain,
                    post_is_deleted,
                    space_id, 
                    space_slug, 
                    space_name
                        FROM posts
                        LEFT JOIN spaces ON space_id = post_space_id
                            WHERE post_id = :post_id";

        return DB::run($sql, ['post_id' => $post_id])->fetch(PDO::FETCH_ASSOC);
    }

    // Рекомендованные посты
    public static function postsSimilar($post_id, $space_id, $user_id, $quantity)
    {
        $sql = "SELECT 
                    post_id,
                    post_title,
                    post_slug,
                    post_type,
                    post_answers_count,
                    post_space_id,
                    post_user_id,
                    post_is_deleted
                        FROM posts
                            WHERE post_id < :post_id 
                                AND post_space_id = :space_id 
                                AND post_is_deleted = 0 
                                AND post_user_id != :user_id
                                ORDER BY post_id DESC LIMIT $quantity";

        return DB::run($sql, ['post_id' => $post_id, 'space_id' => $space_id, 'user_id' => $user_id])->fetchall(PDO::FETCH_ASSOC);
    }

    // Пересчитываем количество
    // $type (comments / answers / hits)
    public static function updateCount($post_id, $type)
    {
        $sql = "UPDATE posts SET post_" . $type . "_count = (post_" . $type . "_count + 1) WHERE post_id = :post_id";

        return DB::run($sql, ['post_id' => $post_id]);
    }

    // Редактирование поста
    public static function editPost($data)
    {
        $params = [
            'post_title'            => $data['post_title'],
            'post_type'             => $data['post_type'],
            'post_translation'      => $data['post_translation'],
            'post_date'             => $data['post_date'],
            'post_user_id'          => $data['post_user_id'],
            'post_draft'            => $data['post_draft'],
            'post_space_id'         => $data['post_space_id'],
            'post_modified'         => date("Y-m-d H:i:s"),
            'post_content'          => $data['post_content'],
            'post_content_img'      => $data['post_content_img'],
            'post_related'          => $data['post_related'],
            'post_merged_id'        => $data['post_merged_id'],
            'post_tl'               => $data['post_tl'],
            'post_closed'           => $data['post_closed'],
            'post_top'              => $data['post_top'],
            'post_id'               => $data['post_id'],
        ];

        $sql = "UPDATE posts SET 
                    post_title            = :post_title,
                    post_type             = :post_type,
                    post_translation      = :post_translation,
                    post_date             = :post_date,
                    post_user_id          = :post_user_id,
                    post_draft            = :post_draft,
                    post_space_id         = :post_space_id,
                    post_modified         = :post_modified,
                    post_content          = :post_content,
                    post_content_img      = :post_content_img,
                    post_related          = :post_related,
                    post_merged_id        = :post_merged_id,
                    post_tl               = :post_tl,
                    post_closed           = :post_closed,
                    post_top              = :post_top
                         WHERE post_id = :post_id";

        return DB::run($sql, $params);
    }

    // Связанные посты для поста
    public static function postRelated($post_related)
    {
        $sql = "SELECT 
                    post_id, 
                    post_title, 
                    post_slug, 
                    post_type, 
                    post_draft, 
                    post_related, 
                    post_is_deleted
                        FROM posts 
                            WHERE post_id IN(0, " . $post_related . ") AND post_is_deleted = 0 AND post_tl = 0";

        return DB::run($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Удаление обложки
    public static function setPostImgRemove($post_id)
    {
        $sql_two = "UPDATE posts SET post_content_img = '' WHERE post_id = :post_id";

        return DB::run($sql_two, ['post_id' => $post_id]);
    }

    // Добавить пост в профиль
    public static function addPostProfile($post_id, $user_id)
    {
        $sql_two = "UPDATE users SET my_post = :post_id WHERE id = :user_id";

        return DB::run($sql_two, ['post_id' => $post_id, 'user_id' => $user_id]);
    }

    // Удален пост или нет
    public static function isThePostDeleted($post_id)
    {
        $sql = "SELECT post_id, post_is_deleted
                    FROM posts
                        WHERE post_id = :post_id";

        $result = DB::run($sql, ['post_id' => $post_id])->fetch(PDO::FETCH_ASSOC);

        return $result['post_is_deleted'];
    }

    // Частота размещения постов участника 
    public static function getPostSpeed($user_id)
    {
        $sql = "SELECT 
                    post_id, 
                    post_user_id, 
                    post_date
                        FROM posts 
                            WHERE post_user_id = :user_id
                            AND post_date >= DATE_SUB(NOW(), INTERVAL 1 DAY)";

        return  DB::run($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPostTopic($post_id)
    {
        $sql = "SELECT
                    topic_id,
                    topic_title,
                    topic_slug,
                    relation_topic_id,
                    relation_post_id
                        FROM topics  
                        INNER JOIN topics_post_relation ON relation_topic_id = topic_id
                            WHERE relation_post_id  = :post_id";

        return DB::run($sql, ['post_id' => $post_id])->fetchAll(PDO::FETCH_ASSOC);
    }
}
