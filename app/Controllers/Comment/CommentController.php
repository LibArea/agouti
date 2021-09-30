<?php

namespace App\Controllers\Comment;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use App\Models\{CommentModel, UserModel};
use Agouti\{Content, Config, Base};

class CommentController extends MainController
{
    // Все комментарии
    public function index()
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit  = 25;
        $pagesCount = CommentModel::getCommentAllCount();
        $comments   = CommentModel::getCommentsAll($page, $limit, $uid);

        $result = array();
        foreach ($comments  as $ind => $row) {
            $row['date']                = lang_date($row['comment_date']);
            $row['comment_content']     = Content::text($row['comment_content'], 'line');
            $result[$ind]   = $row;
        }

        $num = ' | ';
        if ($page > 1) {
            $num = sprintf(lang('page-number'), $page) . ' | ';
        }

        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . '/comments',
            'sheet'         => 'comments',
            'meta_title'    => lang('all comments') . $num . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('comments-desc') . $num . Config::get(Config::PARAM_HOME_TITLE),
        ];
        
        $data = [
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'sheet'         => 'comments',
            'comments'      => $result
        ];

        return view('/comment/comments', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }

    // Комментарии участника
    public function userComments()
    {
        $login  = Request::get('login');
        $user   = UserModel::getUser($login, 'slug');
        Base::PageError404($user);

        $comments  = CommentModel::userComments($login);

        $result = array();
        foreach ($comments as $ind => $row) {
            $row['comment_content'] = Content::text($row['comment_content'], 'line');
            $row['date']            = lang_date($row['comment_date']);
            $result[$ind]           = $row;
        }

        $uid  = Base::getUid();
        $meta = [
            'canonical'     => Config::get(Config::PARAM_URL) . getUrlByName('comments.user', ['login' => $login]),
            'sheet'         => 'user-comments',
            'meta_title'    => lang('comments-n') . ' ' . $login . ' | ' . Config::get(Config::PARAM_NAME),
            'meta_desc'     => lang('comments-n') . ' ' . $login . ' ' . Config::get(Config::PARAM_HOME_TITLE),
        ];

        $data = [
            'h1'            => lang('comments-n') . ' ' . $login,
            'sheet'         => 'user-comments',
            'comments'      => $result
        ];

        return view('/comment/comment-user', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
