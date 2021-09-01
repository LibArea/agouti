<?php

namespace Modules\Admin\Controllers;

use Hleb\Scheme\App\Controllers\MainController;
use Hleb\Constructor\Handlers\Request;
use Modules\Admin\Models\CommentModel;
use Lori\{Content, Base};

class CommentsController extends MainController
{
    public function index($sheet)
    {
        $uid    = Base::getUid();
        $page   = Request::getInt('page');
        $page   = $page == 0 ? 1 : $page;

        $limit      = 100;
        $pagesCount = CommentModel::getCommentsAllCount($sheet);
        $comments   = CommentModel::getCommentsAll($page, $limit, $sheet);

        $result = array();
        foreach ($comments  as $ind => $row) {
            $row['content'] = Content::text($row['comment_content'], 'text');
            $row['date']    = lang_date($row['comment_date']);
            $result[$ind]   = $row;
        }

        $meta_title = lang('Comments-n');
        if ($sheet == 'ban') {
            $meta_title = lang('Deleted comments');
        }

        $meta = [
            'meta_title'    => $meta_title,
            'sheet'         => 'comments',
        ];
        
        $data = [
            'sheet'         => $sheet == 'all' ? 'comments' : 'comments-ban',
            'pagesCount'    => ceil($pagesCount / $limit),
            'pNum'          => $page,
            'comments'      => $result,
        ];
        
        return view('/comment/comments', ['meta' => $meta, 'uid' => $uid, 'data' => $data]);
    }
}
